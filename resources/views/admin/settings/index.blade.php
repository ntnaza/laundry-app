@extends('layouts.admin')

@section('title', 'Pengaturan Toko')
@section('page-title', 'Identitas Laundry')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Update Informasi Toko</h5>
                <p class="text-muted small mb-0">Identitas ini akan tampil di seluruh sistem.</p>
            </div>
            <div class="card-body p-4">
                @if(session('success')) 
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
                    </div> 
                @endif

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Nama Laundry --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Laundry</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-shop"></i></span>
                            <input type="text" name="shop_name" class="form-control border-light shadow-sm bg-white" value="{{ $setting->shop_name }}" placeholder="Cth: LaundryKuy Premium" required>
                        </div>
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">WhatsApp Owner</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-whatsapp"></i></span>
                            <input type="number" name="phone" class="form-control border-light shadow-sm bg-white" value="{{ $setting->phone }}" placeholder="628..." required>
                        </div>
                        <div class="form-text small text-muted fst-italic">
                            <i class="bi bi-info-circle me-1"></i> Digunakan untuk link WA di Nota & Landing Page.
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Alamat Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-geo-alt-fill"></i></span>
                            <textarea name="address" class="form-control border-light shadow-sm bg-white" rows="3" placeholder="Alamat lengkap outlet..." required>{{ $setting->address }}</textarea>
                        </div>
                    </div>

                    {{-- Logo --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Logo Toko (Opsional)</label>
                        <div class="card bg-light border-0 rounded-4 p-3 text-center">
                            @if($setting->logo)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/'.$setting->logo) }}" width="120" class="rounded-3 shadow-sm border border-white">
                                    <p class="text-muted small mt-2 mb-0">Logo Saat Ini</p>
                                </div>
                            @endif
                            <input type="file" name="logo" class="form-control border-light bg-white shadow-sm rounded-pill px-4">
                        </div>
                    </div>

                    <div class="pt-3 border-top border-light-subtle">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden bg-primary text-white h-100">
            <div class="card-body p-5 position-relative">
                <div class="position-absolute top-0 end-0 p-4 opacity-10">
                    <i class="bi bi-gear-wide-connected" style="font-size: 10rem;"></i>
                </div>
                
                <div class="position-relative z-1">
                    <div class="bg-white bg-opacity-20 rounded-circle box-center mb-4" style="width: 60px; height: 60px;">
                        <i class="bi bi-lightbulb-fill fs-3"></i>
                    </div>
                    <h4 class="fw-heading text-white mb-3">Informasi Penting</h4>
                    <p class="mb-4 opacity-75">Data identitas toko yang Anda masukkan di halaman ini akan secara otomatis terintegrasi dan muncul pada bagian berikut:</p>
                    
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-start gap-3 mb-3">
                            <div class="bg-white bg-opacity-20 rounded-circle box-center mt-1" style="width: 24px; height: 24px; flex-shrink: 0;">
                                <i class="bi bi-check-lg small"></i>
                            </div>
                            <span><strong>Nota Transaksi:</strong> Sebagai Kop surat dan identitas pengirim pada cetakan fisik maupun digital.</span>
                        </li>
                        <li class="d-flex align-items-start gap-3 mb-3">
                            <div class="bg-white bg-opacity-20 rounded-circle box-center mt-1" style="width: 24px; height: 24px; flex-shrink: 0;">
                                <i class="bi bi-check-lg small"></i>
                            </div>
                            <span><strong>Link WhatsApp:</strong> Nomor tujuan otomatis saat pelanggan ingin menghubungi Anda melalui sistem.</span>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="bg-white bg-opacity-20 rounded-circle box-center mt-1" style="width: 24px; height: 24px; flex-shrink: 0;">
                                <i class="bi bi-check-lg small"></i>
                            </div>
                            <span><strong>Landing Page:</strong> Nama, alamat, dan logo toko pada bagian depan website (Area Pelanggan).</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection