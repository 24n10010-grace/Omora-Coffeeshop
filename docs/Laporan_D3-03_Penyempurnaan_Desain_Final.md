# LAPORAN AKTIVITAS D3-03
# PENYEMPURNAAN DESAIN PASCA REVIEW STAKEHOLDER (MILESTONE M3)
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
  - 1.2 Latar Belakang Milestone M3
  - 1.3 Tujuan Laporan D3-03
  - 1.4 Tim Proyek dan Pembagian Peran
- **BAB II HASIL AKTIVITAS D3-03**
  - 2.1 Catatan Evaluasi dan Masukan Review Stakeholder (D1-05)
  - 2.2 Analisis Permasalahan Alur Kerja Barista Saat Jam Sibuk
  - 2.3 Solusi Penyempurnaan Desain Antarmuka Final v1.0
    - 2.3.1 Quick Category Filter Chip (Reduksi Panjang Form)
    - 2.3.2 Real-time Instant Auto-Calculation (Eliminasi Hitung Manual)
    - 2.3.3 Mekanisme Simpan Draft Sementara (Partial Save saat Antrean Datang)
    - 2.3.4 Optimasi Layout Numpad Touch Target untuk Layar Tablet
  - 2.4 Matriks Perbandingan Desain (Sebelum vs Sesudah Revisi)
  - 2.5 Verifikasi Implementasi pada Repositori Frontend Laravel
  - 2.6 Matriks RACI dan Status Penutupan Risiko R07
- **BAB III PENUTUP**
  - 3.1 Tindak Lanjut Menuju Fase Pengembangan Frontend (D3-04 s.d. D3-06)
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
| **Milestone Acuan** | **Milestone M3: Desain sistem disetujui stakeholder OMORA (20 Nov 2026)** |
| **Jadwal Aktivitas** | Jumat, 20 November 2026 (Durasi: 1 hari kalender) |

### 1.2 Latar Belakang Milestone M3
Pada tanggal 20 November 2026, tim proyek melaksanakan evaluasi milestone desain sistem (M3) bersama perwakilan pemilik dan barista OMORA Coffeeshop (terdokumentasi dalam *Laporan Aktivitas D1-05: Berita Acara Persetujuan Desain*). Secara umum, stakeholder menyetujui arah visual antarmuka bertema *Dark Slate Teal* yang bersih dan modern.

Namun, terdapat satu catatan evaluasi kritis dari barista terkait operasional riil di bar: **proses pengisian form perhitungan stok fisik (Form PI / Modul M09) dirasa terlalu panjang dan memakan waktu**, terutama apabila proses opname berbenturan dengan jam sibuk pesanan pelanggan (*rush hour*). Sebagai penanggung jawab divisi UI/UX dan Frontend, Grace Febriani S. P (Divisi 3) melakukan penyempurnaan desain final (D3-03) untuk menyelesaikan kendala tersebut sebelum memasuki tahap koding intensif.

### 1.3 Tujuan Laporan D3-03
1. Mendokumentasikan tindak lanjut atas masukan stakeholder pada dokumen Berita Acara D1-05.
2. Memaparkan solusi perbaikan desain antarmuka Form PI v1.0 yang ergonomis dan cepat.
3. Menyatakan kesiapan desain final v1.0 untuk diimplementasikan ke dalam kode antarmuka Laravel Blade (D3-04 hingga D3-06).

### 1.4 Tim Proyek dan Pembagian Peran

| Divisi | Nama Anggota | NIM | Peran Utama |
|---|---|---|---|
| **Divisi 1** | Michelle Aurellia | 24.N1.0021 | Project Manager & Analis Sistem |
| **Divisi 2** | Kirana Ayu Dwi Putri | 24.N1.0028 | Database Administrator & Backend Developer |
| **Divisi 3** | Grace Febriani S. P | 24.N1.0010 | UI/UX Designer, Frontend Developer & QA Tester (PIC D3) |

---

## BAB II HASIL AKTIVITAS D3-03

### 2.1 Catatan Evaluasi dan Masukan Review Stakeholder (D1-05)
Kutipan langsung dari dokumen Berita Acara Persetujuan Desain (D1-05):
> *"Desain visual dashboard sangat disukai karena bersih dan jelas. Namun, untuk Form Stock Opname (PI), daftarnya terlalu panjang jika barista harus scroll puluhan item ke bawah saat jam ramai. Selain itu, barista masih harus menghitung manual jumlah karton dikali isi karton sebelum mengetik angka total ecer."*

### 2.2 Analisis Permasalahan Alur Kerja Barista Saat Jam Sibuk
1. **Beban Kognitif Berlebih:** Mengalikan angka (misalnya: 8 karton susu $\times$ 1.000 ml $+$ 450 ml di teko) menggunakan kalkulator terpisah memakan waktu rata-rata 30–45 detik per item dan rentan salah ketik (*human error*).
2. **Disorientasi Lokasi Barang:** Menyatukan bahan dingin di kulkas (*chiller*) dengan bahan kering di gudang dalam satu daftar panjang membuat barista bolak-balik memeriksa layar.
3. **Risiko Kehilangan Data saat Interupsi:** Jika tiba-tiba ada transaksi penjualan masuk saat opname berlangsung, form yang belum tersubmit berisiko tertutup dan barista harus mengulang dari awal.

---

### 2.3 Solusi Penyempurnaan Desain Antarmuka Final v1.0

Divisi 3 menerapkan 4 penyempurnaan fitur interaksi utama pada antarmuka Form PI:

#### 2.3.1 Quick Category Filter Chip (Reduksi Panjang Form)
- Disediakan filter tombol cepat di bagian atas form:
  - `Semua Bahan` (Default)
  - `Bahan Kering (DRY)` $\rightarrow$ Biji Kopi House Blend, Bubuk Cokelat, Sirup Caramel, Gula Aren Cair.
  - `Bahan Segar (FRS)` $\rightarrow$ Susu UHT Full Cream, Roti Tawar, Mentega.
  - `Packaging (PKG)` $\rightarrow$ Cup Plastik 16 Oz, Tutup Cup Dome, Sedotan.
- **Hasil:** Barista yang sedang berada di depan chiller cukup mengklik tombol `Bahan Segar (FRS)` sehingga tabel hanya memunculkan 3 baris item yang relevan, tanpa perlu menggulir puluhan baris lain.

#### 2.3.2 Real-time Instant Auto-Calculation (Eliminasi Hitung Manual)
- Kolom perhitungan dipecah menjadi dua field angka yang sangat intuitif:
  1. `Sisa Kemasan (Utuh)`: Contoh input `4` karton.
  2. `Sisa Ecer`: Contoh input `200` ml.
- Sistem menggunakan rumus JavaScript *client-side*:
  $$\text{Total Ecer} = (\text{Input Utuh} \times \text{Isi Pack Std}) + \text{Input Ecer}$$
- Nilai total (`4.200 ml`) langsung muncul secara seketika (*real-time*) dalam badge hijau tebal tanpa perlu menekan tombol 'Hitung'.

#### 2.3.3 Mekanisme Simpan Draft Sementara (Partial Save)
- Ditambahkan tombol sekunder **"Simpan Draft Sementara"** berdampingan dengan tombol utama **"Submit Hasil Stock Opname"**.
- Barista dapat menyimpan hasil hitung 5 item pertama, melayani pesanan kopi pelanggan yang baru datang, lalu kembali membuka form dengan data yang tetap tersimpan aman.

#### 2.3.4 Optimasi Layout Numpad Touch Target untuk Layar Tablet
- Input angka diberi padding sentuh yang lega (tinggi 38px, font 14px bold) dan otomatis memunculkan papan ketik numerik (*virtual numpad*) saat dibuka di browser tablet barista.

---

### 2.4 Matriks Perbandingan Desain (Sebelum vs Sesudah Revisi)

| Parameter Desain | Desain Awal (D3-02) | Desain Final Pasca Revisi (D3-03) |
|---|---|---|
| **Penyajian Daftar Item** | 1 tabel tunggal panjang memuat seluruh SKU | Dilengkapi **Quick Filter Chip** per kelompok lokasi penyimpanan |
| **Metode Perhitungan Total**| Barista menghitung manual di kalkulator | **Auto-Calculated Realtime** saat sisa utuh/ecer diketik |
| **Interupsi Jam Sibuk** | Data hilang jika form ditutup sebelum submit | Tersedia fitur **Simpan Draft Sementara** |
| **Responsivitas Header** | Frame mockup melayang dengan header statis | **Full-Screen Web Dashboard** standar industri SaaS |
| **Waktu Pengerjaan per SKU**| Rata-rata 35 detik | **Di bawah 8 detik per SKU** |

---

### 2.5 Verifikasi Implementasi pada Repositori Frontend Laravel
Seluruh penyempurnaan desain D3-03 telah dikodekan langsung pada berkas:
- **View Template:** `resources/views/welcome.blade.php`
- **Routing:** `routes/web.php`
- **Logic Script:** Fungsi JavaScript `calculateRow()` dan `filterPiTable()` terpasang langsung di sisi klien, memastikan performa instan tanpa beban jeda server.
- **Server Uji Lokal:** Dapat diakses dan diuji secara langsung melalui alamat `http://127.0.0.1:8000`.

---

### 2.6 Matriks RACI dan Status Penutupan Risiko R07
- **Grace Febriani S. P (Divisi 3):** **A/R** — Mengimplementasikan seluruh revisi desain antarmuka dan memvalidasi ergonomi form.
- **Michelle Aurellia (Divisi 1):** **C** — Memvalidasi bahwa revisi D3-03 telah memenuhi seluruh butir catatan Berita Acara D1-05.
- **Kirana Ayu Dwi Putri (Divisi 2):** **C** — Mengonfirmasi bahwa pemisahan field `sisa_utuh`, `sisa_ecer`, dan `total_ecer` selaras dengan skema tabel `t_stock_opname`.

**Status Mitigasi Risiko R07:**
- *Risiko:* Barista kesulitan beradaptasi dengan form stock opname (Skor 9).
- *Status:* **Terkendali / Terselesaikan (Closed)** melalui penyederhanaan alur form dan otomatisasi hitung pada D3-03.

---

## BAB III PENUTUP

### 3.1 Tindak Lanjut Menuju Fase Pengembangan Frontend
Dengan disahkannya laporan D3-03 ini, tahapan perancangan desain antarmuka sistem InvenKopi OMORA dinyatakan **selesai 100% dan disetujui (Milestone M3 Tercapai)**. Divisi 3 siap melanjutkan pekerjaan ke tahap implementasi kode frontend penuh:
1. **D3-04:** Pengembangan Frontend Autentikasi dan Master Data M01–M04 (21–23 November).
2. **D3-05:** Pengembangan Frontend Transaksi Stok M05–M08 (24–26 November).
3. **D3-06:** Pengembangan Frontend Form PI, Variance, dan Dashboard M09–M12 (27–29 November).

### 3.2 Penutup
Laporan aktivitas D3-03 telah selesai disusun sebagai dokumentasi resmi penyempurnaan desain akhir antarmuka pengguna sistem informasi inventaris InvenKopi OMORA.

