<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiStubController extends Controller
{
    /**
     * M01: Login stub
     */
    public function login(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => 'sample_sanctum_token_omora_dev_test',
                'user'  => [
                    'id'           => 1,
                    'username'     => $request->input('username', 'owner_omora'),
                    'nama_lengkap' => 'Grace Febriani S. P',
                    'role'         => 'owner',
                    'outlet'       => 'Semua Outlet',
                ]
            ]
        ], 200);
    }

    /**
     * M01: Logout stub
     */
    public function logout(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Sesi login berhasil diakhiri.',
        ], 200);
    }

    /**
     * M01: User Profile Me stub
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id'           => 2,
                'username'     => 'supervisor_omora',
                'nama_lengkap' => 'Michelle Aurellia',
                'role'         => 'supervisor',
                'outlet_id'    => 1,
            ]
        ], 200);
    }

    /**
     * M02: Master Items stub
     */
    public function getItems(): JsonResponse
    {
        $items = [
            ['sku' => 'DRY001', 'nama_item' => 'Biji Kopi House Blend', 'tipe' => 'DRY', 'uom_ecer' => 'G', 'uom_utuh' => 'PAC', 'pack_standard' => 1000, 'stok_minimum' => 2000],
            ['sku' => 'DRY002', 'nama_item' => 'Bubuk Cokelat', 'tipe' => 'DRY', 'uom_ecer' => 'G', 'uom_utuh' => 'PAC', 'pack_standard' => 500, 'stok_minimum' => 1000],
            ['sku' => 'DRY003', 'nama_item' => 'Sirup Caramel', 'tipe' => 'DRY', 'uom_ecer' => 'ML', 'uom_utuh' => 'BOX', 'pack_standard' => 700, 'stok_minimum' => 500],
            ['sku' => 'DRY004', 'nama_item' => 'Gula Aren Cair', 'tipe' => 'DRY', 'uom_ecer' => 'ML', 'uom_utuh' => 'BOX', 'pack_standard' => 1000, 'stok_minimum' => 1500],
            ['sku' => 'FRS001', 'nama_item' => 'Susu UHT Full Cream', 'tipe' => 'FRS', 'uom_ecer' => 'ML', 'uom_utuh' => 'CAR', 'pack_standard' => 1000, 'stok_minimum' => 5000],
            ['sku' => 'FRS002', 'nama_item' => 'Roti Tawar', 'tipe' => 'FRS', 'uom_ecer' => 'PC', 'uom_utuh' => 'PAC', 'pack_standard' => 10, 'stok_minimum' => 20],
            ['sku' => 'FRS003', 'nama_item' => 'Mentega', 'tipe' => 'FRS', 'uom_ecer' => 'G', 'uom_utuh' => 'PAC', 'pack_standard' => 200, 'stok_minimum' => 400],
            ['sku' => 'PKG001', 'nama_item' => 'Cup Plastik 16 Oz', 'tipe' => 'PKG', 'uom_ecer' => 'PC', 'uom_utuh' => 'POC', 'pack_standard' => 50, 'stok_minimum' => 100],
            ['sku' => 'PKG002', 'nama_item' => 'Tutup Cup Dome', 'tipe' => 'PKG', 'uom_ecer' => 'PC', 'uom_utuh' => 'POC', 'pack_standard' => 50, 'stok_minimum' => 100],
            ['sku' => 'PKG003', 'nama_item' => 'Sedotan', 'tipe' => 'PKG', 'uom_ecer' => 'PC', 'uom_utuh' => 'PAC', 'pack_standard' => 100, 'stok_minimum' => 150],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Daftar master material berhasil dimuat.',
            'data'    => $items,
        ], 200);
    }

    /**
     * M03: Master Menus stub
     */
    public function getMenus(): JsonResponse
    {
        $menus = [
            ['kode_menu' => 'MNU001', 'nama_menu' => 'Americano', 'kategori' => 'Kopi', 'harga' => 18000],
            ['kode_menu' => 'MNU002', 'nama_menu' => 'Latte', 'kategori' => 'Kopi', 'harga' => 24000],
            ['kode_menu' => 'MNU003', 'nama_menu' => 'Caramel Latte', 'kategori' => 'Kopi', 'harga' => 28000],
            ['kode_menu' => 'MNU004', 'nama_menu' => 'Es Kopi Susu Aren', 'kategori' => 'Kopi', 'harga' => 22000],
            ['kode_menu' => 'MNU005', 'nama_menu' => 'Cappuccino', 'kategori' => 'Kopi', 'harga' => 24000],
            ['kode_menu' => 'MNU006', 'nama_menu' => 'Chocolate', 'kategori' => 'Non-Kopi', 'harga' => 22000],
            ['kode_menu' => 'MNU007', 'nama_menu' => 'Roti Bakar Cokelat', 'kategori' => 'Makanan', 'harga' => 16000],
        ];

        return response()->json([
            'success' => true,
            'data'    => $menus,
        ], 200);
    }

    /**
     * M03: Menu BOM Recipe stub
     */
    public function getMenuBom(string $kodeMenu): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'kode_menu' => $kodeMenu,
                'resep' => [
                    ['sku' => 'DRY001', 'nama_item' => 'Biji Kopi House Blend', 'qty_per_porsi' => 18, 'satuan' => 'G'],
                    ['sku' => 'FRS001', 'nama_item' => 'Susu UHT Full Cream', 'qty_per_porsi' => 150, 'satuan' => 'ML'],
                    ['sku' => 'PKG001', 'nama_item' => 'Cup Plastik 16 Oz', 'qty_per_porsi' => 1, 'satuan' => 'PC'],
                ]
            ]
        ], 200);
    }

    /**
     * M05: Usage summary stub
     */
    public function getUsage(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'periode' => '2026-11 (7 Hari)',
            'data' => [
                ['sku' => 'DRY001', 'nama_item' => 'Biji Kopi House Blend', 'total_usage' => 11520, 'satuan' => 'G'],
                ['sku' => 'DRY002', 'nama_item' => 'Bubuk Cokelat', 'total_usage' => 2400, 'satuan' => 'G'],
                ['sku' => 'DRY003', 'nama_item' => 'Sirup Caramel', 'total_usage' => 1800, 'satuan' => 'ML'],
                ['sku' => 'DRY004', 'nama_item' => 'Gula Aren Cair', 'total_usage' => 5000, 'satuan' => 'ML'],
                ['sku' => 'FRS001', 'nama_item' => 'Susu UHT Full Cream', 'total_usage' => 74600, 'satuan' => 'ML'],
                ['sku' => 'PKG001', 'nama_item' => 'Cup Plastik 16 Oz', 'total_usage' => 700, 'satuan' => 'PC'],
            ]
        ], 200);
    }

    /**
     * M09: Stock Opname input stub (auto-calculate demo)
     */
    public function submitStockOpname(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Stock opname berhasil disimpan.',
            'data' => [
                'no_dokumen' => 'PI-' . date('Ymd') . '-001',
                'status'     => $request->input('status', 'posted'),
                'tanggal'    => $request->input('tanggal', date('Y-m-d')),
            ]
        ], 201);
    }

    /**
     * M10: Stock Variance Analysis stub (angka 100% cocok dengan Proto Variance)
     */
    public function getVariance(): JsonResponse
    {
        $data = [
            ['sku' => 'DRY001', 'nama_item' => 'Biji Kopi House Blend', 'satuan' => 'G', 'begin_stock' => 6000, 'receive' => 8000, 'waste' => 40, 'usage' => 11520, 'std_end_stock' => 2440, 'pi_count' => 2350, 'variance' => -90, 'persen_variance' => -3.69, 'status' => 'Low Var', 'doi_hari' => 1.4],
            ['sku' => 'DRY002', 'nama_item' => 'Bubuk Cokelat', 'satuan' => 'G', 'begin_stock' => 1500, 'receive' => 2000, 'waste' => 0, 'usage' => 2400, 'std_end_stock' => 1100, 'pi_count' => 1080, 'variance' => -20, 'persen_variance' => -1.82, 'status' => 'Low Var', 'doi_hari' => 3.2],
            ['sku' => 'DRY003', 'nama_item' => 'Sirup Caramel', 'satuan' => 'ML', 'begin_stock' => 1000, 'receive' => 1500, 'waste' => 0, 'usage' => 1800, 'std_end_stock' => 700, 'pi_count' => 700, 'variance' => 0, 'persen_variance' => 0.00, 'status' => 'No Var', 'doi_hari' => 2.7],
            ['sku' => 'DRY004', 'nama_item' => 'Gula Aren Cair', 'satuan' => 'ML', 'begin_stock' => 2000, 'receive' => 4000, 'waste' => 100, 'usage' => 5000, 'std_end_stock' => 900, 'pi_count' => 700, 'variance' => -200, 'persen_variance' => -22.22, 'status' => 'High Var', 'doi_hari' => 1.0],
            ['sku' => 'FRS001', 'nama_item' => 'Susu UHT Full Cream', 'satuan' => 'ML', 'begin_stock' => 20000, 'receive' => 60000, 'waste' => 600, 'usage' => 74600, 'std_end_stock' => 4800, 'pi_count' => 4200, 'variance' => -600, 'persen_variance' => -12.50, 'status' => 'Medium Var', 'doi_hari' => 0.4],
            ['sku' => 'FRS002', 'nama_item' => 'Roti Tawar', 'satuan' => 'PC', 'begin_stock' => 40, 'receive' => 60, 'waste' => 4, 'usage' => 80, 'std_end_stock' => 16, 'pi_count' => 16, 'variance' => 0, 'persen_variance' => 0.00, 'status' => 'No Var', 'doi_hari' => 1.4],
            ['sku' => 'FRS003', 'nama_item' => 'Mentega', 'satuan' => 'G', 'begin_stock' => 500, 'receive' => 500, 'waste' => 0, 'usage' => 400, 'std_end_stock' => 600, 'pi_count' => 590, 'variance' => -10, 'persen_variance' => -1.67, 'status' => 'Low Var', 'doi_hari' => 10.3],
            ['sku' => 'PKG001', 'nama_item' => 'Cup Plastik 16 Oz', 'satuan' => 'PC', 'begin_stock' => 300, 'receive' => 500, 'trf_in' => 100, 'waste' => 5, 'usage' => 700, 'std_end_stock' => 195, 'pi_count' => 190, 'variance' => -5, 'persen_variance' => -2.56, 'status' => 'Low Var', 'doi_hari' => 1.9],
            ['sku' => 'PKG002', 'nama_item' => 'Tutup Cup Dome', 'satuan' => 'PC', 'begin_stock' => 300, 'receive' => 500, 'waste' => 0, 'usage' => 700, 'std_end_stock' => 100, 'pi_count' => 96, 'variance' => -4, 'persen_variance' => -4.00, 'status' => 'Low Var', 'doi_hari' => 1.0],
            ['sku' => 'PKG003', 'nama_item' => 'Sedotan', 'satuan' => 'PC', 'begin_stock' => 400, 'receive' => 500, 'trf_out' => 50, 'waste' => 0, 'usage' => 620, 'std_end_stock' => 230, 'pi_count' => 215, 'variance' => -15, 'persen_variance' => -6.52, 'status' => 'Medium Var', 'doi_hari' => 2.4],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Laporan analisis stock variance periode 2026-11 berhasil dihitung.',
            'data'    => $data,
        ], 200);
    }

    /**
     * M11: Low stock alerts stub
     */
    public function getStockAlerts(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                ['sku' => 'DRY004', 'nama_item' => 'Gula Aren Cair', 'sisa_stok' => 700, 'stok_minimum' => 1500, 'satuan' => 'ML', 'status' => 'KRITIS'],
                ['sku' => 'FRS001', 'nama_item' => 'Susu UHT Full Cream', 'sisa_stok' => 4200, 'stok_minimum' => 5000, 'satuan' => 'ML', 'status' => 'MENIPIS'],
            ]
        ], 200);
    }

    /**
     * M12: Dashboard Summary stub
     */
    public function getDashboardSummary(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_item_sku'      => 14,
                'transaksi_penerimaan'=> 28,
                'total_stok_ecer'     => 4850,
                'stok_kritis_count'   => 2,
                'persen_terpakai'     => 32,
                'persen_sisa'         => 68,
                'top_usage'           => [
                    ['nama' => 'Susu UHT Full Cream', 'qty' => '74.600 ML'],
                    ['nama' => 'Biji Kopi House Blend', 'qty' => '11.520 G'],
                    ['nama' => 'Gula Aren Cair', 'qty' => '5.000 ML'],
                ]
            ]
        ], 200);
    }
}
