# Whale Dive Centre — Member / Dashboard Area UI/UX Audit

Date: 2026-05-30 · Author: UI UX Designer (Kiro)
Scope: login, register, dashboard, my-courses, my-gear, my-bookings, settings, profile,
my-account, membership, my-travels (+ admin/* member views)

## ✅ FIXED & VERIFIED (auth pages — publicly renderable)
- Login (/member-login/) + Register (/member-register/)
- Auth card (.wdc-auth-card): radius 30px → 24px (panel token)
- Input (.wdc-auth-field input): radius 16px → 12px (match site forms on About)
- Input font-size: 13.3px → 16px (prevents iOS Safari auto-zoom on field focus — important mobile fix)
- Verified at CSS ver 2.2.6, visual check passed.

## 🔴 SYSTEMIC ISSUE FOUND (dashboard area — login-gated, NOT yet changed)

The entire logged-in member area uses a GENERIC TAILWIND SLATE palette instead of the
brand OCEAN palette. This makes the dashboard feel like a different product than the public site.
Found 119 occurrences across 7 files.

### Off-brand colors in use (generic slate)
- #0f172a (slate-900) — headings
- #64748b (slate-500) — muted text
- #e2e8f0 (slate-200) — borders
- #f8fafc (slate-50) — panel backgrounds
- #94a3b8 / #475569 / #cbd5e1 / #1e293b — misc text/borders

### Should map to brand tokens
- Ink/heading:   #0f172a → #06384d (or #0b1930)
- Muted text:    #64748b → #5f7180 / #607684
- Border:        #e2e8f0 → rgba(6,56,77,.08) or #ccecf5 (ocean-tinted)
- Panel bg:      #f8fafc → #f5fbff / #f7fbfd (ocean-tinted off-white)
- Accent (keep): #06384d, #0b617c, #08a7c7, #4cc8ed already brand-correct in places

### Radius inconsistency in dashboard
- Mix of 12px / 14px / 16px / 20px inline. Should follow token: cards 18px, large panels 24px,
  inner chips/rows 12-14px (acceptable for nested elements), pills 999px.

## Why dashboard NOT auto-fixed yet
1. Pages are gated behind member login — cannot render/visually verify changes in this environment
   (no member test credentials).
2. Colors are INLINE styles in PHP (119 spots across 7 files). A blind global find-replace risks
   breaking semantic state colors (success #dcfce7/#166534, error #fee2e2/#991b1b — these are
   intentional status colors and must be PRESERVED, not recolored to ocean).
3. Safe path = controlled implementation with verification, ideally with a member test account.

## Recommendation (for execution)
Option A (safe, recommended): Create a test member account → render each dashboard page →
apply brand-token mapping page by page with visual verification. Preserve status colors.
Option B (faster, riskier): Centralize dashboard CSS into a `.wd-member` scoped block in style.css,
replace inline slate hex with brand tokens via careful mapping, keep status colors. Verify after login.

Either way: status/semantic colors (success green, error red, warning amber) stay as-is.

## Flags for other agents
- Contact still shows WhatsApp/Phone in footer (see prior specs) → Writer/Coder.
- Implementation of dashboard re-skin is heavy template work → Coder (with this spec as guide).

## ✅ DASHBOARD RE-SKIN — DONE (Option A: verified with test login)

Created a test member account (subscriber) to render and visually verify each logged-in page.

### What was already in place (sibling agent prior work)
dashboard-header.php already had a brand-override block (line ~146+): body bg, sidebar,
card border/shadow → ocean tokens via later-source !important. CSS vars defined:
--wdc-deep:#061a36, --wdc-blue:#004A98, --wdc-muted:#63748a, etc.

### Root cause found & fixed (this session)
An OLDER rule block using :has() still hardcoded slate with !important and OUT-RANKED the
brand override on specificity:
- `.dashboard-main > div:has(> h1) h1 { color:#0f172a !important }` (won over `.dashboard-main h1`)
Fixed at source in dashboard-header.php:
- heading strip h1 / page-title: #0f172a → var(--wdc-deep)
- heading strip p / subtitle: #475569 → #5f7180
- .user-email: #94a3b8 → #5f7180

### Added (shared header — covers ALL member pages at once)
Attribute-selector overrides scoped to .dashboard-main that remap leftover INLINE slate to brand:
- [style*="color:#0f172a"] → var(--wdc-deep)
- [style*="color:#64748b/#475569/#94a3b8"] → #5f7180
- [style*="background:#f8fafc"] → #f5fbff
- [style*="border:1px solid #e2e8f0"] → rgba(0,74,152,.12)
- .dashboard-main input/select/textarea → font-size:16px (prevents iOS focus zoom)
STATUS COLORS PRESERVED: success #dcfce7/#166534, error #fee2e2/#991b1b, warning amber — untouched.

### Verified (rendered DOM + vision, logged in as test member)
- dashboard: h1/h2 = rgb(6,26,54) ✓, email/muted = #5f7180 ✓, cards 18-20px, slateTextHits {} ✓
- my-courses: card H3 navy ✓, inputs 16px ✓, slateTextHits {} ✓, 6-card grid consistent
- my-gear: card H3 navy ✓, price #06384d ✓, slateTextHits {} ✓
- settings: inputs 12px radius / 48px / 16px font ✓, slateTextHits {} ✓
- my-bookings/profile/membership → redirect to active pages (my-courses/settings/dashboard)
Vision passes: dashboard + my-courses confirmed cohesive, premium, on-brand. No off-brand gray.

### Files changed (member area)
- dashboard-header.php (inline <style>, shared across all member pages) — auto-refresh, no cache bump.

## Status
Auth pages: DONE (CSS 2.2.6). Dashboard area re-skin: DONE & VERIFIED (Option A).
LOCAL only — not pushed to live/cPanel.
Test member account created for QA: login `uiuxtester` (subscriber). Remove before/after handoff if desired.
