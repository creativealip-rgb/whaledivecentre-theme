# Whale Dive Centre — Closing CTA Normalization Spec

Date: 2026-05-30 · Author: UI UX Designer (Kiro)
Scope: End-of-page closing CTA across all public pages (home, courses, equipment, blog, single post, about)

## Problem found (audit)
Closing CTAs were inconsistent across pages:
1. Panel radius 30px on courses/equipment/single bottom CTA + form card 32px on about → outliers vs 24px panel token.
2. Closing CTA button used `.wd-btn.alt` GHOST style (bg rgba(76,200,237,.12) + blue text) — the final
   conversion button was the FAINTEST on the page, weaker than the hero buttons above it.
3. Bottom rhythm before footer inconsistent: courses/equipment/single padB 48px, about 72px, blog none.
4. Blog had NO closing CTA at all — went straight from pagination to footer.
5. About contact submit button font 13.3px (too small for a primary CTA).

## Fixes applied (style.css EOF block + page-blog.php)
All scoped to closing-CTA containers; status colors & hero/header .alt buttons untouched.

### Panel radius → 24px token
- `.wdc-card-cta .wd-shell`, `.wd-story-bottom-cta .wd-shell` → border-radius 24px
- `.wd-contact-form` (about) → border-radius 24px

### Closing CTA button → PRIMARY solid
- `.wdc-card-cta .wd-btn.alt`, `.wd-story-bottom-cta .wd-btn.alt`, `.wd-community .wd-btn.alt`:
  background linear-gradient(135deg, #004A98, #3B44AC), color #fff, box-shadow 0 12px 30px rgba(0,74,152,.28)
- Hover: reversed gradient + deeper shadow

### Uniform bottom rhythm
- `.wdc-card-cta`, `.wd-story-bottom-cta` → padding-bottom 64px (mobile 48px)

### About submit button
- `.wd-contact-form button[type="submit"]` → font-size 16px, font-weight 800, min-height 48px

### Blog closing CTA (new)
- Added `<section class="wdc-card-cta">` before footer in page-blog.php with kicker "Mulai dive kamu",
  heading "Siap ambil langkah pertama di bawah air?", button "Tanya Crew" → inherits all CTA normalization.

## Verified (rendered DOM + vision, CSS 2.2.8)
- courses: shell 24px ✓, padB 64px ✓, btn gradient+white+shadow ✓
- equipment: same pattern ✓
- blog: NEW CTA present, shell 24px ✓, padB 64px ✓, btn gradient ✓
- single post: shell 24px ✓, padB 64px ✓, btn gradient ✓
- about: form card 24px ✓, submit font→16px
- home: community CTA "Buat Akun Gratis" now solid gradient+white+shadow ✓ (vision confirmed prominent)

## Files changed
- style.css (EOF closing-CTA normalization block)
- page-blog.php (added closing CTA section)
- functions.php (cache version 2.2.6 → 2.2.8)

## Token reference (closing CTA)
- Panel radius: 24px
- Button: gradient #004A98→#3B44AC, white text, pill 999px, h48, shadow 0 12px 30px rgba(0,74,152,.28)
- Section padding-bottom before footer: 64px desktop / 48px mobile

## Status
DONE & VERIFIED. LOCAL only (port 8088) — not pushed to live/cPanel.