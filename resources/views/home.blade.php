@extends('layouts.template')

@section('content')

<div class="jumbotron py-4 px-5">
    @csrf
        @if(Session::get('alreadyIn'))
            <div class="alert alert-danger">{{ Session::get('alreadyIn') }}</div>
        @endif
    <h1 class="display-4">
        Selamat Datang ! 
        {{ Auth::user()->name }}
    </h1>
    <hr class="my-4">
    <p>Aplikasi ini digunakan hanya oleh pegawai administrator APOTEK. Digunakan untuk mengelola data obat, penyetokan, juga pembelian (kasir).</p>
</div>
@endsection