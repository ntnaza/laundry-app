@extends('layouts.admin')

@section('title', 'Proses Transaksi')
@section('page-title', 'Proses Order #' . $transaction->invoice_code)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-gear-fill"></i> Panel Kontrol Order</h5>
            </div>
            <div class="card-body mt-3">
                
                {{-- Info Customer --}}
                <div class="alert alert-light-primary color-primary">
                    <h6 class="alert-heading"><i class="bi bi-person-circle"></i> Data Pelanggan</h6>
                    <p class="mb-0">
                        <strong>Nama:</strong> {{ $transaction->customer->name }} <br>
                        <strong>No HP:</strong> {{ $transaction->customer->phone ?? '-' }} <br>
                        <strong>Alamat Jemput:</strong> {{ $transaction->pickup_address ?? 'Datang Sendiri' }} <br>
                        <strong>Catatan:</strong> <em>"{{ $transaction->note ?? '-' }}"</em>
                    </p>
                </div>

                <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold text-danger">Total Biaya (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="total_price" class="form-control form-control-lg fw-bold" 
                                       value="{{ $transaction->total_price }}" placeholder="Masukkan hasil timbangan..." required>
                            </div>
                            <small class="text-muted">Isi nominal setelah laundry ditimbang di toko.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Laundry</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Menunggu (Pending)</option>
                                <option value="process" {{ $transaction->status == 'process' ? 'selected' : '' }}>Sedang Dicuci (Process)</option>
                                <option value="ready" {{ $transaction->status == 'ready' ? 'selected' : '' }}>Siap Ambil (Ready)</option>
                                <option value="done" {{ $transaction->status == 'done' ? 'selected' : '' }}>Selesai (Done)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status Kurir / Pengiriman</label>
                            <select name="delivery_status" class="form-select">
                                <option value="pending" {{ $transaction->delivery_status == 'pending' ? 'selected' : '' }}>Cari Kurir (Pending)</option>
                                <option value="on_the_way" {{ $transaction->delivery_status == 'on_the_way' ? 'selected' : '' }}>Kurir Jalan (OTW)</option>
                                <option value="delivered" {{ $transaction->delivery_status == 'delivered' ? 'selected' : '' }}>Selesai Diantar</option>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary fw-bold px-5">
                            <i class="bi bi-save"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 