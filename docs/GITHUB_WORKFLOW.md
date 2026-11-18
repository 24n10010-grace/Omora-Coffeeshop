# GitHub Branch Protection & Pull Request Workflow

Alur disesuaikan dengan D2-01: `main` untuk versi stabil, `develop` untuk integrasi, dan feature branch untuk pekerjaan per modul.

## Alur kerja

1. `main` menjadi sumber pembuatan `develop`.
2. Buat branch kerja dari `develop`, misalnya `feature/M02-master-item` atau `docs/D2-01-data-mapping`.
3. Commit kecil dan rutin, lalu push branch ke GitHub.
4. Buka Pull Request dengan base `develop`; gunakan `.github/PULL_REQUEST_TEMPLATE.md`.
5. Minimal satu anggota lain mereview dan menyetujui PR sebelum merge. Gunakan squash and merge untuk menjaga riwayat integrasi tetap ringkas.
6. Setelah milestone disetujui, buka PR dari `develop` ke `main`; beri tag rilis sesuai versi proyek.
7. Hapus feature branch setelah PR berhasil digabung.

## Pengaturan proteksi di GitHub

Pada repository, buka **Settings → Branches** (atau Rulesets jika antarmuka akun menampilkannya), lalu buat aturan untuk `main` dan `develop`:

- Wajibkan Pull Request sebelum merge.
- Wajibkan minimal 1 approval dari anggota lain.
- Tolak force-push.
- Cegah penghapusan branch.
- Wajibkan status checks setelah workflow CI tersedia dan sudah stabil.

Untuk `main`, batasi merge ke PR milestone dari `develop`. Untuk `develop`, semua pekerjaan masuk melalui PR dari feature/docs/fix branch. Jangan mengubah default branch tanpa persetujuan tim.

## Catatan akses

Pembuatan branch remote, push, pengaturan proteksi, dan pembuatan PR memerlukan hak tulis/administrasi yang sesuai. Script `scripts/create_develop_branch.sh` hanya membuat atau checkout `develop`; aturan proteksi tetap perlu disetel di GitHub.
