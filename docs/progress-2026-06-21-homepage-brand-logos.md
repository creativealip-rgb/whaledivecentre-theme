# Progress — Homepage Brand Logo Strip (2026-06-21)

## Scope

Update Whale Dive Centre homepage brand/affiliation presentation, focused on Welcome and Courses sections.

## Environment

- Repository: `/root/projects/whaledivecentre-theme`
- Live temp working copy: `/tmp/whaledivecentre-theme-main-fresh`
- Theme file set: WordPress PHP theme
- Theme version noted in `style.css`: `2.2.3`

## Files Changed

Source repo:

- `/root/projects/whaledivecentre-theme/index.php`
- `/root/projects/whaledivecentre-theme/style.css`

Live temp working copy:

- `/tmp/whaledivecentre-theme-main-fresh/index.php`
- `/tmp/whaledivecentre-theme-main-fresh/style.css`

## Completed Work

### Courses Section Affiliation Move

Moved training affiliations into `Kursus Selam Kami` section.

Previous state:

- Separate `Afiliasi Kami` / `Our Affiliations` section existed in older position.

New state:

- Old standalone affiliation section removed from live index.
- New affiliation strip inserted after course subtitle and before course cards.
- Wrapper class: `wd-course-affiliations`
- Logo pill class: `wd-logo-pill`

Current course affiliation content:

- `NAUI` — `Safety standards`
- `TDI` — `Technical pathway`
- `DAN` — `Emergency awareness`

Styling added:

- Dark translucent pill cards.
- 3-column desktop grid.
- Single-column mobile layout.
- Strong white logo text with small uppercase descriptor.

### Welcome Section Brand Logo Strip

Added new brand/logo strip under Welcome section subtitle.

Target section:

- `Selamat Datang di Whale Dive Centre`
- English: `Welcome to Whale Dive Centre`

Added markup:

- Wrapper class: `wd-welcome-logos`
- Row class: `wd-welcome-logo-row`

Line 1:

- `NAUI`
- `TDI`
- `DAN`

Line 2:

- `Sherwood`
- `Zeagle`
- `Waterproof`
- `Shearwater`

Styling added:

- Centered white pill badges.
- Soft border and shadow.
- Blue brand text.
- Uppercase styling.
- Responsive wrapping for mobile.

### Badge Number Guidance

Design decision for card number badges:

- Keep number badge positioned at top-left of cards.
- Do not horizontally center badge in card.
- Center number inside circle only.
- Reason: left-top badge feels more modern/editorial; centered badge would feel more corporate/template.

## Verification

Completed checks:

- `Afiliasi Kami` removed from live index.
- `wd-course-affiliations` exists.
- `DAN` exists in course affiliation strip.
- `wd-welcome-logos` exists in source and live temp index.
- `wd-welcome-logo-row` CSS exists in source and live temp stylesheet.
- `Sherwood`, `Zeagle`, `Waterproof`, `Shearwater` inserted in Welcome section.

Attempted syntax check:

```text
php -l /root/projects/whaledivecentre-theme/index.php && php -l /tmp/whaledivecentre-theme-main-fresh/index.php
```

Result:

```text
/usr/bin/bash: line 3: php: command not found
```

Reason:

- PHP CLI is not installed in current environment, so PHP lint could not run here.

## Current Status

Ready to continue from:

- Homepage Welcome section brand logo strip.
- Homepage Courses section affiliation strip.

## Next Suggested Work

1. Open homepage in browser and visually inspect spacing.
2. Confirm Welcome logo strip looks good on desktop and mobile.
3. Decide whether Welcome strip should use real logo images instead of text pills.
4. If real logos needed, add assets under `assets/` and swap text pills to `<img>` markup.
5. Sync final source files to production/cPanel when ready.

## Notes

- Both source repo and live temp working copy were updated.
- Existing LSP diagnostics about WordPress functions like `wp_kses_post` and `esc_html` are environment-related, not introduced runtime errors.
