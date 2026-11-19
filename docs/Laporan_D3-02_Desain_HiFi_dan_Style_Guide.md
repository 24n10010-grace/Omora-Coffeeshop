# LAPORAN AKTIVITAS D3-02
# DESAIN UI HIGH-FIDELITY & STYLE GUIDE (FIGMA)
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
  - 1.3 Tujuan Laporan D3-02
  - 1.4 Tim Proyek dan Pembagian Peran
- **BAB II HASIL AKTIVITAS D3-02**
  - 2.1 Style Guide & Design System InvenKopi
    - 2.1.1 Palet Warna Baku (Color Tokens)
    - 2.1.2 Tipografi dan Skala Hierarki Teks
    - 2.1.3 Spacing, Grid System, dan Radius
  - 2.2 Desain Komponen UI Reusable
    - 2.2.1 Sidebar Navigasi dengan Curved Active Tab
    - 2.2.2 Metric Card & Alert Indicator
    - 2.2.3 Data Table & Numpad Input Field
    - 2.2.4 Micro-Charts (Donut, Horizontal Bar, Line SVG)
  - 2.3 Spesifikasi Layar High-Fidelity Berdasarkan Data Riil
    - 2.3.1 Layar Dashboard Overview (M12)
    - 2.3.2 Layar Form Stock Opname / PI (M09)
    - 2.3.3 Layar Master Bahan & Resep BOM (M02 & M03)
    - 2.3.4 Layar Laporan Variance & Rekomendasi Pemesanan (M10 & M11)
  - 2.4 Responsivitas Multi-Device (Desktop Outlet, Tablet Barista, Mobile)
  - 2.5 Matriks RACI dan Mitigasi Risiko
- **BAB III PENUTUP**
  - 3.1 Tindak Lanjut Menuju Aktivitas D3-03 dan Milestone D1-05
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
| **Jadwal D3-02** | 15 – 19 November 2026 (Durasi: 5 hari kalender) |

### 1.2 Latar Belakang
Setelah rancangan kerangka kawat (*wireframe*) diselesaikan pada aktivitas D3-01, tahapan berikutnya adalah mewujudkan kerangka tersebut menjadi rancangan antarmuka visual resolusi tinggi (*High-Fidelity UI Design*) serta menyusun panduan gaya desain (*style guide*). 

Dalam konteks operasional OMORA Coffeeshop, konsistensi visual sangat penting agar staf barista yang bekerja di bawah tekanan jam sibuk (*rush hour*) dapat membaca informasi stok secara cepat tanpa ragu. Oleh karena itu, aktivitas D3-02 memetakan palet warna, tipografi, dan komponen antarmuka yang terstandarisasi untuk diimplementasikan ke dalam *frontend* Laravel.

### 1.3 Tujuan Laporan D3-02
1. Menetapkan dokumentasi panduan desain baku (*Design Style Guide*) meliputi warna, tipografi, grid, dan komponen antarmuka sistem InvenKopi.
2. Menghasilkan spesifikasi antarmuka *High-Fidelity* untuk seluruh modul inventaris, yang diselaraskan dengan data contoh riil dari lembar kerja *Proto BOM* dan *Proto Variance*.
3. Menyusun panduan adaptasi responsivitas antarmuka pada tablet konter barista dan perangkat ponsel.

### 1.4 Tim Proyek dan Pembagian Peran

| Divisi | Nama Anggota | NIM | Peran Utama |
|---|---|---|---|
| **Divisi 1** | Michelle Aurellia | 24.N1.0021 | Project Manager & Analis Sistem |
| **Divisi 2** | Kirana Ayu Dwi Putri | 24.N1.0028 | Database Administrator & Backend Developer |
| **Divisi 3** | Grace Febriani S. P | 24.N1.0010 | UI/UX Designer, Frontend Developer & QA Tester (PIC D3) |

---

## BAB II HASIL AKTIVITAS D3-02

### 2.1 Style Guide & Design System InvenKopi

#### 2.1.1 Palet Warna Baku (Color Tokens)

| Nama Token Warna | Kode HEX | Peran dan Penggunaan Antarmuka |
|---|---|---|
| `--sidebar-bg` | `#1C3746` | Warna utama sidebar navigasi, latar header logo, tombol aksi primer |
| `--sidebar-text-muted` | `#8FAAB7` | Teks menu navigasi tidak aktif, label sekunder di sidebar |
| `--sidebar-active-bg` | `#F4F8FA` | Latar belakang kanvas utama dan tab menu yang sedang aktif |
| `--card-bg` | `#FFFFFF` | Latar belakang kartu metrik, panel formulir, dan tabel data |
| `--text-dark` | `#142730` | Warna tipografi judul, angka metrik utama, nilai tabel |
| `--text-muted` | `#647D8C` | Keterangan unit satuan, deskripsi modul, waktu transaksi |
| `--border-light` | `#E1EBF0` | Garis pembatas kartu, pemisah baris tabel, border input |
| `--accent-green-bg` | `#E6F6EE` | Latar badge status stok aman, metrik penerimaan barang |
| `--accent-green-text` | `#1B8A5A` | Teks status stok aman, ikon metrik penerimaan barang |
| `--accent-coral-bg` | `#FDEDEA` | Latar badge peringatan stok menipis (*low stock*), limbah (*waste*) |
| `--accent-coral-border`| `#F7CAC3` | Garis tepi kartu peringatan darurat stok kritis |
| `--accent-coral-text` | `#C0392B` | Teks peringatan stok menipis, nilai variansi negatif berlebih |

#### 2.1.2 Tipografi dan Skala Hierarki Teks
Menggunakan rumpun huruf **Plus Jakarta Sans** yang memiliki keterbacaan (*legibility*) tinggi pada layar gawai digital:

| Tingkatan Teks | Ukuran / Bobot | Kegunaan |
|---|---|---|
| **Display Title** | 24px / Bold 800 | Judul utama layar (*Dashboard Overview*, *Stock Opname*) |
| **Section Header** | 18px / Bold 700 | Judul kartu panel (*Master Data Bahan*, *Laporan Variance*) |
| **Metric Large** | 22px / Bold 800 | Angka kuantitas pada kartu ringkasan (*14*, *4.850*) |
| **Body Regular** | 13px / Regular 400 | Teks isi tabel, catatan alasan transaksi, notifikasi |
| **Body Semibold** | 13px / Semibold 600 | Kode SKU, nama menu, nama kolom tabel (*table header*) |
| **Badge / Caption** | 11px–12px / Bold 700 | Tag kategori (`DRY`, `FRS`, `PKG`), unit satuan (`G`, `ML`, `PC`) |

#### 2.1.3 Spacing, Grid System, dan Radius
- **Base Spacing Unit:** 4px / 8px / 12px / 16px / 20px / 24px.
- **Card Border Radius:** 16px untuk kartu kecil, 18px untuk panel konten utama, 24px untuk tab tombol aktif.
- **Elevation / Shadow:** `0 2px 6px rgba(25, 52, 65, 0.03)` untuk kartu normal; `0 6px 16px rgba(25, 52, 65, 0.06)` untuk kartu yang sedang di-*hover*.

---

### 2.2 Desain Komponen UI Reusable

1. **Sidebar Navigasi dengan Curved Active Tab:**
   Menggunakan teknik *pseudo-element* CSS (`::before` dan `::after`) dengan efek lengkungan kurva 20px ke luar, menciptakan ilusi optik bahwa tab yang sedang aktif menyatu secara alami ke dalam kanvas putih konten.
2. **Kartu OverView Peringatan (Alert Metric Card):**
   Memiliki *border* ganda berwarna merah muda lembut (`#F7CAC3`) dengan indikator lingkaran tanda seru di pojok kanan bawah, memberikan pembeda visual seketika terhadap item yang membutuhkan perhatian darurat.
3. **Ergonomic Numpad Input Field (Form PI):**
   Input angka dengan ukuran tinggi 36px, teks rata kanan, dan batas fokus biru tua (`#2C6E8A`). Sangat nyaman ditekan menggunakan keyboard numerik ponsel/tablet.
4. **Calculated Value Pill:**
   Badge hijau tua (`#1C6444`) berlatar mint lembut (`#EDF7F1`) yang menampilkan total akumulasi eceran secara instan saat data kemasan dimasukkan.

---

### 2.3 Spesifikasi Layar High-Fidelity Berdasarkan Data Riil

#### 2.3.1 Layar Dashboard Overview (Modul M12)
Menggunakan integrasi data riil coffeeshop OMORA dari periode uji 7 hari:
- **4 Metrik Utama:**
  - Total Bahan: **14 SKU** (Biji Kopi, Susu, Sirup, Gula, Cup, Lid, Sedotan, Roti, Mentega, dll).
  - Transaksi Penerimaan: **28 Transaksi** (Total barang masuk dari supplier).
  - Total Stok Ecer: **4.850 Unit Ecer** (Akumulasi stok aman).
  - Stok Kritis: **2 Item Menipis** (Sirup Vanila & Susu UHT mendekati batas minimum).
- **Donut Chart Nilai Stok:**
  - 32% Bahan Terpakai (Usage periode berjalan).
  - 68% Sisa Stok Tersedia di Outlet.
- **Top 5 Usage Bahan:**
  - Susu UHT Full Cream (`FRS001`): 74.600 ML
  - Biji Kopi House Blend (`DRY001`): 11.520 G
  - Gula Aren Cair (`DRY004`): 5.000 ML
  - Bubuk Cokelat (`DRY002`): 2.400 G
  - Sirup Caramel (`DRY003`): 1.800 ML

#### 2.3.2 Layar Form Stock Opname / PI (Modul M09)
Menampilkan daftar bahan baku yang siap dihitung saat pergantian shift:

| SKU | Nama Material | Tipe | Kemasan Utuh | Isi / Utuh | Sisa Utuh | Sisa Ecer | Total Fisik (Ecer) |
|---|---|---|---|---|---|---|---|
| `DRY001` | Biji Kopi House Blend | DRY | Pouch | 1.000 G | **2** | **350** | **2.350 G** |
| `DRY002` | Bubuk Cokelat | DRY | Pack | 500 G | **2** | **80** | **1.080 G** |
| `DRY003` | Sirup Caramel | DRY | Botol | 700 ML | **1** | **0** | **700 ML** |
| `DRY004` | Gula Aren Cair | DRY | Botol | 1.000 ML | **0** | **700** | **700 ML** |
| `FRS001` | Susu UHT Full Cream | FRS | Karton | 1.000 ML | **4** | **200** | **4.200 ML** |
| `FRS002` | Roti Tawar | FRS | Pack | 10 PC | **1** | **6** | **16 PC** |
| `FRS003` | Mentega | FRS | Pack | 200 G | **2** | **190** | **590 G** |
| `PKG001` | Cup Plastik 16 Oz | PKG | Slop | 50 PC | **3** | **40** | **190 PC** |
| `PKG002` | Tutup Cup Dome | PKG | Slop | 50 PC | **1** | **46** | **96 PC** |
| `PKG003` | Sedotan | PKG | Pack | 100 PC | **2** | **15** | **215 PC** |

#### 2.3.3 Layar Laporan Variance (Modul M10 & M11)
Menyajikan hasil perbandingan matematis antara standar stok sistem terhadap hasil perhitungan fisik barista:

| SKU | Material Desc. | UoM | Begin | Receive | Usage | Std. End | PI Count | Var. | % Var. | Status Klasifikasi | DOI |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `DRY001` | Biji Kopi House Blend | G | 6.000 | 8.000 | 11.520 | 2.440 | 2.350 | -90 | -3,69% | **Low Var.** | 1,4 hari |
| `DRY002` | Bubuk Cokelat | G | 1.500 | 2.000 | 2.400 | 1.100 | 1.080 | -20 | -1,82% | **Low Var.** | 3,2 hari |
| `DRY003` | Sirup Caramel | ML | 1.000 | 1.500 | 1.800 | 700 | 700 | 0 | 0,00% | **No Var.** | 2,7 hari |
| `DRY004` | Gula Aren Cair | ML | 2.000 | 4.000 | 5.000 | 900 | 700 | -200 | -22,22% | **High Var. ⚠️** | 1,0 hari |
| `FRS001` | Susu UHT Full Cream | ML | 20.000 | 60.000 | 74.600 | 4.800 | 4.200 | -600 | -12,50% | **Medium Var.** | 0,4 hari |
| `PKG001` | Cup Plastik 16 Oz | PC | 300 | 500 | 700 | 195 | 190 | -5 | -2,56% | **Low Var.** | 1,9 hari |

---

### 2.4 Responsivitas Multi-Device

1. **Layar Desktop Outlet (Resolusi $\ge$ 1024px):**
   - Menggunakan tata letak dua kolom: Sidebar tetap di sisi kiri selebar 260px dan kanvas dinamis di sebelah kanan.
   - Tabel menampilkan seluruh kolom data secara lengkap tanpa pemotongan teks.
2. **Layar Tablet Barista (Resolusi 768px – 1023px):**
   - Sidebar bertransformasi menjadi bilah horizontal kompak di bagian atas layar.
   - Kolom tabel Form PI mempertahankan lebar kolom input minimum 80px agar mudah dijangkau jari barista saat melakukan pengecekan di rak/chiller.
3. **Layar Ponsel Pintar (Resolusi $\le$ 640px):**
   - Kolom pencarian di navbar disembunyikan untuk memberi ruang pada judul dan notifikasi.
   - Kartu metrik bersusun 1 kolom (*single column vertical stack*).
   - Tabel mengaktifkan fungsi geser horizontal (*horizontal touch scroll*) yang mulus.

---

### 2.5 Matriks RACI dan Mitigasi Risiko
- **Grace Febriani S. P (Divisi 3):** **A/R** — Bertanggung jawab penuh menyusun style guide, komponen desain token, dan prototipe hi-fi.
- **Michelle Aurellia (Divisi 1):** **C** — Memverifikasi kesesuaian formula variance dan satuan kemasan dengan spesifikasi charter.
- **Kirana Ayu Dwi Putri (Divisi 2):** **C** — Memastikan nama properti field pada desain sesuai dengan skema database SQL (`sku`, `sisa_utuh`, `sisa_ecer`, `total_ecer`).

---

## BAB III PENUTUP

### 3.1 Tindak Lanjut Menuju Aktivitas D3-03 dan Milestone D1-05
Hasil desain *High-Fidelity* dan *Style Guide* pada laporan D3-02 ini siap dipresentasikan pada agenda **Milestone Review Desain Sistem (D1-05 & D2-04)** bersama perwakilan manajemen/pemilik OMORA Coffeeshop. Masukan yang diperoleh dari sesi peninjauan tersebut akan disempurnakan pada deliverable **D3-03 (Penyempurnaan Desain Final v1.0)**.

### 3.2 Penutup
Laporan aktivitas D3-02 telah selesai disusun secara komprehensif sebagai acuan baku visual dan dokumentasi teknis antarmuka sistem informasi inventaris InvenKopi OMORA.

