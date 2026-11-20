SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- =============================================================================
-- 1. MASTER DATA
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 1.1  m_outlet — Outlet / Lokasi Operasional
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `m_outlet` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kode_outlet`  VARCHAR(20)  NOT NULL COMMENT 'Kode unik outlet, contoh: OUT001',
    `nama_outlet`  VARCHAR(100) NOT NULL,
    `alamat`       TEXT         NULL,
    `telepon`      VARCHAR(20)  NULL,
    `is_active`    TINYINT(1)   NOT NULL DEFAULT 1 COMMENT '1=aktif, 0=nonaktif',
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_outlet_kode`   (`kode_outlet`),
    KEY            `idx_outlet_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master outlet / lokasi operasional coffeeshop';

-- ---------------------------------------------------------------------------
-- 1.2  m_user — Pengguna Sistem
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `m_user` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `outlet_id`    INT UNSIGNED NULL COMMENT 'Outlet utama pengguna; NULL = lintas outlet (owner)',
    `username`     VARCHAR(50)  NOT NULL,
    `email`        VARCHAR(150) NOT NULL,
    `password`     VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt — JANGAN simpan plaintext',
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `role`         ENUM('owner','supervisor','staff') NOT NULL DEFAULT 'staff',
    `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_user_username` (`username`),
    UNIQUE KEY `uq_user_email`    (`email`),
    KEY `idx_user_outlet`         (`outlet_id`),
    KEY `idx_user_role`           (`role`),
    KEY `idx_user_active`         (`is_active`),
    CONSTRAINT `fk_user_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Akun pengguna sistem: owner, supervisor, staf/barista';

-- ---------------------------------------------------------------------------
-- 1.3  m_supplier — Pemasok / Vendor
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `m_supplier` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kode_supplier`  VARCHAR(20)  NOT NULL COMMENT 'Kode unik, contoh: SUP001',
    `nama_supplier`  VARCHAR(150) NOT NULL,
    `kontak_person`  VARCHAR(100) NULL,
    `telepon`        VARCHAR(20)  NULL,
    `alamat`         TEXT         NULL,
    `is_active`      TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_supplier_kode`   (`kode_supplier`),
    KEY            `idx_supplier_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master pemasok / vendor bahan baku';

-- ---------------------------------------------------------------------------
-- 1.4  m_item — Master Material / Bahan Baku
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `m_item` (
    `id`             INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `sku`            VARCHAR(30)    NOT NULL COMMENT 'Stock Keeping Unit unik, contoh: DRY001',
    `nama_item`      VARCHAR(150)   NOT NULL,
    `tipe_material`  ENUM('DRY','FRS','FRZ','PKG') NOT NULL COMMENT 'DRY=kering,FRS=segar,FRZ=beku,PKG=kemasan',
    `satuan_ecer`    ENUM('G','ML','PC') NOT NULL COMMENT 'Satuan satuan ecer/pakai',
    `satuan_utuh`    ENUM('POC','PAC','BOX','CAR') NOT NULL COMMENT 'Satuan kemasan utuh beli',
    `pack_standard`  DECIMAL(10,4)  NOT NULL COMMENT 'Isi per kemasan utuh dalam satuan ecer',
    `stok_minimum`   DECIMAL(10,4)  NOT NULL DEFAULT 0 COMMENT 'Ambang batas stok minimum dalam satuan ecer',
    `is_active`      TINYINT(1)     NOT NULL DEFAULT 1,
    `created_at`     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_item_sku`         (`sku`),
    KEY            `idx_item_tipe`    (`tipe_material`),
    KEY            `idx_item_active`  (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master bahan baku / material inventaris';

-- ---------------------------------------------------------------------------
-- 1.5  m_menu — Master Menu
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `m_menu` (
    `id`           INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `kode_menu`    VARCHAR(30)    NOT NULL COMMENT 'Kode menu unik, contoh: MNU001',
    `nama_menu`    VARCHAR(150)   NOT NULL,
    `kategori`     VARCHAR(50)    NULL COMMENT 'Contoh: Kopi, Non-Kopi, Makanan',
    `harga`        DECIMAL(12,2)  NOT NULL DEFAULT 0.00,
    `is_active`    TINYINT(1)     NOT NULL DEFAULT 1,
    `created_at`   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_menu_kode`      (`kode_menu`),
    KEY            `idx_menu_kategori` (`kategori`),
    KEY            `idx_menu_active`   (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Master menu minuman dan makanan yang dijual';

-- =============================================================================
-- 2. DATA RESEP
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 2.1  t_bom — Bill of Materials (Resep Menu)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_bom` (
    `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `menu_id`       INT UNSIGNED  NOT NULL,
    `item_id`       INT UNSIGNED  NOT NULL,
    `qty_per_porsi` DECIMAL(10,4) NOT NULL COMMENT 'Kuantitas bahan per 1 porsi menu dalam satuan ecer',
    `satuan`        ENUM('G','ML','PC') NOT NULL COMMENT 'Harus konsisten dengan m_item.satuan_ecer',
    `created_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_bom_menu_item` (`menu_id`, `item_id`),
    KEY `idx_bom_menu` (`menu_id`),
    KEY `idx_bom_item` (`item_id`),
    CONSTRAINT `fk_bom_menu`
        FOREIGN KEY (`menu_id`) REFERENCES `m_menu` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_bom_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Bill of Materials — komposisi bahan per porsi menu';

-- =============================================================================
-- 3. TRANSAKSI PENJUALAN
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 3.1  t_penjualan — Header Penjualan
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_penjualan` (
    `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `no_dokumen`   VARCHAR(30)   NOT NULL COMMENT 'Nomor dokumen unik, contoh: SAL-20261120-001',
    `tanggal`      DATE          NOT NULL,
    `outlet_id`    INT UNSIGNED  NOT NULL,
    `user_id`      INT UNSIGNED  NOT NULL COMMENT 'Petugas yang mencatat',
    `shift`        ENUM('pagi','siang','malam') NOT NULL DEFAULT 'pagi',
    `catatan`      TEXT          NULL,
    `status`       ENUM('draft','posted','void') NOT NULL DEFAULT 'posted',
    `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_penjualan_nodok` (`no_dokumen`),
    KEY `idx_penjualan_tanggal`     (`tanggal`),
    KEY `idx_penjualan_outlet`      (`outlet_id`),
    KEY `idx_penjualan_user`        (`user_id`),
    KEY `idx_penjualan_status`      (`status`),
    CONSTRAINT `fk_penjualan_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_penjualan_user`
        FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Header transaksi penjualan per shift';

-- ---------------------------------------------------------------------------
-- 3.2  t_penjualan_detail — Detail Penjualan
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_penjualan_detail` (
    `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `penjualan_id`   INT UNSIGNED  NOT NULL,
    `menu_id`        INT UNSIGNED  NOT NULL,
    `qty`            DECIMAL(10,4) NOT NULL,
    `harga_satuan`   DECIMAL(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Snapshot harga saat transaksi',
    `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_penjdetail_penjualan` (`penjualan_id`),
    KEY `idx_penjdetail_menu`      (`menu_id`),
    CONSTRAINT `fk_penjdetail_penjualan`
        FOREIGN KEY (`penjualan_id`) REFERENCES `t_penjualan` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_penjdetail_menu`
        FOREIGN KEY (`menu_id`) REFERENCES `m_menu` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Detail menu yang terjual per transaksi penjualan';

-- =============================================================================
-- 4. TRANSAKSI PENERIMAAN BARANG
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 4.1  t_penerimaan — Header Penerimaan
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_penerimaan` (
    `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `no_dokumen`   VARCHAR(30)   NOT NULL COMMENT 'Nomor surat jalan / faktur, contoh: RCV-20261120-001',
    `tanggal`      DATE          NOT NULL,
    `outlet_id`    INT UNSIGNED  NOT NULL COMMENT 'Outlet tujuan penerimaan',
    `supplier_id`  INT UNSIGNED  NOT NULL,
    `user_id`      INT UNSIGNED  NOT NULL COMMENT 'Petugas penerima',
    `catatan`      TEXT          NULL,
    `status`       ENUM('draft','posted','void') NOT NULL DEFAULT 'posted',
    `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_penerimaan_nodok` (`no_dokumen`),
    KEY `idx_penerimaan_tanggal`     (`tanggal`),
    KEY `idx_penerimaan_outlet`      (`outlet_id`),
    KEY `idx_penerimaan_supplier`    (`supplier_id`),
    KEY `idx_penerimaan_user`        (`user_id`),
    KEY `idx_penerimaan_status`      (`status`),
    CONSTRAINT `fk_penerimaan_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_penerimaan_supplier`
        FOREIGN KEY (`supplier_id`) REFERENCES `m_supplier` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_penerimaan_user`
        FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Header penerimaan barang dari supplier';

-- ---------------------------------------------------------------------------
-- 4.2  t_penerimaan_detail — Detail Penerimaan
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_penerimaan_detail` (
    `id`              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `penerimaan_id`   INT UNSIGNED  NOT NULL,
    `item_id`         INT UNSIGNED  NOT NULL,
    `qty_utuh`        DECIMAL(10,4) NOT NULL DEFAULT 0 COMMENT 'Jumlah kemasan utuh yang diterima',
    `qty_ecer`        DECIMAL(10,4) NOT NULL DEFAULT 0 COMMENT 'Sisa satuan ecer di luar kemasan utuh',
    `total_ecer`      DECIMAL(12,4) NOT NULL COMMENT '(qty_utuh x pack_standard) + qty_ecer',
    `created_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_trndetail_penerimaan` (`penerimaan_id`),
    KEY `idx_trndetail_item`       (`item_id`),
    CONSTRAINT `fk_trndetail_penerimaan`
        FOREIGN KEY (`penerimaan_id`) REFERENCES `t_penerimaan` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_trndetail_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Detail baris material yang diterima per dokumen penerimaan';

-- =============================================================================
-- 5. TRANSAKSI TRANSFER STOK
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 5.1  t_transfer — Header Transfer
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_transfer` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `no_dokumen`       VARCHAR(30)  NOT NULL COMMENT 'Nomor dokumen transfer, contoh: TRF-20261120-001',
    `tanggal`          DATE         NOT NULL,
    `outlet_asal_id`   INT UNSIGNED NOT NULL,
    `outlet_tujuan_id` INT UNSIGNED NOT NULL,
    `user_id`          INT UNSIGNED NOT NULL,
    `catatan`          TEXT         NULL,
    `status`           ENUM('draft','posted','void') NOT NULL DEFAULT 'posted',
    `created_at`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_transfer_nodok`      (`no_dokumen`),
    KEY `idx_transfer_tanggal`          (`tanggal`),
    KEY `idx_transfer_outlet_asal`      (`outlet_asal_id`),
    KEY `idx_transfer_outlet_tujuan`    (`outlet_tujuan_id`),
    KEY `idx_transfer_user`             (`user_id`),
    KEY `idx_transfer_status`           (`status`),
    CONSTRAINT `fk_transfer_outlet_asal`
        FOREIGN KEY (`outlet_asal_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_transfer_outlet_tujuan`
        FOREIGN KEY (`outlet_tujuan_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_transfer_user`
        FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Header transfer stok antar outlet / station';

-- ---------------------------------------------------------------------------
-- 5.2  t_transfer_detail — Detail Transfer
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_transfer_detail` (
    `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `transfer_id`  INT UNSIGNED  NOT NULL,
    `item_id`      INT UNSIGNED  NOT NULL,
    `qty_ecer`     DECIMAL(12,4) NOT NULL COMMENT 'Kuantitas yang dipindahkan dalam satuan ecer',
    `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_trfdetail_transfer` (`transfer_id`),
    KEY `idx_trfdetail_item`     (`item_id`),
    CONSTRAINT `fk_trfdetail_transfer`
        FOREIGN KEY (`transfer_id`) REFERENCES `t_transfer` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_trfdetail_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Detail baris material yang dipindahkan per dokumen transfer';

-- =============================================================================
-- 6. WASTE & SPOILAGE
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 6.1  t_waste — Pencatatan Pembuangan Material
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_waste` (
    `id`           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `no_dokumen`   VARCHAR(30)   NOT NULL COMMENT 'Nomor dokumen waste, contoh: WST-20261120-001',
    `tanggal`      DATE          NOT NULL,
    `outlet_id`    INT UNSIGNED  NOT NULL,
    `item_id`      INT UNSIGNED  NOT NULL,
    `user_id`      INT UNSIGNED  NOT NULL,
    `qty_ecer`     DECIMAL(12,4) NOT NULL COMMENT 'Kuantitas terbuang dalam satuan ecer',
    `alasan`       VARCHAR(100)  NOT NULL COMMENT 'Contoh: expired, spilled, damaged, over-production',
    `catatan`      TEXT          NULL,
    `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_waste_nodok`     (`no_dokumen`),
    KEY `idx_waste_tanggal`         (`tanggal`),
    KEY `idx_waste_outlet`          (`outlet_id`),
    KEY `idx_waste_item`            (`item_id`),
    KEY `idx_waste_user`            (`user_id`),
    CONSTRAINT `fk_waste_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_waste_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_waste_user`
        FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Pencatatan waste / pembuangan material per kejadian';

-- =============================================================================
-- 7. STOCK OPNAME (PHYSICAL INVENTORY)
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 7.1  t_stock_opname — Header Stock Opname
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_stock_opname` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `no_dokumen`   VARCHAR(30)  NOT NULL COMMENT 'Nomor dokumen PI, contoh: PI-20261120-001',
    `tanggal`      DATE         NOT NULL,
    `outlet_id`    INT UNSIGNED NOT NULL,
    `user_id`      INT UNSIGNED NOT NULL COMMENT 'Petugas penghitung',
    `status`       ENUM('draft','posted','void') NOT NULL DEFAULT 'draft'
                   COMMENT 'draft=belum selesai/simpan sementara, posted=submitted, void=dibatalkan',
    `catatan`      TEXT         NULL,
    `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_opname_nodok`    (`no_dokumen`),
    KEY `idx_opname_tanggal`        (`tanggal`),
    KEY `idx_opname_outlet`         (`outlet_id`),
    KEY `idx_opname_user`           (`user_id`),
    KEY `idx_opname_status`         (`status`),
    CONSTRAINT `fk_opname_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_opname_user`
        FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Header stock opname / physical inventory per tanggal per outlet';

-- ---------------------------------------------------------------------------
-- 7.2  t_stock_opname_detail — Detail Stock Opname
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_stock_opname_detail` (
    `id`               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `stock_opname_id`  INT UNSIGNED  NOT NULL,
    `item_id`          INT UNSIGNED  NOT NULL,
    `sisa_utuh`        DECIMAL(10,4) NOT NULL DEFAULT 0 COMMENT 'Sisa kemasan utuh yang dihitung fisik',
    `sisa_ecer`        DECIMAL(10,4) NOT NULL DEFAULT 0 COMMENT 'Sisa satuan ecer di luar kemasan utuh',
    `total_ecer`       DECIMAL(12,4) NOT NULL
                       COMMENT '(sisa_utuh x m_item.pack_standard) + sisa_ecer — dihitung saat input',
    `created_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_opname_detail_item` (`stock_opname_id`, `item_id`),
    KEY `idx_opndetail_opname`         (`stock_opname_id`),
    KEY `idx_opndetail_item`           (`item_id`),
    CONSTRAINT `fk_opndetail_opname`
        FOREIGN KEY (`stock_opname_id`) REFERENCES `t_stock_opname` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_opndetail_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Detail hasil hitung fisik per material per dokumen stock opname';

-- =============================================================================
-- 8. KARTU STOK & STOK AWAL
-- =============================================================================

-- ---------------------------------------------------------------------------
-- 8.1  t_stock_ledger — Kartu Stok / Buku Besar Mutasi
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `t_stock_ledger` (
    `id`               INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `outlet_id`        INT UNSIGNED   NOT NULL,
    `item_id`          INT UNSIGNED   NOT NULL,
    `tanggal`          DATE           NOT NULL,
    `jenis_mutasi`     ENUM(
                           'begin_stock',
                           'receive',
                           'trf_in',
                           'trf_out',
                           'waste',
                           'usage',
                           'opname_adj'
                       ) NOT NULL COMMENT 'Jenis mutasi stok',
    `referensi_tipe`   VARCHAR(50)    NOT NULL COMMENT 'Contoh: t_penerimaan, t_transfer, t_waste',
    `referensi_id`     INT UNSIGNED   NOT NULL COMMENT 'ID baris di tabel referensi',
    `qty_masuk`        DECIMAL(14,4)  NOT NULL DEFAULT 0 COMMENT 'Kuantitas masuk (ecer)',
    `qty_keluar`       DECIMAL(14,4)  NOT NULL DEFAULT 0 COMMENT 'Kuantitas keluar (ecer)',
    `saldo_ecer`       DECIMAL(14,4)  NOT NULL COMMENT 'Saldo stok setelah mutasi ini (ecer)',
    `created_at`       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_ledger_outlet_item_tanggal` (`outlet_id`, `item_id`, `tanggal`),
    KEY `idx_ledger_item`               (`item_id`),
    KEY `idx_ledger_tanggal`            (`tanggal`),
    KEY `idx_ledger_mutasi`             (`jenis_mutasi`),
    KEY `idx_ledger_ref`                (`referensi_tipe`, `referensi_id`),
    CONSTRAINT `fk_ledger_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_ledger_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Kartu stok / buku besar semua mutasi stok per item per outlet';

-- ---------------------------------------------------------------------------
-- 8.2  r_begin_stock — Stok Awal per Periode
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `r_begin_stock` (
    `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `outlet_id`  INT UNSIGNED  NOT NULL,
    `item_id`    INT UNSIGNED  NOT NULL,
    `periode`    CHAR(7)       NOT NULL COMMENT 'Format YYYY-MM, contoh: 2026-11',
    `qty_ecer`   DECIMAL(14,4) NOT NULL DEFAULT 0 COMMENT 'Stok awal dalam satuan ecer',
    `user_id`    INT UNSIGNED  NOT NULL COMMENT 'Petugas yang menginput',
    `created_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_begin_stock` (`outlet_id`, `item_id`, `periode`),
    KEY `idx_begin_outlet`      (`outlet_id`),
    KEY `idx_begin_item`        (`item_id`),
    KEY `idx_begin_periode`     (`periode`),
    CONSTRAINT `fk_begin_outlet`
        FOREIGN KEY (`outlet_id`) REFERENCES `m_outlet` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_begin_item`
        FOREIGN KEY (`item_id`) REFERENCES `m_item` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_begin_user`
        FOREIGN KEY (`user_id`) REFERENCES `m_user` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Stok awal per item per outlet per periode — dasar perhitungan variance';

-- =============================================================================
-- 9. RE-AKTIFKAN FOREIGN KEY CHECK
-- =============================================================================

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- RINGKASAN TABEL YANG DIBUAT
-- =============================================================================
-- Master   : m_outlet, m_user, m_supplier, m_item, m_menu      (5 tabel)
-- Resep    : t_bom                                               (1 tabel)
-- Transaksi: t_penjualan, t_penjualan_detail,
--            t_penerimaan, t_penerimaan_detail,
--            t_transfer, t_transfer_detail,
--            t_waste,
--            t_stock_opname, t_stock_opname_detail               (9 tabel)
-- Stok     : t_stock_ledger, r_begin_stock                       (2 tabel)
-- ─────────────────────────────────────────────
-- TOTAL    : 17 tabel
-- =============================================================================
-- EOF: schema.sql  |  InvenKopi OMORA  |  D2-02  |  2026-11-20
-- =============================================================================
