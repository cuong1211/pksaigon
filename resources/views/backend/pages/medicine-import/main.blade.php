@extends('backend.layout.index')

@section('title', 'Quản lý nhập thuốc')

@section('breadcrumb')
    <div class="page-title d-flex flex-column me-5">
        <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Quản lý nhập thuốc</h1>
        <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('admin') }}" class="text-muted text-hover-primary">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-200 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-dark">Quản lý nhập thuốc</li>
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
                                <input type="text" data-kt-imports-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-15 search_table"
                                    placeholder="Tìm kiếm phiếu nhập..." />
                            </div>
                        </div>

                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end align-items-center" data-kt-imports-table-toolbar="base">
                                <!-- Date Filter -->
                                <select class="form-select form-select-solid w-150px me-3 search_table" data-filter="date">
                                    <option value="">Tất cả thời gian</option>
                                    <option value="today">Hôm nay</option>
                                    <option value="week">Tuần này</option>
                                    <option value="month">Tháng này</option>
                                </select>

                                <!-- Medicine Filter -->
                                <select class="form-select form-select-solid w-200px me-3 search_table"
                                    data-filter="medicine" id="medicine-filter">
                                    <option value="">Tất cả thuốc</option>
                                    <!-- Options sẽ được load bằng Ajax -->
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

                                <!-- Add Import Button -->
                                <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add_customer">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                            <rect x="4.364" y="11.364" width="16" height="2" rx="1"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    Thêm phiếu nhập
                                </button>
                            </div>

                            <!-- Bulk Actions (Hidden by default) -->
                            <div class="d-flex justify-content-end align-items-center d-none"
                                data-kt-imports-table-toolbar="selected">
                                <div class="fw-bolder me-5">
                                    <span class="me-2" data-kt-imports-table-select="selected_count"></span>
                                    đã chọn
                                </div>
                                <button type="button" class="btn btn-danger"
                                    data-kt-imports-table-select="delete_selected">
                                    Xóa các mục đã chọn
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_customers_table .form-check-input"
                                                value="1" />
                                        </div>
                                    </th>
                                    <th class="min-w-125px">Mã phiếu nhập</th>
                                    <th class="min-w-200px">Thông tin thuốc</th>
                                    <th class="min-w-100px">Số lượng</th>
                                    <th class="min-w-100px">Giá nhập</th>
                                    <th class="min-w-100px">Tổng tiền</th>
                                    <th class="min-w-100px">Ngày nhập</th>
                                    <th class="min-w-100px">Hóa đơn</th>
                                    <th class="text-end min-w-100px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600">
                                <!-- Data will be loaded via DataTables AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Add/Edit Import -->
                @include('backend.pages.medicine-import.modal')
            </div>
        </div>
    </div>
@endsection

@push('jscustom')
    <script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
    @include('backend.pages.medicine-import.js')
@endpush

@push('csscustom')
    <style>
        /* Custom styles for medicine imports management */
        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        .medicine-info {
            display: flex;
            flex-direction: column;
        }

        .medicine-name {
            font-weight: 600;
            color: #181C32;
            line-height: 1.4;
        }

        .medicine-code {
            color: #7E8299;
            font-size: 12px;
            background-color: #F1F1F2;
            padding: 2px 6px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 2px;
        }

        .import-code {
            font-weight: 600;
            color: #3F4254;
            font-family: 'Courier New', monospace;
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

        .quantity-display {
            font-weight: 600;
            color: #3F4254;
            font-size: 14px;
        }

        .invoice-preview {
            max-width: 60px;
            max-height: 40px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
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
        #kt_customers_table {
            border-collapse: separate;
            border-spacing: 0;
        }

        #kt_customers_table tbody tr {
            border-bottom: 1px solid #E4E6EF;
            transition: all 0.2s ease;
        }

        #kt_customers_table tbody tr:hover {
            background-color: #F9F9F9;
            box-shadow: 0 0 20px 0 rgba(76, 87, 125, 0.02);
        }

        #kt_customers_table th {
            background-color: #F7F8FA;
            border-bottom: 1px solid #E4E6EF;
            padding: 12px 8px;
        }

        #kt_customers_table td {
            padding: 16px 8px;
            vertical-align: middle;
        }

        /* Modal styles */
        .image-preview-modal .modal-dialog {
            max-width: 800px;
        }

        .image-preview-modal img {
            width: 100%;
            height: auto;
        }
    </style>
@endpush
