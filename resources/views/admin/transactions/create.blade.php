@extends('layouts.admin')

@section('title', 'Transaksi Baru')
@section('page-title', 'Kasir Laundry')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Buat Transaksi Baru</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label>No. Invoice</label>
                            <input type="text" name="invoice_code" class="form-control bg-light" value="{{ $invoice_code }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Pilih Pelanggan</label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">-- Cari Pelanggan --</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->phone }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted"><a href="#">+ Tambah Pelanggan Baru</a></small>
                        </div>
                        <div class="col-md-4">
                            <label>Kasir Bertugas</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name ?? 'Admin' }}" readonly>
                        </div>
                    </div>

                    <hr>

                    <h5>List Cucian</h5>
                    <table class="table table-bordered" id="table-keranjang">
                        <thead class="table-light">
                            <tr>
                                <th width="40%">Layanan / Paket</th>
                                <th width="20%">Qty (Kg / Pcs)</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="service_id[]" class="form-select" required>
                                        <option value="">-- Pilih Paket --</option>
                                        @foreach($services as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control" placeholder="1" min="1" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm remove-row" disabled>-</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-success btn-sm mt-2" id="add-row">+ Tambah Item Lain</button>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Proses Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Saat tombol tambah diklik
        $('#add-row').click(function() {
            var row = `<tr>
                        <td>
                            <select name="service_id[]" class="form-select" required>
                                <option value="">-- Pilih Paket --</option>
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} - Rp {{ number_format($s->price) }}/{{ $s->unit }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="qty[]" class="form-control" placeholder="1" min="1" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                        </td>
                      </tr>`;
            $('#table-keranjang tbody').append(row);
        });

        // Saat tombol hapus (X) diklik
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
@endsection