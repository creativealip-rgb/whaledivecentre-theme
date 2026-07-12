# Whale Dive Centre — Soft Handover

**Tanggal:** 12 Juli 2026
**Website:** https://whaledivecentre.com
**Status:** Soft handover ready
**Commit:** `6d1be07` · branch `local/polish-20260711` · CSS `2.3.93`

---

## 1. Ringkasan Status

Website Whale Dive Centre sudah live dan siap soft handover ke client.

Core flow yang sudah jalan:

- Public site: homepage, courses, equipment, about/contact, blog, 404 bilingual.
- Member area: register, login, dashboard, my courses, my gear, settings, giveaway claim.
- Commerce: harga publik; CTA Daftar/Beli login-gated; order WA-first + upload bukti TF.
- Admin: WDC Site (konten) + WDC Members (ops member/request/giveaway/payment settings).
- Bilingual chrome custom: ID default + /en/ (EN UI chrome). Isi artikel tidak auto-translate.
- Giveaway: cek ongkir eksternal + cek resi eksternal + upload SS + transfer exact.

Status rekomendasi: Soft handover ready. Residual client-owned: rekening bank TF, SMTP inbox test (opsional), konten final (crew/testimonial/harga/stok).

---

## 2. URL Utama

Live site: https://whaledivecentre.com

Local preview: http://168.144.37.19:8088

- **Homepage ID:** https://whaledivecentre.com/
- **Homepage EN:** https://whaledivecentre.com/en/
- **Courses:** https://whaledivecentre.com/courses/
- **Equipment:** https://whaledivecentre.com/equipment/
- **About + Contact:** https://whaledivecentre.com/about/
- **Blog:** https://whaledivecentre.com/blog/
- **Member Login:** https://whaledivecentre.com/login/
- **Member Register:** https://whaledivecentre.com/member-register/
- **EN Login:** https://whaledivecentre.com/en/login/
- **EN Register:** https://whaledivecentre.com/en/member-register/
- **404 example:** https://whaledivecentre.com/en/aaa
- **Maps:** https://maps.app.goo.gl/7A3Yo7gsaDCcS6xZ6
- **Cek Ongkir:** https://berdu.id/cek-ongkir
- **Cek Resi:** https://cekresi.com/

---

## 3. Kontak & Brand yang Aktif

- **Email admin:** whaledivecentre@gmail.com
- **WhatsApp / telepon display:** 0821-2666-611
- **WhatsApp digits:** 628212666611
- **Instagram:** https://www.instagram.com/whaledivecentre.id/
- **Alamat:** Jl. Tanah Kusir II No.3, Kebayoran Lama, Jakarta Selatan 12240
- **Google Maps:** https://maps.app.goo.gl/7A3Yo7gsaDCcS6xZ6
- **Jam operasional (compact):** Senin–Sabtu, 09:00–18:00 WIB / Mon–Sat, 09:00–18:00 WIB

Catatan: public site tidak hard-sell lewat floating WA sebagai sales pitch. Contact form + member/login-gated flow lebih diutamakan. Float WA tetap ada di mu-plugin untuk akses cepat.

---

## 4. Flow Customer / Member

### A. Register

1. Buka /member-register/ (EN: /en/member-register/).
2. Isi data akun lalu buat akun.
3. Setelah sukses, member masuk dashboard.

### B. Login

1. Buka /login/ (EN: /en/login/).
2. Masukkan email/username + password.
3. Diarahkan ke /dashboard/.

### C. Daftar kursus / beli gear (login-gated)

1. Browse catalog public (harga terlihat, label “Harga mulai”).
2. Klik Daftar/Beli → jika belum login, diarahkan ke login.
3. Setelah login, isi request/checkout sesuai flow.
4. Upload bukti TF bila diperlukan; status muncul di member dashboard.

### D. Request bantuan kursus / gear

1. Login → My Courses (/my-courses/) atau My Gear (/my-gear/).
2. Isi form request / fitting.
3. Admin melihat di WDC Members → Course Requests / Gear Requests.

### E. Giveaway claim

1. Login → dashboard giveaway section.
2. Pilih item, isi alamat/penerima.
3. Cek ongkir di https://berdu.id/cek-ongkir (asal: label origin admin).
4. Upload screenshot cek ongkir + input nominal + kurir.
5. Transfer ongkir exact sesuai SS, upload bukti TF.
6. Admin verifikasi → isi resi → member cek tracking lewat cekresi.com.

### F. Settings akun

1. Login → /settings/.
2. Update nama, phone/WA, catatan gear, emergency contact.
3. Level diver otomatis dari kursus tertinggi (auto).

---

## 5. Flow Admin / Crew

Admin WordPress punya 2 group menu utama:

- WDC Site — konten public (hero, about, contact, crew, testimonials, CTA copy, dll).
- WDC Members — ops member (dashboard, member list, course/gear requests, giveaway orders, payment settings).

### A. Konten site (WDC Site)

1. Login wp-admin.
2. Buka WDC Site.
3. Edit section yang dibutuhkan (contact, about values, crew, testimonials, CTA).
4. Simpan. Hard refresh browser (Ctrl+F5) karena LiteSpeed cache HTML/CSS sampai 7 hari.

### B. Member List

1. WDC Members → Member List.
2. Cek data member, completed courses meta, status request/order.

### C. Course / Gear Requests

1. Buka Course Requests atau Gear Requests.
2. Update status + admin note.
3. Member melihat status terbaru di area member.

### D. Giveaway Orders

1. WDC Members → Giveaway Orders.
2. Tombol cepat: Cek Ongkir (berdu.id) + Cek Resi (cekresi.com).
3. Review SS ongkir + bukti TF.
4. Verifikasi pembayaran → set status shipped + isi no. resi.
5. Member melihat progress + link cek resi.

### E. Giveaway Settings

1. Set link Cek Ongkir: https://berdu.id/cek-ongkir
2. Set link Cek Resi: https://cekresi.com/
3. Set label asal pengiriman (contoh: Jakarta Selatan).
4. Biteship field boleh kosong (flow saat ini external manual).

### F. Payment Settings

1. Isi rekening bank TF (sumber tunggal bank accounts).
2. Jangan ubah Midtrans/live payment config tanpa approval.
3. Saat soft handover, rekening masih client-owned residual.

---

## 6. Bahasa (ID / EN)

Strategi final soft handover: custom bilingual chrome only.

- ID = default path (/).
- EN = prefix path (/en/).
- Switcher di header (ID | EN).
- UI chrome (nav, button, kicker, about values titles, contact labels) ikut bahasa.
- Isi artikel/blog body = bahasa tulisan post (tidak auto-translate).
- Polylang dicoba lalu dicabut; jangan reinstall kecuali client minta EN body penuh.

Helper penting: wdc_site_tr() agar admin text ID tidak menimpa EN chrome defaults.

Tombol header: guest = Masuk/Login; logged-in = Dashboard (JS cookie fix melawan LiteSpeed guest cache).

---

## 7. Design & UX Rules

- Palette brand only: #000000 #3B44AC #4CC8ED #96DAEA #C31C4A #004A98 #FFFFFF.
- Font UI: Plus Jakarta Sans.
- Primary button #004A98; hover #3B44AC; danger #C31C4A.
- Mobile + tablet drawer: hamburger ≤1099.98px; full menu ≥1100px.
- Auth pages: no public header/footer.
- Member page heads: .wd-page-head / .wdc-section-title.
- Public prices OK; action Daftar/Beli login-gated.
- 404 page bilingual clean card (Back to Home / Explore Courses / Contact Crew).

---

## 8. Yang Sudah Selesai (Soft Handover Scope)

- Public catalog + about/contact compact redesign.
- Member login/register polish (compact fields, password eye).
- Member hubs: dashboard, my-courses, my-gear, settings.
- Giveaway checkout 2-col + external ongkir/resi.
- Admin ops pages full-width, menu bersih (no emoji decorative).
- Bilingual custom chrome ID/EN + live deploy.
- Google Maps shortlink updated.
- 404 bilingual page.
- Nav member button cookie-aware across language switch.

---

## 9. Residual Client-Owned (Bukan Blocker Dev)

1. Isi rekening bank di Payment Settings.
2. Validasi final konten: crew, testimonials, info pages, harga, stok gear.
3. Opsional: SMTP transactional agar email contact/request lebih reliable.
4. Opsional nanti: full EN article bodies (Polylang/manual pair) kalau target turis serius.
5. Opsional: Midtrans auto payment jika ingin non-WA checkout full.

---

## 10. Cara Update Konten Harian

### Edit teks/section site

1. wp-admin → WDC Site.
2. Ubah field yang perlu.
3. Save → hard refresh frontend (Ctrl+F5).

### Edit blog

1. Posts → Add/Edit.
2. Publish.
3. EN body butuh post EN terpisah (belum auto).

### Edit kursus / equipment

1. Kelola CPT terkait di admin.
2. Pastikan harga & deskripsi final.
3. CTA copy bisa di-override lewat WDC Site bila tersedia.

### Cache note

LiteSpeed server cache HTML+CSS max-age 7 hari. Setelah CSS/theme change, bump version sudah dilakukan dev; client cukup Ctrl+F5. Kalau masih stale, purge LiteSpeed cache di cPanel bila ada akses.

---

## 11. Checklist Acceptance Soft Handover

- ☐ Homepage ID/EN load normal.
- ☐ Courses/Equipment catalog tampil, harga terlihat.
- ☐ Login/register member berhasil.
- ☐ Dashboard member tampil setelah login.
- ☐ About contact compact + maps shortlink benar.
- ☐ Contact form submit (cek inbox jika SMTP sudah siap).
- ☐ Course/Gear request masuk admin.
- ☐ Giveaway claim → SS ongkir → TF → admin verify → resi.
- ☐ 404 page muncul untuk URL random.
- ☐ Switch ID↔EN: chrome berubah, logged-in tetap Dashboard.
- ☐ Payment Settings diisi rekening client.

---

## 12. Technical Reference

- **Live domain:** https://whaledivecentre.com
- **Local preview:** http://168.144.37.19:8088
- **Theme path live:** /home/whalediv/public_html/wp-content/themes/theme-travel-master
- **Theme repo local:** /root/projects/whaledivecentre-theme
- **Git branch:** local/polish-20260711
- **Latest commit:** 6d1be07
- **Public CSS version:** 2.3.93
- **Deploy method:** cPanel Fileman binary upload + MD5 verify
- **Float WA:** mu-plugin wa-float.php
- **Bilingual engine:** contenly_tr + /en/ + wdc_site_tr
- **Giveaway ongkir option:** wdc_giveaway_external_ongkir_url
- **Giveaway resi option:** wdc_giveaway_external_resi_url

Push policy: hanya branch local/polish-20260711. Jangan force push main.

---

## 13. Statement Handover (siap dikirim client)

> Website Whale Dive Centre sudah live dan soft handover ready. Public catalog, member login/register, request kursus/gear, giveaway + cek ongkir/resi eksternal, admin ops, about/contact, bilingual chrome ID/EN, dan halaman 404 sudah berfungsi. Residual di sisi client: isi rekening TF di Payment Settings, validasi konten final, dan opsional setup SMTP/strategi EN konten penuh.

---

_Dokumen digenerate otomatis · 12 July 2026_