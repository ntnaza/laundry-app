@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Data Transaksi & Order Masuk')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">List Cucian Masuk</h4>
        <a href="{{ route('transactions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Transaksi Manual
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Tipe Order</th> {{-- Kolom Baru --}}
                        <th>Status Laundry</th>
                        <th>Total & Kasir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $t)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">{{ $t->invoice_code }}</span><br>
                            <small class="text-muted">{{ $t->created_at->format('d M, H:i') }}</small>
                        </td>
                        <td>
                            <span class="fw-bold">{{ $t->customer->name }}</span><br>
                            
                            {{-- Kalau ada No HP di tabel customer, tampilkan --}}
                            @if(isset($t->customer->phone))
                                <small class="text-muted"><i class="bi bi-whatsapp"></i> {{ $t->customer->phone }}</small>
                            @endif

                            {{-- Tampilkan Alamat kalau minta jemput --}}
                            @if($t->delivery_type != 'none' && $t->pickup_address)
                                <div class="alert alert-light-warning p-1 mt-1 mb-0 small" style="font-size: 0.8em;">
                                    <i class="bi bi-geo-alt-fill"></i> {{ Str::limit($t->pickup_address, 30) }}
                                </div>
                            @endif
                        </td>
                        
                        {{-- LOGIKA TIPE ORDER (JEMPUT/ANTAR) --}}
                        <td>
                            @if($t->delivery_type == 'pickup')
                                <span class="badge bg-warning text-dark">Jemput Saja</span>
                            @elseif($t->delivery_type == 'delivery')
                                <span class="badge bg-info">Antar Balik</span>
                            @elseif($t->delivery_type == 'both')
                                <span class="badge bg-purple" style="background-color: #6f42c1; color:white;">Antar-Jemput</span>
                            @else
                                <span class="badge bg-secondary">Datang Sendiri</span>
                            @endif

                            {{-- Status Kurir --}}
                            @if($t->delivery_status == 'pending' && $t->delivery_type != 'none')
                                <div class="mt-1 text-danger fw-bold small blink">BUTUH KURIR!</div>
                            @elseif($t->delivery_status == 'on_the_way')
                                <div class="mt-1 text-primary fw-bold small"><i class="bi bi-scooter"></i> OTW</div>
                            @endif
                        </td>

                        <td>
                            @if($t->status == 'pending') <span class="badge bg-secondary">Menunggu</span>
                            @elseif($t->status == 'process') <span class="badge bg-info">Sedang Cuci</span>
                            @elseif($t->status == 'ready') <span class="badge bg-warning text-dark">Siap Ambil</span>
                            @elseif($t->status == 'done') <span class="badge bg-success">Selesai</span>
                            @endif
                        </td>
                        
                        <td>
                            {{-- LOGIKA KASIR (Handle User Null) --}}
                            @if($t->total_price == 0)
                                <span class="badge bg-danger">Belum Ditimbang</span>
                            @else
                                Rp {{ number_format($t->total_price) }}
                            @endif
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $t->user->name ?? 'Online Order' }}
                            </small>
                        </td>

                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('transactions.show', $t->id) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                {{-- TOMBOL PROSES (Khusus Order Online yg belum ditimbang) --}}
                                @if($t->total_price == 0)
                                    <a href="{{ route('transactions.edit', $t->id) }}" class="btn btn-sm btn-warning" title="Proses / Timbang">
                                        <i class="bi bi-basket"></i> Proses
                                    </a>
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
    /* Animasi kedip buat orderan baru */
    .blink { animation: blinker 1.5s linear infinite; }
    @keyframes blinker { 50% { opacity: 0; } }
</style>
@endsection