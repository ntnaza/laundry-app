@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit Data Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-soft rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3">
                <h5 class="fw-heading mb-1">Form Edit Pengguna</h5>
                <p class="text-muted small mb-0">Perbarui data akun untuk <strong>{{ $user->name }}</strong>.</p>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
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

                    <div class="row g-4 mb-4">
                        {{-- Password --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Password Baru (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" class="form-control border-light shadow-sm bg-white" placeholder="******">
                            </div>
                            <div class="form-text small text-muted fst-italic"><i class="bi bi-info-circle me-1"></i> Kosongkan jika tidak ingin mengubah password.</div>
                        </div>
                        
                        {{-- Role --}}
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase ls-1">Jabatan (Role)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light shadow-sm text-muted ps-3"><i class="bi bi-shield-lock-fill"></i></span>
                                <select name="role" class="form-select border-light shadow-sm bg-white" required>
                                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end align-items-center gap-3 mt-5 pt-3 border-top border-light-subtle">
                        <a href="{{ route('users.index') }}" class="btn btn-light rounded-pill px-4 fw-bold text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-lg hover-top transition-300">
                            <i class="bi bi-save2-fill me-2"></i> Update User
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