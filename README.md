# Whale Dive Centre Theme

Custom WordPress theme for the Whale Dive Centre public site, member dashboard, course catalogue, equipment rental flow, and direct-payment member ordering.

## Status

- Theme version: 2.2.3 (see `style.css`)
- Active development branch: `main`
- Local dev: `http://168.144.37.19:8088/` via `whaledive-local-wp`
- Live cPanel: `https://whaledivecentre.com/` (About page not yet synced with latest local changes)
- Production/deploy notes live in `docs/production-checklist.md`

## Recent Progress

### About Page Refresh (local only, 2026-06-20)

Files changed:

- `page-about.php`
- `functions.php`
- `style.css`
- `assets/wdc-about-ebram-pool.jpg`
- `assets/wdc-about-mimi-pool.jpg`
- `assets/wdc-about-jovan.jpg`

Completed:

- Rebuilt About page content from supplied WDC About document.
- Added WDC institutional intro: founded 2009, Jakarta, NAUI Indonesia Headquarters, NAUI/TDI/DAN affiliation.
- Added leadership profiles for Ebram Harimurti, Mimi Amilia, and Jovan Lesmana.
- Standardized crew profile photos to fixed `220x220` presentation with compact profile cards.
- Polished `Nilai Kerja` cards with stronger contrast, border, shadow, badge, and hover treatment.
- Polished `Hubungi Kami` section with distinct contact cards, stronger form card, compact inputs, and clearer Google Maps CTA.
- Fixed broken About page blank render caused by missing `</head>` in `page-about.php`.
- Added `Tentang` to global navbar and set active state for `/about/`.
- Fixed navbar `Tentang` URL from `/tentang/` to `/about/`.
- Matched language switcher to `Masuk` spacing across Home and About (`24px` visual gap).

Verified locally:

- `http://168.144.37.19:8088/about/` returns `200`.
- `page-about.php` and `functions.php` pass PHP syntax checks inside `whaledive-local-wp`.
- Latest About content appears locally; live cPanel still needs file sync/upload.

## Main Features

### Public Site

- Whale Dive Centre branded homepage, inner pages, blog archive, and article templates.
- Responsive navigation and shared footer across public pages.
- Polished course, equipment, blog, and journal card layouts.
- Local iBrand font support through `assets/fonts/ibrand.otf`.

### Course And Equipment Catalogue

- Course custom post type support through `wm_course`.
- Equipment custom post type support through `wm_equipment`.
- Course taxonomies including `course_level` and `course_agency`.
- Equipment taxonomies including `equipment_category` and `equipment_brand`.
- Dedicated archive/detail templates for courses, equipment, and dive sites.

### Member Area

- Member-facing pages for dashboard, courses, gear, travels, rewards, reviews, notifications, wishlist, and settings.
- Login and registration templates.
- Dashboard header/footer components for member pages.
- Direct course/equipment checkout flows with order status handling.

### Payments And Admin

- Manual payment proof upload flow.
- Payment proof storage expected at `wp-content/uploads/wdc-payment-proofs/`.
- Member/admin pages for dashboard, members, payments, and reports.
- Midtrans/manual payment integration files are included for payment configuration work.

## Important Files

| Path | Purpose |
| --- | --- |
| `style.css` | Main theme stylesheet and WordPress theme metadata. |
| `functions.php` | Theme setup, custom post types, member/order logic, and AJAX hooks. |
| `plugin-integration.php` | Integration helpers for Whale Dive/WordPress plugin data. |
| `manual-payment-handler.php` | Manual payment proof handling. |
| `midtrans-gateway.php` | Midtrans gateway integration. |
| `page-courses.php` | Public courses page. |
| `page-equipment.php` | Public equipment page. |
| `page-checkout.php` | Checkout flow. |
| `page-my-courses.php` | Member course orders page. |
| `page-my-gear.php` | Member gear/equipment orders page. |
| `admin/` | WDC member admin screens. |
| `scripts/` | Local sync, production deploy, and catalogue QA scripts. |

## Installation

Upload this directory to the active WordPress install as:

```bash
wp-content/themes/whaledivecentre-theme
```

Then activate it from WordPress admin:

```bash
wp theme activate whaledivecentre-theme
```

If using SFTP/rsync, make sure the theme folder name stays consistent with the deployed path and any scripts that reference it.

## Required WordPress Data

Courses (`wm_course`) should include:

- `_wm_price`
- `_wm_duration`
- `course_level` taxonomy term

Equipment (`wm_equipment`) should include:

- `_wm_price`
- `_wm_stock`

The payment proof upload directory must be writable by the web server:

```text
wp-content/uploads/wdc-payment-proofs/
```

## Development Notes

- Keep generated deploy backups out of git; `deploy-backups/` is ignored intentionally.
- Avoid committing production credentials, API keys, payment secrets, or uploaded proof files.
- Use the checklist in `docs/production-checklist.md` before pushing to production.

## QA

Run PHP syntax checks before deploying changed PHP files:

```bash
find . -path './deploy-backups' -prune -o -name '*.php' -print0 | xargs -0 -n1 php -l
```

Run catalogue data QA from the production WordPress root:

```bash
php wp-content/themes/whaledivecentre-theme/scripts/check-catalog-data.php /path/to/wordpress
```

## Deploy

Use the deploy helper with explicit target paths:

```bash
REMOTE_THEME_PATH=/path/to/wp-content/themes/whaledivecentre-theme \
HEALTH_URL=https://example.com/ \
./scripts/deploy-production.sh
```

The helper backs up the current remote theme into `deploy-backups/`, syncs this checkout, and optionally checks `HEALTH_URL`.

## Post-Deploy Smoke Test

- Login as a test member.
- Open `/my-courses/` and checkout one course.
- Upload a dummy payment proof.
- Verify the order in `WDC Members > Direct Orders`.
- Open `/my-gear/` and checkout one in-stock equipment item.
- Activate it in admin and confirm stock decrements.
- Cancel it and confirm stock restores.
- Set an equipment item to stock `0` and confirm checkout is blocked.
