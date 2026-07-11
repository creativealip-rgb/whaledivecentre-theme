# Whale Dive Centre — Detail & Static Pages UI/UX Consistency Spec

Date: 2026-05-30 · Author: UI UX Designer (Kiro)
Scope: About, Course Detail, Equipment Detail (+ static panel radius normalization)
Companion to: UIUX-spacing-spec (home), UIUX-allpages-spec (courses/equipment/blog/single)

## Problem found
Panel radius was inconsistent across detail/static pages: 22px, 24px, 26px, 28px, 32px mix.
This broke the premium "unified system" feel — the same visual element had different corners
on different pages.

## Radius token (now enforced site-wide)
- Standard cards (catalog, blog, side cards): 18px
- Large panels (hero cards, sidebars, story article, detail visuals): 24px
- Pills (buttons, chips, kickers): 999px

## Changes applied

### About page (/about/)
- Section rhythm already good (72/72 consistent) ✓
- Crew cards (.wd-instructor-grid article): 28px → 24px
- Contact form already present + well-styled (input radius 12px, h46px) ✓ — matches user
  preference for centralized contact form over WhatsApp

### Course Detail (single-wm_course / whaledive-single-course)
- Hero card (.wd-course-hero-card): kept/normalized 24px ✓
- Sidebar card (.wd-sidebar-card): 22px → 24px
  (needed body.whaledive-single-course .wd-sidebar-card to beat existing specificity)
- Primary CTA "Request Enrollment": confirmed OK — blue gradient + shadow, pill 999px, h48px
- Price prominence: hero shows price; fee box prominent. Acceptable.

### Equipment Detail (page-equipment-detail.php — inline <style>)
- Gear visual card (.wd-gear-visual-card): 32px → 24px (biggest outlier)
- Gear sidebar (.wd-gear-sidebar): 22px → 24px
- Price strong: 28px, good prominence ✓

## Implementation notes
- Inline-PHP <style> edits (gear cards) auto-refresh, no cache bump needed.
- style.css rule additions REQUIRE version bump. Bumped functions.php line 24 across this session:
  2.2.1 → 2.2.2 → 2.2.3 → 2.2.4 → 2.2.5 (final).
- Specificity gotcha: theme uses body-class + element selectors (e.g. body.whaledive-single-course,
  .wd-instructor-grid article). Class-only !important rules lose. Match or exceed specificity.

## Backups
*.bak-uiux-20260530T053518 (courses/equipment/blog/single/style.css)
index.php.bak-uiux-20260530T051620 (home)
page-equipment-detail.php edited in place (inline style only).

## Flags for other agents (NOT UI — not changed)
1. WhatsApp/Phone (021) 27939068 still in footer + about contact block + detail pages.
   Per user preference, route contact to centralized form. → Writer (copy) / Coder (template).
2. Equipment detail page (/equipment/masks/) renders with body class `error404` despite showing
   correct content — template routing/SEO bug. → Coder.

## Status
LOCAL only (whaledive-local-wp container). Not pushed to live/cPanel.
Final CSS version: 2.2.5.
