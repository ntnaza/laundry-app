@extends('layouts.admin')

@section('title', 'Dashboard Owner')
@section('page-title', 'Overview Bisnis')

@section('content')
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon purple">
                            <i class="iconly-boldShow"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Pemasukan</h6>
                        <h6 class="font-extrabold mb-0">Rp {{ number_format($pemasukan) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon red">
                            <i class="iconly-boldBuy"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Pengeluaran</h6>
                        <h6 class="font-extrabold mb-0">Rp {{ number_format($pengeluaran) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon green">
                            <i class="iconly-boldWallet"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Profit Bersih</h6>
                        <h6 class="font-extrabold mb-0 text-success">Rp {{ number_format($profit) }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon blue">
                            <i class="iconly-boldProfile"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Total Order</h6>
                        <h6 class="font-extrabold mb-0">{{ $total_transaksi }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection