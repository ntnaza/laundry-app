<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Cucian - {{ $transaction->invoice_code }}</title>
    
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    
    <style>
        body { background-color: #f2f7ff; }
        .timeline-steps {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .timeline-steps .timeline-step {
            align-items: center;
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 1rem;
        }
        .timeline-content {
            width: 100%;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <a href="{{ url('/') }}" class="btn btn-outline-primary mb-4">&larr; Kembali ke Beranda</a>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center p-5">
                        <h5 class="text-muted mb-2">Status Cucian</h5>
                        <h2 class="fw-bold mb-3">{{ $transaction->invoice_code }}</h2>
                        
                        @if($transaction->status == 'pending')
                            <span class="badge bg-secondary fs-5 px-4 py-2 rounded-pill">⏳ Menunggu Proses</span>
                        @elseif($transaction->status == 'process')
                            <span class="badge bg-info fs-5 px-4 py-2 rounded-pill">🫧 Sedang Dicuci</span>
                        @elseif($transaction->status == 'ready')
                            <span class="badge bg-warning text-dark fs-5 px-4 py-2 rounded-pill">✅ Siap Diambil</span>
                        @elseif($transaction->status == 'done')
                            <span class="badge bg-success fs-5 px-4 py-2 rounded-pill">📦 Sudah Diambil</span>
                        @endif

                        <p class="mt-3 text-muted">
                            Halo <strong>{{ $transaction->customer->name }}</strong>,<br>
                            Berikut adalah detail perjalanan cucian Anda.
                        </p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">📜 Riwayat Pengerjaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            @forelse($transaction->logs as $log)
                                <div class="d-flex align-items-start mb-4">
                                    <div class="me-3">
                                        @if($log->status == 'pending') <div class="avatar bg-secondary text-white"><i class="bi bi-hourglass"></i></div>
                                        @elseif($log->status == 'process') <div class="avatar bg-info text-white"><i class="bi bi-water"></i></div>
                                        @elseif($log->status == 'ready') <div class="avatar bg-warning text-dark"><i class="bi bi-check-lg"></i></div>
                                        @elseif($log->status == 'done') <div class="avatar bg-success text-white"><i class="bi bi-bag-check"></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1">
                                            @if($log->status == 'pending') Pesanan Dibuat / Menunggu
                                            @elseif($log->status == 'process') Sedang Dicuci
                                            @elseif($log->status == 'ready') Selesai (Siap Diambil)
                                            @elseif($log->status == 'done') Pesanan Selesai & Diambil
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $log->created_at->format('d M Y, H:i') }}</small>
                                        <p class="mb-0 text-sm mt-1">Diupdate oleh: {{ $log->user->name ?? 'Sistem' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted">Belum ada riwayat aktivitas.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white" data-bs-toggle="collapse" href="#detailItem" role="button">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0">👕 Detail Item</h5>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                    </div>
                    <div class="collapse show" id="detailItem">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Layanan</th>
                                        <th>Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->details as $item)
                                    <tr>
                                        <td>{{ $item->service->name }}</td>
                                        <td>{{ $item->qty }} {{ $item->service->unit }}</td>
                                        <td class="text-end">Rp {{ number_format($item->subtotal) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="fw-bold text-end">Grand Total</td>
                                        <td class="fw-bold text-end text-primary">Rp {{ number_format($transaction->total_price) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-end">Status Bayar</td>
                                        <td class="text-end">
                                            @if($transaction->payment_status == 'paid')
                                                <span class="text-success fw-bold">LUNAS</span>
                                            @else
                                                <span class="text-danger fw-bold">BELUM BAYAR</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>