# Whale Dive Centre — Panduan Menu Admin (Simple, Lengkap)

**Tanggal:** 12 Juli 2026  
**Website:** https://whaledivecentre.com  
**Isi:** semua menu custom WP admin + fungsi + cara pakai (termasuk Courses & Equipment).

---

## Cara kerja singkat

1. Login admin: https://whaledivecentre.com/wp-admin
2. Pilih menu kiri sesuai kebutuhan (Courses / Equipment / WDC Site / WDC Members / dll).
3. Edit field / status → Save / Update / Publish.
4. Buka website → Ctrl+F5 (cache LiteSpeed bisa nahan tampilan lama).

---

## 1. Courses — katalog kursus

Menu kiri: Courses. CPT: Dive Courses (wm_course). Tampil di /courses/.

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **All Courses / Courses** | Daftar semua kursus di website. | Courses → All Courses. Klik judul untuk edit. Status Publish = aktif. |
| **Add New Course** | Tambah kursus baru. | Courses → Add New. Isi Title, Excerpt, Content, Featured Image. Isi box Course Details. Publish. |
| **Course Details (box di edit course)** | Harga, durasi, kuota, prasyarat, include, highlight, CTA, tampil di katalog. | Isi Price (IDR), Duration, Max Students, Prerequisites, What's Included. Optional: Highlight 1–3, CTA Label. Centang “Show in public/member course catalog”. Centang “Price is estimate / starting price” bila “Harga mulai”. Update. |
| **Course Levels** | Level/filter kursus (badge di katalog). | Di sidebar edit course, atau taxonomy Course Levels. Pilih/assign level → Update. |
| **Course Agencies / Agencies** | Agensi kursus (NAUI, TDI, dll) untuk badge/filter. | Assign agency di panel course → Update. |
| **Featured Image** | Foto card & single course. | Set Featured Image di edit course. Kosong = fallback tema. |

---

## 2. Equipment — katalog peralatan

Menu kiri: Equipment. CPT: Dive Equipment (wm_equipment). Tampil di /equipment/.

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **All Equipment / Equipment** | Daftar semua gear di website. | Equipment → All Equipment. Klik item untuk edit. Publish = aktif. |
| **Add New Equipment** | Tambah item gear baru. | Equipment → Add New. Isi Title, Excerpt, Content, Featured Image. Isi box Equipment Details. Publish. |
| **Equipment Details (box di edit gear)** | Harga, stok, size/varian, fit note, service points, CTA, tampil katalog. | Isi Price (IDR), Stock, Sizes/Variants, Fit/Usage Note. Optional Service Point 1–3 + CTA Label. Centang show in catalog + estimate bila perlu. Update. |
| **Gear Categories / Equipment Categories** | Kategori gear untuk filter/badge. | Assign category di panel equipment → Update. |
| **Brands / Equipment Brands** | Brand gear untuk filter/badge. | Assign brand di panel equipment → Update. |
| **Featured Image** | Foto produk card & single. | Set Featured Image di edit equipment. |

---

## 3. Informasi — info / giveaway / event

Menu kiri: Informasi (wdc_info). Konten info member/public (termasuk giveaway copy).

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **Semua Informasi** | List pengumuman/info/event/giveaway text. | Informasi → Semua Informasi → Edit / Add New → Publish. |
| **Tipe Informasi** | Kategori info (1st Giveaway, Event, Trip, Update NAUI/WDC/TDI/DAN, dll). | Assign tipe di edit info. Bisa tambah term baru di Tipe Informasi. |

---

## 4. Cerita Kamu — story member

Menu kiri: Cerita Kamu (wdc_story). Moderasi cerita member.

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **Semua Cerita** | List cerita yang masuk/diinput. | Cerita Kamu → list → review isi & foto. |
| **Approval Status / Featured** | Approve atau highlight cerita (bila field tersedia di edit). | Buka cerita → set status/featured sesuai box → Update. |
| **Jenis Cerita** | Klasifikasi jenis cerita. | Assign taxonomy Jenis Cerita → Update. |

---

## 5. WDC Site — konten halaman public

Menu kiri: WDC Site. Edit teks section website (bukan order).

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **Contact & Footer** | Email, telepon/WA, alamat, Instagram, teks footer. | Isi field → Save Changes → cek website + Ctrl+F5. |
| **Home Content** | Hero/home headline & copy utama. | Edit teks home → Save → cek beranda. |
| **About Page** | Teks halaman Tentang / values. | Edit About fields → Save → cek /about/. |
| **Contact Page** | Teks form contact, jam, Google Maps URL. | Edit → Save. Maps: https://maps.app.goo.gl/7A3Yo7gsaDCcS6xZ6 |
| **Courses & Equipment CTA** | Copy tombol/CTA di halaman courses & equipment. | Ubah CTA → Save → cek /courses/ & /equipment/. |
| **Partners / Trust** | Logo partner trust (NAUI, TDI, DAN, dll). | Update partner → Save → cek beranda. |
| **Crew** | Profil crew (nama, role, bio, foto). | WDC Site → Crew → Add New / Edit → Publish. (Add New di tombol list, bukan sidebar ekstra.) |
| **Testimonials** | Testimoni klien/member. | WDC Site → Testimonials → Add New / Edit → Publish. |

---

## 6. WDC Members — operasional member

Menu kiri: WDC Members. Request, member data, giveaway, rekening.

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **Dashboard** | Ringkasan ops member/request. | Buka WDC Members untuk overview. |
| **Member List** | Daftar member + data akun + completed courses. | Cari member → buka detail → edit bila perlu → Save. |
| **Course Requests** | Permintaan/review kursus dari member. | Buka list → update status + admin note → Save / Verify / Cancel. |
| **Gear Requests** | Permintaan fitting/ketersediaan gear. | Update status + note → Save. |
| **Giveaway Orders** | Order giveaway: SS ongkir, bukti TF, resi. | Filter status → buka baris → cek SS/TF → Verifikasi → isi kurir + no.resi → set Shipped → Save. Pakai tombol Cek Ongkir / Cek Resi di atas halaman. |
| **Payment Settings** | Rekening bank TF (sumber nomor rekening). | Isi bank, no.rek, atas nama → Save. Jangan ubah Midtrans sembarangan. |

---

## 7. Giveaway Settings (jika muncul di admin)

Submenu giveaway settings (URL eksternal ongkir/resi, origin label, enable giveaway).

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **Giveaway Settings** | Aktifkan giveaway + link cek ongkir/resi + label asal kirim. | Set Cek Ongkir = https://berdu.id/cek-ongkir · Cek Resi = https://cekresi.com/ · isi origin label → Save. |
| **Giveaway Orders** | Sama seperti di WDC Members (bisa muncul di 2 tempat). | Proses order giveaway di sini atau lewat WDC Members → Giveaway Orders. |

---

## 8. Posts (blog) — WP standar yang dipakai

Bukan CPT custom, tapi dipakai konten public.

| Menu | Fungsi | Cara pakai |
|---|---|---|
| **Posts** | Artikel blog public. | Posts → Add New / Edit → Publish. Tampil di /blog/. |
| **Media** | Upload foto/file. | Media → Add New → pakai di course/equipment/crew/post. |

---

## Tidak untuk harian / disembunyikan

| Item | Fungsi | Catatan |
|---|---|---|
| **Dive Sites** | CPT lama, menu disembunyikan (data draft disimpan). | Tidak dipakai di public site. Jangan andalkan untuk konten harian. |
| **Catalog Images (hidden tool)** | Tool backfill foto catalog dari assets tema. | Bukan menu sidebar harian. Foto harian: edit Featured Image di Courses/Equipment. |
| **Direct Orders / Input Pesanan** | Menu order lama dihapus/disembunyikan. | Flow sekarang WA-first + Course/Gear Requests + Giveaway Orders. |

---

## Link penting

- **Website live:** https://whaledivecentre.com — Cek hasil edit. Selalu Ctrl+F5.
- **Login member public:** https://whaledivecentre.com/login/ — Bukan /wp-admin.
- **Courses public:** https://whaledivecentre.com/courses/ — Hasil menu Courses.
- **Equipment public:** https://whaledivecentre.com/equipment/ — Hasil menu Equipment.
- **Cek Ongkir giveaway:** https://berdu.id/cek-ongkir — Hitung ongkir eksternal.
- **Cek Resi giveaway:** https://cekresi.com/ — Tracking resi setelah admin isi resi.

---

## Tips cepat

- Courses & Equipment = data katalog (harga, foto, deskripsi).
- WDC Site = teks section homepage/about/contact/CTA/crew/testimoni.
- WDC Members = request member + giveaway + rekening TF.
- Harga publik; tombol Daftar/Beli butuh login member.
- Centang “Show in catalog” supaya item muncul di /courses/ atau /equipment/.
- Featured Image penting untuk card catalog; kosong = fallback tema.
- EN di frontend = chrome UI (/en/). Isi artikel/course body tidak auto-translate.
- Kalau tampilan belum berubah: Ctrl+F5 dulu.

---

_Versi simple lengkap untuk client / crew ops._
