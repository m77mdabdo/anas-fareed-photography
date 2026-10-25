#!/usr/bin/env bash
# ───────────────────────────────────────────────────────────────────────────
# Runs ON the Hostinger server, called by the deploy workflow when it is run
# by hand with rollback=true. Points `current` back at the release before it.
#
# Code only: the database, .env and shared/storage are not touched, so a
# rollback never loses an upload or a record. A migration that already ran
# stays applied; the pre-migration dump is in backups/ if it is ever needed.
# ───────────────────────────────────────────────────────────────────────────
set -euo pipefail

PHP="${PHP_BIN:-/opt/alt/php84/usr/bin/php}"
APP_DIR="${APP_DIR:-$HOME/apps/anasfareed.com}"

live="$(basename "$(readlink "$APP_DIR/current")")"
target="$(ls -1 "$APP_DIR/releases" | sort -r | awk -v live="$live" 'found { print; exit } $0 == live { found = 1 }')"

[ -n "$target" ] || { echo "::error::No release older than $live to roll back to." >&2; exit 1; }

echo "Rolling back: $live -> $target"
ln -sfn "$APP_DIR/releases/$target" "$APP_DIR/current.tmp"
mv -Tf "$APP_DIR/current.tmp" "$APP_DIR/current"

cd "$APP_DIR/current"
"$PHP" artisan optimize --no-ansi
"$PHP" artisan queue:restart --no-ansi || true
echo "Live release: $target"
