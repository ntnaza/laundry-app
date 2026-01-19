@extends('layouts.admin')

@section('title', 'Daftar Paket Laundry')
@section('page-title', 'Manajemen Paket Laundry')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Harga</h4>
        <a href="{{ route('services.create') }}" class="btn btn-primary">Tambah Paket</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Jenis</th>
                        <th>Harga</th>
                        <th>Durasi (Jam)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>
                            <span class="badge bg-{{ $item->type == 'kiloan' ? 'info' : 'warning' }}">
                                {{ ucfirst($item->type) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($item->price) }} / {{ $item->unit }}</td>
                        <td>{{ $item->estimate_duration }} Jam</td>
                        <td>
                            <a href="{{ route('services.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('services.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection