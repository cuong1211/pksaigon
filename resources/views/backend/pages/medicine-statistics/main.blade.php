@extends('backend.layout.index')

@section('title', 'Thống kê kho thuốc')

@section('breadcrumb')
    <div class="page-title d-flex flex-column me-5">
        <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Thống kê kho thuốc</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin') }}" class="text-muted text-hover-primary">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">Thống kê kho thuốc</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">

                <!-- Overview Statistics Cards -->
                <div class="row g-5 g-xl-8 mb-8">
                    <div class="col-xl-12">
                        <div class="card card-xl-stretch">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Tổng quan kho thuốc</span>
                                    <span class="text-muted fw-bold fs-7">Thống kê tổng quát về tình trạng kho thuốc</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-light-primary" id="refreshOverview">
                                        <i class="fas fa-sync-alt"></i> Làm mới
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="overviewStatsContainer">
                                <!-- Sẽ được load bằng AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row g-5 g-xl-8 mb-8">
                    <!-- Medicine Types Chart -->
                    <div class="col-xl-6">
                        <div class="card card-xl-stretch">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Phân loại thuốc</span>
                                    <span class="text-muted fw-bold fs-7">Biểu đồ phân bố theo loại thuốc</span>
                                </h3>
                            </div>
                            <div class="card-body pt-0">
                                <canvas id="medicineTypesChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Import Trends Chart -->
                    <div class="col-xl-6">
                        <div class="card card-xl-stretch">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Xu hướng nhập kho</span>
                                    <span class="text-muted fw-bold fs-7">12 tháng gần nhất</span>
                                </h3>
                            </div>
                            <div class="card-body pt-0">
                                <canvas id="importTrendsChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Medicines and Expiry Report -->
                <div class="row g-5 g-xl-8 mb-8">
                    <!-- Top Imported Medicines -->
                    <div class="col-xl-6">
                        <div class="card card-xl-stretch">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Top thuốc nhập nhiều</span>
                                    <span class="text-muted fw-bold fs-7">10 thuốc được nhập kho nhiều nhất</span>
                                </h3>
                                <div class="card-toolbar">
                                    <select class="form-select form-select-sm w-150px" id="topMedicinesPeriod">
                                        <option value="all">Tất cả thời gian</option>
                                        <option value="year">Năm nay</option>
                                        <option value="month">Tháng này</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="topMedicinesContainer">
                                <!-- Sẽ được load bằng AJAX -->
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Report -->
                    <div class="col-xl-6">
                        <div class="card card-xl-stretch">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Báo cáo hạn sử dụng</span>
                                    <span class="text-muted fw-bold fs-7">Thuốc hết hạn và sắp hết hạn</span>
                                </h3>
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-sm btn-light-warning" id="viewFullExpiryReport">
                                        <i class="fas fa-list"></i> Xem chi tiết
                                    </button>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="expiryReportContainer">
                                <!-- Sẽ được load bằng AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Type Statistics -->
                <div class="row g-5 g-xl-8 mb-8">
                    <div class="col-xl-12">
                        <div class="card card-xl-stretch">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">Thống kê theo loại thuốc</span>
                                    <span class="text-muted fw-bold fs-7">Chi tiết thống kê cho từng loại thuốc</span>
                                </h3>
                                <div class="card-toolbar">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light-success" id="exportOverview">
                                            <i class="fas fa-file-excel"></i> Xuất tổng quan
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-warning" id="exportExpiry">
                                            <i class="fas fa-file-excel"></i> Xuất hạn sử dụng
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light-info" id="exportImports">
                                            <i class="fas fa-file-excel"></i> Xuất nhập kho
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="typeStatisticsContainer">
                                <!-- Sẽ được load bằng AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expiry Report Modal -->
                <div class="modal fade" id="expiryReportModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="fw-bolder">Báo cáo chi tiết hạn sử dụng</h2>
                                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                                rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                                transform="rotate(45 7.41422 6)" fill="black" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-body" id="expiryReportModalContent">
                                <!-- Nội dung sẽ được load bằng AJAX -->
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('csscustom')
    <style>
        .stats-card {
            transition: transform 0.2s ease;
            cursor: pointer;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .stats-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #7E8299;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .stats-icon i {
            font-size: 28px;
            color:#e9ecef
        }

        .medicine-item {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .medicine-item:last-child {
            border-bottom: none;
        }

        .medicine-rank {
            width: 30px;
            height: 30px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .medicine-rank.top-3 {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #333;
        }

        .expiry-item {
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .expiry-expired {
            background: #fff5f5;
            border-left-color: #dc3545;
        }

        .expiry-warning {
            background: #fffbf0;
            border-left-color: #ffc107;
        }

        .expiry-caution {
            background: #f0f8ff;
            border-left-color: #0dcaf0;
        }

        .type-stats-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .progress-custom {
            height: 8px;
            border-radius: 4px;
            background: #e9ecef;
        }

        .progress-bar-custom {
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Responsive cho mobile */
        @media (max-width: 768px) {
            .stats-number {
                font-size: 1.8rem;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
            }

            .stats-icon i {
                font-size: 24px;
            }
        }
    </style>
@endpush

@push('jscustom')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('backend.pages.medicine-statistics.js')
@endpush
