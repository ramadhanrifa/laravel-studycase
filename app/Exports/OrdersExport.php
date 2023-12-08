<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concers\WithHeadings;
use Maatwebsite\Excel\Concers\WithMapping;
use Excel;

class OrdersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with('user')->get();
    }
    // headings: nama-nama th dari file excel
    public function headings():array
    {
        return [
            "Nama Pembeli", "Obat", "Total bayar", "Kasir", "Tanggal"
        ];
    }
    // map : data yang akan dimunculkan di excelnya (sama kaya foreach di blade)
    public function map($item): array
    {
        $dataObat = '';
        foreach ($item->medicines as $value){
            $format = $value["name_medicine"] . "(qty" . $value['qty']. " : Rp." . number_format($value['sub_price']) . "), ";
            $dataObat .= $format;
        }
        return [
            $item->name_customer,
            $dataObat,
            $item->total_price,
            $item->user->name,
            \Carbon\Carbon::parse($item->created_at)->isoFormat($item->created_at),
        ];
    }
}
