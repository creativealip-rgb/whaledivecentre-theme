# Whale Dive Centre — All-Pages UI/UX Consistency Spec

Date: 2026-05-30 · Author: UI UX Designer (Kiro) · Scope: Courses, Equipment, Blog, Single Post
Companion to: UIUX-spacing-spec-2026-05-30.md (homepage)

## Card design tokens (now unified site-wide)
- Card radius: 18px (standard cards), 24px (large panels: story article, featured)
- Card shadow: 0 14px 34px rgba(2,21,43,.07)
- Card border: 1px solid rgba(6,56,77,.08)
- Card body padding: 16px
- Card title line-height: 1.28
- Card link / mini-CTA: 13px, pill radius 999px
- Catalog grid gap: 22px (courses/equipment), 20px (blog)
- Card media height: 165px (courses), 190px (equipment — gear needs room), 16:9 (blog)
- Section vertical padding: 72px desktop / 56px mobile

## Per-page changes applied

### Courses (page-courses.php — inline element styles + inline <style>)
- grid gap 16px → 22px
- course photo (.wd-equip-visual) 138px → 165px
- card body padding 14px → 16px
- h3 line-height 1.08 → 1.28
- link font-size 12px → 13px
- card radius already 18px ✓

### Equipment (page-equipment.php — inline element styles + inline <style>)
- grid gap 16px → 22px
- card body padding 14px → 16px
- h3 line-height 1.08 → 1.28
- link font-size 12px → 13px
- visual kept at 190px (gear products need contain-fit room)

### Blog (page-blog.php — appended normalization <style> block, last source order)
- card radius 26/28px → 18px (needed body.whaledive-blog article.wd-blog-card-compact to beat article-level specificity)
- grid gap 16px → 20px
- card body padding 14px → 16px
- card title clamp(15px,1.18vw,18px) → clamp(16px,1.25vw,18px), line-height 1.16 → 1.28

### Single post (style.css — appended normalization block at EOF)
- story article radius 28px → 24px
- side card radius 28px → 18px
- story cover aspect-ratio 16/6.8 → 16/9 (less letterboxed)
- content kept at 19px / 1.9 line-height (already good readability)

## Cache busting
Theme stylesheet version bumped in functions.php (line 24): 2.2.1 → 2.2.2.
Required because style.css is browser-cached by ?ver string; inline-PHP page styles refresh
automatically but external style.css changes (single post) need the version bump to reach visitors.

## Root cause (recurring)
Theme has many stacked dated "tightening pass" override blocks (2026-05-14/16) in style.css and
inline <style> blocks, plus inline element styles with !important from PHP. Fix strategy:
1. Inline element style (PHP) → edit at source (only thing that beats inline !important)
2. Inline <style> block rules → append one normalization block last (wins source order)
3. External style.css rules → append at EOF + bump ?ver

## Backups
*.bak-uiux-20260530T053518 for page-courses/equipment/blog, single.php, style.css.
index.php.bak-uiux-20260530T051620 (homepage).

## Non-UI flag (not changed — needs Writer)
Footer + single-post contact still show WhatsApp/Phone (021) 27939068. Per user preference,
contact should route to centralized contact form, not WhatsApp. Copy/content task → Writer.

## Status
All applied to LOCAL only (whaledive-local-wp container). Not pushed to live/cPanel.
