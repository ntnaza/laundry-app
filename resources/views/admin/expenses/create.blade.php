@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran')
@section('page-title', 'Form Pengeluaran')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Keterangan Pengeluaran</label>
                <input type="text" name="description" class="form-control" placeholder="Contoh: Beli Deterjen 5 Liter" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Nominal (Rp)</label>
                    <input type="number" name="amount" class="form-control" placeholder="50000" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label>Catatan Tambahan (Opsional)</label>
                <textarea name="note" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-danger">Simpan Pengeluaran</button>
            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection