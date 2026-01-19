@extends('layouts.admin')

@section('title', 'Tambah Paket')
@section('page-title', 'Tambah Paket Baru')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama Paket</label>
                <input type="text" name="name" class="form-control" placeholder="Contoh: Cuci Komplit Wangi" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jenis</label>
                    <select name="type" class="form-select">
                        <option value="kiloan">Kiloan</option>
                        <option value="satuan">Satuan (Pcs)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Satuan Unit</label>
                    <input type="text" name="unit" class="form-control" placeholder="kg / pcs / lembar" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" placeholder="5000" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Estimasi Durasi (Jam)</label>
                    <input type="number" name="estimate_duration" class="form-control" placeholder="24" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Paket</button>
            <a href="{{ route('services.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection