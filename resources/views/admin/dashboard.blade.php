@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Statistik')

@section('content')
<div class="page-content">
    
    <div class="row">
        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon purple">
                                <i class="iconly-boldShow"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Omset Hari Ini</h6>
                            <h5 class="font-extrabold mb-0">Rp {{ number_format($todayIncome) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon green">
                                <i class="iconly-boldWallet"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Saldo Masuk</h6>
                            <h5 class="font-extrabold mb-0">Rp {{ number_format($totalIncome) }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon blue">
                                <i class="iconly-boldBuy"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Total Transaksi</h6>
                            <h5 class="font-extrabold mb-0">{{ $totalTransactions }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3 col-md-6">
            <div class="card shadow-sm">
                <div class="card-body px-3 py-4-5">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-icon red">
                                <i class="iconly-boldProfile"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h6 class="text-muted font-semibold">Pelanggan</h6>
                            <h5 class="font-extrabold mb-0">{{ $totalCustomers }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>📊 Grafik Pendapatan (7 Hari Terakhir)</h4>
                </div>
                <div class="card-body">
                    <div id="chart-pendapatan"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Transaksi Terbaru</h4>
                </div>
                <div class="card-content pb-4">
                    @foreach($latestTransactions as $trx)
                    <div class="recent-message d-flex px-4 py-3">
                        <div class="avatar avatar-lg">
                            <div class="text-white bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ substr($trx->customer->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="name ms-4">
                            <h5 class="mb-1">{{ $trx->customer->name }}</h5>
                            <h6 class="text-muted mb-0">
                                {{ $trx->invoice_code }} - 
                                <span class="{{ $trx->payment_status == 'paid' ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format($trx->total_price) }}
                                </span>
                            </h6>
                        </div>
                    </div>
                    @endforeach
                    <div class="px-4">
                        <a href="{{ route('transactions.index') }}" class='btn btn-block btn-xl btn-light-primary font-bold mt-3'>Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>

<script>
    var options = {
        series: [{
            name: 'Pendapatan',
            data: @json($chartData) // Data dari Controller
        }],
        chart: {
            height: 350,
            type: 'area', // Bisa ganti 'bar' atau 'line'
            fontFamily: 'Nunito, sans-serif'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: @json($chartCategories), // Label Tanggal
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        },
        colors: ['#435ebe'], // Warna Biru Mazer
    };

    var chart = new ApexCharts(document.querySelector("#chart-pendapatan"), options);
    chart.render();
</script>
@endsection