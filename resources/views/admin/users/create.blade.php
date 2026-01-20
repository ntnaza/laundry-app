@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page-title', 'Buat Akun Baru')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label>Email Login</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Jabatan (Role)</label>
                    <select name="role" class="form-select">
                        <option value="staff">Staff (Kasir Biasa)</option>
                        <option value="admin">Admin (Manajer)</option>
                        <option value="owner">Owner (Bos Besar)</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection