@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Akun Staf')

@section('content')
<div class="card border-0 shadow-soft rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom border-light pt-4 px-4 pb-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-heading mb-1">Daftar Pengguna</h5>
            <p class="text-muted small mb-0">Kelola akses admin dan staff sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2 shadow-sm hover-top transition-300">
            <i class="bi bi-person-plus-fill"></i> Tambah User
        </a>
    </div>

    <div class="card-body p-0">
        
        @if(session('success')) 
            <div class="alert alert-success border-0 shadow-sm rounded-0 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
            </div> 
        @endif
        
        @if(session('error')) 
            <div class="alert alert-danger border-0 shadow-sm rounded-0 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-circle-fill fs-5"></i> {{ session('error') }}
            </div> 
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Nama Pengguna</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Email</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Role</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted border-0 ls-1">Status</th>
                        <th class="pe-4 py-3 text-uppercase small fw-bold text-muted border-0 ls-1 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr class="border-bottom border-light-subtle transition-300 {{ !$u->is_active ? 'bg-light-subtle opacity-75' : '' }}">
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar rounded-circle bg-light-primary text-primary fw-bold d-flex align-items-center justify-content-center overflow-hidden" style="width: 40px; height: 40px;">
                                    @if($u->avatar)
                                        <img src="{{ asset('storage/' . $u->avatar) }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        {{ substr($u->name, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">{{ $u->name }}</h6>
                                    @if(auth()->id() == $u->id)
                                        <span class="badge bg-success-subtle text-success border border-success rounded-pill" style="font-size: 0.6rem;">It's You</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 text-secondary">{{ $u->email }}</td>
                        <td class="py-3">
                            @php $role = strtolower($u->role); @endphp
                            
                            @if($role == 'owner') 
                                <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill px-3 py-1">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Owner
                                </span>
                            @elseif($role == 'admin') 
                                <span class="badge bg-primary-subtle text-primary border border-primary rounded-pill px-3 py-1">
                                    <i class="bi bi-laptop me-1"></i> Admin
                                </span>
                            @elseif($role == 'driver') 
                                <span class="badge bg-info-subtle text-info border border-info rounded-pill px-3 py-1">
                                    <i class="bi bi-truck me-1"></i> Driver
                                </span>
                            @elseif($role == 'customer') 
                                <span class="badge bg-success-subtle text-success border border-success rounded-pill px-3 py-1">
                                    <i class="bi bi-person-fill me-1"></i> Customer
                                </span>
                            @else 
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary rounded-pill px-3 py-1">
                                    <i class="bi bi-person-badge me-1"></i> Staff
                                </span>
                            @endif
                        </td>
                        <td class="py-3">
                            @if($u->is_active)
                                <span class="badge bg-success rounded-pill px-2" style="font-size: 0.7rem;">Aktif</span>
                            @else
                                <span class="badge bg-danger rounded-pill px-2" style="font-size: 0.7rem;">Non-aktif</span>
                            @endif
                        </td>
                        <td class="pe-4 py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('users.edit', $u->id) }}" class="btn btn-icon btn-light-warning text-warning rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                @if(auth()->id() != $u->id)
                                <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-icon btn-light-danger text-danger rounded-circle box-center shadow-sm" style="width: 36px; height: 36px;" title="Hapus">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .hover-top:hover { transform: translateY(-3px); }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-light-warning { background-color: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.1); }
    .btn-light-danger { background-color: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.1); }
    
    .bg-danger-subtle { background-color: #fef2f2 !important; }
    .bg-primary-subtle { background-color: #eff6ff !important; }
    .bg-info-subtle { background-color: #f0f9ff !important; }
    .bg-secondary-subtle { background-color: #f8fafc !important; }
    .bg-success-subtle { background-color: #f0fdf4 !important; }
</style>
@endsection