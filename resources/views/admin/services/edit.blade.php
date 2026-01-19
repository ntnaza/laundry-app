@extends('layouts.admin')

@section('title', 'Edit Paket')
@section('page-title', 'Edit Paket Laundry')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Form Edit Paket</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- WAJIB ADA: Biar Laravel tau ini proses Update --}}

            <div class="mb-3">
                <label>Nama Paket</label>
                <input type="text" name="name" class="form-control" value="{{ $service->name }}" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Jenis</label>
                    <select name="type" class="form-select">
                        <option value="kiloan" {{ $service->type == 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                        <option value="satuan" {{ $service->type == 'satuan' ? 'selected' : '' }}>Satuan (Pcs)</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Satuan Unit</label>
                    <input type="text" name="unit" class="form-control" value="{{ $service->unit }}" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ $service->price }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Estimasi Durasi (Jam)</label>
                    <input type="number" name="estimate_duration" class="form-control" value="{{ $service->estimate_duration }}" required>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('services.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection