@extends('layouts.admin')

@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Data Pelanggan')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form Edit Data</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- WAJIB: Penanda bahwa ini adalah proses UPDATE --}}

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
            </div>
            
            <div class="mb-3">
                <label>Nomor WhatsApp (Aktif)</label>
                <input type="number" name="phone" class="form-control" value="{{ $customer->phone }}" placeholder="Contoh: 628123..." required>
                <small class="text-muted">Pastikan format nomor benar (62xxx) agar fitur WA berjalan.</small>
            </div>
            
            <div class="mb-3">
                <label>Alamat Domisili</label>
                <textarea name="address" class="form-control" rows="3">{{ $customer->address }}</textarea>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning">Update Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection