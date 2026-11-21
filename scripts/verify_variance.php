<?php
/**
 * Script Verifikasi Rumus Bisnis Bagian D
 * Menguji perhitungan matematis menggunakan data dari seeder
 */

$sales = [
    'MNU001' => 120, // Americano
    'MNU002' => 150, // Latte
    'MNU003' => 90,  // Caramel Latte
    'MNU004' => 200, // Es Kopi Susu Aren
    'MNU005' => 80,  // Cappuccino
    'MNU006' => 60,  // Chocolate
    'MNU007' => 40,  // Roti Bakar Cokelat
];

$bom = [
    'MNU001' => ['DRY001' => 18, 'PKG001' => 1, 'PKG002' => 1, 'PKG003' => 1],
    'MNU002' => ['DRY001' => 18, 'FRS001' => 150, 'PKG001' => 1, 'PKG002' => 1, 'PKG003' => 1],
    'MNU003' => ['DRY001' => 18, 'FRS001' => 150, 'DRY003' => 20, 'PKG001' => 1, 'PKG002' => 1, 'PKG003' => 1],
    'MNU004' => ['DRY001' => 18, 'FRS001' => 100, 'DRY004' => 25, 'PKG001' => 1, 'PKG002' => 1, 'PKG003' => 1],
    'MNU005' => ['DRY001' => 18, 'FRS001' => 120, 'PKG001' => 1, 'PKG002' => 1],
    'MNU006' => ['DRY002' => 30, 'FRS001' => 150, 'PKG001' => 1, 'PKG002' => 1, 'PKG003' => 1],
    'MNU007' => ['FRS002' => 2, 'FRS003' => 10, 'DRY002' => 15],
];

// Hitung Usage per SKU
$usage = [];
foreach ($sales as $menu => $qtySold) {
    if (isset($bom[$menu])) {
        foreach ($bom[$menu] as $sku => $qtyPerPortion) {
            $usage[$sku] = ($usage[$sku] ?? 0) + ($qtySold * $qtyPerPortion);
        }
    }
}

// Data Transaksi
$data = [
    'DRY001' => ['begin' => 6000, 'rcv' => 8000, 'trfIn' => 0,   'trfOut' => 0,  'waste' => 40,  'pi' => 2350],
    'DRY002' => ['begin' => 1500, 'rcv' => 2000, 'trfIn' => 0,   'trfOut' => 0,  'waste' => 0,   'pi' => 1080],
    'DRY003' => ['begin' => 1000, 'rcv' => 1500, 'trfIn' => 0,   'trfOut' => 0,  'waste' => 0,   'pi' => 700],
    'DRY004' => ['begin' => 2000, 'rcv' => 4000, 'trfIn' => 0,   'trfOut' => 0,  'waste' => 100, 'pi' => 700],
    'FRS001' => ['begin' => 20000,'rcv' => 60000,'trfIn' => 0,   'trfOut' => 0,  'waste' => 600, 'pi' => 4200],
    'FRS002' => ['begin' => 40,   'rcv' => 60,   'trfIn' => 0,   'trfOut' => 0,  'waste' => 4,   'pi' => 16],
    'FRS003' => ['begin' => 500,  'rcv' => 500,  'trfIn' => 0,   'trfOut' => 0,  'waste' => 0,   'pi' => 590],
    'PKG001' => ['begin' => 300,  'rcv' => 500,  'trfIn' => 100, 'trfOut' => 0,  'waste' => 5,   'pi' => 190],
    'PKG002' => ['begin' => 300,  'rcv' => 500,  'trfIn' => 0,   'trfOut' => 0,  'waste' => 0,   'pi' => 96],
    'PKG003' => ['begin' => 400,  'rcv' => 500,  'trfIn' => 0,   'trfOut' => 50, 'waste' => 0,   'pi' => 215],
];

echo str_pad("SKU", 8) . str_pad("Usage", 10) . str_pad("Std.End", 10) . str_pad("PI", 10) . str_pad("Var", 8) . str_pad("%Var", 10) . "Status\n";
echo str_repeat("-", 66) . "\n";

foreach ($data as $sku => $val) {
    $u = $usage[$sku] ?? 0;
    $stdEnd = max(0, $val['begin'] + $val['rcv'] + $val['trfIn'] - $val['trfOut'] - $val['waste'] - $u);
    $var = $val['pi'] - $stdEnd;
    $pct = $stdEnd > 0 ? ($var / $stdEnd) * 100 : 0;
    $abs = abs($pct);

    if ($abs == 0) $status = 'No Var';
    elseif ($abs <= 5.0) $status = 'Low Var';
    elseif ($abs <= 20.0) $status = 'Medium Var';
    else $status = 'High Var';

    printf("%-8s %-10.0f %-10.0f %-10.0f %-8.0f %-9.2f%% %s\n", $sku, $u, $stdEnd, $val['pi'], $var, $pct, $status);
}
