@extends('layouts.admin')

@section('title', 'Transaksi Baru')
@section('page-title', 'Kasir Laundry')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Buat Transaksi Baru</h5>
                    <p class="text-muted small mb-0">Isi data pelanggan dan item laundry.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2">
                        <i class="bi bi-person-badge me-1"></i> Kasir: {{ Auth::user()->name ?? 'Admin' }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    {{-- 1. DATA UTAMA --}}
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">No. Invoice</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-receipt"></i></span>
                                <input type="text" name="invoice_code" class="form-control bg-light border-0 fw-bold" value="{{ $invoice_code }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small text-uppercase">Pilih Pelanggan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-people"></i></span>
                                <select name="customer_id" class="form-select border-start-0" required>
                                    <option value="">-- Cari Nama / No HP --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-end mt-1">
                                <a href="{{ route('customers.index') }}" class="text-primary small text-decoration-none fw-bold"><i class="bi bi-plus-circle"></i> Pelanggan Baru?</a>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light my-4">

                    {{-- 2. LIST CUCIAN (KERANJANG) --}}
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-basket3-fill text-primary"></i> Keranjang Cucian
                    </h6>
                    
                    <div class="table-responsive rounded-3 border mb-3">
                        <table class="table table-borderless mb-0 align-middle" id="table-keranjang">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3" width="50%">Layanan / Paket</th>
                                    <th class="py-3" width="20%">Jumlah</th>
                                    <th class="pe-4 py-3 text-center" width="10%">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom border-light">
                                    <td class="ps-4">
                                        <select name="service_id[]" class="form-select border-0 bg-light" required>
                                            <option value="">-- Pilih Paket Laundry --</option>
                                            @foreach($services as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" name="qty[]" class="form-control text-center border-0 bg-light" placeholder="1" min="1" required>
                                            <span class="input-group-text border-0 bg-light small text-muted">Unit</span>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <button type="button" class="btn btn-icon btn-light text-muted box-center rounded-circle" disabled style="width: 32px; height: 32px;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-primary border-dashed w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2" id="add-row">
                        <i class="bi bi-plus-lg"></i> Tambah Item Lain
                    </button>

                    {{-- 3. TOMBOL PROSES --}}
                    <div class="d-flex justify-content-end mt-5 pt-3 border-top">
                        <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-pill px-4 me-3">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-lg hover-scale">
                            <i class="bi bi-check-lg me-2"></i> PROSES TRANSAKSI
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT JQUERY --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Template baris baru (Disimpan di variabel biar rapi)
        const rowTemplate = `
            <tr class="border-bottom border-light">
                <td class="ps-4">
                    <select name="service_id[]" class="form-select border-0 bg-light" required>
                        <option value="">-- Pilih Paket Laundry --</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="number" name="qty[]" class="form-control text-center border-0 bg-light" placeholder="1" min="1" required>
                        <span class="input-group-text border-0 bg-light small text-muted">Unit</span>
                    </div>
                </td>
                <td class="pe-4 text-center">
                    <button type="button" class="btn btn-icon btn-light-danger text-danger box-center rounded-circle remove-row" style="width: 32px; height: 32px;">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        // Tambah Baris
        $('#add-row').click(function() {
            $('#table-keranjang tbody').append(rowTemplate);
        });

        // Hapus Baris
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>

<style>
    /* Styling Khusus */
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    .border-dashed:hover { background-color: rgba(37, 99, 235, 0.05); }
    .btn-icon { transition: 0.2s; border: 1px solid transparent; }
    .btn-light-danger { background-color: rgba(220, 53, 69, 0.1); }
    .btn-light-danger:hover { background-color: #dc3545; color: white !important; }
    .hover-scale:hover { transform: translateY(-2px); }
</style>
@endsection