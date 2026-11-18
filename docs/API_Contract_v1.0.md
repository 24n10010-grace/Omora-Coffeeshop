# Kontrak API Awal — InvenKopi v1

Base path: `/api/v1`. Request/response menggunakan JSON UTF-8. Resource menggunakan kata benda jamak. Endpoint bisnis yang memerlukan identitas pengguna dilindungi `auth:sanctum`.

| Metode | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/v1/ping` | Health check tanpa autentikasi |
| POST | `/api/v1/auth/login` | Login dan memperoleh token |
| POST | `/api/v1/auth/logout` | Mengakhiri token aktif |
| GET | `/api/v1/auth/me` | Profil pengguna aktif |
| GET, POST | `/api/v1/items` | Daftar/tambah master item |
| GET, PUT, DELETE | `/api/v1/items/{sku}` | Detail/ubah/nonaktifkan item |
| GET, POST | `/api/v1/menus` | Daftar/tambah master menu |
| GET, PUT | `/api/v1/menus/{kode_menu}/bom` | Lihat/ubah resep menu |
| GET, POST | `/api/v1/sales` | Daftar/input penjualan |
| GET | `/api/v1/usage?from=&to=&outlet_id=` | Ringkasan usage per SKU |
| GET, POST | `/api/v1/receipts` | Daftar/input penerimaan |
| GET, POST | `/api/v1/transfers` | Daftar/input transfer |
| GET, POST | `/api/v1/wastes` | Daftar/input waste |
| GET, POST | `/api/v1/stock-opnames` | Daftar/input physical inventory |
| GET | `/api/v1/variance?from=&to=&outlet_id=` | Laporan variance |
| GET | `/api/v1/inventory/alerts` | Peringatan stok minimum |
| GET | `/api/v1/inventory/reorder-suggestions` | Rekomendasi order sederhana |
| GET | `/api/v1/dashboard/summary` | Ringkasan dashboard sederhana |

## Bentuk respons

Sukses:

```json
{
  "data": { "id": 1, "nama": "contoh" },
  "meta": { "version": "v1", "request_id": "..." }
}
```

Error:

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Data tidak valid.",
    "details": { "sku": ["Field sku wajib diisi."] }
  },
  "meta": { "request_id": "..." }
}
```

HTTP status yang disepakati: 200, 201, 204, 401, 403, 404, 409, 422, dan 500. Jangan mengirim stack trace atau kredensial pada pesan error. Nilai desimal dikirim sebagai angka; tanggal menggunakan `YYYY-MM-DD` atau ISO-8601 untuk timestamp.

## Catatan implementasi

Daftar endpoint ini merupakan kontrak awal agar Divisi 3 dapat memakai mock response. Perubahan kontrak harus diberi versi, dikomunikasikan melalui Pull Request, dan diuji ulang melalui koleksi Postman. Detail input/output setiap endpoint tetap mengacu pada tabel kontrak API di Laporan D2-01.
