@extends('layouts.admin')

@section('title', 'Tugas Saya')
@section('page-title', 'Tugas Pengiriman')

@section('content')
<div class="row">
    @forelse($tasks as $task)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-soft border-0 mb-3 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            {{-- LOGIKA BADGE PINTAR --}}
                            @if($task->delivery_type == 'pickup' || ($task->delivery_type == 'both' && $task->status == 'pending'))
                                <span class="badge bg-warning text-dark mb-2"><i class="bi bi-box-seam me-1"></i> TUGAS JEMPUT</span>
                            @elseif($task->delivery_type == 'delivery' || ($task->delivery_type == 'both' && $task->status == 'ready'))
                                <span class="badge bg-primary mb-2"><i class="bi bi-truck me-1"></i> TUGAS ANTAR</span>
                            @else
                                <span class="badge bg-secondary mb-2">TUGAS LAIN</span>
                            @endif
                            
                            <h5 class="fw-bold mb-0 text-dark">{{ $task->customer->name }}</h5>
                            <small class="text-muted">#{{ $task->invoice_code }}</small>
                        </div>
                        <span class="badge bg-light text-secondary border">
                            {{ strtoupper(str_replace('_', ' ', $task->delivery_status)) }}
                        </span>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Alamat</small>
                        <p class="mb-0 text-dark small">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                            {{ $task->pickup_address ?? $task->customer->address ?? 'Alamat tidak tersedia' }}
                        </p>
                    </div>

                    <div class="d-grid gap-2">
                        {{-- LOGIKA TOMBOL STATUS --}}
                        @if($task->delivery_status == 'pending')
                            <form action="{{ route('driver.updateStatus', $task->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="delivery_status" value="on_the_way">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">
                                    <i class="bi bi-bicycle me-2"></i> BERANGKAT
                                </button>
                            </form>
                        @elseif($task->delivery_status == 'on_the_way')
                            <button class="btn btn-success w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#modalFinish{{ $task->id }}">
                                <i class="bi bi-check-circle-fill me-2"></i> SELESAIKAN TUGAS
                            </button>
                        @endif
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="https://wa.me/{{ $task->customer->phone }}" target="_blank" class="btn btn-outline-success w-100">
                                    <i class="bi bi-whatsapp"></i> Chat
                                </a>
                            </div>
                            <div class="col-6">
                                @if($task->latitude && $task->longitude)
                                     <a href="https://www.google.com/maps/search/?api=1&query={{ $task->latitude }},{{ $task->longitude }}" target="_blank" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-map-fill"></i> Maps
                                    </a>
                                @else
                                    <button disabled class="btn btn-outline-secondary w-100" title="Lokasi tidak ada">
                                        <i class="bi bi-map-fill"></i> Maps
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Finish (Untuk konfirmasi dan COD) -->
        <div class="modal fade" id="modalFinish{{ $task->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('driver.updateStatus', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="delivery_status" value="delivered">
                        
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Selesaikan Tugas?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">Pastikan barang sudah diterima/diambil dengan baik.</p>
                            
                            {{-- LOGIKA COD: Jika tipe ANTAR dan BELUM LUNAS --}}
                            @if($task->delivery_type == 'delivery' && $task->payment_status == 'unpaid')
                                <div class="alert alert-warning border-warning">
                                    <div class="d-flex gap-2">
                                        <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                        <div>
                                            <strong>Tagihan Belum Lunas! (COD)</strong><br>
                                            Total Tagihan: <strong class="text-dark">Rp {{ number_format($task->total_price - $task->paid_amount, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Terima Pembayaran (Cash)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="payment_collect" class="form-control" placeholder="Contoh: 50000">
                                    </div>
                                    <small class="text-muted">Isi jika pelanggan membayar tunai ke Anda.</small>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Konfirmasi Selesai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @empty
        <div class="col-12 text-center py-5">
            <div class="bg-white p-5 rounded-4 shadow-sm d-inline-block">
                <i class="bi bi-clipboard-check display-1 text-muted opacity-25"></i>
                <h5 class="mt-3 text-muted fw-bold">Tugas Hari Ini Beres!</h5>
                <p class="text-muted small">Belum ada tugas pengantaran atau penjemputan baru.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
