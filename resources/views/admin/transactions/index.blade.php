@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Data Transaksi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>List Cucian Masuk</h4>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">+ Transaksi Baru</a>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Pelanggan</th>
                    <th>Total Biaya</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                <tr>
                    <td>{{ $t->invoice_code }}<br><small class="text-muted">{{ $t->created_at->format('d M Y') }}</small></td>
                    <td>{{ $t->customer->name }}</td>
                    <td>Rp {{ number_format($t->total_price) }}</td>
                    <td>
                        @if($t->status == 'pending') <span class="badge bg-secondary">Baru Masuk</span>
                        @elseif($t->status == 'process') <span class="badge bg-info">Dicuci</span>
                        @elseif($t->status == 'ready') <span class="badge bg-warning">Siap Ambil</span>
                        @elseif($t->status == 'done') <span class="badge bg-success">Selesai</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-sm btn-info">Detail & Cetak</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection