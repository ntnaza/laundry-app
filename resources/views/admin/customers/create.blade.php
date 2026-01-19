@extends('layouts.admin')

@section('title', 'Tambah Pelanggan')
@section('page-title', 'Registrasi Pelanggan Baru')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nomor WhatsApp (Aktif)</label>
                <input type="number" name="phone" class="form-control" placeholder="Contoh: 628123..." required>
                <small class="text-muted">Gunakan format 628.. agar fitur kirim WA berjalan lancar.</small>
            </div>
            <div class="mb-3">
                <label>Alamat Domisili</label>
                <textarea name="address" class="form-control" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Data</button>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection