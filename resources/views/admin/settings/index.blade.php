@extends('layouts.admin')

@section('title', 'Pengaturan Toko')
@section('page-title', 'Identitas Laundry')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Update Informasi Toko</h4>
            </div>
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Nama Laundry</label>
                        <input type="text" name="shop_name" class="form-control" value="{{ $setting->shop_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Nomor WhatsApp Owner (Format 628...)</label>
                        <input type="number" name="phone" class="form-control" value="{{ $setting->phone }}" required>
                        <small class="text-muted">Nomor ini akan dipakai di tombol WA Nota & Landing Page.</small>
                    </div>

                    <div class="mb-3">
                        <label>Alamat Lengkap</label>
                        <textarea name="address" class="form-control" rows="3" required>{{ $setting->address }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Logo Toko (Opsional)</label>
                        <input type="file" name="logo" class="form-control">
                        @if($setting->logo)
                            <div class="mt-2">
                                <small>Logo Saat Ini:</small><br>
                                <img src="{{ asset('storage/'.$setting->logo) }}" width="100" class="img-thumbnail">
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>💡 Info</h5>
                <p>Data yang Anda isi disini akan otomatis muncul di:</p>
                <ul>
                    <li>Kop Surat / Nota Transaksi</li>
                    <li>Link WhatsApp ke Pelanggan</li>
                    <li>Halaman Depan (Landing Page)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection