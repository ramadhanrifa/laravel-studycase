@extends('layouts.template');
@section('content')

    <div class="container mt-3">
        <form action="{{ route('kasir.order.store') }}" class="card m-auto p-5" method="POST">
            @csrf
            {{-- validate error message --}}
            @if ($errors->any())
            <ul class="alert alert-danger p-3">
                @foreach ($errors -> all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <p>Penanggung Jawab : <b>{{ Auth::user()->name }}</b></p>
            <div class="mb-3 row">
                <label for="name_customer" class="col-sm-2 col-form-label">Nama Pembeli</label>
                <div class="col-sm-10">
                    <input type="text" name="name_customer" id="name_customer" class="form-control">
                </div>
            </div>
            <div class="mb-3 row"> 
                <label for="medicines" class="col-sm-2 col-form-label">Obat</label>
                <div class="col-sm-10">
                    {{-- name akan dibuat array karena nantinya data obat (medicines) akan berbentuk array --}}
                    <select name="medicines[]" id="medicines" class="form-select">
                        <option selected hidden disabled>Pesanan 1</option>
                        @foreach ($medicines as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                    {{-- div pembungkus untuk tambahana yang akan muncul --}}
                    <div id="wrap-medicines"></div>
                    <br>
                    <p class="text-primary" id="add-select" style="cursor: pointer">Tambah Obat</p>
                </div>
            </div>
            <button type="submit" class="btn btn-block btn-lg btn-primary">Konfirmasi Pembelian</button>
        </form>
    </div>
@endsection

@push('script')
    <script>
        let no = 2;
        // ketika tag dengan id add-select di click jalankan func berikut
        $("#add-select").on("click", function() {
            // tag html yang akan di tambahkan
            let el = `<br> <select name="medicines[]" id="medicines" class="form-select">
                        <option selected hidden disabled>Pesanan ${no}</option>
                        @foreach ($medicines as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select> `;
            // append : tambahkan elemen html dibagian (sebelum penutup tag yang id nya wrap-medicines)
            $("#wrap-medicines").append(el);
            
            no++
        })
    </script>
@endpush