# Whale Dive Centre — Panduan Menu Admin (Simple, Lengkap)

**Tanggal:** 12 Juli 2026 (update lanjutan)  
**Website:** https://whaledivecentre.com  
**Isi:** semua menu custom WP admin + fungsi + cara pakai (termasuk Courses, Equipment, Menus/Links, Partners media).

---

## Cara kerja singkat

1. Login admin: https://whaledivecentre.com/wp-admin
2. Pilih menu kiri sesuai kebutuhan (Courses / Equipment / WDC Site / WDC Members / dll).
3. Edit field / status → Save / Update / Publish.
4. Buka website → Ctrl+F5 (cache LiteSpeed bisa nahan tampilan lama).

---

## 1. Courses — katalog kursus

Menu kiri: Courses. CPT: Dive Courses (wm_course). Tampil di /courses/.

- **All Courses / Courses** — daftar semua kursus. Edit judul, status Publish = aktif.
- **Add New Course** — tambah kursus baru. Isi Title, Excerpt, Content, Featured Image + Course Details.
- **Course Details** — Price (IDR), Duration, Max Students, Prerequisites, What's Included, Highlight 1–3, CTA Label. Centang show in catalog + estimate bila “Harga mulai”.
- **Course Levels / Agencies** — badge/filter.
- **Featured Image** — foto card & single.

---

## 2. Equipment — katalog peralatan

Menu kiri: Equipment. CPT: Dive Equipment (wm_equipment). Tampil di /equipment/.

- **All Equipment** — list gear. Publish = aktif.
- **Add New Equipment** — Title, Excerpt, Content, Featured Image + Equipment Details.
- **Equipment Details** — Price, Stock, Sizes/Variants, Fit note, Service Point, CTA, show catalog.
- **Categories / Brands** — filter/badge.
- **Featured Image** — foto produk.

---

## 3. Informasi — info / giveaway / event

Menu kiri: Informasi (wdc_info).

- **Semua Informasi** — list pengumuman/info/event/giveaway text.
- **Tipe Informasi** — kategori (1st Giveaway, Event, Trip, Update NAUI/WDC/TDI/DAN, dll).

---

## 4. Cerita Kamu — story member

Menu kiri: Cerita Kamu (wdc_story).

- **Semua Cerita** — review cerita + foto.
- **Approval / Featured** — set status/highlight bila field tersedia.
- **Jenis Cerita** — klasifikasi.

---

## 5. WDC Site — konten halaman public

Menu kiri: WDC Site. Edit teks section website (bukan order).

- **Contact & Footer** — email, telepon/WA, alamat, jam, Instagram/Facebook/X, footer kicker/blurb/CTA.
- **Menus / Links** — **(baru)** navbar + footer Jelajahi/Kursus.
- **Home Content** — hero/home headline & copy utama.
- **About Page** — teks Tentang / values.
- **Contact Page** — teks form contact + maps URL.
- **Courses & Equipment CTA** — copy tombol/CTA di halaman courses & equipment.
- **Partners / Trust** — logo partner trust (Media Library picker).
- **Crew** — profil crew (nama, role, bio, foto).
- **Testimonials** — testimoni klien/member.

### 5A. Menus / Links (cara pakai)

1. WDC Site → **Menus / Links**.
2. Edit field:
   - **Navbar links**
   - **Footer kolom 1 title** (default: Jelajahi)
   - **Footer kolom 1 links**
   - **Footer kolom 2 title** (default: Kursus)
   - **Footer kolom 2 links**
3. Format per baris:

```text
Label|URL
Label|URL|navkey
```

Contoh navbar:

```text
Beranda|/|home
Kursus|/courses/|courses
Peralatan|/equipment/|equipment
Tentang|/about/|about
Blog|/blog/|blog
```

Contoh footer Jelajahi:

```text
Kursus Selam|/courses/
Peralatan Selam|/equipment/
Testimoni|/testimonials/
Konservasi|/conservation/
Tentang Kami|/about/
Blog|/blog/
```

4. Save Menus / Links → buka website → Ctrl+F5.

Catatan:

- URL boleh path relatif (`/about/`) atau full URL.
- Baris kosong diabaikan.
- Awali baris dengan `#` untuk komentar.
- Tombol login/dashboard di navbar tetap otomatis (guest Masuk / logged-in Dashboard).

### 5B. Partners / Trust (cara pakai)

1. WDC Site → **Partners / Trust**.
2. Edit trust text + trust label bila perlu.
3. Di builder logo:
   - **Pilih dari Media** → pilih/upload logo di Media Library.
   - Isi **Nama partner**.
   - **Tambah logo di baris ini** / **Baris logo baru**.
4. Save Partners / Trust → cek homepage trust bar → Ctrl+F5.

Catatan:

- Tidak perlu ketik filename theme `assets/partners/`.
- Format simpan otomatis: `Nama|id:123` (media attachment).
- Advanced raw format tetap support: `Nama|file.webp` / `Nama|https://...`.
- Logo default (NAUI, TDI, DAN, Sherwood, Zeagle, Waterproof, Shearwater, BARE) sudah diimport ke Media Library live.

---

## 6. WDC Members — operasional member

Menu kiri: WDC Members. Request, member data, giveaway, rekening.

- **Dashboard** — ringkasan ops.
- **Member List** — data member + completed courses.
- **Course Requests** — permintaan kursus: update status + note.
- **Gear Requests** — fitting/ketersediaan gear.
- **Giveaway Orders** — SS ongkir, bukti TF, resi; tombol Cek Ongkir / Cek Resi.
- **Payment Settings** — rekening bank TF (sumber nomor rekening). Jangan ubah Midtrans sembarangan.

---

## 7. Giveaway Settings (jika muncul di admin)

- **Giveaway Settings** — enable giveaway + link cek ongkir/resi + origin label.
  - Cek Ongkir = https://berdu.id/cek-ongkir
  - Cek Resi = https://cekresi.com/
- **Giveaway Orders** — proses order (bisa muncul di 2 tempat).

---

## 8. Posts (blog) + Media

- **Posts** — artikel blog public di `/blog/`.
- **Media** — upload foto/file; dipakai course/equipment/crew/post/partner logos.

---

## 9. Auth public (bukan menu admin)

- Login member: https://whaledivecentre.com/login/
- Register member: https://whaledivecentre.com/register/
- Legacy `/member-register/` redirect ke `/register/`
- Auth page clean (tanpa public header/footer, tanpa kicker badge).
- Setelah login, header public tombol = **Dashboard**.

---

## Tidak untuk harian / disembunyikan

- **Dive Sites** — CPT lama, menu disembunyikan.
- **Catalog Images (hidden tool)** — backfill foto; harian pakai Featured Image.
- **Direct Orders / Input Pesanan** — dihapus/disembunyikan. Flow: WA-first + Requests + Giveaway.

---

## Link penting

- **Website live:** https://whaledivecentre.com — cek hasil edit, selalu Ctrl+F5.
- **Login member public:** https://whaledivecentre.com/login/
- **Register member public:** https://whaledivecentre.com/register/
- **Courses public:** https://whaledivecentre.com/courses/
- **Equipment public:** https://whaledivecentre.com/equipment/
- **Contact public:** https://whaledivecentre.com/contact/
- **Cek Ongkir giveaway:** https://berdu.id/cek-ongkir
- **Cek Resi giveaway:** https://cekresi.com/

---

## Tips cepat

- Courses & Equipment = data katalog (harga, foto, deskripsi).
- WDC Site = teks section homepage/about/contact/CTA/crew/testimoni + **Menus/Links** + **Partners**.
- WDC Members = request member + giveaway + rekening TF.
- Harga publik; tombol Daftar/Beli butuh login member.
- Centang “Show in catalog” supaya item muncul di /courses/ atau /equipment/.
- Featured Image penting untuk card catalog.
- Partner logo: pilih dari Media, jangan ketik file theme.
- Navbar/footer links: edit di Menus / Links, format `Label|URL`.
- EN di frontend = chrome UI (`/en/`). Isi artikel/course body tidak auto-translate.
- Kalau tampilan belum berubah: Ctrl+F5 dulu.

---

_Versi simple lengkap untuk client / crew ops · update 12 July 2026_
