# Laporan Rekapitulasi Logbook Pengembangan Sistem
### Proyek: ContentImpact CMS (Minggu 7 – Minggu 12)

---

## 1. Ringkasan Singkat Progres (Minggu 7–12)

Selama periode Minggu 7 hingga Minggu 12, tim pengembang telah berhasil menyelesaikan tiga fase krusial dalam siklus hidup pengembangan perangkat lunak (*Software Development Life Cycle* - SDLC) proyek **ContentImpact CMS**. 

Fase diawali dengan perancangan antarmuka pengguna (UI/UX) pada Minggu 7–8 untuk memastikan aspek kemudahan penggunaan (*usability*). Dilanjutkan dengan pemodelan data dan arsitektur database pada Minggu 9–10 melalui Laravel Migrations dan Eloquent ORM. Periode ini ditutup pada Minggu 11–12 dengan implementasi sistem autentikasi, otorisasi berbasis peran (*Role-Based Access Control* - RBAC), serta logika bisnis inti (*Core Logic*) untuk alur kerja penerbitan artikel dari draf hingga publikasi. 

Secara keseluruhan, sistem telah terintegrasi dengan baik antara komponen basis data, middleware keamanan, dan antarmuka dashboard admin, editor, serta jurnalis.

---

## 2. Poin-Poin Pencapaian Utama per Modul

### A. Modul UI/UX Design (Minggu 7–8)
*   **Perancangan Wireframe & Mockup:** Menyelesaikan rancangan *low-fidelity* (wireframe) dan *high-fidelity* (mockup) untuk halaman Portal Berita Publik, Halaman Login, serta Dashboard CMS (Admin, Editor, dan Jurnalis).
*   **Desain Intuitif & Konsistensi Visual:** Menerapkan tema gelap (*dark theme*) premium berbasis palet warna HSL Slate & Indigo, tipografi modern menggunakan font *Outfit*, serta layout responsif untuk perangkat mobile dan desktop.
*   **Validasi & Umpan Balik Mentor:** Melakukan sesi tinjauan desain bersama mentor. Rekomendasi perbaikan interaktivitas widget statistika dan aksesibilitas navigasi langsung diintegrasikan ke dalam hasil akhir desain.

### B. Modul Database Migration & Model Development (Minggu 9–10)
*   **Arsitektur Database Terelasi:** Merancang skema database relasional untuk mendukung siklus hidup artikel, pelacakan revisi, komentar pembaca, manajemen pengguna, dan pengelolaan sesi.
*   **Laravel Migrations:** Mengimplementasikan skema database secara modular ke dalam file migrasi Laravel.
*   **Eloquent Models & Relasi ORM:** Menulis model Eloquent (`User`, `Article`, `Category`, `Comment`, `ArticleRevision`) beserta fungsi relasi kardinalitas (seperti *One-to-Many* dan *BelongsTo*) dan atribut penolong (*accessors/mutators*).

### C. Modul Authentication & Core Logic (Minggu 11–12)
*   **Sistem Autentikasi & Registrasi Baru:** Membangun alur login mandiri yang aman dan alur pendaftaran jurnalis baru (`/register`) dengan penanganan kesalahan token keamanan (CSRF) via penyesuaian middleware sesi ke berbasis file (`SESSION_DRIVER=file`).
*   **Implementasi RBAC (Role-Based Access Control):** Mengamankan route dashboard dan tombol aksi menggunakan Laravel Policies (`ArticlePolicy`) dan Custom Middleware (`RoleMiddleware`, `EnsureUserIsActive`). Tiga peran diakomodasi:
    *   `Admin`: Kontrol penuh atas manajemen kategori dan pengguna (termasuk memblokir/menangguhkan akun).
    *   `Editor`: Hak moderasi komentar dan penyetujuan/permintaan revisi artikel masuk.
    *   `Journalist`: Hak menulis, menyunting draf, dan mengajukan artikel hasil karya sendiri.
*   **Logika Bisnis Inti (CRUD Artikel):** Mengembangkan alur kerja editorial lengkap yang mencakup penyimpanan draf, pengajuan peninjauan (*pending review*), penolakan dengan catatan revisi (*revision required*), hingga penerbitan akhir (*published*).

---

## 3. Daftar Hasil / Deliverables Konkret

### A. Artefak Desain UI/UX
*   **Figma Design File:** Link/File Mockup Figma Halaman Portal & Dashboard CMS (Menggunakan sistem grid responsif, autolayout, dan komponen interaktif).
*   **Design System & Styleguide:** Panduan gaya visual berupa token warna, tipografi font *Outfit*, pustaka ikon *FontAwesome*, serta panduan kontras rasio untuk aksesibilitas kontras teks.

### B. Migrasi Basis Data (Laravel Migrations)
Berkas migrasi berikut telah teruji dan berhasil dieksekusi ke database MySQL (`db_reyhan`):
*   `0001_01_01_000000_create_users_table.php` (Tabel `users`, `password_reset_tokens`, dan `sessions` untuk sistem sesi)
*   `2026_06_23_091510_create_categories_table.php` (Tabel `categories` untuk klasifikasi berita)
*   `2026_06_23_091511_create_articles_table.php` (Tabel `articles` dengan status draft/pending/revision/published)
*   `2026_06_23_091512_create_comments_table.php` (Tabel `comments` untuk interaksi komentar berita)
*   `2026_06_23_091513_create_article_revisions_table.php` (Tabel `article_revisions` untuk pencatatan log feedback revisi editor ke jurnalis)

### C. Backend API & Endpoint Routing (Dashboard CMS)
Daftar route inti yang telah aktif dan terproteksi oleh sistem otorisasi:

| HTTP Method | URI | Route Name | Keterangan & Aksesibilitas |
|---|---|---|---|
| **GET** | `/login` | `login` | Halaman login bertema premium |
| **POST** | `/login` | - | Eksekusi login & pemeriksaan status aktif/suspended |
| **GET** | `/register` | `register` | Halaman pendaftaran jurnalis baru |
| **GET** | `/dashboard/logout` | `logout.get` | Logout aman (Bebas dari kendala token expired 419) |
| **GET** | `/dashboard` | `dashboard` | Beranda ringkasan metrik (Clickable widgets & pending overview) |
| **GET** | `/dashboard/articles` | `dashboard.articles.index` | Daftar kelola artikel sesuai peran masing-masing |
| **POST** | `/dashboard/articles` | `dashboard.articles.store` | Membuat draf artikel baru (Admin/Journalist) |
| **POST** | `/dashboard/articles/{id}/submit` | `dashboard.articles.submit` | Mengajukan draf untuk direview (Journalist) |
| **POST** | `/dashboard/articles/{id}/approve` | `dashboard.articles.approve` | Menyetujui & menerbitkan artikel (Admin/Editor) |
| **POST** | `/dashboard/articles/{id}/revision` | `dashboard.articles.revision` | Mengembalikan artikel dengan catatan revisi (Admin/Editor) |
| **POST** | `/dashboard/users/{id}/toggle-status` | `dashboard.users.toggle-status` | Menangguhkan (suspend) / mengaktifkan akun (Admin) |
