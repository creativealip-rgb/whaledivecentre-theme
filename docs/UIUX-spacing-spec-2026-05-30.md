# Whale Dive Centre — Home UI/UX Spacing & Card Spec

Date: 2026-05-30 · Author: UI UX Designer (Kiro) · Scope: Homepage (`index.php`, `.whaledive-home`)

## Problem
`style.css` accumulated 15+ stacked "tightening pass" override blocks (dated 2026-05-14),
each one cramming sections/cards tighter. End result: broken vertical rhythm and over-compressed
cards on desktop. Worst offender: `#courses` section forced to `48px / 30px` padding (vs 72px standard).

Fix layer: inline `<style id="wd-home-card-sync">` in `index.php` head (loads after style.css,
same/greater specificity + `!important` + later source order = wins cleanly without adding more
random overrides to style.css).

## Design tokens (home sections + cards)

Section vertical padding
- Desktop: 72px top / 72px bottom (ALL home sections, unified)
- Mobile (<=760px): 56px top / 56px bottom

Card grid (courses + equipment)
- Desktop gap: 22px  (was 14px — matched to testimonial rhythm)
- Mobile gap: 16px
- Columns: repeat(auto-fit, minmax(240px,1fr)) → 4-up desktop, 1-up mobile

Card container
- radius 18px · bg #fff · border 1px rgba(6,56,77,.08) · shadow 0 14px 34px rgba(2,21,43,.07)
- inner body padding: 16px (was 14px)

Card media (photo)
- height 165px desktop / 150px mobile (was 138px) → ~16:10 ratio, stronger visuals

Card typography
- Title h3: 20px, line-height 1.28 (was 1.05/1.08), margin 0 0 6px, min-height removed
- Body p: 13px / line-height 1.45
- Link/CTA: font-size 13px (was 12px), pill radius 999px, min-height 38px

## Before → After (measured)
- #courses section padding: 48/30px → 72/72px
- #equipment section padding: 72/64px → 72/72px
- course+equipment grid gap: 14px → 22px
- card body padding: 14px → 16px
- card photo height: 138px → 165px
- card title line-height: 1.05/1.08 → 1.28
- card link font-size: 12px → 13px

## What stays unchanged (already correct)
- Welcome / Affiliations / Testimonials / Articles / Membership = already 72px ✓
- Container max-width 1180px, 22px horizontal padding ✓
- Card radius/shadow/border, pill buttons ✓
- Mobile responsive collapse to 1 column ✓

## Implementation
Single normalization block appended to inline `<style id="wd-home-card-sync">` in `index.php`,
just before `</style></head>`. Backup: `index.php.bak-uiux-<timestamp>`.
