# API Design & Contract Specification — InvenKopi OMORA v1.0
## Aktivitas D2-03: Implementasi Database, Seed Data Master & Desain API

**Versi:** 1.0  
**Tanggal:** 2026-11-21  
**Disusun oleh:** Kirana Ayu Dwi Putri (Divisi 2 – Database Administrator & Backend Developer)  
**Base URL:** `/api/v1`  
**Format Pertukaran Data:** JSON (UTF-8)

---

## 1. Konvensi Umum API

### 1.1 Format Respons Seragam
Semua respons API menggunakan struktur JSON baku yang seragam untuk mempermudah integrasi frontend (Divisi 3):

#### Respons Sukses:
```json
{
  "success": true,
  "message": "Operasi berhasil dilakukan.",
  "data": { ... },
  "meta": {
    "version": "v1.0",
    "timestamp": "2026-11-21T23:00:00+07:00",
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 100,
      "total_pages": 7
    }
  }
}
```

#### Respons Error / Validasi Gagal:
```json
{
  "success": false,
  "message": "Data yang dikirimkan tidak valid.",
  "errors": {
    "sku": ["Field sku wajib diisi."],
    "pack_standard": ["Field pack_standard harus berupa angka desimal lebih dari 0."]
  },
  "meta": {
    "version": "v1.0",
    "timestamp": "2026-11-21T23:00:00+07:00"
  }
}
```

### 1.2 Kode Status HTTP
- `200 OK` — Permintaan GET, PUT berhasil.
- `201 Created` — Sumber daya baru berhasil dibuat via POST.
- `204 No Content` — Berhasil dihapus (DELETE).
- `400 Bad Request` — Permintaan tidak dapat diproses.
- `401 Unauthorized` — Token belum disertakan atau sudah kedaluwarsa.
- `403 Forbidden` — Peran pengguna tidak memiliki hak akses ke endpoint tersebut.
- `404 Not Found` — Data atau endpoint tidak ditemukan.
- `422 Unprocessable Entity` — Validasi form gagal.
- `500 Internal Server Error` — Kesalahan pada server.

### 1.3 Format Tanggal dan Nilai Angka
- Tanggal transaksi: `YYYY-MM-DD` (contoh: `2026-11-19`).
- Timestamp: ISO-8601 (`YYYY-MM-DDTHH:mm:ss+07:00`).
- Kuantitas dan harga dikirimkan sebagai **Number/Float**, bukan string berkoma.

---

## 2. Autentikasi & Matriks Hak Akses (RBAC)

### 2.1 Mekanisme Autentikasi
Sistem menggunakan **Laravel Sanctum** berbasis Bearer Token.
Setelah login berhasil, klien wajib menyertakan header berikut pada setiap panggilan endpoint terproteksi:
```http
Authorization: Bearer <access_token>
Accept: application/json
```

### 2.2 Matriks Hak Akses Peran

| Modul | Endpoint / Fitur | Owner | Supervisor | Staf (Barista) |
|---|---|:---:|:---:|:---:|
| **M01** | Autentikasi (Login/Logout/Me) | ✅ | ✅ | ✅ |
| **M01** | Kelola Pengguna (`/users`) & Log (`/logs`) | ✅ (CRUD) | ❌ | ❌ |
| **M02** | Master Item/Material (`/items`) | Read-Only | ✅ (CRUD) | Read-Only |
| **M03** | Master Menu & BOM (`/menus`, `/bom`) | Read-Only | ✅ (CRUD) | Read-Only |
| **M04** | Master Supplier & Outlet | Read-Only | ✅ (CRUD) | Read-Only |
| **M05** | Penjualan & Laporan Usage (`/sales`, `/usage`) | ✅ (Read) | ✅ (Read) | ✅ (Input/Read) |
| **M06** | Penerimaan Barang (`/receipts`) | ✅ (Read) | ✅ (Input/CRUD)| ✅ (Input Staf) |
| **M07** | Transfer Stok (`/transfers`) | ✅ (Read) | ✅ (Input/CRUD)| ✅ (Input Staf) |
| **M08** | Pencatatan Waste (`/wastes`) | ✅ (Read) | ✅ (Input/CRUD)| ✅ (Input Staf) |
| **M09** | Form Stock Opname (`/stock-opnames`) | ✅ (Read) | ✅ (Approve)  | ✅ (Input Draft/Submit) |
| **M10** | Laporan Stock Variance (`/variance`) | ✅ (Lihat) | ✅ (Analisis) | ❌ |
| **M11** | Stok Menipis & Reorder (`/inventory/*`) | ✅ (Lihat) | ✅ (Order)    | ❌ |
| **M12** | Dashboard Eksekutif (`/dashboard/*`) | ✅ (Lengkap)| ✅ (Operasional)| ❌ |

---

## 3. Daftar Endpoint Lengkap (M01 – M12)

### M01: Autentikasi, Profil & Log Aktivitas
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| POST | `/api/v1/auth/login` | ROUTE SIAP | Publik | Login & memperoleh bearer token |
| POST | `/api/v1/auth/logout` | ROUTE SIAP | Semua | Mengakhiri token aktif |
| GET | `/api/v1/auth/me` | ROUTE SIAP | Semua | Profil pengguna yang sedang login |
| GET | `/api/v1/users` | STUB | Owner | Daftar semua akun pengguna |
| POST | `/api/v1/users` | STUB | Owner | Tambah akun pengguna baru |
| GET | `/api/v1/logs` | STUB | Owner | Riwayat log aktivitas sistem |

### M02: Master Item / Material
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/items` | ROUTE SIAP | Semua | Daftar master material (filter: tipe, search) |
| POST | `/api/v1/items` | STUB | Supervisor | Tambah material baru |
| GET | `/api/v1/items/{sku}` | ROUTE SIAP | Semua | Detail spesifikasi material berdasarkan SKU |
| PUT | `/api/v1/items/{sku}` | STUB | Supervisor | Ubah spesifikasi material |
| DELETE | `/api/v1/items/{sku}` | STUB | Supervisor | Nonaktifkan material (soft-disable) |

### M03: Master Menu & Resep (BOM)
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/menus` | ROUTE SIAP | Semua | Daftar menu dan harga |
| POST | `/api/v1/menus` | STUB | Supervisor | Tambah menu baru |
| GET | `/api/v1/menus/{kode_menu}/bom` | ROUTE SIAP | Semua | Rincian resep komposisi bahan per porsi |
| PUT | `/api/v1/menus/{kode_menu}/bom` | STUB | Supervisor | Update komposisi resep menu |

### M04: Master Supplier & Outlet
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/outlets` | ROUTE SIAP | Semua | Daftar lokasi outlet/gudang |
| GET | `/api/v1/suppliers` | ROUTE SIAP | Semua | Daftar pemasok bahan baku |
| POST | `/api/v1/suppliers` | STUB | Supervisor | Tambah pemasok baru |

### M05: Penjualan & Perhitungan Usage
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/sales` | ROUTE SIAP | Semua | Riwayat rekap penjualan harian |
| POST | `/api/v1/sales` | STUB | Supervisor, Staf | Input transaksi penjualan shift |
| GET | `/api/v1/usage` | ROUTE SIAP | Semua | Kalkulasi akumulasi usage per SKU periode tertentu |

### M06: Penerimaan Barang (Receive)
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/receipts` | ROUTE SIAP | Semua | Riwayat surat jalan penerimaan barang |
| POST | `/api/v1/receipts` | STUB | Supervisor, Staf | Input penerimaan barang dari supplier |

### M07: Transfer Stok Antar-Outlet
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/transfers` | ROUTE SIAP | Semua | Daftar mutasi transfer bahan |
| POST | `/api/v1/transfers` | STUB | Supervisor, Staf | Input transfer (asal -> tujuan) |

### M08: Pencatatan Waste & Spoilage
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/wastes` | ROUTE SIAP | Semua | Riwayat bahan rusak/tumpah/kedaluwarsa |
| POST | `/api/v1/wastes` | STUB | Supervisor, Staf | Input pencatatan limbah bahan baku |

### M09: Stock Opname (Form Physical Inventory)
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/stock-opnames` | ROUTE SIAP | Semua | Riwayat dokumen stock opname |
| POST | `/api/v1/stock-opnames` | ROUTE SIAP | Supervisor, Staf | Input form PI (auto-calculate total_ecer) |

### M10: Laporan Analisis Variance Stok
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/variance` | ROUTE SIAP | Owner, Supervisor | Laporan perbandingan Std. End vs PI Count |

### M11: Peringatan Stok Minimum, DOI & Rekomendasi Order
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/inventory/alerts` | ROUTE SIAP | Semua | Daftar item yang berada di bawah stok minimum |
| GET | `/api/v1/inventory/reorder-suggestions` | ROUTE SIAP | Supervisor | Rekomendasi pemesanan kemasan utuh |

### M12: Dashboard Eksekutif & Ringkasan Laporan
| Metode | Endpoint | Status | Peran | Deskripsi |
|---|---|:---:|:---:|---|
| GET | `/api/v1/dashboard/summary` | ROUTE SIAP | Semua | Ringkasan 4 kartu metrik, donut chart & top usage |
| GET | `/api/v1/reports/export-pdf` | RANCANGAN | Owner, Supervisor | Export laporan inventaris berkas PDF |
| GET | `/api/v1/reports/export-excel` | RANCANGAN | Owner, Supervisor | Export laporan inventaris berkas XLSX |

---

## 4. Contoh Detail Request & Response Realistis (Mock Frontend)

### 4.1 `GET /api/v1/variance?periode=2026-11&outlet_id=1`
Respons realistis berbasis data seeder 7 hari OMORA:
```json
{
  "success": true,
  "message": "Data analisis variance periode 2026-11 berhasil dihitung.",
  "data": [
    {
      "sku": "DRY001",
      "nama_item": "Biji Kopi House Blend",
      "satuan": "G",
      "begin_stock": 6000.0,
      "receive": 8000.0,
      "trf_in": 0.0,
      "trf_out": 0.0,
      "waste": 40.0,
      "usage": 11520.0,
      "std_end_stock": 2440.0,
      "pi_count": 2350.0,
      "variance": -90.0,
      "persen_variance": -3.69,
      "status": "Low Var",
      "doi_hari": 1.4
    },
    {
      "sku": "DRY004",
      "nama_item": "Gula Aren Cair",
      "satuan": "ML",
      "begin_stock": 2000.0,
      "receive": 4000.0,
      "trf_in": 0.0,
      "trf_out": 0.0,
      "waste": 100.0,
      "usage": 5000.0,
      "std_end_stock": 900.0,
      "pi_count": 700.0,
      "variance": -200.0,
      "persen_variance": -22.22,
      "status": "High Var",
      "doi_hari": 1.0
    },
    {
      "sku": "FRS001",
      "nama_item": "Susu UHT Full Cream",
      "satuan": "ML",
      "begin_stock": 20000.0,
      "receive": 60000.0,
      "trf_in": 0.0,
      "trf_out": 0.0,
      "waste": 600.0,
      "usage": 74600.0,
      "std_end_stock": 4800.0,
      "pi_count": 4200.0,
      "variance": -600.0,
      "persen_variance": -12.5,
      "status": "Medium Var",
      "doi_hari": 0.4
    }
  ]
}
```

### 4.2 `POST /api/v1/stock-opnames`
Request input form hitung fisik dari tablet barista (M09):
```json
{
  "tanggal": "2026-11-19",
  "outlet_id": 1,
  "status": "posted",
  "catatan": "Stock opname akhir shift",
  "items": [
    {
      "sku": "DRY001",
      "sisa_utuh": 2,
      "sisa_ecer": 350
    },
    {
      "sku": "FRS001",
      "sisa_utuh": 4,
      "sisa_ecer": 200
    }
  ]
}
```
Respons balasan dari server (auto-calculate total ecer):
```json
{
  "success": true,
  "message": "Dokumen Stock Opname PI-20261119-001 berhasil disimpan.",
  "data": {
    "no_dokumen": "PI-20261119-001",
    "tanggal": "2026-11-19",
    "status": "posted",
    "total_items": 2,
    "details": [
      {
        "sku": "DRY001",
        "nama_item": "Biji Kopi House Blend",
        "pack_standard": 1000.0,
        "sisa_utuh": 2.0,
        "sisa_ecer": 350.0,
        "total_ecer": 2350.0,
        "satuan": "G"
      },
      {
        "sku": "FRS001",
        "nama_item": "Susu UHT Full Cream",
        "pack_standard": 1000.0,
        "sisa_utuh": 4.0,
        "sisa_ecer": 200.0,
        "total_ecer": 4200.0,
        "satuan": "ML"
      }
    ]
  }
}
```
