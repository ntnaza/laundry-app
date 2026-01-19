@extends('layouts.admin')

@section('title', 'Profil Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-xl bg-primary text-white mb-3 fs-3">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <h4>{{ $customer->name }}</h4>
                <p class="text-muted">{{ $customer->phone }}</p>
                <hr>
                <div class="text-start">
                    <small class="text-muted">Alamat:</small>
                    <p>{{ $customer->address ?? '-' }}</p>
                    
                    <small class="text-muted">Bergabung sejak:</small>
                    <p>{{ $customer->created_at?->format('d M Y') ?? '-' }}</p>
                </div>
                <div class="d-grid gap-2">
                    <a href="https://wa.me/{{ $customer->phone }}" target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> Chat WA</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Riwayat Laundry</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Tgl</th>
                            <th>Invoice</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $t)
                        <tr>
                            <td>{{ $t->created_at->format('d/m/y') }}</td>
                            <td>{{ $t->invoice_code }}</td>
                            <td>Rp {{ number_format($t->total_price) }}</td>
                            <td>
                                @if($t->status == 'done') <span class="badge bg-success">Selesai</span>
                                @elseif($t->status == 'pending') <span class="badge bg-secondary">Pending</span>
                                @else <span class="badge bg-warning text-dark">{{ $t->status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-sm btn-light">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada riwayat transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection