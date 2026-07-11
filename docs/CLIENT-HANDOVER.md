# Whale Dive Centre — Panduan Handover Client

Dokumen ini untuk **admin non-teknis** yang mengelola konten website Whale Dive Centre lewat WordPress Admin, **tanpa mengubah kode**.

**Prinsip utama:**  
Layout/desain tetap. Admin cukup ubah **konten, foto, harga, dan status request**.

---

## 1. Akses penting

| Keperluan | URL | Keterangan |
|---|---|---|
| Website publik | `https://whaledivecentre.com` | Halaman pengunjung |
| Login **admin** (kelola konten) | `https://whaledivecentre.com/wp-admin/` | Masuk lewat form WordPress admin |
| Login **member** (siswa/customer) | `https://whaledivecentre.com/login/` | Bukan untuk kelola konten site |
| Dashboard member | `https://whaledivecentre.com/dashboard/` | Area member setelah login |

### Catatan login (penting)
- **Admin** pakai `/wp-admin/` → form login WordPress.
- **Member** pakai `/login/`.
- URL lama `/member-login/` otomatis dialihkan ke `/login/`.
- Kalau password member salah, user **tetap di halaman `/login/`** (bukan ke halaman admin).

> Simpan username/password admin di password manager. Jangan share ke member.

---

## 2. Peta menu admin (yang perlu dipakai)

Setelah login admin, fokus ke menu ini:

### A. `WDC Site` — pusat konten website
Ini menu utama untuk copy marketing & data site.

| Submenu | Untuk apa |
|---|---|
| **Contact & Footer** | Email, telepon, alamat, jam, sosial media, teks footer, CTA footer |
| **Home Content** | Hero home, proof points, judul section kursus/gear, membership, reviews title |
| **Notifications** | Email crew saat ada request course/gear; auto-reply ke member |
| **About Page** | Teks halaman Tentang Kami |
| **Contact Page** | Teks/form copy halaman kontak |
| **Partners / Trust** | Partner/brand trust (format khusus, lihat bawah) |
| **Crew Profiles** | Profil crew (nama, foto, bio) |
| **Testimonials** | Testimoni member/tamu |
| **Add Crew / Add Testimonial** | Tambah data baru |

### B. Katalog produk/kursus
| Menu | Untuk apa |
|---|---|
| **Dive Courses** | Semua kursus (judul, deskripsi, harga, foto, highlight, CTA) |
| **Dive Equipment** | Semua gear (harga, stok, size, foto, CTA) |

### C. Blog & cerita member
| Menu | Untuk apa |
|---|---|
| **Posts / Blog** | Artikel blog resmi crew |
| **Cerita Kamu** | Cerita yang dikirim member — **harus di-approve dulu** sebelum tampil di Blog |

### D. Member & request
| Menu | Untuk apa |
|---|---|
| **Users** | Data akun member |
| **WDC Members** (jika ada) | Ringkasan request/order member |

### Yang **tidak perlu** disentuh admin harian
- Appearance / Theme File Editor / Plugin settings teknis
- Kode, CSS, rewrite, database
- Menu legacy yang disembunyikan (mis. tool internal dev)

---

## 3. Tugas harian / mingguan

### 3.1 Ganti teks Home / About / Contact / Footer
1. Buka **WDC Site**
2. Pilih submenu yang sesuai (Home Content / About Page / Contact Page / Contact & Footer)
3. Edit field
4. Klik **Save / Update**
5. Cek hasil di website publik (hard refresh browser)

### 3.2 Update harga / detail kursus
1. Buka **Dive Courses**
2. Klik kursus yang mau diubah
3. Isi field penting:
   - Judul & ringkasan
   - **Featured image** (foto utama)
   - Harga mulai
   - Durasi, max students, prasyarat
   - Includes / highlight
   - CTA label (contoh: `Daftar Open Water`)
   - Show in catalog (tampil/sembunyi)
4. **Update**
5. Cek:
   - `/courses/`
   - halaman detail kursus

### 3.3 Update gear / stok
1. Buka **Dive Equipment**
2. Edit item
3. Field penting:
   - Featured image
   - Harga mulai
   - Stok
   - Ukuran tersedia
   - Service points / CTA
4. **Update**
5. Cek `/equipment/` dan detail item

### 3.4 Ganti foto (cara benar)
Foto yang tampil di catalog/detail = **Featured Image** di post course/equipment.

1. Buka course/equipment
2. Panel kanan **Featured image**
3. Set / ganti / remove image
4. Update

> Jangan cari menu “Catalog Images” untuk ganti foto harian. Itu tool internal, sudah disembunyikan.

### 3.5 Tambah testimoni
1. **WDC Site → Testimonials → Add Testimonial**
2. Isi nama, isi testimoni, foto (opsional)
3. Publish
4. Cek section testimoni di home

### 3.6 Update crew
1. **WDC Site → Crew Profiles**
2. Edit / add crew
3. Isi nama, role, bio, foto
4. Publish

### 3.7 Partners / Trust
Di **WDC Site → Partners / Trust**:
- Format tiap baris: `Nama Brand|nama-file.webp`
- Pisah baris partner baru dengan `---`
- File logo ditaruh di folder assets partners (koordinasi dev jika perlu upload file baru)

Contoh:
```text
NAUI|naui.webp
TDI|tdi.webp
---
Cressi|cressi.webp
```

---

## 4. Alur bisnis member (request course/gear)

### Cara kerja (penting)
- Harga **tampil publik**
- Tombol **Daftar / Beli** butuh login member
- Member mengajukan request dari dashboard
- Crew follow-up manual (bukan checkout otomatis Midtrans penuh)
- **Tidak ada WhatsApp CTA publik** di halaman course/equipment

### Alur member
1. Member buka `/login/`
2. Masuk dashboard
3. Ajukan kursus (`/my-courses/`) atau gear (`/my-gear/`)
4. Admin/crew terima notifikasi email (jika Notifications aktif)
5. Crew hubungi / proses manual

### Setting notifikasi
**WDC Site → Notifications**
- Email crew penerima request
- On/off notif crew
- On/off auto-reply ke member

Kalau email crew kosong, sistem fallback ke email admin/contact.

---

## 5. Cerita Kamu (moderasi wajib)

Member bisa kirim cerita dari area member (**Cerita Kamu**).

### Aturan
- Submit member **tidak langsung publish**
- Status awal: **Pending review**
- Baru tampil di Blog setelah admin **Approve**

### Cara approve
**Opsi A — dari list**
1. Admin → menu **Cerita Kamu**
2. Cari item Pending
3. Klik **Approve & publish**

**Opsi B — dari edit**
1. Buka cerita
2. Centang **Approve & publish ke Blog**
3. Update

### Hasil approve
- Cerita live di list Cerita Kamu (approved)
- Otomatis dibuatkan/disinkron ke **Blog post** (kategori Community)
- Member melihat status **Live di Blog** di “Kiriman saya”

### Unapprove
- Bisa di-unapprove
- Hilang dari publik / blog jadi draft

---

## 6. Halaman publik yang dikelola

| Halaman | Admin edit di |
|---|---|
| Home | WDC Site → Home Content + CPT courses/equipment + testimonials/crew |
| Courses catalog | Dive Courses |
| Course detail | Dive Courses (single post) |
| Equipment catalog | Dive Equipment |
| Equipment detail | Dive Equipment (single post) |
| About | WDC Site → About Page + Crew Profiles |
| Contact | WDC Site → Contact Page + Contact & Footer |
| Blog | Posts + Cerita Kamu (approved) |
| Login member | `/login/` (konten template; akun lewat Users) |
| Dashboard member | data request/order member |

### URL penting katalog
- Courses: `/courses/`
- Course detail: `/courses/{slug}/`
- Equipment: `/equipment/`
- Equipment detail: `/equipment/{slug}/`
- Blog: `/blog/`

URL lama `/course/...` dan `/gear/...` dialihkan otomatis ke format baru.

---

## 7. Checklist “konten aman diubah”

Boleh diubah admin:
- Teks hero, about, contact, footer
- Harga, deskripsi, CTA course/gear
- Foto featured course/gear
- Testimoni, crew, partners
- Artikel blog
- Approval cerita member
- Email notifikasi request

Jangan diubah tanpa dev:
- Plugin / theme code
- Permalink structure
- Role capability
- Database / server / DNS / email server
- Redirect rules
- Menu technical hidden tools

---

## 8. SOP cepat 10 menit (mingguan)

1. Cek request baru (email + WDC Members / user meta request)
2. Follow-up member pending
3. Review **Cerita Kamu** pending → approve/reject
4. Update stok gear jika berubah
5. Cek 3 halaman kunci di HP:
   - Home
   - 1 course detail
   - 1 equipment detail
6. Pastikan tombol Masuk mengarah ke `/login/`
7. Pastikan admin tetap bisa buka `/wp-admin/`

---

## 9. Troubleshooting admin

| Masalah | Cek dulu |
|---|---|
| Edit sudah save tapi web belum berubah | Hard refresh (`Ctrl+Shift+R`). Cache browser/server bisa delay. |
| Foto course/gear tidak muncul | Featured image di post tersebut sudah di-set? |
| Member tidak bisa daftar/beli | Sudah login? CTA memang login-first. |
| Cerita member tidak muncul di blog | Sudah di-approve? Status harus Approved + publish. |
| `/wp-admin` malah ke login member | Seharusnya tidak. Pakai `/wp-admin/` / `wp-login.php`. Hubungi dev jika masih salah. |
| Email request tidak masuk | WDC Site → Notifications: email crew & folder spam |

---

## 10. Batasan fitur saat handover

Sudah jalan untuk operasional harian:
- Konten site lewat **WDC Site**
- Katalog course/equipment + harga publik
- Login member `/login`
- Request course/gear login-gated
- Moderasi Cerita Kamu → Blog
- Admin `/wp-admin` terpisah dari member login

Belum jadi fokus admin harian / masih lanjutan:
- Multi-image gallery per produk (selain featured image)
- FAQ admin-editable penuh
- Payment gateway full otomatis (Midtrans end-to-end)
- Status label request yang lebih advance
- Dual-language field mendalam untuk semua copy

---

## 11. Kontak teknis / eskalasi

Hubungi tim dev jika:
- Butuh ganti layout/desain
- Error 500 / halaman putih
- Login admin/member rusak
- Redirect salah
- Email server tidak kirim sama sekali
- Perlu tambah field baru di admin

Sertakan:
1. URL halaman
2. Screenshot
3. User role (admin/member)
4. Waktu kejadian
5. Langkah reproduksi singkat

---

## 12. Ringkas untuk owner

**Kalau cuma ingat 5 hal:**
1. Konten site → menu **WDC Site**
2. Kursus/gear → **Dive Courses / Dive Equipment** + Featured image
3. Member login → `/login/`
4. Admin login → `/wp-admin/`
5. Cerita member harus **di-approve** dulu baru ke Blog

---

*Dokumen handover ini disusun untuk operasional non-teknis. Layout tetap dikunci; admin fokus isi konten dan follow-up member.*
