@extends('backend.layout.index')

@section('title', 'Quản lý danh sách khám')

@section('breadcrumb')
    <div class="page-title d-flex flex-column me-5">
        <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Quản lý danh sách khám</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin') }}" class="text-muted text-hover-primary">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">Quản lý danh sách khám</li>
        </ul>
    </div>
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container">
                <!-- Statistics Cards -->
                <div class="row g-5 g-xl-8 mb-8" id="statsContainer">
                    <!-- Stats sẽ được load bằng Ajax -->
                </div>

                <!-- Main Table Card -->
                <div class="card card-xl-stretch">
                    <div class="card-header border-0 pt-6">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="black" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="black" />
                                    </svg>
                                </span>
                                <input type="text" data-kt-examination-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-15 search_table"
                                    placeholder="Tìm kiếm phiếu khám..." />
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end align-items-center"
                                data-kt-examination-table-toolbar="base">
                                <!-- Date Filter -->
                                <select class="form-select form-select-solid w-150px me-3 search_table" data-filter="date">
                                    <option value="">Tất cả thời gian</option>
                                    <option value="today">Hôm nay</option>
                                    <option value="week">Tuần này</option>
                                    <option value="month">Tháng này</option>
                                </select>

                                <!-- Status Filter -->
                                <select class="form-select form-select-solid w-150px me-3 search_table"
                                    data-filter="status">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="waiting">Chờ khám</option>
                                    <option value="examining">Đang khám</option>
                                    <option value="completed">Hoàn thành</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>

                                <!-- Payment Filter -->
                                <select class="form-select form-select-solid w-150px me-3 search_table"
                                    data-filter="payment">
                                    <option value="">Tất cả thanh toán</option>
                                    <option value="pending">Chờ thanh toán</option>
                                    <option value="paid">Đã thanh toán</option>
                                    <option value="cancelled">Đã hủy</option>
                                </select>

                                <!-- Export Button -->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                fill="currentColor" />
                                            <path opacity="0.3"
                                                d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    Xuất dữ liệu
                                </button>

                                <!-- Add Examination Button -->
                                <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_examination">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                            <rect x="4.364" y="11.364" width="16" height="2" rx="1"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    Tạo phiếu khám
                                </button>
                            </div>

                            <!-- Bulk Actions (Hidden by default) -->
                            <div class="d-flex justify-content-end align-items-center d-none"
                                data-kt-examination-table-toolbar="selected">
                                <div class="fw-bolder me-5">
                                    <span class="me-2" data-kt-examination-table-select="selected_count"></span>
                                    đã chọn
                                </div>
                                <button type="button" class="btn btn-danger"
                                    data-kt-examination-table-select="delete_selected">
                                    Xóa các mục đã chọn
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_examination_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_examination_table .form-check-input"
                                                value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-125px">Mã phiếu khám</th>
                                    <th class="min-w-200px">Thông tin bệnh nhân</th>
                                    <th class="min-w-100px">Ngày khám</th>
                                    <th class="min-w-150px">Chuẩn đoán</th>
                                    <th class="min-w-100px">Tổng tiền</th>
                                    <th class="min-w-100px">Trạng thái</th>
                                    <th class="min-w-100px">Thanh toán</th>
                                    <th class="text-end min-w-100px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600">
                                <!-- Data will be loaded via DataTables AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Add/Edit Examination -->
                @include('backend.pages.examination.modal')

                <!-- Modal Payment QR -->
                @include('backend.pages.examination.payment-modal')
            </div>
        </div>
    </div>
@endsection

@push('jscustom')
    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('backend.pages.examination.js')
@endpush

@push('csscustom')
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <style>
        /* Custom styles for examination management */
        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .examination-info {
            display: flex;
            flex-direction: column;
        }

        .examination-code {
            font-weight: 600;
            color: #3F4254;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        .patient-info {
            display: flex;
            flex-direction: column;
        }

        .patient-name {
            font-weight: 600;
            color: #181C32;
            line-height: 1.4;
        }

        .patient-phone {
            color: #7E8299;
            font-size: 12px;
            margin-top: 2px;
        }

        .stats-card {
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .price-display {
            font-weight: 600;
            color: #50CD89;
            font-size: 14px;
        }

        /* Dynamic sections in modal */
        .dynamic-section {
            border: 2px dashed #e4e6ef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
            min-height: 120px;
            position: relative;
        }

        .section-header {
            background: white;
            padding: 5px 15px;
            border-radius: 15px;
            position: absolute;
            top: -12px;
            left: 20px;
            font-weight: 600;
            font-size: 14px;
            color: #5e6278;
        }

        .add-item-btn {
            width: 100%;
            border: 2px dashed #009ef7;
            background: transparent;
            color: #009ef7;
            padding: 10px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .add-item-btn:hover {
            background: #009ef7;
            color: white;
        }

        .item-row {
            background: white;
            border: 1px solid #e4e6ef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
        }

        .remove-item {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #f1416c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 12px;
            cursor: pointer;
        }

        /* Select2 custom styling */
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + 1.3rem + 2px) !important;
            border: 1px solid #e4e6ef !important;
            border-radius: 0.475rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 1.3rem) !important;
            padding-left: 1rem !important;
            color: #5e6278 !important;
        }

        /* Payment QR Modal */
        .qr-container {
            text-align: center;
            padding: 20px;
        }

        .qr-code-image {
            max-width: 300px;
            width: 100%;
            height: auto;
            border: 2px solid #e4e6ef;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }

        .payment-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: #50cd89;
            margin: 15px 0;
        }

        .payment-status {
            padding: 10px;
            border-radius: 6px;
            margin: 10px 0;
            font-weight: 600;
        }

        .payment-status.pending {
            background: #fff8dd;
            color: #f1bc00;
            border: 1px solid #f1bc00;
        }

        .payment-status.paid {
            background: #d7f5d7;
            color: #50cd89;
            border: 1px solid #50cd89;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-toolbar .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .card-toolbar .form-select,
            .card-toolbar .btn {
                margin-bottom: 8px;
                margin-right: 0;
            }
        }

        /* DataTable custom styling */
        #kt_examination_table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #kt_examination_table tbody tr {
            border-bottom: 1px solid #E4E6EF;
            transition: all 0.2s ease;
        }

        #kt_examination_table tbody tr:hover {
            background-color: #F9F9F9;
            box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
        }

        #kt_examination_table th {
            background-color: #F7F8FA;
            border-bottom: 1px solid #E4E6EF;
            padding: 12px 8px;
        }

        #kt_examination_table td {
            padding: 16px 8px;
            vertical-align: middle;
        }

        /* Form enhancements */
        .form-control:focus {
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.25);
        }

        .form-select:focus {
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.25);
        }

        /* Button improvements */
        .btn-primary {
            background: linear-gradient(45deg, #009ef7, #0056b3);
            border: none;
            box-shadow: 0 2px 4px rgba(0, 158, 247, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #004085);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 158, 247, 0.4);
        }
    </style>
@endpush
