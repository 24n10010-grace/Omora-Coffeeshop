# SOP Backup & Restore — InvenKopi OMORA

SOP ini mendukung mitigasi risiko R10 (kehilangan kode/data). GitHub melindungi riwayat kode dan dokumen, sedangkan backup basis data dilakukan terpisah.

## Frekuensi

- Push commit ke GitHub pada akhir setiap sesi kerja.
- Backup database harian pada server uji.
- Backup wajib sebelum setiap migrasi skema dan migrasi data riil.
- Simpan salinan backup pada drive bersama di luar server. Jangan menyimpan satu-satunya backup pada komputer yang sama dengan database.
- Lakukan uji restore pada database kosong; catat hasilnya pada aktivitas D2-09.

## Kredensial MySQL

Buat file `~/.my.cnf` di komputer lokal:

```ini
[client]
user=invenkopi_dev
password=GANTI_DENGAN_PASSWORD_KUAT
host=127.0.0.1
```

Batasi permission:

```bash
chmod 600 ~/.my.cnf
```

Jangan memasukkan kata sandi ke script, command line, commit, atau dokumen yang diunggah.

## Membuat backup

Dari root repositori:

```bash
bash scripts/backup_db.sh
```

File dibuat di `backups/` dengan nama `db_invenkopi_omora_YYYYMMDD_HHMM.sql.gz`. Script menguji integritas gzip dan membatasi permission file.

## Restore

Restore hanya ke database tujuan kosong/khusus pengujian. Pastikan file backup berasal dari sumber tepercaya, lalu jalankan:

```bash
bash scripts/restore_db.sh backups/db_invenkopi_omora_YYYYMMDD_HHMM.sql.gz
```

Script meminta konfirmasi eksplisit `RESTORE` sebelum impor. Buat database kosong terlebih dahulu; script tidak menghapus database yang sudah ada.

## Pengamanan Git

Jalankan `bash scripts/ensure_gitignore.sh` untuk memastikan `.env`, `/backups`, dan `*.sql.gz` dikecualikan dari Git. Jangan pernah commit dump berisi data operasional. Simpan salinan di drive bersama dengan akses terbatas.
