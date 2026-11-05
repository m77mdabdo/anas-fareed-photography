<div align="center">

<a href="https://anasfareed.com"><img src="docs/screenshots/readme/hero-en.jpg" alt="The Anas Fareed home page: a large wordmark interlocked with a studio photograph" width="100%"></a>

# Anas Fareed — Photography Platform

**A bilingual commercial photography website and the studio dashboard that runs it.**<br>
English and right-to-left Arabic, one codebase, every sentence and photograph editable by the owner.

[**Live site**](https://anasfareed.com) &nbsp;·&nbsp; [**الموقع بالعربية**](https://anasfareed.com/ar) &nbsp;·&nbsp; [Architecture](#architecture) &nbsp;·&nbsp; [Screenshots](#screenshots) &nbsp;·&nbsp; [Run it locally](#getting-started)

[![Live](https://img.shields.io/badge/live-anasfareed.com-B9A07E?style=flat-square)](https://anasfareed.com)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-5-F59E0B?style=flat-square)
![Livewire](https://img.shields.io/badge/Livewire-4-FB70A9?style=flat-square&logo=livewire&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=flat-square&logo=vite&logoColor=white)
![Tests](https://img.shields.io/badge/PHPUnit-1%2C467_tests-2EA44F?style=flat-square)
![Locales](https://img.shields.io/badge/locales-EN_%2F_AR_(RTL)-0A0A0A?style=flat-square)

</div>

---

<div dir="rtl">

### نبذة بالعربية

منصة متكاملة للمصور التجاري **أنس فريد**: موقع عام باللغتين العربية (من اليمين لليسار) والإنجليزية، ولوحة تحكم يدير منها المالك المشاريع والصور والخدمات والمجلة والطلبات وكل جملة تظهر على الموقع، من الكمبيوتر أو الموبايل. الصور تتحول تلقائيًا إلى AVIF وWebP بعدة مقاسات، ولوحة التحكم فيها مركز مساعدة بالعربية يفهم العامية المصرية. المشروع منشور على [anasfareed.com](https://anasfareed.com/ar) بنظام نشر آلي مع فحص صحة ورجوع تلقائي عند الفشل.

</div>

---

## Contents

- [At a glance](#at-a-glance)
- [Features](#features)
- [Screenshots](#screenshots)
- [Architecture](#architecture)
- [Tech stack](#tech-stack)
- [Project structure](#project-structure)
- [Security](#security)
- [Performance](#performance)
- [Testing](#testing)
- [Getting started](#getting-started)
- [Deployment](#deployment)
- [Commands](#commands)
- [Documentation](#documentation)
- [Licence](#licence)

## At a glance

| | |
| --- | --- |
| **Status** | In production at [anasfareed.com](https://anasfareed.com) |
| **Public site** | English at `/`, Arabic at `/ar/…`, with translated slugs, `hreflang` and RTL layout |
| **Dashboard** | Filament panel at `/admin`: 16 content resources, role policies, activity log, built-in Help Center |
| **Media** | Every upload becomes AVIF and WebP at six widths, plus JPEG fallbacks, on a background queue |
| **Quality** | 1,467 PHPUnit tests and 18,000+ assertions, plus scripted browser checks across viewports and both languages |
| **Delivery** | Push to `production` → build → atomic release switch → health check → automatic rollback on failure |

> [!NOTE]
> The photographs and some project details on the live site are placeholder content, shown until the studio's own work replaces them. Everything can be replaced from the dashboard without touching code.

## Features

### Public website

- **Designed in two languages, not translated into one.** The Arabic home page has its own composition rather than a mirrored English layout, with dedicated Arabic type and metrics generated from the font file.
- **Editorial project stories.** 14 block types: full-width and contained images, pairs, grids, gallery strips, text, quotes, video, embeds and credits.
- **Cinematic galleries.** Scroll-driven sections where one photograph is lit at a time, with phone-specific behaviour and direction that follows the language.
- **Complete site structure.** Portfolio with category pages, chronological archive, services, journal with categories and tags, about, contact and CMS pages.
- **Works without JavaScript.** Motion is a progressive enhancement that respects `prefers-reduced-motion`. No front-end framework and no runtime npm dependency ship to visitors.
- **Search-ready.** Canonical URLs, `hreflang`, structured data, segmented sitemaps, per-record SEO fields, owner-managed redirects and automatic redirects from old slugs.
- **Protected enquiry form.** Honeypot, timing window, per-IP and per-email limits, hashed IPs, consent recording, plus notification and acknowledgement emails.

### Studio dashboard

- **16 resources.** Projects, media, categories, cinematic sections, journal, journal categories, tags, pages, services, clients, testimonials, menus, enquiries, redirects, users and activity.
- **Visual story builder.** Add, reorder and edit blocks by drag or keyboard, with undo, redo and preview.
- **Media library with usage tracking.** Duplicate detection, EXIF extraction, palette extraction and focal points. A photograph in use cannot be deleted.
- **Every sentence is editable.** Site copy lives in translatable settings, so wording changes never need a deploy.
- **Translation awareness.** The dashboard lists untranslated content, and missing Arabic falls back to English with the right language markup.
- **Bilingual interface.** The panel itself runs fully in English or Arabic, including badges, filters and refusal messages.
- **Built-in Help Center.** 59 task-based articles with guided steps and contextual help buttons. Search understands English, Arabic and Egyptian Arabic.
- **Governance.** Owner and Editor roles, a policy per model, and an activity log with automatic retention.

## Screenshots

### Home page, in both languages

<table>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/hero-en.jpg" alt="English home page"><p align="center"><sub>English: the wordmark interlocks with the photograph</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/hero-ar.jpg" alt="Arabic home page, right to left"><p align="center"><sub>Arabic: its own right-to-left composition</sub></p></td>
  </tr>
</table>

### Portfolio and stories

<table>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/work-grid.jpg" alt="Selected work on the home page"><p align="center"><sub>Selected work</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/project-story.jpg" alt="A full-width image inside a project story"><p align="center"><sub>A project story</sub></p></td>
  </tr>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/cinematic.jpg" alt="Cinematic gallery with one photograph lit"><p align="center"><sub>Cinematic gallery: one photograph lit at a time</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/services.jpg" alt="Services page"><p align="center"><sub>Services</sub></p></td>
  </tr>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/journal.jpg" alt="Journal index"><p align="center"><sub>Journal</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/contact-ar.jpg" alt="Arabic contact page"><p align="center"><sub>Enquiry form, in Arabic</sub></p></td>
  </tr>
</table>

### On a phone

<table>
  <tr>
    <td width="25%"><img src="docs/screenshots/readme/mobile-home-en.jpg" alt="Mobile home page in English"><p align="center"><sub>Home, English</sub></p></td>
    <td width="25%"><img src="docs/screenshots/readme/mobile-home-ar.jpg" alt="Mobile home page in Arabic"><p align="center"><sub>Home, Arabic</sub></p></td>
    <td width="25%"><img src="docs/screenshots/readme/mobile-project-ar.jpg" alt="Mobile project page in Arabic"><p align="center"><sub>Project, Arabic</sub></p></td>
    <td width="25%"><img src="docs/screenshots/readme/mobile-dashboard-ar.jpg" alt="Mobile dashboard in Arabic"><p align="center"><sub>Dashboard, Arabic</sub></p></td>
  </tr>
</table>

### Studio dashboard

<sub>Captured from a local development install with generated sample data.</sub>

<table>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/dashboard.jpg" alt="Dashboard overview"><p align="center"><sub>Overview: what needs attention today</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/story-builder.jpg" alt="Story builder"><p align="center"><sub>Story builder</sub></p></td>
  </tr>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/media.jpg" alt="Media library"><p align="center"><sub>Media library with status, usage and translation state</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/settings.jpg" alt="Site settings"><p align="center"><sub>Site settings: every sentence, in both languages</sub></p></td>
  </tr>
  <tr>
    <td width="50%"><img src="docs/screenshots/readme/help-ar.jpg" alt="Help Center in Arabic"><p align="center"><sub>Help Center, in Arabic</sub></p></td>
    <td width="50%"><img src="docs/screenshots/readme/login.jpg" alt="Sign-in page"><p align="center"><sub>Sign-in</sub></p></td>
  </tr>
</table>

## Architecture

A conventional Laravel application by design: no repository layer, and no service layer for its own sake. Controllers stay thin, single-purpose **Actions** hold the write operations, and small **Support** classes each do one job.

### System overview

```mermaid
flowchart TB
    visitor(["Visitor"]):::actor
    staff(["Owner / Editor"]):::actor

    visitor -->|"/ and /ar/…"| mw
    staff -->|"/admin"| auth

    subgraph edge["Request pipeline"]
        mw["SetLocale · HandleRedirects<br/>EnsureCanonicalSlug"]:::layer
        out["SecurityHeaders (CSP nonce)<br/>CompressResponse"]:::layer
        auth["Fortify sign-in · two-factor<br/>EnsureUserIsAdmin"]:::layer
    end

    subgraph app["Application"]
        ctrl["Public controllers<br/>published content only"]:::app
        panel["Filament panel<br/>16 resources · 4 pages"]:::app
        lw["Livewire<br/>StoryBuilder · MediaPicker"]:::app
        pol{{"17 policies<br/>Owner / Editor"}}:::guard
        act["Actions<br/>StoreUploadedMedia · SaveStory"]:::app
        sup["Support and SEO<br/>Settings · SiteCopy · SeoResolver"]:::app
        view["Blade components<br/>x-picture · story blocks"]:::app
    end

    subgraph data["Data and background work"]
        db[("MariaDB / MySQL<br/>translatable JSON columns")]:::store
        disk[("Media disk<br/>originals + renditions")]:::store
        queue["Queue workers<br/>media · default"]:::work
        cron["Scheduler<br/>activity:prune"]:::work
        mail["Mail<br/>enquiry notifications"]:::work
        log["Activity log"]:::store
    end

    mw --> ctrl --> sup --> view --> out
    auth --> panel --> pol --> act
    panel --> lw --> act
    sup --> db
    act --> db
    act --> disk
    act -->|"dispatch"| queue
    queue --> disk
    ctrl -->|"contact form"| mail
    pol -.->|"audited"| log
    cron --> log

    classDef actor fill:#2B2118,stroke:#B9A07E,color:#F6F3EC
    classDef layer fill:#F6F3EC,stroke:#8A7355,color:#17120D
    classDef app fill:#FBF8F2,stroke:#8A7355,color:#17120D
    classDef guard fill:#EBDDC6,stroke:#8A7355,color:#17120D
    classDef store fill:#E4DACA,stroke:#6E5B43,color:#17120D
    classDef work fill:#D9CDB9,stroke:#6E5B43,color:#17120D
```

### From URL to page

```mermaid
sequenceDiagram
    autonumber
    actor V as Visitor
    participant M as Middleware
    participant C as Controller
    participant S as Settings and SEO
    participant D as Database
    participant B as Blade

    V->>M: GET /ar/work/salt-line
    Note over M: SetLocale reads the path prefix<br/>and sets the language and RTL direction
    M->>M: Apply owner redirects and canonical slug
    M->>C: Forward the request
    C->>D: Load published records only
    D-->>C: Records with translatable fields
    C->>S: Resolve copy, title, canonical, hreflang
    S-->>C: Page metadata
    C->>B: Hand plain data to components
    B-->>M: HTML with AVIF, WebP and JPEG srcset
    Note over M: SecurityHeaders adds CSP and HSTS<br/>CompressResponse applies gzip when needed
    M-->>V: Complete page, usable without JavaScript
```

### Media pipeline

The upload request never does the heavy work. Renditions run on a dedicated queue, so a large file cannot slow the dashboard or the public site.

```mermaid
sequenceDiagram
    autonumber
    actor O as Owner
    participant A as StoreUploadedMedia
    participant D as Database
    participant Q as media queue
    participant J as ProcessMediaVariants
    participant F as Media disk

    O->>A: Upload a photograph
    A->>A: Check real content, 60 MB and 50 MP limits
    A->>D: Look for a file with the same checksum
    alt Already in the library
        A-->>O: Reuse the existing photograph
    else New photograph
        A->>F: Store the original privately
        A->>D: Save the record and EXIF data
        A->>Q: Queue the rendition job
        A-->>O: Show "In queue" immediately
        Q->>J: A worker picks up the job
        J->>F: Write AVIF and WebP, 480 to 2560 px
        J->>F: Write JPEG fallbacks, 1080 and 1920 px
        J->>D: Save the palette and mark Ready
    end
```

### Data model

The core content tables. Translatable fields are JSON columns such as `{"en": …, "ar": …}`.

```mermaid
erDiagram
    USER ||--o{ ACTIVITY_LOG : performs
    USER ||--o{ MEDIA : uploads
    PROJECT ||--o{ PROJECT_BLOCK : "story made of"
    PROJECT ||--o{ PROJECT_CREDIT : credits
    PROJECT }o--o{ CATEGORY : "filed under"
    ARTICLE_CATEGORY ||--o{ ARTICLE : groups
    ARTICLE }o--o{ TAG : "tagged with"
    MEDIA ||--o{ MEDIA_VARIANT : "rendered as"
    MEDIA ||--o{ MEDIA_USAGE : "used through"
    MEDIA_USAGE }o--|| USABLE_RECORD : "points to any content"
    CINEMATIC_SECTION ||--o{ CINEMATIC_SECTION_ITEM : contains
    MEDIA ||--o{ CINEMATIC_SECTION_ITEM : "shown in"
    INQUIRY ||--o{ INQUIRY_NOTE : "annotated by"
    MENU ||--o{ MENU_ITEM : contains
    PAGE ||--o{ MENU_ITEM : "linked from"

    PROJECT {
        json title
        json slug
        string status
        timestamp published_at
    }
    MEDIA {
        char checksum
        string status
        json alt
        string exif_camera
        decimal focal_x
    }
    MEDIA_USAGE {
        string usable_type
        bigint usable_id
    }
    INQUIRY {
        string status
        char ip_hash
        text message
    }
```

### Release flow

```mermaid
flowchart TB
    push["Push to production"]:::step --> build["GitHub Actions<br/>composer --no-dev · Vite build"]:::step
    build --> ship["Upload release<br/>over pinned SSH"]:::step
    ship --> check{"Pending<br/>migrations?"}:::gate
    check -->|"yes"| stop["Stop<br/>live site untouched"]:::warn
    stop -.->|"manual run with<br/>run_migrations=true"| backup["mysqldump backup<br/>then migrate"]:::step
    check -->|"no"| cache["Cache config,<br/>routes and views"]:::step
    backup --> cache
    cache --> switch["Atomic switch of<br/>the current symlink"]:::step
    switch --> health{"/up and /<br/>healthy?"}:::gate
    health -->|"yes"| live(["Live"]):::ok
    health -->|"no"| rollback["Automatic rollback<br/>to previous release"]:::warn

    classDef step fill:#FBF8F2,stroke:#8A7355,color:#17120D
    classDef gate fill:#EBDDC6,stroke:#8A7355,color:#17120D
    classDef ok fill:#DCEBD9,stroke:#3F7A3A,color:#12280F
    classDef warn fill:#F3D9D2,stroke:#A5482F,color:#35120A
```

## Tech stack

| Layer | Technology | Notes |
| --- | --- | --- |
| Language | **PHP 8.4** | Production runtime, with local development also covering 8.5 |
| Framework | **Laravel 12** | Routing, queues, mail, scheduler, Eloquent |
| Dashboard | **Filament 5**, **Livewire 4** | Panel at `/admin`, with custom story builder and media picker |
| Authentication | **Laravel Fortify** | Sign-in, password reset, TOTP two-factor and recovery codes |
| Database | **MariaDB 11.8** / **MySQL 8+** | MariaDB in production, MySQL locally |
| Front end | **Blade**, layered CSS, small ES modules | No front-end framework |
| Build | **Vite 7**, **Tailwind CSS 4** | Tailwind serves as the build pipeline |
| Images | **Intervention Image 4** | AVIF, WebP and JPEG renditions |
| Testing | **PHPUnit 11**, **Puppeteer** | Feature and unit tests, plus real-browser checks |
| Quality | **Laravel Pint** | `laravel` preset |
| Delivery | **GitHub Actions**, SSH, Hostinger | Timestamped releases with shared storage |

## Project structure

```text
.
├── app/
│   ├── Actions/            Single-purpose writes: uploads, stories, slugs, media sync
│   ├── Console/Commands/   Operational commands: media, copy, help coverage, admin
│   ├── Enums/              BlockType, PublishStatus, MediaStatus, InquiryStatus, UserRole
│   ├── Filament/           The dashboard: Resources, Pages, Widgets, shared Support
│   ├── Http/               One controller per page type, plus locale, security and 2FA middleware
│   ├── Jobs/               ProcessMediaVariants
│   ├── Livewire/Studio/    StoryBuilder and MediaPicker
│   ├── Models/             Eloquent models; translatable ones use Concerns/Translatable
│   ├── Policies/           Who may do what, with translated refusal reasons
│   ├── Routing/            LocaleUrlGenerator keeps route() in the current language
│   ├── Services/Seo/       SeoResolver and SitemapBuilder
│   └── Support/            Small focused classes: Locale, Settings, SiteCopy, RichText, Help/…
├── bin/                    Deploy and rollback scripts, browser checks, performance budgets
├── config/media.php        Rendition widths, formats, quality and upload limits
├── database/               Migrations, factories, and production and local-only seeders
├── docs/                   Architecture, ADRs, operations guides, audits and user guides
├── lang/{en,ar}/           Interface strings for both languages
├── public/fonts/           Self-hosted, subsetted fonts
├── resources/
│   ├── css/                tokens → base → fonts → components → pages
│   ├── help/               Help Center articles, stored as data
│   ├── js/                 Public entry, motion layer, cinematic engine, dashboard scripts
│   └── views/              Blade layouts, components, page types and dashboard views
├── routes/
│   ├── public.php          Public pages, registered once per language
│   ├── web.php             Locale groups, dashboard extras and legacy redirects
│   └── console.php         Scheduled tasks
├── tests/
│   ├── Feature/            HTTP, Livewire, Filament, security and localisation behaviour
│   ├── Unit/               Pure classes
│   └── Support/            Fixtures and browser checks (*.mjs)
└── .github/workflows/      Build, deploy and rollback workflow
```

## Security

Reviewed against the OWASP Top 10 (2021) and relevant ASVS 4.0 level 2 controls. Findings and evidence are in [`docs/SECURITY_AUDIT.md`](docs/SECURITY_AUDIT.md). No external penetration test has been performed.

| Area | Control |
| --- | --- |
| **Sign-in** | Fortify, 12-character password minimum, per-email and IP throttling, no public registration |
| **Two-factor** | TOTP with recovery codes, required by default; one Owner account can be exempted through an explicit environment setting |
| **Authorisation** | A policy for every content type, with Owner-only user management and permanent deletion |
| **Browser** | Per-request CSP nonce, HSTS, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`, secure session cookies in production |
| **Content** | Blade escaping, rich text sanitised on save, `$fillable` on models, bound queries |
| **Uploads** | Validated by content, size and megapixel limits, re-encoded, no script execution in the upload directory |
| **Personal data** | Enquiry IPs stored hashed, consent recorded |
| **Production guards** | `APP_DEBUG` forced off and logged, development routes limited to `local` |
| **Secrets** | `.env` lives outside every release, and the SSH host key is pinned in CI |

## Performance

Lab results from 23–24 October 2026 using Lighthouse mobile emulation on a production configuration. Method and raw numbers are in [`docs/PERFORMANCE_REPORT.md`](docs/PERFORMANCE_REPORT.md).

| Metric | Budget | English | Arabic |
| --- | --- | --- | --- |
| Lighthouse performance | — | **97** | **97** |
| Largest Contentful Paint | 2.5 s | 2.42–2.49 s | 2.36–2.45 s |
| Total Blocking Time | ≈ 0 | 0 ms | 0 ms |
| Cumulative Layout Shift, after scrolling | 0.1 | 0.0000 | ≤ 0.0010 |
| Home page JavaScript, gzipped | 60 kB | 5.26 kB | same files |
| Public CSS, gzipped | 35 kB | 16.5 kB | same file |

The JavaScript and CSS budgets are enforced by the test suite, so a change that exceeds them fails.

## Testing

```bash
php artisan config:clear && php artisan test    # 1,467 tests, about 2.5 minutes
vendor/bin/pint --test                          # code style
npm run check:browser                           # browser checks, English (needs a running server)
npm run check:browser -- --locale=ar            # browser checks, Arabic
node tests/Support/<name>-check.mjs             # one focused browser check
```

- **PHPUnit** covers HTTP behaviour, Filament and Livewire screens, security headers, uploads, translation completeness, SEO and migrations.
- **Arabic rendering tests** render dashboard screens in Arabic and fail on stray English.
- **Browser checks** drive real Chrome through layout, overflow, motion, fonts and dashboard-to-site round trips. See [`docs/operations/browser-checks.md`](docs/operations/browser-checks.md).

> [!WARNING]
> Never run the PHP suite while the configuration is cached. `bin/perf-budgets.sh` caches the production configuration while it runs, and a cached configuration points the suite at the development database.

## Getting started

**Requirements:** PHP 8.4+ with `gd` or `imagick`, `intl`, `mbstring`, `pdo_mysql`, `exif` and `fileinfo`; Composer 2; Node 20+; MySQL 8+ or MariaDB 11+. Google Chrome is needed only for the browser checks.

```bash
composer install
cp .env.example .env            # then set DB_* and APP_URL
php artisan key:generate
php artisan migrate
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=NavigationSeeder
php artisan db:seed --class=LegalPagesSeeder
php artisan db:seed --class=ServicesSeeder
php artisan storage:link
npm install && npm run build
php artisan make:admin-user     # create the Owner account
```

Run everything together:

```bash
composer dev    # server, both queue workers, log tail and Vite
```

Uploaded photographs stay **In queue** until a worker runs. If an image never becomes Ready, check the worker first:

```bash
php artisan queue:work --queue=media,default
```

<details>
<summary><strong>Optional demonstration content (local only)</strong></summary>

<br>

Every demo seeder refuses to run outside local and testing environments: `DemoContentSeeder`, `DemoServicesSeeder`, `DemoStoriesSeeder`, `DemoJournalSeeder`, `DemoArabicSeeder` and `DemoCinematicSeeder`. Stock photographs need `PEXELS_API_KEY` and `php artisan media:fetch-demo`.

</details>

## Deployment

A push to `production` runs [`.github/workflows/deploy.yml`](.github/workflows/deploy.yml), and [`bin/deploy-remote.sh`](bin/deploy-remote.sh) performs the server side.

```text
~/apps/anasfareed.com/
├── releases/<time>-<sha>/   One directory per deploy, never reused (last 5 kept)
├── shared/.env              Written once on the server, never in Git
├── shared/storage/          Uploads, originals and logs survive every deploy
├── backups/                 Database dump taken before any migration
└── current -> releases/…    Switched atomically
```

- **Migrations never run by themselves.** A release with pending migrations stops before the switch. Run the workflow manually with `run_migrations=true` to back up the database and apply them.
- **Failed health checks roll back.** The script checks `/up` and `/` after the switch and returns to the previous release if either fails.
- **Manual rollback** is available from the workflow with `rollback=true`.

Full guides: [`DEPLOYMENT_GUIDE`](docs/DEPLOYMENT_GUIDE.md) · [`PRODUCTION_CHECKLIST`](docs/PRODUCTION_CHECKLIST.md) · [`ROLLBACK_AND_RECOVERY`](docs/ROLLBACK_AND_RECOVERY.md)

## Commands

| Command | Purpose |
| --- | --- |
| `php artisan make:admin-user` | Create an Owner account (production-safe) |
| `php artisan site:upgrade-copy --dry-run` | Update only the site sentences the owner never edited |
| `php artisan content:rerender-html` | Rebuild stored rich-text HTML after a renderer change |
| `php artisan media:backfill-exif` / `media:backfill-palette` | Fill camera data or accent colours for older photographs |
| `php artisan activity:prune` | Remove activity entries past retention (scheduled nightly) |
| `php artisan admin:clear-two-factor` | Reset a person's two-factor (refuses in production) |
| `php artisan help:coverage` | Measure Help Center coverage of the live dashboard |
| `php artisan media:fetch-demo` / `media:purge-demo` | Add or remove local demo photographs |
| `bin/perf-budgets.sh LABEL` | Run Lighthouse budgets on the production rig |

## Documentation

| For | Read |
| --- | --- |
| Understanding the code | [`ARCHITECTURE`](docs/ARCHITECTURE.md) |
| Making a change | [`CONTRIBUTING`](docs/CONTRIBUTING.md) |
| Design system, RTL and motion | [`FRONTEND_DESIGN_SYSTEM`](docs/FRONTEND_DESIGN_SYSTEM.md) |
| Keeping it running | [`MAINTENANCE_GUIDE`](docs/MAINTENANCE_GUIDE.md) |
| Using the dashboard | [English](docs/DASHBOARD_USER_GUIDE_EN.md) · [العربية](docs/DASHBOARD_USER_GUIDE_AR.md) |
| Evidence | [`QA`](docs/QA_TEST_REPORT.md) · [`Performance`](docs/PERFORMANCE_REPORT.md) · [`Security`](docs/SECURITY_AUDIT.md) · [`CMS coverage`](docs/CMS_COVERAGE_MATRIX.md) · [`Help coverage`](docs/HELP_CENTER_COVERAGE.md) |
| Why it was built this way | [`Decision records`](docs/decisions/) · [`Operations notes`](docs/operations/) |
| Status and handover | [`PROJECT_HANDOVER`](docs/PROJECT_HANDOVER.md) · [`FINAL_PROJECT_AUDIT`](docs/FINAL_PROJECT_AUDIT.md) |

## Licence

Application code is proprietary, all rights reserved. Fonts in `public/fonts/` are under the SIL Open Font License, with licence files beside them; the Arabic and figures subsets are derived from those fonts under the same licence.

---

<div align="center">
<sub><a href="https://anasfareed.com">anasfareed.com</a> · Maintained by <a href="https://github.com/m77mdabdo">Mohamed Abdo</a></sub>
</div>
