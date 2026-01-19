@extends('layouts.admin')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Pendapatan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center no-print">
        <h5 class="mb-0">Filter Periode</h5>
        <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Laporan</button>
    </div>
    <div class="card-body">
        
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3 mb-4 no-print">
            <div class="col-auto">
                <label class="col-form-label">Dari Tanggal</label>
            </div>
            <div class="col-auto">
                <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
            </div>
            <div class="col-auto">
                <label class="col-form-label">Sampai</label>
            </div>
            <div class="col-auto">
                <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-info">Tampilkan</button>
            </div>
        </form>

        <div id="printableArea">
            <div class="text-center mb-4">
                <h3>Laporan Pendapatan LaundryKuy</h3>
                <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                        <td>{{ $item->invoice_code }}</td>
                        <td>{{ $item->customer->name }}</td>
                        <td class="text-end">Rp {{ number_format($item->total_price) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data transaksi lunas pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-light fw-bold fs-5">
                        <td colspan="4" class="text-end">TOTAL PENDAPATAN</td>
                        <td class="text-end">Rp {{ number_format($totalOmzet) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-5 text-end d-none d-print-block">
                <p>Bandung, {{ date('d M Y') }}</p>
                <br><br><br>
                <p>( Owner Laundry )</p>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .sidebar, header, footer { display: none !important; }
        .card { border: none !important; shadow: none !important; }
        .d-print-block { display: block !important; }
    }
</style>
@endsection