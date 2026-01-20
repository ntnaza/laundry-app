@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('page-title', 'Pengaturan Akun')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white"><i class="bi bi-person-circle"></i> Edit Profil</h5>
            </div>
            <div class="card-body mt-3">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Login</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <hr>
                    <h6 class="text-danger mb-3"><i class="bi bi-key"></i> Ganti Password (Opsional)</h6>
                    <p class="text-muted small">Kosongkan jika tidak ingin mengganti password.</p>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection