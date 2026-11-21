# InvenKopi — Sistem Informasi Manajemen Inventaris Coffeeshop OMORA

Sistem Informasi Manajemen Inventaris berbasis web untuk **OMORA Coffeeshop** yang dikembangkan menggunakan **Laravel 13**, **MySQL 8.x**, dan **Bootstrap 5**.

Repositori ini disiapkan untuk mendukung operasional satu outlet utama dengan arsitektur basis data yang siap diskalakan ke multi-outlet di masa mendatang.

---

## 🚀 Panduan Setup Singkat (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal:

### 1. Clone Repositori
```bash
git clone https://github.com/24n10010-grace/Omora-Coffeeshop.git
cd Omora-Coffeeshop
```

### 2. Salin Konfigurasi Environment
```bash
cp .env.example .env
```
Sesuaikan konfigurasi database pada berkas `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_invenkopi_omora
DB_USERNAME=invenkopi_dev
DB_PASSWORD=
```

### 3. Instalasi Dependensi
```bash
composer install
npm install
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Migrasi & Seeding Database
Jalankan migrasi seluruh tabel (18 tabel) beserta seed data master dan data uji 7 hari:
```bash
php artisan migrate:fresh --seed
```
*Catatan:*
- `MasterDataSeeder`: Mengisi 2 outlet, 3 pengguna awal, 3 supplier, 10 SKU bahan baku, 7 menu, dan resep komposisi BOM.
- `DemoTransactionSeeder`: Mengisi simulasi transaksi 7 hari (penjualan, penerimaan, transfer, waste, dan physical opname) yang menghasilkan verifikasi angka variance 100% presisi dengan sheet *Proto Variance*.

### 6. Jalankan Server Lokal
Terminal 1 (Backend API & Web):
```bash
php artisan serve
```
Terminal 2 (Frontend Asset Bundler):
```bash
npm run dev
```
Akses aplikasi melalui peramban web pada alamat: `http://localhost:8000`.

---

## 🔐 Kredensial Akun Awal (Data Seeder Master)

Sesuai dengan rancangan persona pengguna pada Laporan D3-01 & D3-03, berikut akun awal yang terdaftar untuk pengujian hak akses (RBAC):

| Peran (Role) | Username | Password | Nama Pengguna / Keterangan |
|---|---|---|---|
| **Owner** | `owner_omora` | `password123` | **Budi Santoso (Owner OMORA)** — Akses eksekutif dashboard & laporan lintas outlet |
| **Supervisor** | `supervisor_omora` | `password123` | **Michelle Aurellia (Supervisor Bar / PM)** — Pengelolaan master data, BOM, approval opname |
| **Staf / Barista** | `barista_omora` | `password123` | **Grace Febriani S. P (Barista Konter)** — Input form stock opname, penjualan, dan mutasi |

> **Perhatian:** Kredensial di atas hanya untuk keperluan pengembangan (*development*) dan pengujian lokal. Password telah di-hash menggunakan algoritma *bcrypt*.

---

## 📡 Dokumentasi & Koleksi API

- **Kontrak Desain API Lengkap (M01–M12):** [`docs/API_DESIGN.md`](docs/API_DESIGN.md)
- **Koleksi Postman:** [`docs/OMORA.postman_collection.json`](docs/OMORA.postman_collection.json) (Bisa langsung diimpor ke Postman untuk pengujian endpoint `/api/v1`).
- **Diagram ERD:** [`docs/database/ERD.md`](docs/database/ERD.md) & [`docs/database/ERD.png`](docs/database/ERD.png)
- **Skrip Skema SQL Mentah:** [`docs/database/schema.sql`](docs/database/schema.sql)

---

## 👥 Tim Proyek

- **Michelle Aurellia (24.N1.0021)** — Project Manager & Analis Sistem (PIC D1)
- **Kirana Ayu Dwi Putri (24.N1.0028)** — Database Administrator & Backend Developer (PIC D2)
- **Grace Febriani S. P (24.N1.0010)** — UI/UX Designer, Frontend Developer & QA Tester (PIC D3)

Program Studi Sistem Informasi, Fakultas Ilmu Komputer, Universitas Katolik Soegijapranata Semarang (2026/2027)
