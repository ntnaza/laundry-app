@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Pendapatan')

@section('content')

{{-- STATISTIK RINGKAS --}}
{{-- <div class="row g-3 mb-4 no-print">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-soft h-100 rounded-4 card-hover">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="stats-icon flex-shrink-0 rounded-3 box-center bg-light-success text-success shadow-sm" style="width: 48px; height: 48px;">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted fw-bold mb-0 text-uppercase ls-1" style="font-size: 0.65rem;">Total Pendapatan</h6>
                    <h5 class="fw-heading mb-0 text-dark" style="font-size: 1.1rem;">Rp {{ number_format($totalOmzet) }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card border-0 shadow-soft h-100 rounded-4 card-hover">
            <div class="card-body p-3 d-flex align-items-center">
                <div class="stats-icon flex-shrink-0 rounded-3 box-center bg-light-primary text-primary shadow-sm" style="width: 48px; height: 48px;">
                    <i class="bi bi-receipt fs-5"></i>
                </div>
                <div class="ms-3">
                    <h6 class="text-muted fw-bold mb-0 text-uppercase ls-1" style="font-size: 0.65rem;">Total Transaksi</h6>
                    <h5 class="fw-heading mb-0 text-dark" style="font-size: 1.1rem;">{{ $transactions->count() }} <small class="text-muted fs-6 fw-normal">Order</small></h5>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="card border-0 shadow-soft rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3 no-print">
        <div>
            <h5 class="fw-heading mb-1">Filter Laporan</h5>
            <p class="text-muted small mb-0">Pilih rentang tanggal untuk menampilkan data.</p>
        </div>
        
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-light-secondary text-secondary rounded-pill px-3 fw-bold small transition-300">
                <i class="bi bi-printer-fill me-1"></i> Cetak PDF
            </button>
            <a href="{{ route('reports.export') }}" class="btn btn-light-success text-success rounded-pill px-3 fw-bold small transition-300">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card-body p-4">
        {{-- FORM FILTER --}}
        <form action="{{ route('reports.index') }}" method="GET" class="no-print mb-4 p-4 bg-light rounded-4 border border-light-subtle">
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
                    <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-bold shadow-sm hover-top transition-300">
                        <i class="bi bi-funnel-fill me-1"></i> Tampilkan Laporan
                    </button>
                </div>
            </div>
        </form>

        {{-- AREA PRINTABLE --}}
        <div id="printableArea">
            <div class="text-center mb-5 d-none d-print-block">
                @php $setting = \App\Models\Setting::first(); @endphp
                <h2 class="fw-heading mb-1">{{ strtoupper($setting->shop_name ?? 'LAUNDRYKUY') }}</h2>
                <p class="text-muted small mb-1">{{ $setting->address ?? '' }} | WA: {{ $setting->phone ?? '' }}</p>
                <hr style="border-top: 2px solid #000; opacity: 1;">
                
                <h4 class="mt-4 fw-bold">LAPORAN PENDAPATAN</h4>
                <p class="text-muted small">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>

            <div class="table-responsive rounded-4 border border-light-subtle overflow-hidden">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-center" style="width: 5%;">No</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1" style="width: 15%;">Tanggal</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1" style="width: 20%;">Invoice</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Pelanggan</th>
                            <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end" style="width: 20%;">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $item)
                        <tr class="transition-300 border-bottom border-light-subtle">
                            <td class="ps-4 py-3 text-center text-secondary small">{{ $index + 1 }}</td>
                            <td class="py-3 text-secondary small">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="py-3">
                                <span class="badge bg-light-primary text-primary border-0 rounded-pill px-3 py-1 fw-bold font-monospace">
                                    {{ $item->invoice_code }}
                                </span>
                            </td>
                            <td class="py-3 fw-bold text-dark">{{ $item->customer->name }}</td>
                            <td class="pe-4 py-3 text-end fw-heading text-primary">
                                {{ number_format($item->total_price) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center p-5">
                                    <div class="bg-light rounded-circle box-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                                    </div>
                                    <h6 class="text-muted fw-bold">Tidak ada transaksi.</h6>
                                    <p class="text-muted small mb-0">Tidak ditemukan transaksi lunas pada periode ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-light-primary bg-opacity-10 border-top border-primary border-opacity-10">
                        <tr>
                            <td colspan="4" class="ps-4 py-3 text-end fw-bold text-uppercase small text-primary">Total Pendapatan</td>
                            <td class="pe-4 py-3 text-end fw-heading text-primary fs-5">
                                Rp {{ number_format($totalOmzet) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- TANDA TANGAN PAS PRINT --}}
            <div class="mt-5 text-end d-none d-print-block">
                <p class="small mb-5">Dicetak pada: {{ date('d F Y, H:i') }}</p>
                <div style="margin-top: 80px;">
                    <p class="fw-bold mb-0 text-dark">( ____________________ )</p>
                    <p class="small text-muted">Owner / Manajer</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 0.5px; }
    .hover-top:hover { transform: translateY(-3px); }
    .btn-light-secondary { background-color: #f8fafc; border: 1px solid #e2e8f0; }
    .btn-light-secondary:hover { background-color: #e2e8f0; }
    .btn-light-success { background-color: #f0fdf4; border: 1px solid #dcfce7; }
    .btn-light-success:hover { background-color: #dcfce7; }

    @media print {
        .no-print { display: none !important; }
        body, #main, #app { 
            margin: 0 !important; 
            padding: 0 !important; 
            background: white !important; 
            width: 100% !important;
        }
        .d-print-block { display: block !important; }
        .card { border: none !important; box-shadow: none !important; }
        .table { border-collapse: collapse !important; }
        .table th, .table td { border: 1px solid #dee2e6 !important; }
        .bg-light-primary { background-color: transparent !important; color: black !important; }
        .badge { border: 1px solid #000 !important; color: black !important; background: transparent !important; }
    }
</style>
@endsection