@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Akun Staf')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Daftar Pengguna</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
    </div>
    <div class="card-body">
        
        @if(session('success')) 
            <div class="alert alert-success">{{ session('success') }}</div> 
        @endif
        
        @if(session('error')) 
            <div class="alert alert-danger">{{ session('error') }}</div> 
        @endif

        <div class="table-responsive">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role (Jabatan)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td class="fw-bold">{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            {{-- LOGIKA BARU: Paksa jadi huruf kecil biar tidak sensitif --}}
                            @php $role = strtolower($u->role); @endphp
                            
                            @if($role == 'owner') 
                                <span class="badge bg-danger">Owner</span>
                            @elseif($role == 'admin') 
                                <span class="badge bg-primary">Admin</span>
                            @else 
                                <span class="badge bg-secondary">Staff</span>
                            @endif

                            {{-- DEBUG: Tampilkan isi asli database (bisa dihapus nanti kalau sudah oke) --}}
                            <small class="text-muted ms-1">({{ $u->role }})</small>
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-warning me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            @if(auth()->id() != $u->id)
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf 
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection