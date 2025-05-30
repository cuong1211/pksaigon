@extends('frontend.layouts.index')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque"><span>Thực phẩm</span> Bổ sung</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Medicines Start -->
    <div class="page-medicines">
        <div class="container">
            <!-- Filter Section -->
            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="medicines-filter wow fadeInUp">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0">Hiển thị
                                    {{ $medicines->firstItem() ?? 0 }}-{{ $medicines->lastItem() ?? 0 }} trong tổng số
                                    {{ $medicines->total() }} sản phẩm</p>
                            </div>
                            <div class="col-md-6">
                                <div class="medicines-search">
                                    <form method="GET" action="{{ route('frontend.medicines') }}" class="d-flex">
                                        <input type="text" name="search" class="form-control me-2"
                                            placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row">
                @forelse($medicines as $medicine)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <!-- Medicine Item Start -->
                        <div class="medicine-item wow fadeInUp" data-wow-delay="{{ $loop->index * 0.1 }}s">
                            <!-- Medicine Image Start -->
                            <div class="medicine-image" data-cursor-text="Xem">
                                <figure>
                                    <a href="{{ route('frontend.medicines.show', $medicine->slug ?? $medicine->id) }}"
                                        class="image-anime">
                                        <img src="{{ $medicine->image_url }}" alt="{{ $medicine->name }}">
                                    </a>
                                </figure>
                                @if ($medicine->is_expired)
                                    <div class="medicine-badge expired">Hết hạn</div>
                                @elseif($medicine->is_expiring_soon)
                                    <div class="medicine-badge expiring">Sắp hết hạn</div>
                                @endif
                            </div>
                            <!-- Medicine Image End -->

                            <!-- Medicine Content Start -->
                            <div class="medicine-content">
                                <div class="medicine-type">
                                    <span
                                        class="badge badge-{{ $medicine->type === 'supplement' ? 'success' : 'primary' }}">
                                        {{ $medicine->type_name }}
                                    </span>
                                </div>

                                <h3 class="medicine-title">
                                    <a href="{{ route('frontend.medicines.show', $medicine->slug ?? $medicine->id) }}">
                                        {{ $medicine->name }}
                                    </a>
                                </h3>

                                @if ($medicine->description)
                                    <p class="medicine-description">
                                        {{ Str::limit(strip_tags($medicine->description), 120) }}
                                    </p>
                                @endif

                                <div class="medicine-price">
                                    <span class="current-price">{{ $medicine->formatted_sale_price }}</span>
                                    @if ($medicine->import_price < $medicine->sale_price)
                                        <span class="original-price">{{ $medicine->formatted_import_price }}</span>
                                    @endif
                                </div>

                                @if ($medicine->expiry_date)
                                    <div class="medicine-expiry">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            HSD: {{ $medicine->expiry_date->format('d/m/Y') }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                            <!-- Medicine Content End -->

                            <!-- Medicine Action Start -->
                            <div class="medicine-action">
                                <a href="{{ route('frontend.medicines.show', $medicine->slug ?? $medicine->id) }}"
                                    class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye me-2"></i>Xem chi tiết
                                </a>
                            </div>
                            <!-- Medicine Action End -->
                        </div>
                        <!-- Medicine Item End -->
                    </div>
                @empty
                    <div class="col-lg-12">
                        <div class="no-medicines text-center py-5">
                            <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                            <h4>Không tìm thấy sản phẩm nào</h4>
                            <p class="text-muted">Hiện tại chưa có sản phẩm nào trong danh mục này.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">Về trang chủ</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($medicines->hasPages())
                <div class="row">
                    <div class="col-md-12">
                        <div class="medicines-pagination wow fadeInUp" data-wow-delay="0.5s">
                            {{ $medicines->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Page Medicines End -->
@endsection

@push('csscustom')
    <style>
        /* Medicine Items Styling */
        .medicine-item {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .medicine-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .medicine-image {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .medicine-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .medicine-item:hover .medicine-image img {
            transform: scale(1.05);
        }

        .medicine-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .medicine-badge.expired {
            background: #dc3545;
            color: white;
        }

        .medicine-badge.expiring {
            background: #ffc107;
            color: #000;
        }

        .medicine-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .medicine-type {
            margin-bottom: 10px;
        }

        .medicine-type .badge {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-primary {
            background: #007bff;
            color: white;
        }

        .medicine-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .medicine-title a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .medicine-title a:hover {
            color: #007bff;
        }

        .medicine-description {
            color: #666;
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .medicine-price {
            margin-bottom: 10px;
        }

        .current-price {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
        }

        .original-price {
            font-size: 14px;
            color: #999;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .medicine-expiry {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .medicine-action {
            padding: 0 20px 20px;
        }

        .medicine-action .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Filter Section */
        .medicines-filter {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .medicines-search .form-control {
            border-radius: 25px;
            border: 1px solid #ddd;
            padding: 10px 20px;
        }

        .medicines-search .btn {
            border-radius: 25px;
            padding: 10px 20px;
        }

        /* Pagination */
        .medicines-pagination {
            margin-top: 40px;
            text-align: center;
        }

        .medicines-pagination .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 4px;
            border: 1px solid #ddd;
            color: #007bff;
            padding: 10px 15px;
        }

        .pagination .page-link:hover {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination .page-item.active .page-link {
            background: #007bff;
            border-color: #007bff;
        }

        /* No medicines state */
        .no-medicines {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 60px 20px;
        }

        .no-medicines i {
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .medicines-filter .row {
                text-align: center;
            }

            .medicines-filter .col-md-6:first-child {
                margin-bottom: 15px;
            }

            .medicine-image {
                height: 180px;
            }

            .medicine-content {
                padding: 15px;
            }

            .medicine-title {
                font-size: 16px;
            }
        }
    </style>
@endpush
