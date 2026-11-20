# Dokumentasi Keputusan Desain Database
# InvenKopi — Sistem Informasi Manajemen Inventaris Coffeeshop OMORA
# Aktivitas D2-02

**Versi:** 1.0  
**Tanggal:** 2026-11-20  
**Disusun oleh:** Divisi 2 — Kirana Ayu Dwi Putri (Database Administrator & Backend Developer)  
**Teknologi:** MySQL 8.x · InnoDB · utf8mb4_unicode_ci · Laravel 13 (PHP 8.3)

---

## Daftar Isi

1. [Ringkasan Desain](#1-ringkasan-desain)
2. [Daftar Tabel dan Deskripsi](#2-daftar-tabel-dan-deskripsi)
3. [Penjelasan Primary Key dan Foreign Key](#3-penjelasan-primary-key-dan-foreign-key)
4. [Kardinalitas Relasi Lengkap](#4-kardinalitas-relasi-lengkap)
5. [Normalisasi](#5-normalisasi)
6. [Aturan Bisnis yang Didukung](#6-aturan-bisnis-yang-didukung)
7. [Perubahan dari Rancangan Awal](#7-perubahan-dari-rancangan-awal)
8. [Keputusan Desain Teknis](#8-keputusan-desain-teknis)
9. [Dukungan Modul M01–M12](#9-dukungan-modul-m01m12)
10. [Asumsi dan Hal yang Perlu Dikonfirmasi](#10-asumsi-dan-hal-yang-perlu-dikonfirmasi)
11. [Rekomendasi untuk D2-03](#11-rekomendasi-untuk-d2-03)

---

## 1. Ringkasan Desain

Database dirancang untuk mendukung **Sistem Informasi Manajemen Inventaris Coffeeshop OMORA** (InvenKopi) dengan total **17 tabel** yang mencakup master data, resep (BOM), seluruh transaksi inventaris, kartu stok, dan pencatatan stok awal.

### Statistik Skema

| Kategori | Jumlah Tabel | Nama Tabel |
|---|---|---|
| Master Data | 5 | m_outlet, m_user, m_supplier, m_item, m_menu |
| Data Resep | 1 | t_bom |
| Transaksi Inventaris | 9 | t_penjualan, t_penjualan_detail, t_penerimaan, t_penerimaan_detail, t_transfer, t_transfer_detail, t_waste, t_stock_opname, t_stock_opname_detail |
| Stok & Referensi | 2 | t_stock_ledger, r_begin_stock |
| **TOTAL** | **17** | |

### Prinsip Desain Utama

- **Integritas referensial** dijaga melalui FOREIGN KEY dengan aturan `ON DELETE RESTRICT` untuk data master yang berkaitan dengan transaksi historis.
- **Semua kuantitas stok** menggunakan `DECIMAL` (bukan `INT`) untuk mendukung nilai pecahan (misalnya 1.5 kemasan, 0.75 ML).
- **Password** disimpan dalam hash bcrypt melalui Laravel — tidak ada plaintext.
- **SKU**, **username**, dan **email** bersifat `UNIQUE`.
- **Soft delete tidak digunakan**; sebagai gantinya, kolom `is_active` pada tabel master mencegah penghapusan data yang masih digunakan dalam transaksi historis.

---

## 2. Daftar Tabel dan Deskripsi

### 2.1 Tabel Master

#### `m_outlet`
Menyimpan data lokasi operasional. Mendukung skenario multi-outlet (skalabilitas ke depan), meskipun OMORA saat ini beroperasi di 1 outlet.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT UNSIGNED AUTO_INCREMENT | PK |
| kode_outlet | VARCHAR(20) | Kode unik, contoh: `OUT001` |
| nama_outlet | VARCHAR(100) | Nama tampilan outlet |
| alamat | TEXT NULL | Alamat lengkap |
| telepon | VARCHAR(20) NULL | Nomor telepon |
| is_active | TINYINT(1) | Soft-disable outlet |

#### `m_user`
Menyimpan akun pengguna sistem. Role menentukan hak akses: `owner` (lintas outlet, semua fitur), `supervisor` (manajemen stok), `staff`/barista (input harian).

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT UNSIGNED AUTO_INCREMENT | PK |
| outlet_id | INT UNSIGNED NULL | FK → m_outlet; NULL = lintas outlet |
| username | VARCHAR(50) UNIQUE | Login identifier |
| email | VARCHAR(150) UNIQUE | Kontak & reset password |
| password | VARCHAR(255) | Hash bcrypt Laravel |
| nama_lengkap | VARCHAR(100) | Nama tampilan |
| role | ENUM('owner','supervisor','staff') | Hak akses |
| is_active | TINYINT(1) | Soft-disable user |

#### `m_supplier`
Direktori vendor/pemasok bahan baku.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT UNSIGNED AUTO_INCREMENT | PK |
| kode_supplier | VARCHAR(20) UNIQUE | Kode unik, contoh: `SUP001` |
| nama_supplier | VARCHAR(150) | Nama perusahaan/pemasok |
| kontak_person | VARCHAR(100) NULL | Nama PIC pemasok |
| telepon | VARCHAR(20) NULL | |
| alamat | TEXT NULL | |

#### `m_item`
Master bahan baku / material inventaris. Kolom `pack_standard` adalah kunci perhitungan konversi satuan utuh ke ecer.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | INT UNSIGNED AUTO_INCREMENT | PK |
| sku | VARCHAR(30) UNIQUE | SKU unik, contoh: `DRY001`, `FRS001` |
| nama_item | VARCHAR(150) | Nama material |
| tipe_material | ENUM('DRY','FRS','FRZ','PKG') | Klasifikasi material |
| satuan_ecer | ENUM('G','ML','PC') | Satuan saat digunakan |
| satuan_utuh | ENUM('POC','PAC','BOX','CAR') | Satuan saat dibeli |
| pack_standard | DECIMAL(10,4) | Isi per kemasan utuh dalam satuan ecer |
| stok_minimum | DECIMAL(10,4) | Ambang batas peringatan stok |

> **Contoh:** Susu UHT Full Cream — SKU `FRS001`, tipe `FRS`, satuan ecer `ML`, satuan utuh `CAR` (karton), pack_standard = `1000` (1 karton = 1000 ML).

#### `m_menu`
Master menu minuman/makanan yang dijual. Kolom `harga` disimpan sebagai snapshot; harga aktual saat transaksi disimpan di `t_penjualan_detail.harga_satuan`.

---

### 2.2 Tabel Resep

#### `t_bom`
Bill of Materials. Setiap baris menyatakan: "Menu X membutuhkan Y gram/ml/pcs Item Z per porsi." Constraint `UNIQUE(menu_id, item_id)` mencegah duplikasi bahan dalam satu resep.

| Kolom | Tipe | Keterangan |
|---|---|---|
| menu_id | INT UNSIGNED | FK → m_menu |
| item_id | INT UNSIGNED | FK → m_item |
| qty_per_porsi | DECIMAL(10,4) | Kuantitas per 1 porsi |
| satuan | ENUM('G','ML','PC') | Harus konsisten dengan satuan_ecer item |

**Rumus Usage:**
```
Usage = SUM(t_penjualan_detail.qty × t_bom.qty_per_porsi)
        per item_id, per outlet, per periode
```

---

### 2.3 Tabel Transaksi (Header + Detail)

Semua transaksi menggunakan pola **Header-Detail** (master-child) untuk mendukung multi-item dalam satu dokumen.

#### `t_penjualan` + `t_penjualan_detail`
- **Header:** Tanggal, outlet, petugas, shift (pagi/siang/malam), nomor dokumen, status.
- **Detail:** Menu yang terjual + kuantitas + snapshot harga saat transaksi.
- Kolom `status` mendukung alur `draft → posted → void` untuk kontrol data entry barista.

#### `t_penerimaan` + `t_penerimaan_detail`
- **Header:** Tanggal, outlet tujuan, supplier, petugas, nomor surat jalan.
- **Detail:** Per baris — item, qty_utuh, qty_ecer, total_ecer.
- `total_ecer` dihitung aplikasi: `(qty_utuh × item.pack_standard) + qty_ecer`.

#### `t_transfer` + `t_transfer_detail`
- **Header:** Tanggal, outlet asal, outlet tujuan, petugas.
- **Detail:** Per baris — item, qty_ecer yang dipindahkan.
- Dua FK berbeda ke `m_outlet` (`outlet_asal_id` dan `outlet_tujuan_id`) mendukung transfer antar-outlet.

#### `t_waste`
Satu record per kejadian waste. Dipilih tabel flat (bukan header-detail) karena satu kejadian waste biasanya hanya menyangkut satu jenis material (sesuai praktik operasional coffeeshop). Kolom `alasan` mendukung kategorisasi (expired, spilled, damaged, over-production).

#### `t_stock_opname` + `t_stock_opname_detail`
- **Header:** Tanggal, outlet, petugas penghitung, status (mendukung fitur **Simpan Draft Sementara** dari desain D3-03).
- **Detail:** Per item — sisa_utuh, sisa_ecer, total_ecer.
- Constraint `UNIQUE(stock_opname_id, item_id)` mencegah duplikasi item dalam satu dokumen opname.

---

### 2.4 Tabel Stok & Referensi

#### `t_stock_ledger`
Kartu stok / buku besar mutasi. Setiap perubahan stok (dari transaksi apapun) menghasilkan satu baris di sini. Kolom `saldo_ecer` menyimpan saldo setelah mutasi, memudahkan query saldo tanpa harus mengagregasi semua transaksi.

Kolom `referensi_tipe` + `referensi_id` merekam dokumen asal sebagai "polymorphic reference" tanpa FK formal (untuk fleksibilitas), sesuai pola yang umum di Laravel.

| jenis_mutasi | Asal Dokumen |
|---|---|
| `begin_stock` | r_begin_stock |
| `receive` | t_penerimaan_detail |
| `trf_in` | t_transfer_detail (tujuan) |
| `trf_out` | t_transfer_detail (asal) |
| `waste` | t_waste |
| `usage` | t_penjualan_detail (via BOM) |
| `opname_adj` | t_stock_opname_detail |

#### `r_begin_stock`
Menyimpan stok awal per item per outlet per periode (format `YYYY-MM`). Constraint `UNIQUE(outlet_id, item_id, periode)` memastikan tidak ada duplikasi stok awal. Ini adalah solusi untuk kebutuhan yang tidak tercakup dalam rancangan awal.

---

## 3. Penjelasan Primary Key dan Foreign Key

### Primary Key
Semua tabel menggunakan **surrogate key** `INT UNSIGNED AUTO_INCREMENT` sebagai PK. Alasan:
- Performa JOIN lebih baik dibanding UUID atau composite key.
- Konsisten dengan konvensi Laravel (Eloquent).
- Identitas bisnis (SKU, kode_menu, dsb) dijaga dengan UNIQUE constraint terpisah.

### Foreign Key dan Aturan Referensial

| FK | Tabel Asal | Referensi | ON DELETE | ON UPDATE | Alasan |
|---|---|---|---|---|---|
| fk_user_outlet | m_user.outlet_id | m_outlet.id | SET NULL | CASCADE | User dapat lintas outlet |
| fk_bom_menu | t_bom.menu_id | m_menu.id | RESTRICT | CASCADE | Lindungi resep historis |
| fk_bom_item | t_bom.item_id | m_item.id | RESTRICT | CASCADE | Lindungi resep historis |
| fk_penjualan_outlet | t_penjualan.outlet_id | m_outlet.id | RESTRICT | CASCADE | Lindungi transaksi historis |
| fk_penjualan_user | t_penjualan.user_id | m_user.id | RESTRICT | CASCADE | Lindungi transaksi historis |
| fk_penjdetail_penjualan | t_penjualan_detail.penjualan_id | t_penjualan.id | RESTRICT | CASCADE | Integritas header-detail |
| fk_penjdetail_menu | t_penjualan_detail.menu_id | m_menu.id | RESTRICT | CASCADE | Lindungi menu historis |
| fk_penerimaan_outlet | t_penerimaan.outlet_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_penerimaan_supplier | t_penerimaan.supplier_id | m_supplier.id | RESTRICT | CASCADE | |
| fk_penerimaan_user | t_penerimaan.user_id | m_user.id | RESTRICT | CASCADE | |
| fk_trndetail_penerimaan | t_penerimaan_detail.penerimaan_id | t_penerimaan.id | RESTRICT | CASCADE | |
| fk_trndetail_item | t_penerimaan_detail.item_id | m_item.id | RESTRICT | CASCADE | |
| fk_transfer_outlet_asal | t_transfer.outlet_asal_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_transfer_outlet_tujuan | t_transfer.outlet_tujuan_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_transfer_user | t_transfer.user_id | m_user.id | RESTRICT | CASCADE | |
| fk_trfdetail_transfer | t_transfer_detail.transfer_id | t_transfer.id | RESTRICT | CASCADE | |
| fk_trfdetail_item | t_transfer_detail.item_id | m_item.id | RESTRICT | CASCADE | |
| fk_waste_outlet | t_waste.outlet_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_waste_item | t_waste.item_id | m_item.id | RESTRICT | CASCADE | |
| fk_waste_user | t_waste.user_id | m_user.id | RESTRICT | CASCADE | |
| fk_opname_outlet | t_stock_opname.outlet_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_opname_user | t_stock_opname.user_id | m_user.id | RESTRICT | CASCADE | |
| fk_opndetail_opname | t_stock_opname_detail.stock_opname_id | t_stock_opname.id | RESTRICT | CASCADE | |
| fk_opndetail_item | t_stock_opname_detail.item_id | m_item.id | RESTRICT | CASCADE | |
| fk_ledger_outlet | t_stock_ledger.outlet_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_ledger_item | t_stock_ledger.item_id | m_item.id | RESTRICT | CASCADE | |
| fk_begin_outlet | r_begin_stock.outlet_id | m_outlet.id | RESTRICT | CASCADE | |
| fk_begin_item | r_begin_stock.item_id | m_item.id | RESTRICT | CASCADE | |
| fk_begin_user | r_begin_stock.user_id | m_user.id | RESTRICT | CASCADE | |

> **ON DELETE RESTRICT** digunakan hampir di semua relasi untuk mencegah penghapusan data master yang masih terhubung dengan transaksi historis. Data master yang sudah tidak aktif cukup di-nonaktifkan melalui `is_active = 0`.

---

## 4. Kardinalitas Relasi Lengkap

| Entitas A | Kardinalitas | Entitas B | Keterangan |
|---|---|---|---|
| m_menu | 1 | N | t_bom | Satu menu → banyak komponen bahan |
| m_item | 1 | N | t_bom | Satu bahan → digunakan di banyak resep |
| m_outlet | 1 | N | t_penjualan | Satu outlet → banyak transaksi penjualan |
| m_user | 1 | N | t_penjualan | Satu petugas → banyak penjualan |
| t_penjualan | 1 | N | t_penjualan_detail | Satu header → banyak baris menu |
| m_menu | 1 | N | t_penjualan_detail | Satu menu → muncul di banyak transaksi |
| m_outlet | 1 | N | t_penerimaan | Satu outlet → banyak penerimaan |
| m_supplier | 1 | N | t_penerimaan | Satu supplier → banyak penerimaan |
| t_penerimaan | 1 | N | t_penerimaan_detail | Satu header → banyak baris item |
| m_item | 1 | N | t_penerimaan_detail | Satu item → diterima di banyak transaksi |
| m_outlet | 1 | N | t_transfer (asal) | Satu outlet → banyak transfer keluar |
| m_outlet | 1 | N | t_transfer (tujuan) | Satu outlet → banyak transfer masuk |
| t_transfer | 1 | N | t_transfer_detail | Satu header → banyak baris item |
| m_item | 1 | N | t_transfer_detail | Satu item → dipindahkan di banyak transfer |
| m_outlet | 1 | N | t_waste | Satu outlet → banyak kejadian waste |
| m_item | 1 | N | t_waste | Satu item → banyak kejadian waste |
| m_outlet | 1 | N | t_stock_opname | Satu outlet → banyak dokumen opname |
| t_stock_opname | 1 | N | t_stock_opname_detail | Satu opname → banyak baris item |
| m_item | 1 | N | t_stock_opname_detail | Satu item → dihitung di banyak opname |
| m_outlet | 1 | N | t_stock_ledger | Satu outlet → banyak baris mutasi |
| m_item | 1 | N | t_stock_ledger | Satu item → banyak baris mutasi |
| m_outlet | 1 | N | r_begin_stock | Satu outlet → banyak periode stok awal |
| m_item | 1 | N | r_begin_stock | Satu item → banyak periode stok awal |

---

## 5. Normalisasi

Skema dirancang memenuhi **Third Normal Form (3NF)**:

### 1NF — Atomisitas
- Setiap sel menyimpan satu nilai atomik.
- Tidak ada kolom multi-value atau kolom berulang.
- Contoh: Satuan ecer dan satuan utuh disimpan dalam kolom terpisah di `m_item`, bukan digabung.

### 2NF — Ketergantungan Penuh pada PK
- Semua kolom non-key bergantung penuh pada primary key.
- Pemisahan header-detail pada semua transaksi (penjualan, penerimaan, transfer, opname) memastikan atribut detail tidak bergantung sebagian pada PK header.

### 3NF — Tidak Ada Ketergantungan Transitif
- `pack_standard` disimpan di `m_item`, bukan diulang di setiap tabel transaksi.
- `total_ecer` di tabel detail adalah nilai turunan yang dihitung dan disimpan (denormalisasi terkontrol) untuk performa query. Nilainya selalu sinkron karena dihitung oleh aplikasi saat insert.
- `saldo_ecer` di `t_stock_ledger` adalah denormalisasi terkontrol yang menghindari agregasi besar-besaran saat query saldo terkini.

> **Denormalisasi Terkontrol** pada `total_ecer` dan `saldo_ecer` adalah keputusan sadar untuk performa operasional, bukan pelanggaran normalisasi. Nilai ini bersifat derived tapi disimpan karena sering diquery dan mahal jika selalu dihitung ulang.

---

## 6. Aturan Bisnis yang Didukung

### A. Pengelolaan Material (M02)
✅ SKU unik → `UNIQUE KEY uq_item_sku`  
✅ Tipe material DRY/FRS/FRZ/PKG → `ENUM('DRY','FRS','FRZ','PKG')`  
✅ Satuan ecer G/ML/PC → `ENUM('G','ML','PC')`  
✅ Satuan utuh POC/PAC/BOX/CAR → `ENUM('POC','PAC','BOX','CAR')`  
✅ Isi per kemasan → `pack_standard DECIMAL(10,4)`  
✅ Stok minimum → `stok_minimum DECIMAL(10,4)`  

### B. Bill of Materials (M03)
✅ Satu menu → banyak bahan → `t_bom (menu_id, item_id)`  
✅ Kuantitas per porsi → `qty_per_porsi DECIMAL(10,4)`  
✅ Usage = qty_terjual × qty_per_porsi → dapat dihitung via JOIN `t_penjualan_detail` ⨝ `t_bom`  
✅ Duplikasi bahan dalam satu resep dicegah → `UNIQUE(menu_id, item_id)`  

### C. Penerimaan Barang (M06)
✅ Tanggal, nomor dokumen, supplier, outlet tujuan → `t_penerimaan`  
✅ Detail per material, kuantitas utuh & ecer → `t_penerimaan_detail`  
✅ total_ecer = (qty_utuh × pack_standard) + qty_ecer → dihitung aplikasi saat insert  
✅ Penerimaan menambah stok → dicatat di `t_stock_ledger` (jenis_mutasi = 'receive')  

### D. Transfer Stok (M07)
✅ Outlet asal & tujuan → `outlet_asal_id`, `outlet_tujuan_id` di `t_transfer`  
✅ Material & kuantitas → `t_transfer_detail`  
✅ Transfer keluar catat `qty_keluar` di ledger outlet asal (trf_out)  
✅ Transfer masuk catat `qty_masuk` di ledger outlet tujuan (trf_in)  

### E. Waste & Spoilage (M08)
✅ Outlet, item, kuantitas, tanggal, alasan → `t_waste`  
✅ Waste mengurangi stok → dicatat di `t_stock_ledger` (jenis_mutasi = 'waste')  

### F. Stock Opname / Physical Inventory (M09)
✅ Tanggal, petugas, outlet → `t_stock_opname`  
✅ Sisa utuh, sisa ecer per item → `t_stock_opname_detail`  
✅ total_ecer = (sisa_utuh × pack_standard) + sisa_ecer → dihitung aplikasi  
✅ Status `draft` mendukung fitur **Simpan Draft Sementara** (D3-03)  

### G. Stock Variance (M10)

Formula yang didukung database:

```
Std. End Stock = Begin Stock + Receive + Trf In − Trf Out − Waste − Usage

Variance = PI Count − Std. End Stock

% Variance = Variance / Std. End Stock   (handle div/zero: jika Std.End=0, % = NULL atau 0)
```

**Sumber data per komponen:**
| Komponen | Sumber |
|---|---|
| Begin Stock | `r_begin_stock.qty_ecer` per (outlet, item, periode) |
| Receive | SUM `t_penerimaan_detail.total_ecer` via `t_penerimaan` per (outlet, item, tanggal range) |
| Trf In | SUM `t_transfer_detail.qty_ecer` via `t_transfer` (outlet_tujuan) per (outlet, item, tanggal range) |
| Trf Out | SUM `t_transfer_detail.qty_ecer` via `t_transfer` (outlet_asal) per (outlet, item, tanggal range) |
| Waste | SUM `t_waste.qty_ecer` per (outlet, item, tanggal range) |
| Usage | SUM(`t_penjualan_detail.qty` × `t_bom.qty_per_porsi`) per (outlet, item, tanggal range) |
| PI Count | `t_stock_opname_detail.total_ecer` pada opname terakhir di periode |

**Klasifikasi Variance:**

| Klasifikasi | Kondisi |
|---|---|
| No Variance | \|% Variance\| = 0% |
| Low Variance | 0% < \|% Variance\| ≤ 5% |
| Medium Variance | 5% < \|% Variance\| ≤ 20% |
| High Variance | \|% Variance\| > 20% |

> Pembagian dengan nol ditangani pada layer aplikasi (Laravel Service / Query). Jika `Std. End Stock = 0`, maka `% Variance = NULL` dan diklasifikasikan sebagai "No Variance" atau ditampilkan sebagai `N/A`.

### H. Days of Inventory / DOI (M11)

```
DOI = PI Count / Rata-rata Usage Harian

Rata-rata Usage Harian = Total Usage Periode / Jumlah Hari Periode
```

Data historis untuk DOI tersedia melalui kombinasi `t_penjualan_detail` ⨝ `t_bom` per periode yang dikonfigurasi.

---

## 7. Perubahan dari Rancangan Awal

Rancangan awal menetapkan 10 entitas: `m_user`, `m_outlet`, `m_supplier`, `m_item`, `m_menu`, `t_bom`, `t_penjualan`, `t_penerimaan`, `t_transfer`, `t_waste`, `t_stock_opname`.

Desain akhir D2-02 memiliki **17 tabel**. Berikut pemetaan perubahan:

| Rancangan Awal | Desain D2-02 | Alasan |
|---|---|---|
| `m_user` | `m_user` (diperluas) | Tambah kolom: `outlet_id`, `nama_lengkap`, `is_active` |
| `m_outlet` | `m_outlet` (diperluas) | Tambah: `kode_outlet`, `is_active` |
| `m_supplier` | `m_supplier` (diperluas) | Tambah: `kode_supplier`, `kontak_person`, `is_active` |
| `m_item` | `m_item` (diperluas) | Tambah: `tipe_material`, `satuan_ecer`, `satuan_utuh`, `pack_standard`, `stok_minimum`, `is_active` |
| `m_menu` | `m_menu` (diperluas) | Tambah: `kode_menu`, `kategori`, `harga`, `is_active` |
| `t_bom` | `t_bom` | Tidak berubah signifikan |
| `t_penjualan` | **`t_penjualan` + `t_penjualan_detail`** | **Dipisah** untuk mendukung multi-menu per transaksi |
| `t_penerimaan` | **`t_penerimaan` + `t_penerimaan_detail`** | **Dipisah** untuk mendukung multi-item per dokumen penerimaan |
| `t_transfer` | **`t_transfer` + `t_transfer_detail`** | **Dipisah** untuk mendukung multi-item per dokumen transfer |
| `t_waste` | `t_waste` (diperluas) | Tambah: `no_dokumen`, `alasan`, `catatan`; tetap flat (satu item per record) |
| `t_stock_opname` | **`t_stock_opname` + `t_stock_opname_detail`** | **Dipisah** untuk mendukung multi-item per dokumen opname |
| *(tidak ada)* | **`t_stock_ledger`** (BARU) | Kartu stok untuk tracking mutasi dan saldo real-time |
| *(tidak ada)* | **`r_begin_stock`** (BARU) | Stok awal per periode — wajib untuk perhitungan Std. End Stock & Variance |

### Alasan Pemisahan Header-Detail

Tanpa pemisahan, satu dokumen penerimaan (misalnya) yang mencakup 5 bahan baku harus disimpan dalam 5 baris duplikat di tabel header, yang menyebabkan:
1. **Redundansi data** (tanggal, supplier, outlet diulang 5 kali).
2. **Anomali update** (mengubah tanggal harus diubah di 5 baris).
3. **Sulit mencetak laporan per dokumen** (tidak ada identitas dokumen yang bersih).

Pola Header-Detail menyelesaikan ketiga masalah tersebut dan merupakan standar industri untuk sistem inventaris.

---

## 8. Keputusan Desain Teknis

### 8.1 Tipe Data

| Jenis Data | Tipe MySQL | Alasan |
|---|---|---|
| ID / PK | INT UNSIGNED | Range 0–4.29 miliar; konsisten dengan Laravel |
| Kuantitas stok | DECIMAL(10,4) atau DECIMAL(12,4) | Mendukung nilai pecahan (0.5 kemasan) |
| Harga | DECIMAL(12,2) | Mendukung hingga 10 milyar rupiah dengan 2 desimal |
| Saldo ledger | DECIMAL(14,4) | Akumulasi jangka panjang; presisi lebih tinggi |
| SKU, kode | VARCHAR(20–30) | Alfanumerik, panjang terbatas |
| Nama, deskripsi | VARCHAR(100–150) | Teks pendek terstruktur |
| Catatan bebas | TEXT | Teks panjang tak terbatas |
| Tanggal transaksi | DATE | Hanya tanggal (bukan waktu) |
| Waktu pencatatan | TIMESTAMP | Otomatis diisi oleh MySQL |
| Status | ENUM | Nilai terbatas; lebih aman dari VARCHAR bebas |
| Boolean | TINYINT(1) | Konvensi MySQL; Laravel casting `boolean` |
| Periode | CHAR(7) | Format tetap `YYYY-MM` |

### 8.2 Strategi Soft Delete
Data master (item, menu, supplier, outlet, user) tidak dihapus fisik. Kolom `is_active = 0` menonaktifkan entitas tanpa memutus referensi dengan transaksi historis. Ini memenuhi aturan integritas data jangka panjang.

### 8.3 Nomor Dokumen
Setiap transaksi memiliki `no_dokumen` unik (VARCHAR + UNIQUE KEY). Format yang disarankan:
- Penjualan: `SAL-YYYYMMDD-NNN`
- Penerimaan: `RCV-YYYYMMDD-NNN`
- Transfer: `TRF-YYYYMMDD-NNN`
- Waste: `WST-YYYYMMDD-NNN`
- Stock Opname: `PI-YYYYMMDD-NNN`

Generasi nomor dokumen dilakukan di layer aplikasi Laravel (D2-03).

### 8.4 Stock Ledger vs Hanya Query Agregasi
Dua pendekatan tersedia:
1. **Hanya Query Agregasi:** Hitung saldo dengan SUM semua transaksi setiap kali dibutuhkan.
2. **Stock Ledger (dipilih):** Simpan saldo running setelah setiap mutasi.

**Alasan memilih stock ledger:**
- Query saldo terkini cukup `SELECT saldo_ecer FROM t_stock_ledger ORDER BY id DESC LIMIT 1`.
- Mendukung audit trail lengkap.
- Performa lebih baik untuk dashboard real-time.
- Mendukung rekonstruksi riwayat stok per periode untuk DOI dan Variance.

### 8.5 Charset dan Collation
- Charset: `utf8mb4` → Mendukung karakter Indonesia + emoji dalam catatan.
- Collation: `utf8mb4_unicode_ci` → Case-insensitive, case-insensitive accent.

---

## 9. Dukungan Modul M01–M12

| Modul | Nama | Tabel Utama | Status |
|---|---|---|---|
| M01 | Autentikasi & Hak Akses | m_user | ✅ Didukung (role ENUM, Sanctum via Laravel) |
| M02 | Master Data Item/Material | m_item | ✅ Didukung lengkap |
| M03 | Master Menu & Resep (BOM) | m_menu, t_bom | ✅ Didukung lengkap |
| M04 | Master Supplier & Outlet | m_supplier, m_outlet | ✅ Didukung lengkap |
| M05 | Penjualan & Usage | t_penjualan, t_penjualan_detail, t_bom | ✅ Usage dihitung via JOIN |
| M06 | Penerimaan Barang (Receive) | t_penerimaan, t_penerimaan_detail | ✅ Didukung lengkap |
| M07 | Transfer Stok | t_transfer, t_transfer_detail | ✅ Didukung (dual FK outlet) |
| M08 | Waste & Spoilage | t_waste | ✅ Didukung lengkap |
| M09 | Stock Opname (Form PI) | t_stock_opname, t_stock_opname_detail | ✅ Termasuk fitur draft |
| M10 | Analisis Variance Stok | Semua tabel transaksi + r_begin_stock | ✅ Semua komponen tersedia |
| M11 | Stok Min, DOI & Reorder | m_item (stok_minimum), t_stock_ledger | ✅ Didukung |
| M12 | Dashboard & Laporan | Semua tabel | ✅ Data tersedia untuk agregasi |

---

## 10. Asumsi dan Hal yang Perlu Dikonfirmasi

### Asumsi yang Dibuat

1. **Satu outlet aktif:** OMORA saat ini beroperasi di 1 outlet, namun desain mendukung multi-outlet untuk skalabilitas.
2. **Waste per kejadian:** Satu record waste = satu jenis material. Jika satu kejadian waste melibatkan banyak bahan sekaligus, perlu dipertimbangkan penambahan header-detail pada `t_waste`.
3. **Stok awal per bulan:** Periode `r_begin_stock` menggunakan format `YYYY-MM` (bulanan). Jika diperlukan per minggu atau per sesi, format perlu disesuaikan.
4. **Status dokumen:** Alur `draft → posted → void` diasumsikan cukup. Jika ada alur approval tambahan (misalnya supervisor harus menyetujui penerimaan), perlu penambahan status.
5. **Harga menu:** Disimpan sebagai snapshot di `t_penjualan_detail.harga_satuan`. Riwayat perubahan harga tidak disimpan (tidak ada tabel harga historis).
6. **Satuan BOM:** Kolom `satuan` di `t_bom` diasumsikan harus konsisten dengan `satuan_ecer` di `m_item`. Validasi ini dilakukan di layer aplikasi.
7. **FRZ (Frozen):** Tipe material `FRZ` disertakan di ENUM `tipe_material` meskipun tidak disebutkan dalam wireframe D3-02. Dimasukkan sesuai spesifikasi prompt asli.

### Hal yang Perlu Dikonfirmasi ke Divisi 1

1. **Apakah waste dapat melibatkan banyak jenis bahan dalam satu kejadian?**  
   → Jika ya, `t_waste` perlu dipecah menjadi header + detail seperti tabel transaksi lainnya.

2. **Bagaimana mekanisme penentuan stok awal (begin stock)?**  
   → Apakah diinput manual oleh supervisor setiap awal periode, atau merupakan saldo akhir periode sebelumnya yang otomatis dicarry-forward?

3. **Apakah diperlukan pencatatan harga beli pada penerimaan barang?**  
   → `t_penerimaan_detail` belum memiliki kolom `harga_satuan_beli`. Jika diperlukan untuk laporan nilai stok (inventory valuation), perlu ditambahkan.

4. **Format nomor dokumen yang disepakati tim?**  
   → Usulan: `SAL-YYYYMMDD-NNN`, `RCV-YYYYMMDD-NNN`, dll. Perlu konfirmasi dari Divisi 1 (PM).

5. **Apakah satu pengguna dapat bertugas di lebih dari satu outlet?**  
   → Desain saat ini: `m_user.outlet_id` adalah nullable (NULL = lintas outlet untuk owner). Jika supervisor bisa bertugas di beberapa outlet sekaligus, perlu tabel pivot `m_user_outlet`.

6. **Frekuensi stock opname?**  
   → Apakah per shift, harian, atau mingguan? Mempengaruhi pengisian `r_begin_stock`.

7. **Apakah diperlukan pencatatan transfer dalam satuan utuh juga?**  
   → Saat ini `t_transfer_detail.qty_ecer` hanya menyimpan satuan ecer. Jika bisnis perlu mencatat "transfer 2 karton susu", perlu ditambahkan kolom `qty_utuh` juga.

---

## 11. Rekomendasi untuk D2-03

Aktivitas D2-03 (Implementasi Database, Seed Data Master & Desain API) dapat langsung menggunakan desain ini dengan langkah berikut:

### Database Migration (Laravel)
Buat migration files dalam urutan dependency yang benar:

```
1. create_m_outlet_table
2. create_m_user_table
3. create_m_supplier_table
4. create_m_item_table
5. create_m_menu_table
6. create_t_bom_table
7. create_t_penjualan_table
8. create_t_penjualan_detail_table
9. create_t_penerimaan_table
10. create_t_penerimaan_detail_table
11. create_t_transfer_table
12. create_t_transfer_detail_table
13. create_t_waste_table
14. create_t_stock_opname_table
15. create_t_stock_opname_detail_table
16. create_t_stock_ledger_table
17. create_r_begin_stock_table
```

### Eloquent Models
Buat model untuk setiap tabel di `app/Models/`. Gunakan `$guarded = []` atau `$fillable` sesuai konvensi tim. Tambahkan `$casts` untuk ENUM, DECIMAL, dan boolean.

### Enums (PHP 8.1+)
Buat PHP Enum classes di `app/Enums/`:
- `TipeMaterial`: DRY, FRS, FRZ, PKG
- `SatuanEcer`: G, ML, PC
- `SatuanUtuh`: POC, PAC, BOX, CAR
- `UserRole`: owner, supervisor, staff
- `StatusDokumen`: draft, posted, void
- `JenisMutasi`: begin_stock, receive, trf_in, trf_out, waste, usage, opname_adj

### Seed Data Master
Seed hanya data master (D2-03 scope):
- `m_outlet`: 1 outlet OMORA
- `m_user`: akun owner, supervisor, 1 staff (password di-hash)
- `m_item`: minimal 10 SKU dari data riil D3-02 (DRY001–DRY004, FRS001–FRS003, PKG001–PKG003)
- `m_menu`: menu utama dari Proto BOM
- `t_bom`: resep dari Proto BOM

### Validasi Skema
Setelah migrasi berhasil dijalankan, verifikasi dengan:
```bash
php artisan db:show
php artisan migrate:status
```
