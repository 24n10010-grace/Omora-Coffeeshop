<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use App\Models\User;
use App\Models\Item;
use App\Models\Menu;
use App\Models\BeginStock;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Penerimaan;
use App\Models\PenerimaanDetail;
use App\Models\Transfer;
use App\Models\TransferDetail;
use App\Models\Waste;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Models\StockLedger;

class DemoTransactionSeeder extends Seeder
{
    /**
     * Seed 7-day demo transactions matching Proto Variance & Proto BOM sheets.
     * Periode: 2026-11 (13 Nov - 19 Nov 2026)
     */
    public function run(): void
    {
        $outletBar = Outlet::where('kode_outlet', 'OUT001')->firstOrFail();
        $outletGudang = Outlet::where('kode_outlet', 'OUT002')->firstOrFail();
        $userSupervisor = User::where('username', 'supervisor_omora')->firstOrFail();
        $userBarista = User::where('username', 'barista_omora')->firstOrFail();

        $items = Item::all()->keyBy('sku');
        $menus = Menu::all()->keyBy('kode_menu');

        $periode = '2026-11';
        $tanggalMulai = '2026-11-13';
        $tanggalSelesai = '2026-11-19';

        // ---------------------------------------------------------------------
        // 1. BEGIN STOCK (Stok Awal Periode 7 Hari)
        // ---------------------------------------------------------------------
        $beginStocks = [
            'DRY001' => 6000.0000,
            'DRY002' => 1500.0000,
            'DRY003' => 1000.0000,
            'DRY004' => 2000.0000,
            'FRS001' => 20000.0000,
            'FRS002' => 40.0000,
            'FRS003' => 500.0000,
            'PKG001' => 300.0000,
            'PKG002' => 300.0000,
            'PKG003' => 400.0000,
        ];

        foreach ($beginStocks as $sku => $qty) {
            $item = $items[$sku];
            $bs = BeginStock::updateOrCreate(
                [
                    'outlet_id' => $outletBar->id,
                    'item_id'   => $item->id,
                    'periode'   => $periode,
                ],
                [
                    'qty_ecer'  => $qty,
                    'user_id'   => $userSupervisor->id,
                ]
            );

            // Record to Ledger
            StockLedger::updateOrCreate(
                [
                    'outlet_id'      => $outletBar->id,
                    'item_id'        => $item->id,
                    'referensi_tipe' => 'r_begin_stock',
                    'referensi_id'   => $bs->id,
                ],
                [
                    'tanggal'      => $tanggalMulai,
                    'jenis_mutasi' => 'begin_stock',
                    'qty_masuk'    => $qty,
                    'qty_keluar'   => 0,
                    'saldo_ecer'   => $qty,
                ]
            );
        }

        // ---------------------------------------------------------------------
        // 2. PENJUALAN 7 HARI (Aggregated Total Menu Terjual)
        // Americano 120, Latte 150, Caramel Latte 90, Es Kopi Susu Aren 200,
        // Cappuccino 80, Chocolate 60, Roti Bakar Cokelat 40
        // Dicatat oleh Barista (Grace)
        // ---------------------------------------------------------------------
        $penjualan = Penjualan::updateOrCreate(
            ['no_dokumen' => 'SAL-202611-001'],
            [
                'tanggal'   => $tanggalSelesai,
                'outlet_id' => $outletBar->id,
                'user_id'   => $userBarista->id,
                'shift'     => 'siang',
                'catatan'   => 'Akumulasi Penjualan 7 Hari Periode Uji',
                'status'    => 'posted',
            ]
        );

        $salesItems = [
            'MNU001' => 120,
            'MNU002' => 150,
            'MNU003' => 90,
            'MNU004' => 200,
            'MNU005' => 80,
            'MNU006' => 60,
            'MNU007' => 40,
        ];

        foreach ($salesItems as $kodeMenu => $qty) {
            $menu = $menus[$kodeMenu];
            PenjualanDetail::updateOrCreate(
                [
                    'penjualan_id' => $penjualan->id,
                    'menu_id'      => $menu->id,
                ],
                [
                    'qty'          => $qty,
                    'harga_satuan' => $menu->harga,
                ]
            );
        }

        // ---------------------------------------------------------------------
        // 3. RECEIVE (Penerimaan dari Supplier)
        // ---------------------------------------------------------------------
        $penerimaan = Penerimaan::updateOrCreate(
            ['no_dokumen' => 'RCV-202611-001'],
            [
                'tanggal'     => '2026-11-14',
                'outlet_id'   => $outletBar->id,
                'supplier_id' => 1,
                'user_id'     => $userSupervisor->id,
                'catatan'     => 'Penerimaan Bahan Baku Mingguan',
                'status'      => 'posted',
            ]
        );

        $receives = [
            'DRY001' => 8000.0000,
            'DRY002' => 2000.0000,
            'DRY003' => 1500.0000,
            'DRY004' => 4000.0000,
            'FRS001' => 60000.0000,
            'FRS002' => 60.0000,
            'FRS003' => 500.0000,
            'PKG001' => 500.0000,
            'PKG002' => 500.0000,
            'PKG003' => 500.0000,
        ];

        foreach ($receives as $sku => $totalEcer) {
            $item = $items[$sku];
            $qtyUtuh = floor($totalEcer / $item->pack_standard);
            $qtyEcer = $totalEcer - ($qtyUtuh * $item->pack_standard);

            PenerimaanDetail::updateOrCreate(
                [
                    'penerimaan_id' => $penerimaan->id,
                    'item_id'       => $item->id,
                ],
                [
                    'qty_utuh'   => $qtyUtuh,
                    'qty_ecer'   => $qtyEcer,
                    'total_ecer' => $totalEcer,
                ]
            );
        }

        // ---------------------------------------------------------------------
        // 4. TRANSFER IN / OUT
        // PKG001: TrfIn 100 dari Gudang ke Bar Utama
        // PKG003: TrfOut 50 dari Bar Utama ke Gudang
        // ---------------------------------------------------------------------
        $transferIn = Transfer::updateOrCreate(
            ['no_dokumen' => 'TRF-202611-IN01'],
            [
                'tanggal'          => '2026-11-15',
                'outlet_asal_id'   => $outletGudang->id,
                'outlet_tujuan_id' => $outletBar->id,
                'user_id'          => $userSupervisor->id,
                'catatan'          => 'Transfer masuk cup tambahan dari gudang',
                'status'           => 'posted',
            ]
        );

        TransferDetail::updateOrCreate(
            [
                'transfer_id' => $transferIn->id,
                'item_id'     => $items['PKG001']->id,
            ],
            [
                'qty_ecer'    => 100.0000,
            ]
        );

        $transferOut = Transfer::updateOrCreate(
            ['no_dokumen' => 'TRF-202611-OUT01'],
            [
                'tanggal'          => '2026-11-16',
                'outlet_asal_id'   => $outletBar->id,
                'outlet_tujuan_id' => $outletGudang->id,
                'user_id'          => $userSupervisor->id,
                'catatan'          => 'Transfer keluar sedotan ke event back-office',
                'status'           => 'posted',
            ]
        );

        TransferDetail::updateOrCreate(
            [
                'transfer_id' => $transferOut->id,
                'item_id'     => $items['PKG003']->id,
            ],
            [
                'qty_ecer'    => 50.0000,
            ]
        );

        // ---------------------------------------------------------------------
        // 5. WASTE (Limbah / Terbuang)
        // Dicatat oleh Barista (Grace)
        // ---------------------------------------------------------------------
        $wastes = [
            'DRY001' => ['qty' => 40.0000,  'alasan' => 'Tumpah saat kalibrasi grinder'],
            'DRY004' => ['qty' => 100.0000, 'alasan' => 'Bocor wadah saus pump'],
            'FRS001' => ['qty' => 600.0000, 'alasan' => 'Susu sisa steaming teko bar'],
            'FRS002' => ['qty' => 4.0000,   'alasan' => 'Roti berjamur (kedaluwarsa)'],
            'PKG001' => ['qty' => 5.0000,   'alasan' => 'Cup pecah terinjak saat jam ramai'],
        ];

        foreach ($wastes as $sku => $w) {
            Waste::updateOrCreate(
                [
                    'no_dokumen' => 'WST-202611-' . $sku,
                ],
                [
                    'tanggal'   => '2026-11-18',
                    'outlet_id' => $outletBar->id,
                    'item_id'   => $items[$sku]->id,
                    'user_id'   => $userBarista->id,
                    'qty_ecer'  => $w['qty'],
                    'alasan'    => $w['alasan'],
                    'catatan'   => 'Dicatat oleh Barista pada shift siang',
                ]
            );
        }

        // ---------------------------------------------------------------------
        // 6. STOCK OPNAME (PHYSICAL INVENTORY COUNT)
        // Dihitung oleh Barista (Grace) sesuai Wireframe M09 Laporan D3-01
        // ---------------------------------------------------------------------
        $stockOpname = StockOpname::updateOrCreate(
            ['no_dokumen' => 'PI-20261119-001'],
            [
                'tanggal'   => $tanggalSelesai,
                'outlet_id' => $outletBar->id,
                'user_id'   => $userBarista->id,
                'status'    => 'posted',
                'catatan'   => 'Stock Opname Akhir Shift oleh Barista Grace',
            ]
        );

        $piCounts = [
            'DRY001' => ['utuh' => 2, 'ecer' => 350, 'total' => 2350.0000],
            'DRY002' => ['utuh' => 2, 'ecer' => 80,  'total' => 1080.0000],
            'DRY003' => ['utuh' => 1, 'ecer' => 0,   'total' => 700.0000],
            'DRY004' => ['utuh' => 0, 'ecer' => 700, 'total' => 700.0000],
            'FRS001' => ['utuh' => 4, 'ecer' => 200, 'total' => 4200.0000],
            'FRS002' => ['utuh' => 1, 'ecer' => 6,   'total' => 16.0000],
            'FRS003' => ['utuh' => 2, 'ecer' => 190, 'total' => 590.0000],
            'PKG001' => ['utuh' => 3, 'ecer' => 40,  'total' => 190.0000],
            'PKG002' => ['utuh' => 1, 'ecer' => 46,  'total' => 96.0000],
            'PKG003' => ['utuh' => 2, 'ecer' => 15,  'total' => 215.0000],
        ];

        foreach ($piCounts as $sku => $pi) {
            StockOpnameDetail::updateOrCreate(
                [
                    'stock_opname_id' => $stockOpname->id,
                    'item_id'         => $items[$sku]->id,
                ],
                [
                    'sisa_utuh'  => $pi['utuh'],
                    'sisa_ecer'  => $pi['ecer'],
                    'total_ecer' => $pi['total'],
                ]
            );
        }
    }
}
