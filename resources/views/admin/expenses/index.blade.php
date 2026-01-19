@extends('layouts.admin')

@section('title', 'Laporan Pengeluaran')
@section('page-title', 'Data Pengeluaran Operasional')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>Riwayat Pengeluaran</h4>
        <a href="{{ route('expenses.create') }}" class="btn btn-danger">Catat Pengeluaran Baru</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Pelapor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $item)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                    <td>
                        {{ $item->description }}
                        <div class="text-muted small">{{ $item->note }}</div>
                    </td>
                    <td class="text-danger fw-bold">Rp {{ number_format($item->amount) }}</td>
                    <td>{{ $item->user->name ?? 'System' }}</td>
                    <td>
                        <form action="{{ route('expenses.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection