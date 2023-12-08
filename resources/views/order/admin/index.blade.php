@extends('layouts.template')

@section('content')
    <div class="my-5 d-flex justify-content-end">
        <a href="{{ route('user.export-excel') }}" class="btn btn-primary">Export Data (excel)</a>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Pembeli</th>
                <th>Obat</th>
                <th>Kasir</th>
                <th>Tanggal Pembelian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody> 
            @foreach ($orders as $order)
            <tr>
                {{-- ,ema,pilkan angka urutan berdasarkan page pagination --}}
                <td>{{ ($orders->currentpage()-1) * $orders->perpage() + $loop->index +1 }} </td>
                <td> {{$order->name_customer}} </td>
                <td>
                    {{-- nested loop : looping di dalam looping --}}
                    {{-- karena column medicines berbentuk array json, maka untuk mengaksesnya perlu dilooping --}}
                    <ol>
                        @foreach ($order['medicines'] as $medicine )
                            <li>
                                {{-- hasil yang diinginkan --}}
                                {{$medicine['name_medicine']}}
                                (Rp. {{ number_format($medicine['price'],0, ',', '.' ) }} ) :
                                Rp. {{number_format($medicine['sub_price'], 0, ',', '.')}}
                                <small>qty {{$medicine['qty']}} </small>
                            </li>                            
                        @endforeach
                    </ol>
                </td>
                <td> {{$order['user']['name']}} </td>
                @php
                // setting lokal time sebagai wilayah indonesia
                    setlocale(LC_ALL, 'IND');
                @endphp
                <td> {{Carbon\Carbon::parse($order->created_at)->formatlocalized('%d %B %Y') }} </td>
                <td><a href="{{ route('kasir.order.download', $order['id']) }}" class="btn btn-secondary">Unduh (.pdf)</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection