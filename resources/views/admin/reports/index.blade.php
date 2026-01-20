@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Pendapatan')

@section('content')

<div class="row mb-4 no-print">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon green">
                            <i class="iconly-boldWallet"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                        <h5 class="font-extrabold mb-0">Rp {{ number_format($totalOmzet) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body px-3 py-4-5">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stats-icon blue">
                            <i class="iconly-boldBuy"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h6 class="text-muted font-semibold">Total Transaksi</h6>
                        <h5 class="font-extrabold mb-0">{{ $transactions->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom no-print">
        <h5 class="mb-0 text-primary"><i class="bi bi-funnel-fill"></i> Filter Data</h5>
        
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="bi bi-printer-fill"></i> Cetak PDF
            </button>
            <a href="{{ route('reports.export') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="card-body pt-4">
        
        <form action="{{ route('reports.index') }}" method="GET" class="mb-5 no-print p-3 bg-light rounded">
            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan Laporan
                    </button>
                </div>
            </div>
        </form>

        <div id="printableArea">
            
            <div class="text-center mb-5 d-none d-print-block">
                @php $setting = \App\Models\Setting::first(); @endphp
                <h2 class="fw-bold">{{ strtoupper($setting->shop_name ?? 'LAUNDRYKUY') }}</h2>
                <p>{{ $setting->address ?? '' }} | WA: {{ $setting->phone ?? '' }}</p>
                <hr style="border: 2px solid black;">
                
                <h4 class="mt-4">LAPORAN PENDAPATAN</h4>
                <p class="text-muted">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 15%;">Tanggal</th>
                            <th style="width: 20%;">Invoice</th>
                            <th>Pelanggan</th>
                            <th class="text-end" style="width: 20%;">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $item->invoice_code }}</span></td>
                            <td>{{ $item->customer->name }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->total_price) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox-fill fs-1 d-block mb-2"></i>
                                Tidak ada data transaksi lunas pada periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr class="fw-bold fs-5">
                            <td colspan="4" class="text-end text-uppercase">Total Pendapatan</td>
                            <td class="text-end text-success">Rp {{ number_format($totalOmzet) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-5 text-end d-none d-print-block">
                <p>Bandung, {{ date('d F Y') }}</p>
                <br><br><br>
                <p class="fw-bold text-decoration-underline">( Owner / Manajer )</p>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        /* Sembunyikan elemen dashboard */
        .no-print, .sidebar, #sidebar, header, .burger-btn, footer { 
            display: none !important; 
        }
        
        /* Reset margin body biar full page */
        body, #main, #app { 
            margin: 0 !important; 
            padding: 0 !important; 
            background: white !important; 
            width: 100% !important;
        }

        /* Tampilkan elemen khusus print */
        .d-print-block { 
            display: block !important; 
        }

        /* Styling tabel pas print biar tajam */
        .table-primary { 
            background-color: #ddd !important; 
            color: black !important; 
            -webkit-print-color-adjust: exact; 
        }
        
        .badge {
            border: none !important;
            color: black !important;
        }
    }
</style>
@endsection