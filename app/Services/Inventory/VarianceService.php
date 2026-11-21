<?php

namespace App\Services\Inventory;

use App\Models\Item;
use App\Models\BeginStock;
use App\Models\PenerimaanDetail;
use App\Models\TransferDetail;
use App\Models\Waste;
use App\Models\PenjualanDetail;
use App\Models\StockOpnameDetail;
use Illuminate\Support\Facades\DB;

class VarianceService
{
    /**
     * Hitung analisis variance inventaris per outlet dan periode.
     *
     * @param int $outletId
     * @param string $periode (format YYYY-MM)
     * @param int $jumlahHariPeriode (default 7 hari)
     * @return array
     */
    public function hitungVariance(int $outletId = 1, string $periode = '2026-11', int $jumlahHariPeriode = 7): array
    {
        $items = Item::where('is_active', true)->orderBy('sku')->get();
        $results = [];

        foreach ($items as $item) {
            // 1. Begin Stock
            $beginStock = (float) (BeginStock::where('outlet_id', $outletId)
                ->where('item_id', $item->id)
                ->where('periode', $periode)
                ->value('qty_ecer') ?? 0);

            // 2. Receive (Penerimaan)
            $receive = (float) (PenerimaanDetail::whereHas('penerimaan', function ($q) use ($outletId) {
                $q->where('outlet_id', $outletId)->where('status', 'posted');
            })->where('item_id', $item->id)->sum('total_ecer') ?? 0);

            // 3. Transfer In
            $trfIn = (float) (TransferDetail::whereHas('transfer', function ($q) use ($outletId) {
                $q->where('outlet_tujuan_id', $outletId)->where('status', 'posted');
            })->where('item_id', $item->id)->sum('qty_ecer') ?? 0);

            // 4. Transfer Out
            $trfOut = (float) (TransferDetail::whereHas('transfer', function ($q) use ($outletId) {
                $q->where('outlet_asal_id', $outletId)->where('status', 'posted');
            })->where('item_id', $item->id)->sum('qty_ecer') ?? 0);

            // 5. Waste
            $waste = (float) (Waste::where('outlet_id', $outletId)
                ->where('item_id', $item->id)
                ->sum('qty_ecer') ?? 0);

            // 6. Usage = SUM(qty_terjual_menu * qty_per_porsi_bom)
            $usage = (float) (PenjualanDetail::whereHas('penjualan', function ($q) use ($outletId) {
                $q->where('outlet_id', $outletId)->where('status', 'posted');
            })->join('t_bom', 't_penjualan_detail.menu_id', '=', 't_bom.menu_id')
              ->where('t_bom.item_id', $item->id)
              ->sum(DB::raw('t_penjualan_detail.qty * t_bom.qty_per_porsi')) ?? 0);

            // 7. Std. End Stock = MAKS(0; Begin + Receive + TrfIn - TrfOut - Waste - Usage)
            $kalkulasiStdEnd = $beginStock + $receive + $trfIn - $trfOut - $waste - $usage;
            $stdEndStock = max(0, $kalkulasiStdEnd);

            // 8. PI Count (Hasil Stock Opname terakhir)
            $piCount = (float) (StockOpnameDetail::whereHas('stockOpname', function ($q) use ($outletId) {
                $q->where('outlet_id', $outletId)->where('status', 'posted');
            })->where('item_id', $item->id)->latest('id')->value('total_ecer') ?? 0);

            // 9. Variance & % Variance
            $variance = $piCount - $stdEndStock;
            $persenVariance = $stdEndStock > 0 ? ($variance / $stdEndStock) * 100 : 0;
            $absPersen = abs($persenVariance);

            // 10. Klasifikasi Variance
            if ($absPersen == 0) {
                $status = 'No Var';
            } elseif ($absPersen <= 5.0) {
                $status = 'Low Var';
            } elseif ($absPersen <= 20.0) {
                $status = 'Medium Var';
            } else {
                $status = 'High Var';
            }

            // 11. Days of Inventory (DOI) = PI Count / Rata-rata Usage Harian
            $rataUsageHarian = $jumlahHariPeriode > 0 ? ($usage / $jumlahHariPeriode) : 0;
            $doi = $rataUsageHarian > 0 ? ($piCount / $rataUsageHarian) : 0;

            $results[] = [
                'sku'             => $item->sku,
                'nama_item'       => $item->nama_item,
                'satuan'          => $item->satuan_ecer,
                'begin_stock'     => $beginStock,
                'receive'         => $receive,
                'trf_in'          => $trfIn,
                'trf_out'         => $trfOut,
                'waste'           => $waste,
                'usage'           => $usage,
                'std_end_stock'   => $stdEndStock,
                'pi_count'        => $piCount,
                'variance'        => $variance,
                'persen_variance' => round($persenVariance, 2),
                'status'          => $status,
                'doi_hari'        => round($doi, 1),
            ];
        }

        return $results;
    }
}
