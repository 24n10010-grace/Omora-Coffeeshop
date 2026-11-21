<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ---------------------------------------------------------------------
        // 1. MASTER DATA
        // ---------------------------------------------------------------------

        // 1.1 m_outlet
        Schema::create('m_outlet', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_outlet', 20)->unique();
            $table->string('nama_outlet', 100);
            $table->text('alamat')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        // 1.2 m_user
        Schema::create('m_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('outlet_id')->nullable();
            $table->string('username', 50)->unique();
            $table->string('email', 150)->unique();
            $table->string('password', 255);
            $table->string('nama_lengkap', 100);
            $table->enum('role', ['owner', 'supervisor', 'staff'])->default('staff');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->index('role');
            $table->index('is_active');
        });

        // 1.3 m_supplier
        Schema::create('m_supplier', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_supplier', 20)->unique();
            $table->string('nama_supplier', 150);
            $table->string('kontak_person', 100)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        // 1.4 m_item (Master Bahan Baku)
        Schema::create('m_item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku', 30)->unique();
            $table->string('nama_item', 150);
            $table->enum('tipe_material', ['DRY', 'FRS', 'FRZ', 'PKG']);
            $table->enum('satuan_ecer', ['G', 'ML', 'PC']);
            $table->enum('satuan_utuh', ['POC', 'PAC', 'BOX', 'CAR']);
            $table->decimal('pack_standard', 10, 4)->comment('Isi per kemasan utuh dalam satuan ecer');
            $table->decimal('stok_minimum', 10, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('tipe_material');
            $table->index('is_active');
        });

        // 1.5 m_menu (Master Menu Jual)
        Schema::create('m_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_menu', 30)->unique();
            $table->string('nama_menu', 150);
            $table->string('kategori', 50)->nullable();
            $table->decimal('harga', 12, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('kategori');
            $table->index('is_active');
        });

        // ---------------------------------------------------------------------
        // 2. DATA RESEP (BOM)
        // ---------------------------------------------------------------------

        // 2.1 t_bom
        Schema::create('t_bom', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('item_id');
            $table->decimal('qty_per_porsi', 10, 4)->comment('Kuantitas per 1 porsi menu dalam satuan ecer');
            $table->enum('satuan', ['G', 'ML', 'PC']);
            $table->timestamps();

            $table->unique(['menu_id', 'item_id']);

            $table->foreign('menu_id')
                  ->references('id')->on('m_menu')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });

        // ---------------------------------------------------------------------
        // 3. TRANSAKSI PENJUALAN
        // ---------------------------------------------------------------------

        // 3.1 t_penjualan (Header)
        Schema::create('t_penjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_dokumen', 30)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('user_id');
            $table->enum('shift', ['pagi', 'siang', 'malam'])->default('pagi');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'posted', 'void'])->default('posted');
            $table->timestamps();

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('tanggal');
            $table->index('status');
        });

        // 3.2 t_penjualan_detail
        Schema::create('t_penjualan_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('penjualan_id');
            $table->unsignedInteger('menu_id');
            $table->decimal('qty', 10, 4);
            $table->decimal('harga_satuan', 12, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('penjualan_id')
                  ->references('id')->on('t_penjualan')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('menu_id')
                  ->references('id')->on('m_menu')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });

        // ---------------------------------------------------------------------
        // 4. TRANSAKSI PENERIMAAN BARANG
        // ---------------------------------------------------------------------

        // 4.1 t_penerimaan (Header)
        Schema::create('t_penerimaan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_dokumen', 30)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('user_id');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'posted', 'void'])->default('posted');
            $table->timestamps();

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('supplier_id')
                  ->references('id')->on('m_supplier')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('tanggal');
            $table->index('status');
        });

        // 4.2 t_penerimaan_detail
        Schema::create('t_penerimaan_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('penerimaan_id');
            $table->unsignedInteger('item_id');
            $table->decimal('qty_utuh', 10, 4)->default(0);
            $table->decimal('qty_ecer', 10, 4)->default(0);
            $table->decimal('total_ecer', 12, 4);
            $table->timestamps();

            $table->foreign('penerimaan_id')
                  ->references('id')->on('t_penerimaan')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });

        // ---------------------------------------------------------------------
        // 5. TRANSAKSI TRANSFER STOK
        // ---------------------------------------------------------------------

        // 5.1 t_transfer (Header)
        Schema::create('t_transfer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_dokumen', 30)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('outlet_asal_id');
            $table->unsignedInteger('outlet_tujuan_id');
            $table->unsignedInteger('user_id');
            $table->text('catatan')->nullable();
            $table->enum('status', ['draft', 'posted', 'void'])->default('posted');
            $table->timestamps();

            $table->foreign('outlet_asal_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('outlet_tujuan_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('tanggal');
            $table->index('status');
        });

        // 5.2 t_transfer_detail
        Schema::create('t_transfer_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('transfer_id');
            $table->unsignedInteger('item_id');
            $table->decimal('qty_ecer', 12, 4);
            $table->timestamps();

            $table->foreign('transfer_id')
                  ->references('id')->on('t_transfer')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });

        // ---------------------------------------------------------------------
        // 6. WASTE & SPOILAGE
        // ---------------------------------------------------------------------

        // 6.1 t_waste
        Schema::create('t_waste', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_dokumen', 30)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('user_id');
            $table->decimal('qty_ecer', 12, 4);
            $table->string('alasan', 100);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('tanggal');
        });

        // ---------------------------------------------------------------------
        // 7. STOCK OPNAME (PHYSICAL INVENTORY)
        // ---------------------------------------------------------------------

        // 7.1 t_stock_opname (Header)
        Schema::create('t_stock_opname', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_dokumen', 30)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('user_id');
            $table->enum('status', ['draft', 'posted', 'void'])->default('draft');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('tanggal');
            $table->index('status');
        });

        // 7.2 t_stock_opname_detail
        Schema::create('t_stock_opname_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stock_opname_id');
            $table->unsignedInteger('item_id');
            $table->decimal('sisa_utuh', 10, 4)->default(0);
            $table->decimal('sisa_ecer', 10, 4)->default(0);
            $table->decimal('total_ecer', 12, 4);
            $table->timestamps();

            $table->unique(['stock_opname_id', 'item_id']);

            $table->foreign('stock_opname_id')
                  ->references('id')->on('t_stock_opname')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');
        });

        // ---------------------------------------------------------------------
        // 8. KARTU STOK & STOK AWAL
        // ---------------------------------------------------------------------

        // 8.1 t_stock_ledger
        Schema::create('t_stock_ledger', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('item_id');
            $table->date('tanggal');
            $table->enum('jenis_mutasi', [
                'begin_stock',
                'receive',
                'trf_in',
                'trf_out',
                'waste',
                'usage',
                'opname_adj'
            ]);
            $table->string('referensi_tipe', 50);
            $table->unsignedInteger('referensi_id');
            $table->decimal('qty_masuk', 14, 4)->default(0);
            $table->decimal('qty_keluar', 14, 4)->default(0);
            $table->decimal('saldo_ecer', 14, 4);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index(['outlet_id', 'item_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('jenis_mutasi');
        });

        // 8.2 r_begin_stock
        Schema::create('r_begin_stock', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('outlet_id');
            $table->unsignedInteger('item_id');
            $table->char('periode', 7)->comment('Format YYYY-MM, contoh: 2026-11');
            $table->decimal('qty_ecer', 14, 4)->default(0);
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->unique(['outlet_id', 'item_id', 'periode']);

            $table->foreign('outlet_id')
                  ->references('id')->on('m_outlet')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('item_id')
                  ->references('id')->on('m_item')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('restrict');

            $table->index('periode');
        });

        // ---------------------------------------------------------------------
        // 9. LOG AKTIVITAS (MODUL M01)
        // ---------------------------------------------------------------------

        // 9.1 t_log_aktivitas
        Schema::create('t_log_aktivitas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('aksi', 50);
            $table->string('modul', 50);
            $table->text('keterangan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')
                  ->references('id')->on('m_user')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->index(['user_id', 'created_at']);
            $table->index('modul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_log_aktivitas');
        Schema::dropIfExists('r_begin_stock');
        Schema::dropIfExists('t_stock_ledger');
        Schema::dropIfExists('t_stock_opname_detail');
        Schema::dropIfExists('t_stock_opname');
        Schema::dropIfExists('t_waste');
        Schema::dropIfExists('t_transfer_detail');
        Schema::dropIfExists('t_transfer');
        Schema::dropIfExists('t_penerimaan_detail');
        Schema::dropIfExists('t_penerimaan');
        Schema::dropIfExists('t_penjualan_detail');
        Schema::dropIfExists('t_penjualan');
        Schema::dropIfExists('t_bom');
        Schema::dropIfExists('m_menu');
        Schema::dropIfExists('m_item');
        Schema::dropIfExists('m_supplier');
        Schema::dropIfExists('m_user');
        Schema::dropIfExists('m_outlet');
    }
};
