<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;
use Excel;
use App\Exports\OrdersExport;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // paginatationya

        if($request->has('date')){
            $searchDate = $request->input('date');

    // Query orders based on the date
    $orders = Order::whereDate('created_at', '=', $searchDate)
                    ->simplePaginate(10);

        } else{
            $orders = Order::simplePaginate(10);

        }


    return view('order.kasir.index', compact('orders'));

    }

    public function data()
    {
        $orders= Order::with('user')->simplePaginate(5);
        return view("order.admin.index", compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::all();
        return view("order.kasir.create", compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required',
        ]);
        // mencari jumlah item yangsama pada array contoh ["item" => "jumlah"]
        $arrayDistinct = array_count_values($request->medicines);
        // menyiapkan array kosog untuk menampung format array baru
        $arrayAssocMedicines = [];
        // looping hasil penghitungan item distinct (duplikat)
        // key akan berupa value dari input medicines(id), item array berupa jumlah perhitungan item duplikat
        foreach ($arrayDistinct as $id => $count){
            // mencari data obat berdasarkan id
            $medicine = Medicine::where('id', $id)->first();
            // ambil bagian column price dari hasil pencarian lalu kalikan dengan jumlah item duplikat
            // sehingga akan menghasilkan total harga
            $subPrice = $medicine['price'] * $count;
            // struktur value column medicines menjadi multidimensi dengan dimensi kedua berbentuk array assoc
            // dengan key "id", dll
            $arrayitem = [
                "id"=> $id,
                "name_medicine" => $medicine['name'],
                "qty" => $count,
                "price" => $medicine['price'],
                "sub_price" => $subPrice,
            ];

            // maasukan struktur array tersebut ke array kosong yang disediakan sebelumnya
            array_push($arrayAssocMedicines, $arrayitem);
        }
        // total harga pembelian dari obat-obat yang dipilih
        $totalPrice = 0;
        // looping format array medicines baru
        foreach($arrayAssocMedicines as $item){
            // total harga pembelian ditambahkan dari keseluruhan sub_price data medicines
            $totalPrice += (int)$item['sub_price'];
        }
        // harga beli ditambah 10% ppn
        $priceWithPPN = $totalPrice + ($totalPrice *0.01);
        // tambah data ke database
        $proses = Order::create([
            // data user_id diambil dari id akun kasir yang sedang login
            'user_id' => Auth::user()->id,
            'medicines'=> $arrayAssocMedicines,
            'name_customer'=> $request->name_customer,
            "total_price" => $priceWithPPN,
        ]);

        if ($proses) {
            // jika proses berhasil, ambil data order yang dibuat oleh kasir yang sedang login (where), dengan tanggal paling terbaru
            // (orderBy), ambil hanya satu data (first)
            $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            // kirim data order yang diambil td, bagian column id sebagai parameter path dari route print
            return redirect()->route('kasir.order.print', $order['id']);
        } else {
            // jika tidak berhasi, maka diarahkan kembali ke halam form dengan pesan pemberitahuan
            return redirect()->back()->with('failed', 'Gagal membuat data pembelian, Silahkan coba kembali dengan data yang sesuai!');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::find($id);
        return view('order.kasir.print', compact('order'));
    }

    public function downloadPDF($id)
    {
        // ambil data yang diperlukan dan formatkan ke array
        $order = Order::find($id)->toArray();
        // mengirim inisial variabel yang akan digunakan pada layout pdf
        view()->share('order', $order);
        // buat instansi class PDF
        // $pdf = new PDF();
        // panggil blade yang akan didownload
        $pdf = PDF::loadView('order.kasir.download-pdf', $order);
        // return bentuk pdf
        return $pdf->stream('receipt.pdf');
        // beda dari download() => langsung download
        // stream()=> diperlihatkan dulu file nya baru download
    }

    public function exportExcel()
    {
        $file_name = 'data_pembelian'. '.xlsx';

        return Excel::download(new OrdersExport, $file_name);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }


}
