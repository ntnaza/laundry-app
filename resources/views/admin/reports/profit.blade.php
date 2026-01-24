@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi')
@section('page-title', 'Profit & Loss')

@section('content')
{{-- FILTER TANGGAL --}}
<div class="card border-0 shadow-soft rounded-4 mb-4 overflow-hidden no-print">
    <div class="card-body p-4">
        <form action="{{ route('reports.profit') }}" method="GET">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Dari Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-light shadow-sm text-muted"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" name="start_date" class="form-control border-light shadow-sm bg-white" value="{{ $startDate }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase ls-1">Sampai Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-light shadow-sm text-muted"><i class="bi bi-calendar-check"></i></span>
                        <input type="date" name="end_date" class="form-control border-light shadow-sm bg-white" value="{{ $endDate }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm hover-top transition-300">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                        <button type="button" onclick="window.print()" class="btn btn-light w-100 rounded-pill py-2 fw-bold shadow-sm hover-top transition-300">
                            <i class="bi bi-printer me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="printableArea">
    {{-- HEADER CETAK --}}
    <div class="text-center mb-5 d-none d-print-block">
        <h3 class="fw-bold">LAPORAN LABA RUGI</h3>
        <p class="text-muted">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <hr class="border-dark opacity-100">
    </div>

    {{-- KARTU STATISTIK UTAMA --}}
    <div class="row g-4 mb-4">
        {{-- PENDAPATAN --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-soft h-100 rounded-4 bg-success text-white overflow-hidden position-relative">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-circle box-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-arrow-down-left fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ms-3 text-uppercase ls-1 opacity-75">Total Pendapatan</h6>
                    </div>
                    <h3 class="fw-heading mb-0">Rp {{ number_format($income) }}</h3>
                </div>
                {{-- Dekorasi --}}
                <div class="position-absolute opacity-10" style="bottom: 0; right: 15px; pointer-events: none;">
                    <i class="bi bi-wallet2" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>

        {{-- PENGELUARAN --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-soft h-100 rounded-4 bg-danger text-white overflow-hidden position-relative">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-circle box-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-arrow-up-right fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ms-3 text-uppercase ls-1 opacity-75">Total Pengeluaran</h6>
                    </div>
                    <h3 class="fw-heading mb-0">Rp {{ number_format($expense) }}</h3>
                </div>
                {{-- Dekorasi --}}
                <div class="position-absolute opacity-10" style="bottom: 0; right: 15px; pointer-events: none;">
                    <i class="bi bi-cart-x" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>

        {{-- LABA BERSIH --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-soft h-100 rounded-4 {{ $netProfit >= 0 ? 'bg-primary' : 'bg-secondary' }} text-white overflow-hidden position-relative">
                <div class="card-body p-4 position-relative z-1">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 rounded-circle box-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-piggy-bank fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0 ms-3 text-uppercase ls-1 opacity-75">Laba Bersih</h6>
                    </div>
                    <h3 class="fw-heading mb-0">Rp {{ number_format($netProfit) }}</h3>
                    <small class="opacity-75 mt-2 d-block">
                        {{ $netProfit >= 0 ? 'Keuntungan (Profit)' : 'Kerugian (Loss)' }}
                    </small>
                </div>
                {{-- Dekorasi --}}
                <div class="position-absolute opacity-10" style="bottom: 0; right: 15px; pointer-events: none;">
                    <i class="bi bi-graph-up-arrow" style="font-size: 6rem;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- RINCIAN --}}
    <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
            <h5 class="fw-heading mb-0">Rincian Keuangan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Keterangan</th>
                            <th class="text-end pe-4 py-3">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-success"><i class="bi bi-plus-circle me-2"></i> Pemasukan Laundry</span>
                                <small class="d-block text-muted ms-4">Total tagihan transaksi lunas</small>
                            </td>
                            <td class="pe-4 py-3 text-end fw-bold text-dark">
                                Rp {{ number_format($income) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-4 py-3">
                                <span class="fw-bold text-danger"><i class="bi bi-dash-circle me-2"></i> Biaya Operasional</span>
                                <small class="d-block text-muted ms-4">Listrik, deterjen, gaji, dll.</small>
                            </td>
                            <td class="pe-4 py-3 text-end fw-bold text-danger">
                                - Rp {{ number_format($expense) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-light-primary bg-opacity-10">
                        <tr>
                            <td class="ps-4 py-3 fw-bold text-uppercase ls-1">Net Profit (Laba Bersih)</td>
                            <td class="pe-4 py-3 text-end fw-heading fs-4 {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }}">
                                Rp {{ number_format($netProfit) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    @media print {
        .no-print, #sidebar, header { display: none !important; }
        body, #main { margin: 0 !important; padding: 0 !important; background: white !important; width: 100% !important; }
        .d-print-block { display: block !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; }
        .bg-success, .bg-danger, .bg-primary, .bg-secondary { background-color: white !important; color: black !important; border: 1px solid #000 !important; }
        .text-white { color: black !important; }
    }
</style>
@endsection