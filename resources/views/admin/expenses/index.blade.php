@extends('layouts.admin')

@section('title', 'Laporan Pengeluaran')
@section('page-title', 'Data Pengeluaran Operasional')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0 text-white"><i class="bi bi-plus-circle"></i> Catat Baru</h5>
            </div>
            <div class="card-body mt-3">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Pengeluaran</label>
                        <input type="text" name="description" class="form-control" placeholder="Contoh: Beli Deterjen, Listrik..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nominal (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="amount" class="form-control" placeholder="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Bayar</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan (Opsional)</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Detail tambahan..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2">
                        <i class="bi bi-save"></i> SIMPAN PENGELUARAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header">
                <h4>Riwayat Pengeluaran</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle"></i> {{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover" id="table1">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th class="text-end">Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenses as $item)
                            <tr>
                                <td style="width: 15%">{{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $item->description }}</span>
                                    
                                    <small class="text-primary d-block mt-1">
                                        <i class="bi bi-person"></i> {{ $item->user->name ?? 'Admin' }}
                                    </small>

                                    @if($item->note)
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">Note: {{ $item->note }}</small>
                                    @endif
                                </td>
                                <td class="text-end text-danger fw-bold">Rp {{ number_format($item->amount) }}</td>
                                <td style="width: 10%">
                                    <form action="{{ route('expenses.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada data pengeluaran.</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">TOTAL PENGELUARAN</td>
                                <td class="text-end fw-bold text-danger fs-6">Rp {{ number_format($expenses->sum('amount')) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection