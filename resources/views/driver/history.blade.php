@extends('layouts.admin')

@section('title', 'Riwayat Tugas')
@section('page-title', 'Riwayat Pengiriman')

@section('content')
<div class="card border-0 shadow-soft">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-secondary small text-uppercase">Waktu</th>
                        <th class="text-secondary small text-uppercase">Pelanggan</th>
                        <th class="text-secondary small text-uppercase">Tipe</th>
                        <th class="text-secondary small text-uppercase">Tagihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td class="px-4">
                                <span class="fw-bold text-dark">{{ $task->updated_at->format('d/m/Y') }}</span><br>
                                <small class="text-muted">{{ $task->updated_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $task->customer->name }}</span><br>
                                <small class="text-muted">{{ $task->invoice_code }}</small>
                            </td>
                            <td>
                                @if($task->delivery_type == 'pickup')
                                    <span class="badge bg-warning text-dark border border-warning bg-opacity-10">Jemput</span>
                                @else
                                    <span class="badge bg-primary text-primary border border-primary bg-opacity-10">Antar</span>
                                @endif
                            </td>
                            <td>
                                @if($task->payment_status == 'paid')
                                    <span class="badge bg-success text-success border border-success bg-opacity-10">Lunas</span>
                                @else
                                    <span class="text-danger fw-bold">Rp {{ number_format($task->total_price - $task->paid_amount, 0, ',', '.') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <img src="{{ asset('assets/static/images/no-data.svg') }}" alt="No Data" style="width: 100px; opacity: 0.3">
                                <p class="text-muted mt-2 small">Belum ada riwayat tugas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-4 py-3 border-top">
        {{ $tasks->links() }}
    </div>
</div>
@endsection
