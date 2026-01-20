@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit Data Akun')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            
            <div class="mb-3">
                <label>Email Login</label>
                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Isi jika ingin mengganti password">
                    <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jabatan (Role)</label>
                    <select name="role" class="form-select">
                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-warning">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection