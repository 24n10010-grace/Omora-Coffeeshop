# LAPORAN AKTIVITAS D3-01
# RISET UI DAN WIREFRAME SELURUH MODUL
### INVENKOPI - SISTEM INFORMASI MANAJEMEN INVENTARIS COFFEE SHOP OMORA

**Disusun oleh:**  
- **Grace Febriani S. P (24.N1.0010)** — UI/UX Designer, Frontend Developer & QA Tester (PIC D3)  
- **Michelle Aurellia (24.N1.0021)** — Project Manager & Analis Sistem (PIC D1)  
- **Kirana Ayu Dwi Putri (24.N1.0028)** — Database Administrator & Backend Developer (PIC D2)  

**PROGRAM STUDI SISTEM INFORMASI**  
**FAKULTAS ILMU KOMPUTER**  
**UNIVERSITAS KATOLIK SOEGIJAPRANATA**  
**SEMARANG**  
**2026/2027**

---

## DAFTAR ISI
- **BAB I PENDAHULUAN**
  - 1.1 Informasi Proyek
  - 1.2 Latar Belakang
  - 1.3 Tujuan Laporan D3-01
  - 1.4 Tim Proyek dan Pembagian Peran
- **BAB II HASIL AKTIVITAS D3-01**
  - 2.1 Riset Pengguna dan Karakteristik Lingkungan Barista
  - 2.2 Moodboard dan Arah Visual (Design Direction)
  - 2.3 Pemetaan Kebutuhan Antarmuka Modul M01–M12
  - 2.4 Wireframe Antarmuka Modul Utama
    - 2.4.1 Wireframe Dashboard Eksekutif (M12)
    - 2.4.2 Wireframe Form Perhitungan Fisik / Stock Opname (M09 - Prioritas)
    - 2.4.3 Wireframe Master Data Bahan & Resep BOM (M02 & M03)
    - 2.4.4 Wireframe Riwayat Transaksi Stok (M05–M08)
    - 2.4.5 Wireframe Laporan Analisis Variance (M10 & M11)
  - 2.5 Matriks RACI dan Mitigasi Risiko Divisi 3 (R07 & R04)
- **BAB III PENUTUP**
  - 3.1 Tindak Lanjut Menuju Aktivitas D3-02
  - 3.2 Penutup

---

## BAB I PENDAHULUAN

### 1.1 Informasi Proyek

| Atribut Proyek | Keterangan |
|---|---|
| **Nama Proyek** | InvenKopi - Sistem Informasi Manajemen Inventaris Coffee Shop OMORA |
| **Objek Studi Kasus** | OMORA Coffeeshop (1 Outlet Operasional) |
| **Mata Kuliah** | Manajemen Proyek |
| **Dosen Pengampu** | Ibu Thecla Brenda Chandrawati, ST., MT., IPM |
| **Tanggal Mulai** | Senin, 9 November 2026 |
| **Target Selesai** | Minggu, 6 Desember 2026 |
| **Durasi Proyek** | 28 hari kalender (4 minggu) |
| **Metodologi** | Waterfall (SDLC) dengan review di setiap milestone |
| **Teknologi Utama** | Laravel 13 (PHP 8.3), MySQL, Bootstrap 5 / Tailwind CSS, Figma, Git/GitHub |

### 1.2 Latar Belakang
OMORA Coffeeshop mengelola puluhan jenis bahan baku dan kemasan yang terdiri dari bahan kering (*DRY* seperti biji kopi house blend, bubuk cokelat, gula aren cair), bahan segar (*FRS* seperti susu UHT full cream, roti tawar, mentega), dan packaging (*PKG* seperti cup plastik 16 oz, tutup cup dome, sedotan). Operasional coffeeshop melibatkan dua unit satuan: satuan utuh saat barang dibeli (karton, pouch, pack, box) dan satuan ecer saat bahan diracik menjadi minuman (gram, ml, pcs).

Pencatatan persediaan secara manual atau melalui lembar kerja terpisah terbukti rawan salah hitung, memperlambat proses stock opname harian/mingguan, dan menyulitkan pelacakan selisih (*variance*). Sebagai bagian dari fase desain sistem, Divisi 3 bertanggung jawab merancang antarmuka sistem (UI/UX) yang ergonomis, responsif, dan mudah dioperasikan langsung oleh staf barista maupun supervisor di area bar.

### 1.3 Tujuan Laporan D3-01
1. Mendokumentasikan hasil riset antarmuka dan moodboard desain visual sistem InvenKopi OMORA.
2. Merancang kerangka dasar antarmuka (*wireframe*) untuk modul M01 hingga M12, dengan prioritas utama pada **Form Stock Opname (Physical Inventory / PI - M09)**.
3. Memastikan keselarasan tata letak antarmuka dengan kebutuhan pengguna pada dokumen SRS v1.0 (D1-03) dan model proses bisnis to-be (D1-04).

### 1.4 Tim Proyek dan Pembagian Peran

| Divisi | Nama Anggota | NIM | Peran Utama |
|---|---|---|---|
| **Divisi 1** | Michelle Aurellia | 24.N1.0021 | Project Manager & Analis Sistem |
| **Divisi 2** | Kirana Ayu Dwi Putri | 24.N1.0028 | Database Administrator & Backend Developer |
| **Divisi 3** | Grace Febriani S. P | 24.N1.0010 | UI/UX Designer, Frontend Developer & QA Tester (PIC D3) |

---

## BAB II HASIL AKTIVITAS D3-01

### 2.1 Riset Pengguna dan Karakteristik Lingkungan Barista
Berdasarkan hasil wawancara pada dokumen D1-02 dan pengamatan alur kerja bar OMORA:
1. **Perangkat Operasional:** Antarmuka harus optimal saat diakses melalui tablet konter barista (layar sentuh 10 inci) serta ponsel pintar staf, di samping laptop manajemen milik supervisor/owner.
2. **Kondisi Pencahayaan & Ergonomi:** Area bar coffeeshop memiliki pencahayaan temaram (*warm light*). Diperlukan antarmuka dengan kontras teks tinggi, tombol sentuh berukuran minimal 44x44 px, dan minim beban kognitif.
3. **Jam Sibuk (*Rush Hour*):** Saat antrean pesanan ramai, barista tidak memiliki waktu untuk menghitung rumus matematika rumit. Oleh karena itu, form opname harus mengotomatiskan konversi satuan utuh ke ecer.

### 2.2 Moodboard dan Arah Visual (Design Direction)
Arah visual sistem InvenKopi OMORA dirancang dengan prinsip *modern minimalist coffee inventory*:
- **Primary Tone:** *Dark Slate Teal* (`#1C3746`), memberikan identitas visual yang solid, tenang, dan mencerminkan manajemen profesional.
- **Surface & Canvas:** *Clean Ice-Blue Canvas* (`#F4F8FA` dan `#FFFFFF`), menciptakan ruang baca yang bersih dan melegakan mata.
- **Functional Accents:**
  - *Mint Green* (`#E6F6EE` / `#1B8A5A`): Status stok aman, transaksi penerimaan berhasil.
  - *Soft Coral Alert* (`#FDEDEA` / `#C0392B`): Peringatan stok menipis (*low stock*), selisih tinggi (*high variance*).
  - *Subtle Gray-Blue Border* (`#E1EBF0`): Pemisah antar modul kartu data.

---

### 2.3 Pemetaan Kebutuhan Antarmuka Modul M01–M12

| ID Modul | Nama Modul | Prioritas MoSCoW | Komponen UI Utama |
|---|---|---|---|
| **M01** | Autentikasi & Hak Akses | Must | Form login ringkas, role badge (Owner, Supervisor, Staf), session bar |
| **M02** | Master Data Item/Material | Must | Katalog tabel bahan baku (SKU, tipe, uom utuh/ecer, pack std, min. stock) |
| **M03** | Master Menu & Resep (BOM) | Must | Daftar menu, modal rincian komposisi bahan baku per porsi |
| **M04** | Master Supplier & Outlet | Should | Form & tabel direktori pemasok serta data lokasi outlet OMORA |
| **M05** | Penjualan & Usage | Must | Ringkasan penjualan menu dan tabel kalkulasi pemakaian bahan otomatis |
| **M06** | Penerimaan Barang (Receive) | Must | Form pencatatan surat jalan/faktur barang masuk menambah stok |
| **M07** | Transfer Stok | Should | Form mutasi bahan antar station/outlet (Trf In & Trf Out) |
| **M08** | Waste & Spoilage | Must | Form pencatatan bahan terbuang dengan dropdown alasan baku |
| **M09** | Stock Opname (Form PI) | Must | **Form hitung fisik cepat, input sisa utuh & ecer, auto-calculate total ecer** |
| **M10** | Analisis Variance Stok | Must | Tabel perbandingan Std. End Stock vs PI Count dan klasifikasi warna |
| **M11** | Stok Min, DOI & Reorder | Should | Notifikasi kartu bahan menipis dan rekomendasi order kemasan utuh |
| **M12** | Dashboard & Laporan | Should | OverView 4 metrik, donut chart nilai stok, bar chart top usage, line chart tren |

---

### 2.4 Wireframe Antarmuka Modul Utama

#### 2.4.1 Wireframe Dashboard Eksekutif (Modul M12)
Tata letak dashboard web standar dengan navigasi sidebar penuh di sisi kiri dan header dinamis di atas kanvas:

```text
+---------------------------------------------------------------------------------------------------+
| [BRAND: InvenKopi OMORA] | TOP NAVBAR: Dashboard Overview | [Search Bahan (Q)] [Outlet #01] [(2)!] |
|--------------------------+------------------------------------------------------------------------|
| (x) Dashboard            | OVERVIEW (4 KARTU METRIK UTAMA)                                        |
| ( ) Stock Opname (PI)    | +--------------+ +--------------+ +--------------+ +-----------------+  |
| ( ) Master Bahan         | | Total Bahan  | | Penerimaan   | | Total Stok   | |! Stok Kritis !  |  |
| ( ) Transaksi Stok       | |   14 SKU     | | 28 Transaksi | | 4.850 Ecer   | |    2 Item     |  |
| ( ) Laporan Variance     | +--------------+ +--------------+ +--------------+ +-----------------+  |
|                          |------------------------------------------------------------------------|
|                          | WIDGET TENGAH (3 KOLOM ANALITIK)                                       |
|                          | +--------------+ +--------------------+ +----------------------------+ |
|                          | | Shift Barista| | Inventory Values   | | Top 5 Pemakaian (Usage)    | |
|                          | | Shift Pagi   | | (O) Donut Chart    | | 1. Susu UHT       74.6 L   | |
|                          | | Grace & Kira | | 32% Bahan Terpakai | | 2. Kopi Arabika   11.5 Kg  | |
|                          | | Status: Open | | 68% Sisa Stok      | | 3. Gula Aren Cair  5.0 L   | |
|                          | +--------------+ +--------------------+ +----------------------------+ |
|                          |------------------------------------------------------------------------|
|                          | GRAFIK TREN PEMAKAIAN BAHAN VS PENJUALAN MENU (Line Chart 6 Bulan)     |
| [Grace - Barista] [->]   | [~~~~~~~~ Kurva Fluktuasi Bahan Baku vs Porsi Terjual ~~~~~~~~]        |
+---------------------------------------------------------------------------------------------------+
```

#### 2.4.2 Wireframe Khusus: Form Stock Opname (Form PI - Modul M09)
*Prioritas utama Divisi 3: Dioptimalkan untuk kecepatan pengisian di tablet tanpa memerlukan kalkulator terpisah.*

```text
+---------------------------------------------------------------------------------------------------+
| FORM PERHITUNGAN STOK FISIK (PHYSICAL INVENTORY)                         [ Tanggal: 19 Nov 2026 ] |
| Subtitle: Modul M09 - Dirancang khusus untuk input cepat pergantian shift                         |
| Filter Kategori: [ Semua Bahan ] [ Bahan Kering (DRY) ] [ Bahan Segar (FRS) ] [ Packaging (PKG) ]|
+---------------------------------------------------------------------------------------------------+
| SKU    | Nama Bahan Baku       | Tipe | Kemasan Utuh   | Sisa Utuh | Sisa Ecer | Total Fisik (Ecer)|
|--------+-----------------------+------+----------------+-----------+-----------+-------------------|
| DRY001 | Biji Kopi House Blend | DRY  | Pouch (1.000g) | [   2   ] | [  350  ] | = 2.350 G (Auto)  |
| DRY002 | Bubuk Cokelat         | DRY  | Pack (500g)    | [   2   ] | [   80  ] | = 1.080 G (Auto)  |
| DRY003 | Sirup Caramel         | DRY  | Botol (700ml)  | [   1   ] | [    0  ] | = 700 ML (Auto)   |
| DRY004 | Gula Aren Cair        | DRY  | Botol (1.000ml)| [   0   ] | [  700  ] | = 700 ML (Auto)   |
| FRS001 | Susu UHT Full Cream   | FRS  | Karton (1.000ml| [   4   ] | [  200  ] | = 4.200 ML (Auto) |
| PKG001 | Cup Plastik 16 Oz     | PKG  | Slop (50 pcs)  | [   3   ] | [   40  ] | = 190 PC (Auto)   |
+---------------------------------------------------------------------------------------------------+
| Tombol Aksi:                          [ Simpan Draft Sementara ]  [ Submit Hasil Stock Opname (V) ]|
+---------------------------------------------------------------------------------------------------+
```

**Rumus Baku Otomatisasi Input:**
$$\text{Total Ecer} = (\text{Sisa Kemasan Utuh} \times \text{Isi per Kemasan Utuh}) + \text{Sisa Ecer}$$

---

### 2.5 Matriks RACI dan Mitigasi Risiko Divisi 3

#### 2.5.1 Matriks RACI Aktivitas D3-01
- **Grace Febriani S. P (Divisi 3):** **A/R** (*Accountable & Responsible*) — Merancang moodboard, diagram kawat (*wireframe*), dan tata letak seluruh antarmuka.
- **Michelle Aurellia (Divisi 1):** **C** (*Consulted*) — Memvalidasi kesesuaian cakupan wireframe dengan SRS (D1-03) dan use case (D1-04).
- **Kirana Ayu Dwi Putri (Divisi 2):** **C** (*Consulted*) — Memberikan masukan terkait kelayakan struktur data dan ketersediaan parameter API backend.

#### 2.5.2 Mitigasi Risiko Terkait Antarmuka
1. **Risiko R07 (Barista kesulitan beradaptasi dengan form stock opname, Skor: 9 - Sedang):**
   - *Mitigasi:* Mengintegrasikan filter chip per kelompok penyimpanan (chiller/gudang kering) dan kalkulasi otomatis satuan utuh-ke-ecer langsung pada wireframe D3-01.
2. **Risiko R04 (Keterlambatan integrasi backend menghambat frontend, Skor: 12 - Sedang):**
   - *Mitigasi:* Mengacu secara ketat pada kontrak API v1.0 (`/api/v1/ping`, `/api/v1/items`, `/api/v1/stock-opnames`) dan menyematkan simulasi data prototipe riil di frontend.

---

## BAB III PENUTUP

### 3.1 Tindak Lanjut Menuju Aktivitas D3-02
Hasil wireframe dan moodboard pada laporan D3-01 ini menjadi fondasi langsung untuk pelaksanaan aktivitas **D3-02 (Desain UI High-Fidelity & Style Guide)**, di mana seluruh sketsa kerangka kawat ini diwujudkan menjadi antarmuka visual berwarna lengkap dengan spesifikasi tipografi, komponen token, dan prototipe responsif.

### 3.2 Penutup
Laporan aktivitas D3-01 telah selesai disusun sebagai bukti deliverable tahap awal perancangan antarmuka pengguna sistem InvenKopi OMORA. Seluruh sketsa antarmuka telah siap untuk direalisasikan ke tahap desain grafis resolusi tinggi dan implementasi kode frontend Laravel.

