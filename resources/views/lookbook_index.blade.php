@extends('layouts.admin')

@section('title', 'Dashboard | Lookbook')
@section('page_title', 'Dashboard Rekap IT')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .stat-card {
            border-radius: 10px;
            transition: transform .2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .chart-box {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .rec-item {
            border-left: 4px solid #007bff;
            padding: 10px 15px;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-radius: 0 5px 5px 0;
        }

        .rec-item.danger {
            border-color: #dc3545;
        }

        .rec-item.warning {
            border-color: #ffc107;
        }

        .rec-item.success {
            border-color: #28a745;
        }
    </style>
@endsection

@section('content')

    <!-- Stat Cards Row -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-info stat-card">
                <div class="inner">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Seluruh Laporan</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                <a href="{{ route('lookbook.data') }}" class="small-box-footer">Lihat Semua <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-success stat-card">
                <div class="inner">
                    <h3>{{ $stats['selesai'] }}</h3>
                    <p>Selesai Ditangani</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ route('lookbook.data') }}" class="small-box-footer">Lihat Data <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-warning stat-card">
                <div class="inner">
                    <h3>{{ $stats['proses'] }}</h3>
                    <p>Masih Dalam Proses</p>
                </div>
                <div class="icon"><i class="fas fa-tools"></i></div>
                <a href="{{ route('lookbook.data') }}" class="small-box-footer">Lihat Data <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-gradient-danger stat-card">
                <div class="inner">
                    <h3>{{ $stats['batal'] }}</h3>
                    <p>Tertunda / Dibatalkan</p>
                </div>
                <div class="icon"><i class="fas fa-ban"></i></div>
                <a href="{{ route('lookbook.data') }}" class="small-box-footer">Lihat Data <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Charts + Summary Row -->
    <div class="row">
        <!-- Pie Chart -->
        <div class="col-md-5">
            <div class="chart-box mb-3">
                <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-chart-pie mr-1"></i> Distribusi Kategori
                    Pekerjaan (All-Time)</h6>
                <canvas id="pieChart" style="min-height:170px; height:170px; max-height:170px;"></canvas>
            </div>
        </div>

        <!-- Summary Cards per Kategori -->
        <div class="col-md-7">
            <div class="chart-box mb-3">
                <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-th-list mr-1"></i> Ringkasan per Kategori
                </h6>
                <div class="rec-item danger">
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-desktop mr-2"></i> Hardware (Perangkat Keras)</span>
                        <span class="badge badge-danger badge-pill" style="font-size:14px;">{{ $stats['hardware'] }}</span>
                    </div>
                    <small class="text-muted">Perbaikan / Penggantian Komponen</small>
                </div>
                <div class="rec-item warning" style="border-color:#6f42c1;">
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-code mr-2"></i> Software (Perangkat Lunak)</span>
                        <span class="badge badge-pill text-white"
                            style="background:#6f42c1; font-size:14px;">{{ $stats['software'] }}</span>
                    </div>
                    <small class="text-muted">Instalasi / Troubleshoot Aplikasi & OS</small>
                </div>
                <div class="rec-item success">
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-broom mr-2"></i> Pembersihan</span>
                        <span class="badge badge-success badge-pill"
                            style="font-size:14px;">{{ $stats['pembersihan'] }}</span>
                    </div>
                    <small class="text-muted">Perawatan Kebersihan Perangkat & Ruangan</small>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <span class="badge badge-success mr-1">{{ $stats['selesai'] }} Selesai</span>
                        <span class="badge badge-warning mr-1">{{ $stats['proses'] }} Proses</span>
                        <span class="badge badge-danger">{{ $stats['batal'] }} Batal</span>
                    </div>
                    <a href="{{ route('lookbook.data') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-table mr-1"></i> Kelola Data Lookbook
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    $(function () {
        var canvas = document.getElementById('pieChart');
        if (!canvas) return; // Prevent error on other pages

        var ctx = canvas.getContext('2d');
        new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Hardware', 'Software', 'Pembersihan'],
                    datasets: [{
                        data: [{{ $stats['hardware'] }}, {{ $stats['software'] }}, {{ $stats['pembersihan'] }}],
                        backgroundColor: ['#dc3545', '#6f42c1', '#28a745'],
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
@endsection