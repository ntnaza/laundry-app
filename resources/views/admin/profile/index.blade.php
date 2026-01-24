@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Edit Profil</h5>
                <p class="text-muted small mb-0">Perbarui informasi akun dan keamanan Anda.</p>
            </div>
            
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="name" class="form-control border-light shadow-sm bg-white" value="{{ $user->name }}" required>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Email Login</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-envelope-fill"></i></span>
                            <input type="email" name="email" class="form-control border-light shadow-sm bg-white" value="{{ $user->email }}" required>
                        </div>
                    </div>

                    <hr class="border-light-subtle my-4">
                    
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="bg-light-danger text-danger rounded-circle box-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Ganti Password (Opsional)</h6>
                    </div>
                    <p class="text-muted small mb-4 ms-1">Kosongkan kolom di bawah jika tidak ingin mengubah password saat ini.</p>

                    {{-- Password Baru --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Password Baru</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-key-fill"></i></span>
                            <input type="password" name="password" class="form-control border-light shadow-sm bg-white" placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase ls-1">Ulangi Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-check-all"></i></span>
                            <input type="password" name="password_confirmation" class="form-control border-light shadow-sm bg-white" placeholder="Konfirmasi password baru">
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
</style>
@endsection