# Environment & Instalasi Lokal — InvenKopi OMORA

## 1. Prasyarat

- PHP dengan ekstensi yang diwajibkan `composer.json` Laravel yang dipasang.
- Composer 2.
- Node.js LTS dan npm.
- MySQL 8.x.
- Git.

Catat versi aktual setiap perangkat agar seluruh anggota menggunakan environment yang konsisten:

```bash
php -v
composer -V
node -v
npm -v
git --version
mysql --version
php artisan --version
```

Versi Laravel/PHP final mengikuti hasil instalasi dan persyaratan `composer.json`, bukan angka versi yang diasumsikan di dokumen ini. Commit `composer.lock` dan `package-lock.json` agar dependensi tim konsisten.

## 2. Branch kerja

Jalankan dari root repository dengan working tree bersih:

```bash
bash scripts/create_develop_branch.sh
```

Seluruh setup dan fitur baru dikerjakan di `develop` atau feature branch turunan `develop`, bukan langsung di `main`.

## 3. Tambahkan skeleton Laravel

```bash
bash scripts/bootstrap_laravel.sh
```

Script membuat skeleton Laravel di direktori sementara, lalu menyalin file yang belum ada ke root repositori. File/dokumen yang sudah ada tidak ditimpa. Script kemudian menjalankan `composer install`, menyiapkan `.env` dari `.env.example` bila belum ada, dan membuat `APP_KEY` lokal.

Jika proses bootstrap gagal karena versi PHP atau dependensi sistem, perbaiki prasyarat berdasarkan pesan Composer, kemudian jalankan kembali setelah memastikan file proyek yang sudah terbentuk tetap aman.

## 4. Buat database dan akun aplikasi

Jalankan pada MySQL client dengan akun administrator lokal:

```sql
CREATE DATABASE db_invenkopi_omora
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER 'invenkopi_dev'@'localhost' IDENTIFIED BY 'GANTI_DENGAN_PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON db_invenkopi_omora.* TO 'invenkopi_dev'@'localhost';
FLUSH PRIVILEGES;
```

Ganti placeholder password dengan password lokal yang kuat. Jangan memakai akun `root` sebagai akun koneksi aplikasi. Bila konfigurasi MySQL lokal menggunakan host berbeda, samakan host user MySQL dengan `DB_HOST` pada `.env`.

## 5. Konfigurasi `.env`

Salin contoh jika `.env` belum tersedia:

```bash
cp .env.example .env
php artisan key:generate
```

Atur `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada `.env` lokal. Jangan commit `.env`; yang dibagikan hanya `.env.example` tanpa kredensial.

Pastikan konfigurasi timezone Laravel menggunakan Asia/Jakarta. Pada `config/app.php`, setel konfigurasi timezone menjadi:

```php
'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
```

## 6. Instal API dan aset frontend

```bash
php artisan install:api
npm install
npm install bootstrap @popperjs/core sass --save-dev
```

`routes/api.php` pada paket ini menyediakan endpoint health check `GET /api/v1/ping`. Setelah API routing aktif, jalankan:

```bash
php artisan db:show
php artisan serve
```

Di terminal kedua:

```bash
npm run dev
```

Uji endpoint:

```bash
curl http://localhost:8000/api/v1/ping
```

Respons yang diharapkan:

```json
{"data":{"status":"ok","app":"InvenKopi"}}
```

## 7. Migrasi database

Pada tahap D2-01, cukup verifikasi koneksi dengan `php artisan db:show`. Tabel `users` bawaan Laravel akan disesuaikan/digantikan dengan `m_user` pada desain database berikutnya. Jangan menjalankan migrasi awal atau `migrate:fresh` sampai ERD dan skema D2-02/D2-03 disepakati dan backup tersedia.

## 8. Struktur folder tambahan

Script bootstrap menyiapkan folder konvensi proyek untuk:

- `app/Enums/`
- `app/Http/Controllers/Api/V1/`
- `app/Http/Requests/` dan `app/Http/Resources/`
- `app/Services/Inventory/`
- `database/migrations/`, `database/seeders/`, `database/factories/`
- `docs/`, `postman/`, `scripts/`
- `tests/Unit/Inventory/` dan `tests/Feature/Api/`

Simpan ERD, skema SQL, kontrak API, dan dokumen versi di `docs/`. Area `resources/` dan antarmuka dikelola bersama Divisi 3.
