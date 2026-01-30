@extends('layouts.admin')

@section('title', 'Transaksi Baru')
@section('page-title', 'Kasir Laundry')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                <div class="mb-3">
                    <h5 class="fw-heading mb-1">Buat Transaksi Baru</h5>
                    <p class="text-muted small mb-0">Isi data pelanggan dan layanan laundry.</p>
                </div>
                <div class="d-none d-md-flex align-items-center gap-2 mb-3">
                    <div class="bg-light-primary text-primary px-3 py-2 rounded-pill d-flex align-items-center gap-2 small fw-bold">
                        <i class="bi bi-person-badge-fill"></i> Kasir: {{ Auth::user()->name ?? 'Admin' }}
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    {{-- 1. DATA PELANGGAN & PENGIRIMAN --}}
                    <div class="row g-4 mb-4">
                        {{-- Kiri: Data Utama --}}
                        <div class="col-md-7">
                            <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Data Pelanggan</h6>
                            
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">No. Invoice</label>
                                    <input type="text" name="invoice_code" class="form-control bg-light border-0 fw-bold font-monospace text-primary" value="{{ $invoice_code }}" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-bold">Pilih Pelanggan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="customer_id" id="customerSelect" class="form-select border-light bg-white shadow-sm" required style="border-radius: 0.5rem 0 0 0.5rem;">
                                            <option value="" data-points="0">-- Cari Nama / No HP --</option>
                                            @foreach($customers as $c)
                                                <option value="{{ $c->id }}" data-points="{{ $c->points ?? 0 }}">{{ $c->name }} ({{ $c->phone }})</option>
                                            @endforeach
                                        </select>
                                        <a href="{{ route('customers.index') }}" class="btn btn-primary px-3 d-flex align-items-center" style="border-radius: 0 0.5rem 0.5rem 0;" title="Tambah Pelanggan Baru">
                                            <i class="bi bi-plus-lg"></i>
                                        </a>
                                    </div>
                                    <div class="form-text small">
                                        <i class="bi bi-info-circle me-1"></i> Pelanggan belum terdaftar? Klik tombol (+) di samping.
                                    </div>

                                    {{-- INFO POIN (Hidden by default) --}}
                                    <div id="pointSection" class="mt-3 p-3 bg-warning bg-opacity-10 border border-warning rounded-3 d-none animate__animated animate__fadeIn">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-uppercase fw-bold text-warning ls-1">Poin Member</small>
                                                <h5 class="fw-bold text-dark mb-0"><span id="pointDisplay">0</span> Poin</h5>
                                                <small class="text-muted" style="font-size: 0.7rem;">Nilai Tukar: 1 Poin = Rp 50</small>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input fs-5" type="checkbox" role="switch" name="use_points" id="usePointsCheck" value="1">
                                                <label class="form-check-label small fw-bold pt-1" for="usePointsCheck">Tukar Poin</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Opsi Pengiriman --}}
                        <div class="col-md-5">
                            <h6 class="fw-bold text-dark mb-3 small text-uppercase ls-1">Opsi Layanan & Promo</h6>
                            
                            <div class="card bg-light border-0 rounded-4 p-3">
                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Jenis Pengiriman</label>
                                    <select name="delivery_type" class="form-select border-0 bg-white shadow-sm rounded-3" id="delivery_type">
                                        <option value="none">Datang Sendiri (Walk-In)</option>
                                        <option value="pickup">Jemput Saja (Pickup)</option>
                                        <option value="delivery">Antar Saja (Delivery)</option>
                                        <option value="both">Antar Jemput (Full Service)</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-muted small fw-bold">Kode Promo (Opsional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-white shadow-sm ps-3 rounded-start-3"><i class="bi bi-ticket-perforated-fill text-warning"></i></span>
                                        <input type="text" name="promo_code" class="form-control border-0 bg-white shadow-sm text-uppercase fw-bold" placeholder="KODE DISKON">
                                    </div>
                                </div>
                                
                                <div class="mb-0">
                                    <label class="form-label text-muted small fw-bold">Catatan Order</label>
                                    <textarea name="note" class="form-control border-0 bg-white shadow-sm rounded-3" rows="2" placeholder="Cth: Jangan disetrika, baju putih pisah..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="border-light-subtle my-4">

                    {{-- 2. LIST ITEM (KERANJANG) --}}
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="fw-bold text-dark mb-0 small text-uppercase ls-1">
                            <i class="bi bi-basket2-fill text-primary me-2"></i>Item Laundry
                        </h6>
                    </div>
                    
                    <div class="table-responsive rounded-4 border border-light-subtle mb-3">
                        <table class="table table-borderless mb-0 align-middle" id="table-keranjang">
                            <thead class="bg-light-primary text-primary small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3" width="50%">Pilih Layanan</th>
                                    <th class="py-3" width="20%">Jumlah (Kg/Pcs)</th>
                                    <th class="pe-4 py-3 text-center" width="10%">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom border-light-subtle">
                                    <td class="ps-4 py-3">
                                        <select name="service_id[]" class="form-select border-light shadow-sm" required>
                                            <option value="">-- Pilih Paket --</option>
                                            @foreach($services as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3">
                                        <input type="number" name="qty[]" class="form-control border-light shadow-sm text-center fw-bold" placeholder="0" step="0.1" min="0.1" required>
                                    </td>
                                    <td class="pe-4 text-center py-3">
                                        <button type="button" class="btn btn-icon btn-light text-muted box-center rounded-circle" disabled style="width: 36px; height: 36px;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-outline-primary border-dashed w-100 py-3 rounded-4 fw-bold d-flex align-items-center justify-content-center gap-2 hover-bg-light transition-300" id="add-row">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Item Lain
                    </button>

                    {{-- 3. ACTION BUTTONS --}}
                    <div class="d-flex justify-content-end align-items-center gap-3 mt-5 pt-3 border-top border-light-subtle">
                        <a href="{{ route('transactions.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg hover-top transition-300 d-flex align-items-center gap-2">
                            <i class="bi bi-save2-fill"></i> SIMPAN TRANSAKSI
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
        // Logika Tampil Poin
        $('#customerSelect').change(function() {
            const selectedOpt = $(this).find(':selected');
            const points = parseInt(selectedOpt.data('points')) || 0;
            const pointSection = $('#pointSection');
            const pointDisplay = $('#pointDisplay');
            const usePointsCheck = $('#usePointsCheck');

            // Reset checkbox
            usePointsCheck.prop('checked', false);

            if (points > 0) {
                pointDisplay.text(points);
                pointSection.removeClass('d-none');
            } else {
                pointSection.addClass('d-none');
            }
        });

        const rowTemplate = `
            <tr class="border-bottom border-light-subtle animate__animated animate__fadeIn">
                <td class="ps-4 py-3">
                    <select name="service_id[]" class="form-select border-light shadow-sm" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($services as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="py-3">
                    <input type="number" name="qty[]" class="form-control border-light shadow-sm text-center fw-bold" placeholder="0" step="0.1" min="0.1" required>
                </td>
                <td class="pe-4 text-center py-3">
                    <button type="button" class="btn btn-icon btn-light-danger text-danger box-center rounded-circle remove-row shadow-sm" style="width: 36px; height: 36px;">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#add-row').click(function() {
            $('#table-keranjang tbody').append(rowTemplate);
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>

<style>
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    .btn-light-danger { background-color: #fef2f2; border: 1px solid #fee2e2; }
    .btn-light-danger:hover { background-color: #fee2e2; color: #dc2626; }
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection