#!/usr/bin/env bash
# ───────────────────────────────────────────────────────────────────────────
# Runs ON the Hostinger server, called by .github/workflows/deploy.yml after
# the built release archive has been copied to $ARCHIVE.
#
# Layout (everything outside public_html, which is a symlink to
# current/public):
#
#   ~/apps/anasfareed.com/   (Hostinger marks ~/domains/<site>/ DO_NOT_UPLOAD_HERE)
#     releases/<time>-<sha>/ one directory per deploy, never reused
#     shared/.env            written once by hand; never in Git, never replaced
#     shared/storage/        uploads, originals, logs — survives every deploy
#     backups/               mysqldump taken before any migration
#     current -> releases/<time>-<sha>
#
# MIGRATIONS NEVER RUN BY THEMSELVES. A push whose release has pending
# migrations stops before the switch and leaves the live release untouched.
# Re-run the workflow by hand with run_migrations=true to back up the database
# and apply them.
# ───────────────────────────────────────────────────────────────────────────
set -euo pipefail

: "${RELEASE:?}" "${ARCHIVE:?}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-false}"
PHP="${PHP_BIN:-/opt/alt/php84/usr/bin/php}"
APP_DIR="${APP_DIR:-$HOME/apps/anasfareed.com}"
SITE_URL="${SITE_URL:-https://anasfareed.com}"
PUBLIC_HTML="${PUBLIC_HTML:-$HOME/domains/anasfareed.com/public_html}"
KEEP_RELEASES=5

# Timestamped so re-deploying the same commit never touches the live copy.
release_dir="$APP_DIR/releases/$(date +%Y%m%d%H%M%S)-${RELEASE:0:12}"
shared="$APP_DIR/shared"

log() { printf '\n▶ %s\n' "$*"; }

[ -f "$shared/.env" ] || { echo "Missing $shared/.env — create it on the server first." >&2; exit 1; }

log "Preparing shared storage"
mkdir -p "$shared/storage/app/public" "$shared/storage/app/private" "$shared/storage/app/media" \
         "$shared/storage/framework/cache/data" "$shared/storage/framework/sessions" \
         "$shared/storage/framework/views" "$shared/storage/logs" "$APP_DIR/backups"

log "Unpacking release $RELEASE"
mkdir -p "$release_dir"
tar -xzf "$ARCHIVE" -C "$release_dir"
rm -f "$ARCHIVE"

# The upload-directory guard ships with the release; refresh it in shared
# storage every time, since that is the directory public/storage serves.
install -m 644 "$release_dir/storage/app/public/.htaccess" "$shared/storage/app/public/.htaccess"
rm -rf "$release_dir/storage"
ln -s "$shared/storage" "$release_dir/storage"
ln -s "$shared/.env" "$release_dir/.env"
rm -rf "$release_dir/public/storage"
ln -s ../storage/app/public "$release_dir/public/storage"

cd "$release_dir"

log "Checking migrations"
if ! status_out="$("$PHP" artisan migrate:status --pending --no-ansi 2>&1)" \
   && ! printf '%s' "$status_out" | grep -q 'Migration table not found'; then
    echo "$status_out" >&2
    echo "::error::Could not read migration status (database unreachable, or never migrated). The live site was NOT changed." >&2
    rm -rf "$release_dir"
    exit 1
fi
pending="$(printf '%s\n' "$status_out" | grep -c 'Pending' || true)"
# A database that was never migrated counts as pending, so the first
# initialisation also needs run_migrations=true.
if printf '%s' "$status_out" | grep -q 'Migration table not found'; then pending=1; fi
if [ "$pending" -gt 0 ]; then
    printf '%s\n' "$status_out"
    if [ "$RUN_MIGRATIONS" != "true" ]; then
        echo "::error::$pending pending migration(s). The live site was NOT changed. Re-run the workflow manually with run_migrations=true." >&2
        rm -rf "$release_dir"
        exit 1
    fi

    log "Backing up the database before migrating"
    db_conf="$(mktemp)"
    chmod 600 "$db_conf"
    "$PHP" -r '
        $env = parse_ini_file($argv[1], false, INI_SCANNER_RAW);
        $v = fn ($k) => trim($env[$k] ?? "", "\"");
        printf("[client]\nhost=%s\nport=%s\nuser=%s\npassword=\"%s\"\n",
            $v("DB_HOST") ?: "127.0.0.1", $v("DB_PORT") ?: "3306", $v("DB_USERNAME"), $v("DB_PASSWORD"));
        echo $v("DB_DATABASE"), PHP_EOL;
    ' "$shared/.env" > "$db_conf.raw"
    head -n -1 "$db_conf.raw" > "$db_conf"
    db_name="$(tail -n 1 "$db_conf.raw")"
    rm -f "$db_conf.raw"
    backup="$APP_DIR/backups/$(date +%Y%m%d-%H%M%S)-$RELEASE.sql.gz"
    mysqldump --defaults-extra-file="$db_conf" --single-transaction --no-tablespaces "$db_name" | gzip > "$backup"
    rm -f "$db_conf"
    echo "Backup: $backup"

    log "Migrating"
    "$PHP" artisan migrate --force --no-interaction
fi

log "Caching config, routes, views"
"$PHP" artisan optimize --no-ansi
"$PHP" artisan filament:optimize --no-ansi || true

previous="$(readlink "$APP_DIR/current" 2>/dev/null || true)"

log "Switching current -> $(basename "$release_dir")"
ln -sfn "$release_dir" "$APP_DIR/current.tmp"
mv -Tf "$APP_DIR/current.tmp" "$APP_DIR/current"

"$PHP" artisan queue:restart --no-ansi || true

log "Health check"
if [ "$(readlink "$PUBLIC_HTML" 2>/dev/null || true)" != "$APP_DIR/current/public" ]; then
    echo "::warning::$PUBLIC_HTML is not linked to $APP_DIR/current/public yet; release is in place but not live, health check skipped."
    exit 0
fi
ok=""
# /up proves the framework boots; / proves the database answers too.
for _ in 1 2 3 4 5 6; do
    if curl -fsS -o /dev/null --max-time 10 "$SITE_URL/up" \
       && curl -fsS -o /dev/null --max-time 20 "$SITE_URL/"; then ok=1; break; fi
    sleep 5
done
if [ -z "$ok" ]; then
    echo "::error::$SITE_URL/up did not answer after the switch." >&2
    if [ -n "$previous" ] && [ -d "$previous" ]; then
        ln -sfn "$previous" "$APP_DIR/current.tmp"
        mv -Tf "$APP_DIR/current.tmp" "$APP_DIR/current"
        echo "Rolled back to $previous" >&2
    fi
    exit 1
fi

log "Pruning old releases (keeping $KEEP_RELEASES)"
cd "$APP_DIR/releases"
ls -1 | sort -r | tail -n +$((KEEP_RELEASES + 1)) | while read -r old; do
    [ "$APP_DIR/releases/$old" = "$(readlink "$APP_DIR/current")" ] || rm -rf "$APP_DIR/releases/$old"
done

log "Deployed $RELEASE"
