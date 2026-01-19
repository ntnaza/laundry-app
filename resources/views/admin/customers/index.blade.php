@extends('layouts.admin')

@section('title', 'Data Pelanggan')
@section('page-title', 'Manajemen Pelanggan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h4>List Member</h4>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Pelanggan Baru</a>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <table class="table table-striped" id="table1">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $c)
                <tr>
                    <td class="fw-bold">{{ $c->name }}</td>
                    <td>{{ $c->phone }}</td>
                    <td>{{ Str::limit($c->address, 30) }}</td>
                    <td>
                        <a href="{{ route('customers.show', $c->id) }}" class="btn btn-sm btn-info me-1"><i class="bi bi-eye"></i></a>
                        
                        <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-sm btn-warning me-1"><i class="bi bi-pencil"></i></a>
                        
                        <form action="{{ route('customers.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection