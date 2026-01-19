<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaundryKuy - Cek Status Cucian</title>
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <style>
        body { background-color: #f2f7ff; }
        .hero-section {
            background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);
            color: white;
            padding: 80px 0 100px;
            border-radius: 0 0 50px 50px;
            margin-bottom: -50px;
        }
        .tracking-card {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 20px;
        }
    </style>
</head>
<body>

    <div class="hero-section text-center">
        <div class="container">
            <h1 class="fw-bold display-4 mb-3">Laundry<span class="text-warning">Kuy</span></h1>
            <p class="lead mb-5">Cucian numpuk? Santai aja, kami yang urus sampai wangi.</p>
        </div>
    </div>

    <div class="container" style="position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                @if(session('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card tracking-card bg-white mb-5">
                    <div class="card-body p-5 text-center">
                        <h3 class="mb-4 text-primary">Cek Status Cucian Kamu</h3>
                        <form action="{{ route('track') }}" method="POST">
                            @csrf
                            <div class="input-group input-group-lg mb-3">
                                <input type="text" name="invoice_code" class="form-control" placeholder="Masukkan Kode Invoice (Contoh: INV-2026...)" required>
                                <button class="btn btn-primary px-4" type="submit">Lacak Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if(isset($tracking_result))
                <div class="card tracking-card bg-white mb-5 border-primary border-2">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="mb-0">Hasil Pelacakan: {{ $tracking_result->invoice_code }}</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row text-center mb-4">
                            <div class="col-6">
                                <p class="text-muted mb-1">Nama Pelanggan</p>
                                <h5>{{ $tracking_result->customer->name }}</h5>
                            </div>
                            <div class="col-6">
                                <p class="text-muted mb-1">Status Saat Ini</p>
                                @if($tracking_result->status == 'pending') 
                                    <span class="badge bg-secondary fs-5">Baru Masuk / Antri</span>
                                @elseif($tracking_result->status == 'process') 
                                    <span class="badge bg-info fs-5">Sedang Dicuci</span>
                                @elseif($tracking_result->status == 'ready') 
                                    <span class="badge bg-warning fs-5">Siap Diambil</span>
                                @elseif($tracking_result->status == 'done') 
                                    <span class="badge bg-success fs-5">Sudah Diambil</span>
                                @endif
                            </div>
                        </div>

                        <div class="alert alert-light border text-center">
                            <small class="text-muted">Total Tagihan:</small>
                            <h3 class="text-primary">Rp {{ number_format($tracking_result->total_price) }}</h3>
                            <small class="text-{{ $tracking_result->payment_status == 'paid' ? 'success' : 'danger' }} fw-bold">
                                {{ $tracking_result->payment_status == 'paid' ? '(LUNAS)' : '(BELUM BAYAR)' }}
                            </small>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>

        <div class="row mt-4 mb-5">
            <div class="col-12 text-center mb-4">
                <h3 class="fw-bold">Daftar Layanan Kami</h3>
            </div>
            
            @foreach($services as $service)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <div class="avatar avatar-xl bg-light-primary text-primary mb-3 fs-3">
                            <i class="bi bi-basket"></i>
                        </div>
                        <h5 class="card-title">{{ $service->name }}</h5>
                        <h3 class="text-primary fw-bold my-3">Rp {{ number_format($service->price) }}</h3>
                        <p class="text-muted">per {{ $service->unit }}</p>
                        <p class="small text-muted">{{ $service->description ?? 'Estimasi ' . $service->estimate_duration . ' Jam' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center text-muted py-4">
            &copy; 2026 LaundryKuy. Dibuat dengan Cinta & Laravel.
        </div>
    </div>

</body>
</html>