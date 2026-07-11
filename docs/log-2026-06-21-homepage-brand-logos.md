# Log — Homepage Brand Logos (2026-06-21)

## Summary

Homepage updated with brand/logo text strips in Welcome and Courses sections.

## Changed Files

- `index.php`
- `style.css`
- `docs/progress-2026-06-21-homepage-brand-logos.md`
- `docs/log-2026-06-21-homepage-brand-logos.md`

## Work Log

1. Checked latest project progress from prior session.
2. Located homepage Welcome section in `index.php`.
3. Found existing Courses affiliation strip styles in `style.css`.
4. Added Welcome logo strip under Welcome subtitle:
   - Line 1: `NAUI`, `TDI`, `DAN`
   - Line 2: `Sherwood`, `Zeagle`, `Waterproof`, `Shearwater`
5. Added CSS for `wd-welcome-logos` and `wd-welcome-logo-row`.
6. Applied same changes to source repo and live temp working copy.
7. Verified class names and inserted brands via file search.
8. Tried PHP lint, blocked because PHP CLI is missing.

## Verification Result

Pass:

- `wd-welcome-logos` found in `index.php`.
- `wd-welcome-logo-row` styles found in `style.css`.
- Welcome brand list added.
- Existing course affiliations remain.

Blocked:

- `php -l` unavailable because `php` command not installed.

## Continue Later From

Visual QA for homepage Welcome logo strip.
