<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Supplier;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Bom;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Idempotent seeding using updateOrCreate.
     */
    public function run(): void
    {
        // ---------------------------------------------------------------------
        // 1. OUTLET (Minimal 2 outlet: Bar Utama & Gudang)
        // ---------------------------------------------------------------------
        $outletBar = Outlet::updateOrCreate(
            ['kode_outlet' => 'OUT001'],
            [
                'nama_outlet' => 'OMORA – Bar Utama',
                'alamat'      => 'Jl. Pawiyatan Luhur, Bendan Dhuwur, Semarang',
                'telepon'     => '024-8441555',
                'is_active'   => true,
            ]
        );

        $outletGudang = Outlet::updateOrCreate(
            ['kode_outlet' => 'OUT002'],
            [
                'nama_outlet' => 'OMORA – Gudang Pusat / Back Office',
                'alamat'      => 'Jl. Pawiyatan Luhur No. 2, Semarang',
                'telepon'     => '024-8441556',
                'is_active'   => true,
            ]
        );

        // ---------------------------------------------------------------------
        // 2. USER (3 Akun: Owner, Supervisor, Staff/Barista)
        // Password default: password123 (bcrypt) - didokumentasikan di README
        // ---------------------------------------------------------------------
        User::updateOrCreate(
            ['username' => 'owner_omora'],
            [
                'email'        => 'owner@omoracoffee.com',
                'password'     => Hash::make('password123'),
                'nama_lengkap' => 'Grace Febriani S. P (Owner)',
                'role'         => 'owner',
                'outlet_id'    => null, // Lintas outlet
                'is_active'    => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'supervisor_omora'],
            [
                'email'        => 'supervisor@omoracoffee.com',
                'password'     => Hash::make('password123'),
                'nama_lengkap' => 'Michelle Aurellia (Supervisor Bar)',
                'role'         => 'supervisor',
                'outlet_id'    => $outletBar->id,
                'is_active'    => true,
            ]
        );

        User::updateOrCreate(
            ['username' => 'barista_omora'],
            [
                'email'        => 'barista@omoracoffee.com',
                'password'     => Hash::make('password123'),
                'nama_lengkap' => 'Kirana Ayu Dwi Putri (Barista Shift Pagi)',
                'role'         => 'staff',
                'outlet_id'    => $outletBar->id,
                'is_active'    => true,
            ]
        );

        // ---------------------------------------------------------------------
        // 3. SUPPLIER (3 Pemasok Contoh)
        // ---------------------------------------------------------------------
        Supplier::updateOrCreate(
            ['kode_supplier' => 'SUP001'],
            [
                'nama_supplier' => 'PT Roastery Nusantara Jaya',
                'kontak_person' => 'Bpk. Hendra',
                'telepon'       => '0812-3456-7890',
                'alamat'        => 'Kawasan Industri Candi, Semarang',
                'is_active'     => true,
            ]
        );

        Supplier::updateOrCreate(
            ['kode_supplier' => 'SUP002'],
            [
                'nama_supplier' => 'CV Sumber Susu Segar',
                'kontak_person' => 'Ibu Maya',
                'telepon'       => '0813-9876-5432',
                'alamat'        => 'Ungaran Barat, Kab. Semarang',
                'is_active'     => true,
            ]
        );

        Supplier::updateOrCreate(
            ['kode_supplier' => 'SUP003'],
            [
                'nama_supplier' => 'Toko Kemasan Abadi',
                'kontak_person' => 'Ko David',
                'telepon'       => '0819-1122-3344',
                'alamat'        => 'Krapyak, Semarang Barat',
                'is_active'     => true,
            ]
        );

        // ---------------------------------------------------------------------
        // 4. ITEM (10 SKU Bahan Baku Proto BOM & Proto Variance)
        // Catatan: pack_standard & stok_minimum merupakan DATA CONTOH - Asumsi realistis
        // ---------------------------------------------------------------------
        $items = [
            [
                'sku'           => 'DRY001',
                'nama_item'     => 'Biji Kopi House Blend',
                'tipe_material' => 'DRY',
                'satuan_ecer'   => 'G',
                'satuan_utuh'   => 'PAC',
                'pack_standard' => 1000.0000, // 1 pack = 1000 gram
                'stok_minimum'  => 2000.0000, // min 2000 gram
            ],
            [
                'sku'           => 'DRY002',
                'nama_item'     => 'Bubuk Cokelat',
                'tipe_material' => 'DRY',
                'satuan_ecer'   => 'G',
                'satuan_utuh'   => 'PAC',
                'pack_standard' => 500.0000, // 1 pack = 500 gram
                'stok_minimum'  => 1000.0000,
            ],
            [
                'sku'           => 'DRY003',
                'nama_item'     => 'Sirup Caramel',
                'tipe_material' => 'DRY',
                'satuan_ecer'   => 'ML',
                'satuan_utuh'   => 'BOX',
                'pack_standard' => 700.0000, // 1 botol = 700 ml
                'stok_minimum'  => 500.0000,
            ],
            [
                'sku'           => 'DRY004',
                'nama_item'     => 'Gula Aren Cair',
                'tipe_material' => 'DRY',
                'satuan_ecer'   => 'ML',
                'satuan_utuh'   => 'BOX',
                'pack_standard' => 1000.0000, // 1 jerigen/botol = 1000 ml
                'stok_minimum'  => 1500.0000,
            ],
            [
                'sku'           => 'FRS001',
                'nama_item'     => 'Susu UHT Full Cream',
                'tipe_material' => 'FRS',
                'satuan_ecer'   => 'ML',
                'satuan_utuh'   => 'CAR',
                'pack_standard' => 1000.0000, // 1 karton = 1000 ml
                'stok_minimum'  => 5000.0000,
            ],
            [
                'sku'           => 'FRS002',
                'nama_item'     => 'Roti Tawar',
                'tipe_material' => 'FRS',
                'satuan_ecer'   => 'PC',
                'satuan_utuh'   => 'PAC',
                'pack_standard' => 10.0000, // 1 pack = 10 slice/lembar
                'stok_minimum'  => 20.0000,
            ],
            [
                'sku'           => 'FRS003',
                'nama_item'     => 'Mentega',
                'tipe_material' => 'FRS',
                'satuan_ecer'   => 'G',
                'satuan_utuh'   => 'PAC',
                'pack_standard' => 200.0000, // 1 pack = 200 gram
                'stok_minimum'  => 400.0000,
            ],
            [
                'sku'           => 'PKG001',
                'nama_item'     => 'Cup Plastik 16 Oz',
                'tipe_material' => 'PKG',
                'satuan_ecer'   => 'PC',
                'satuan_utuh'   => 'POC',
                'pack_standard' => 50.0000, // 1 slop = 50 pcs
                'stok_minimum'  => 100.0000,
            ],
            [
                'sku'           => 'PKG002',
                'nama_item'     => 'Tutup Cup Dome',
                'tipe_material' => 'PKG',
                'satuan_ecer'   => 'PC',
                'satuan_utuh'   => 'POC',
                'pack_standard' => 50.0000, // 1 slop = 50 pcs
                'stok_minimum'  => 100.0000,
            ],
            [
                'sku'           => 'PKG003',
                'nama_item'     => 'Sedotan',
                'tipe_material' => 'PKG',
                'satuan_ecer'   => 'PC',
                'satuan_utuh'   => 'PAC',
                'pack_standard' => 100.0000, // 1 pack = 100 pcs
                'stok_minimum'  => 150.0000,
            ],
        ];

        $itemMap = [];
        foreach ($items as $data) {
            $created = Item::updateOrCreate(
                ['sku' => $data['sku']],
                array_merge($data, ['is_active' => true])
            );
            $itemMap[$data['sku']] = $created;
        }

        // ---------------------------------------------------------------------
        // 5. MENU (7 Menu Minuman & Makanan)
        // ---------------------------------------------------------------------
        $menus = [
            ['kode_menu' => 'MNU001', 'nama_menu' => 'Americano', 'kategori' => 'Kopi', 'harga' => 18000.00],
            ['kode_menu' => 'MNU002', 'nama_menu' => 'Latte', 'kategori' => 'Kopi', 'harga' => 24000.00],
            ['kode_menu' => 'MNU003', 'nama_menu' => 'Caramel Latte', 'kategori' => 'Kopi', 'harga' => 28000.00],
            ['kode_menu' => 'MNU004', 'nama_menu' => 'Es Kopi Susu Aren', 'kategori' => 'Kopi', 'harga' => 22000.00],
            ['kode_menu' => 'MNU005', 'nama_menu' => 'Cappuccino', 'kategori' => 'Kopi', 'harga' => 24000.00],
            ['kode_menu' => 'MNU006', 'nama_menu' => 'Chocolate', 'kategori' => 'Non-Kopi', 'harga' => 22000.00],
            ['kode_menu' => 'MNU007', 'nama_menu' => 'Roti Bakar Cokelat', 'kategori' => 'Makanan', 'harga' => 16000.00],
        ];

        $menuMap = [];
        foreach ($menus as $m) {
            $created = Menu::updateOrCreate(
                ['kode_menu' => $m['kode_menu']],
                array_merge($m, ['is_active' => true])
            );
            $menuMap[$m['kode_menu']] = $created;
        }

        // ---------------------------------------------------------------------
        // 6. BILL OF MATERIALS (BOM)
        // Komposisi bahan per 1 porsi menu
        // ---------------------------------------------------------------------
        $recipes = [
            // MNU001 Americano: DRY001 18, PKG001 1, PKG002 1, PKG003 1
            'MNU001' => [
                ['sku' => 'DRY001', 'qty' => 18.0000, 'satuan' => 'G'],
                ['sku' => 'PKG001', 'qty' => 1.0000,  'satuan' => 'PC'],
                ['sku' => 'PKG002', 'qty' => 1.0000,  'satuan' => 'PC'],
                ['sku' => 'PKG003', 'qty' => 1.0000,  'satuan' => 'PC'],
            ],
            // MNU002 Latte: DRY001 18, FRS001 150, PKG001 1, PKG002 1, PKG003 1
            'MNU002' => [
                ['sku' => 'DRY001', 'qty' => 18.0000,  'satuan' => 'G'],
                ['sku' => 'FRS001', 'qty' => 150.0000, 'satuan' => 'ML'],
                ['sku' => 'PKG001', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG002', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG003', 'qty' => 1.0000,   'satuan' => 'PC'],
            ],
            // MNU003 Caramel Latte: DRY001 18, FRS001 150, DRY003 20, PKG001 1, PKG002 1, PKG003 1
            'MNU003' => [
                ['sku' => 'DRY001', 'qty' => 18.0000,  'satuan' => 'G'],
                ['sku' => 'FRS001', 'qty' => 150.0000, 'satuan' => 'ML'],
                ['sku' => 'DRY003', 'qty' => 20.0000,  'satuan' => 'ML'],
                ['sku' => 'PKG001', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG002', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG003', 'qty' => 1.0000,   'satuan' => 'PC'],
            ],
            // MNU004 Es Kopi Susu Aren: DRY001 18, FRS001 100, DRY004 25, PKG001 1, PKG002 1, PKG003 1
            'MNU004' => [
                ['sku' => 'DRY001', 'qty' => 18.0000,  'satuan' => 'G'],
                ['sku' => 'FRS001', 'qty' => 100.0000, 'satuan' => 'ML'],
                ['sku' => 'DRY004', 'qty' => 25.0000,  'satuan' => 'ML'],
                ['sku' => 'PKG001', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG002', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG003', 'qty' => 1.0000,   'satuan' => 'PC'],
            ],
            // MNU005 Cappuccino: DRY001 18, FRS001 120, PKG001 1, PKG002 1
            'MNU005' => [
                ['sku' => 'DRY001', 'qty' => 18.0000,  'satuan' => 'G'],
                ['sku' => 'FRS001', 'qty' => 120.0000, 'satuan' => 'ML'],
                ['sku' => 'PKG001', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG002', 'qty' => 1.0000,   'satuan' => 'PC'],
            ],
            // MNU006 Chocolate: DRY002 30, FRS001 150, PKG001 1, PKG002 1, PKG003 1
            'MNU006' => [
                ['sku' => 'DRY002', 'qty' => 30.0000,  'satuan' => 'G'],
                ['sku' => 'FRS001', 'qty' => 150.0000, 'satuan' => 'ML'],
                ['sku' => 'PKG001', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG002', 'qty' => 1.0000,   'satuan' => 'PC'],
                ['sku' => 'PKG003', 'qty' => 1.0000,   'satuan' => 'PC'],
            ],
            // MNU007 Roti Bakar Cokelat: FRS002 2, FRS003 10, DRY002 15
            'MNU007' => [
                ['sku' => 'FRS002', 'qty' => 2.0000,  'satuan' => 'PC'],
                ['sku' => 'FRS003', 'qty' => 10.0000, 'satuan' => 'G'],
                ['sku' => 'DRY002', 'qty' => 15.0000, 'satuan' => 'G'],
            ],
        ];

        foreach ($recipes as $kodeMenu => $components) {
            $menuModel = $menuMap[$kodeMenu];
            foreach ($components as $c) {
                $itemModel = $itemMap[$c['sku']];
                Bom::updateOrCreate(
                    [
                        'menu_id' => $menuModel->id,
                        'item_id' => $itemModel->id,
                    ],
                    [
                        'qty_per_porsi' => $c['qty'],
                        'satuan'        => $c['satuan'],
                    ]
                );
            }
        }
    }
}
