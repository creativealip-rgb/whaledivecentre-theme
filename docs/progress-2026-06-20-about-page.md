# Progress — About Page Refresh (2026-06-20)

## Scope

Refresh local Whale Dive Centre About page based on supplied WDC About document and crew photos.

## Environment

- Local WordPress: `whaledive-local-wp`
- Local URL: `http://168.144.37.19:8088/`
- About URL: `http://168.144.37.19:8088/about/`
- Repository: `/root/projects/whaledivecentre-theme`
- Branch: `main`
- Theme version: `2.2.3`
- Live cPanel: `https://whaledivecentre.com/` (not yet synced with latest local About changes)

## Files Changed

- `page-about.php`
- `functions.php`
- `style.css`
- `README.md`
- `assets/wdc-about-ebram-pool.jpg`
- `assets/wdc-about-mimi-pool.jpg`
- `assets/wdc-about-jovan.jpg`

## Completed Work

### About Content

- Rebuilt About page narrative from supplied WDC About document.
- Added institution positioning:
  - founded in 2009
  - headquartered in Jakarta
  - NAUI Indonesia Headquarters
  - affiliation with NAUI, TDI, and DAN
  - focus on diver education, safety, underwater exploration, and professional development
- Added training focus section for recreational, professional, and technical/safety pathways.

### Leadership Profiles

Added profile cards for:

- Ebram Harimurti — NAUI Course Director, NAUI Rep. Indonesia, TDI Instructor, DAN Instructor Trainer
- Mimi Amilia — NAUI Instructor, DAN Instructor, TDI Diver
- Jovan Lesmana — NAUI Instructor

Crew photo treatment:

- Standardized profile image display to fixed `220x220` sizing.
- Used `object-fit: cover` for consistent crop.
- Reduced card padding/gap to avoid empty whitespace.
- Kept cards equal height on desktop.

### Navbar Fixes

- Added `Tentang` item to global public navbar.
- Set active navbar state for `/about/` and `/tentang/` detection.
- Fixed navbar About URL from `/tentang/` to `/about/`.
- Matched language switcher and `Masuk` spacing across Home and About.
- Verified final language-to-login visual gap: `24px`.

### Values + Contact Polish

`Nilai Kerja`:

- Increased card contrast.
- Added border/shadow/accent treatment.
- Improved number badge hierarchy.
- Added subtle hover lift.
- Improved section background separation.

`Hubungi Kami`:

- Made left contact info cards more distinct.
- Added stronger visual treatment for contact rows.
- Upgraded form into clearer elevated card.
- Added form header: `Kirim pesan ke crew`.
- Made Google Maps link feel like a clearer CTA.
- Improved input spacing and section balance.

### Bug Fixes

- Fixed blank About page render caused by missing `</head>` after inline style block in `page-about.php`.
- Rebuilt page head area to close properly before `<body>`.

## Verification

Local checks completed:

```text
http://168.144.37.19:8088/about/ -> 200
page-about.php PHP syntax -> OK
functions.php PHP syntax -> OK
```

Browser checks completed:

- About page loads locally.
- Navbar appears with correct `Tentang` link and active state.
- Crew images render with equal visual dimensions.
- Values section has stronger card separation.
- Contact section has clearer card/form hierarchy.
- No visible overlap in polished sections.

## Not Done Yet

- Changes are local only.
- Live cPanel `https://whaledivecentre.com/about/` does not yet contain latest About page refresh.
- Need upload/sync to cPanel theme folder:
  - `page-about.php`
  - `functions.php`
  - `style.css`
  - `assets/wdc-about-ebram-pool.jpg`
  - `assets/wdc-about-mimi-pool.jpg`
  - `assets/wdc-about-jovan.jpg`

## Current Git State Notes

Expected modified/untracked files after this work:

```text
 M README.md
 M functions.php
 M page-about.php
 M style.css
?? assets/wdc-about-ebram-pool.jpg
?? assets/wdc-about-jovan.jpg
?? assets/wdc-about-mimi-pool.jpg
?? docs/progress-2026-06-20-about-page.md
```
