@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Overview')

@section('content')
<div class="page-content pb-3">
    
    {{-- =======================
         BARIS 1: KARTU STATISTIK
         ======================= --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Omset Hari Ini --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-soft h-100 rounded-4 card-hover">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stats-icon flex-shrink-0 rounded-3 box-center bg-light-primary text-primary shadow-sm" 
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-wallet2 fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted fw-bold mb-0 text-uppercase ls-1" style="font-size: 0.65rem;">Omset Hari Ini</h6>
                        <h5 class="fw-heading mb-0 text-dark" style="font-size: 1.1rem;">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Total Saldo --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-soft h-100 rounded-4 card-hover">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stats-icon flex-shrink-0 rounded-3 box-center bg-light-success text-success shadow-sm" 
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted fw-bold mb-0 text-uppercase ls-1" style="font-size: 0.65rem;">Total Saldo</h6>
                        <h5 class="fw-heading mb-0 text-dark" style="font-size: 1.1rem;">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Transaksi --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-soft h-100 rounded-4 card-hover">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stats-icon flex-shrink-0 rounded-3 box-center bg-light-info text-info shadow-sm" 
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-receipt fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted fw-bold mb-0 text-uppercase ls-1" style="font-size: 0.65rem;">Transaksi</h6>
                        <h5 class="fw-heading mb-0 text-dark" style="font-size: 1.1rem;">{{ number_format($totalTransactions, 0, ',', '.') }} <small class="text-muted fs-6 fw-normal">Data</small></h5>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Pelanggan --}}
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-soft h-100 rounded-4 card-hover">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="stats-icon flex-shrink-0 rounded-3 box-center bg-light-warning text-warning shadow-sm" 
                         style="width: 48px; height: 48px;">
                        <i class="bi bi-people fs-5"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted fw-bold mb-0 text-uppercase ls-1" style="font-size: 0.65rem;">Pelanggan</h6>
                        <h5 class="fw-heading mb-0 text-dark" style="font-size: 1.1rem;">{{ number_format($totalCustomers, 0, ',', '.') }} <small class="text-muted fs-6 fw-normal">Orang</small></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =======================
         BARIS 2: GRAFIK & LIST
         ======================= --}}
    <div class="row g-4">
        
        {{-- GRAFIK PENDAPATAN --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-soft rounded-4 h-100 card-hover border-light-subtle">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-heading mb-1 d-flex align-items-center gap-2">
                            <i class="bi bi-graph-up-arrow text-primary"></i> Laju Pendapatan
                        </h5>
                        <p class="text-muted small mb-0">Statistik pemasukan 7 hari terakhir.</p>
                    </div>
                </div>
                <div class="card-body px-2 pb-2 mt-2">
                    <div id="chart-pendapatan"></div>
                </div>
            </div>
        </div>

        {{-- TRANSAKSI TERBARU --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-soft rounded-4 h-100 overflow-hidden card-hover border-light-subtle">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-heading mb-0">Antrean Terbaru</h5>
                    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light-primary rounded-pill px-3 fw-bold small">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($latestTransactions as $trx)
                        <div class="list-group-item border-0 px-4 py-3 d-flex align-items-center hover-bg-light transition-300">
                            {{-- AVATAR --}}
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar bg-light-primary text-primary rounded-circle box-center shadow-sm border border-white" 
                                     style="width: 44px; height: 44px;">
                                    <span class="fw-bold">{{ substr($trx->customer->name, 0, 1) }}</span>
                                </div>
                            </div>
                            
                            {{-- TEXT --}}
                            <div class="flex-grow-1 min-width-0 pe-2">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 0.9rem;">{{ Str::limit($trx->customer->name, 18) }}</h6>
                                <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">{{ $trx->invoice_code }}</small>
                            </div>

                            {{-- PRICE & BADGE --}}
                            <div class="text-end flex-shrink-0 d-flex flex-column align-items-end justify-content-center">
                                <div class="fw-bold text-primary mb-1" style="font-size: 0.9rem;">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</div>
                                @if($trx->payment_status == 'paid')
                                    <span class="badge bg-light-success text-success border-0 rounded-pill px-2" style="font-size: 0.6rem;">LUNAS</span>
                                @else
                                    <span class="badge bg-light-danger text-danger border-0 rounded-pill px-2" style="font-size: 0.6rem;">PENDING</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 px-4">
                            <div class="bg-light rounded-circle box-center mx-auto mb-3" style="width: 70px; height: 70px;">
                                <i class="bi bi-inbox fs-1 opacity-25 text-muted"></i>
                            </div>
                            <p class="text-muted fw-bold mb-0">Belum ada transaksi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS TAMBAHAN --}}
<style>
    /* Scrollbar List */
    .card-body.p-0 { max-height: 420px; overflow-y: auto; }
    .card-body.p-0::-webkit-scrollbar { width: 4px; }
    .card-body.p-0::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

{{-- SCRIPT CHART --}}
<script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script>
    var options = {
        series: [{ name: 'Pendapatan', data: @json($chartData) }],
        chart: {
            height: 350, 
            type: 'area', 
            fontFamily: 'Plus Jakarta Sans, sans-serif',
            toolbar: { show: false }, 
            zoom: { enabled: false },
            offsetY: 10
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3, colors: ['#2563EB'] },
        
        xaxis: {
            categories: @json($chartCategories),
            axisBorder: { show: false }, 
            axisTicks: { show: false },
            labels: { 
                style: { colors: '#64748b', fontSize: '12px', fontFamily: 'Plus Jakarta Sans', fontWeight: 600 },
                offsetY: 5
            },
            crosshairs: { show: false },
            tooltip: { enabled: false }
        },
        
        yaxis: {
            labels: {
                align: 'right',
                minWidth: 100, 
                formatter: function (value) { 
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
                },
                style: { colors: '#475569', fontSize: '11px', fontWeight: 600, fontFamily: 'Plus Jakarta Sans' },
                offsetX: -10 
            },
        },
        
        fill: {
            type: "gradient",
            gradient: { 
                shadeIntensity: 1, 
                opacityFrom: 0.5, 
                opacityTo: 0.05, 
                stops: [0, 100], 
                colorStops: [ { offset: 0, color: '#2563EB', opacity: 0.5 }, { offset: 100, color: '#2563EB', opacity: 0 } ] 
            }
        },
        
        tooltip: {
            y: { formatter: function (val) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val); } },
            theme: 'light', 
            style: { fontFamily: 'Plus Jakarta Sans' }
        },
        
        colors: ['#2563EB'],
        
        grid: { 
            show: true,
            borderColor: '#f1f5f9', 
            strokeDashArray: 4,
            padding: { top: 0, right: 20, bottom: 0, left: 15 } 
        }
    };
    
    var chart = new ApexCharts(document.querySelector("#chart-pendapatan"), options);
    chart.render();
</script>
    <style>
        .hover-scale:hover { transform: translateY(-2px); }
    </style>
    @endsection