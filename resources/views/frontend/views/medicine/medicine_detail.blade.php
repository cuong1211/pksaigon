@extends('frontend.layouts.index')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $medicine->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('frontend.medicines') }}">Sản phẩm</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $medicine->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Medicine Detail Start -->
    <div class="medicine-detail-section">
        <div class="container">
            <div class="row">
                <!-- Medicine Main Content -->
                <div class="col-lg-8">
                    <div class="medicine-detail-main">
                        <!-- Medicine Images -->
                        <div class="medicine-images wow fadeInUp">
                            <div class="main-image">
                                <img src="{{ $medicine->image_url }}" alt="{{ $medicine->name }}" class="img-fluid rounded"
                                    id="mainImage">

                                @if ($medicine->is_expired)
                                    <div class="medicine-status expired">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Sản phẩm đã hết hạn
                                    </div>
                                @elseif($medicine->is_expiring_soon)
                                    <div class="medicine-status expiring">
                                        <i class="fas fa-clock"></i>
                                        Sản phẩm sắp hết hạn
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Medicine Info -->
                        <div class="medicine-info wow fadeInUp" data-wow-delay="0.2s">
                            <div class="medicine-meta">
                                <span
                                    class="medicine-type-badge badge-{{ $medicine->type === 'supplement' ? 'success' : 'primary' }}">
                                    {{ $medicine->type_name }}
                                </span>

                                @if ($medicine->expiry_date)
                                    <span class="medicine-expiry">
                                        <i class="fas fa-calendar-alt"></i>
                                        HSD: {{ $medicine->expiry_date->format('d/m/Y') }}
                                    </span>
                                @endif
                            </div>

                            <h1 class="medicine-title">{{ $medicine->name }}</h1>

                            <div class="medicine-price">
                                <span class="current-price">{{ $medicine->formatted_sale_price }}</span>
                                @if ($medicine->import_price < $medicine->sale_price)
                                    <span class="original-price">{{ $medicine->formatted_import_price }}</span>
                                    <span class="discount-percent">
                                        -{{ round((($medicine->sale_price - $medicine->import_price) / $medicine->sale_price) * 100) }}%
                                    </span>
                                @endif
                            </div>

                            @if ($medicine->description)
                                <div class="medicine-description">
                                    <h3>Mô tả sản phẩm</h3>
                                    <div class="description-content">
                                        {!! $medicine->description !!}
                                    </div>
                                </div>
                            @endif

                            <!-- Contact Info -->
                            <div class="contact-info">
                                <h4>Liên hệ tư vấn</h4>
                                <div class="contact-details">
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <span>Hotline: <a href="tel:+84123456789">+(84) 123 456 789</a></span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>123 Đường Nguyễn Văn Cừ, Quận 1, TP. HCM</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="fas fa-clock"></i>
                                        <span>Thứ 2 - Chủ nhật: 8:00 - 17:00</span>
                                    </div>
                                </div>

                                <div class="contact-actions">
                                    <a href="{{ route('contact') }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-comments"></i>
                                        Liên hệ tư vấn
                                    </a>
                                    <a href="{{ route('frontend.appointment') }}" class="btn btn-success btn-lg">
                                        <i class="fas fa-calendar-check"></i>
                                        Đặt lịch khám
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="medicine-sidebar">
                        <!-- Medicine Specifications -->
                        <div class="sidebar-widget medicine-specs wow fadeInUp" data-wow-delay="0.3s">
                            <h4 class="widget-title">Thông tin sản phẩm</h4>
                            <div class="specs-list">
                                <div class="spec-item">
                                    <span class="spec-label">Loại sản phẩm:</span>
                                    <span class="spec-value">{{ $medicine->type_name }}</span>
                                </div>

                                @if ($medicine->expiry_date)
                                    <div class="spec-item">
                                        <span class="spec-label">Hạn sử dụng:</span>
                                        <span
                                            class="spec-value 
                                            @if ($medicine->is_expired) text-danger 
                                            @elseif($medicine->is_expiring_soon) text-warning 
                                            @else text-success @endif">
                                            {{ $medicine->expiry_date->format('d/m/Y') }}
                                        </span>
                                    </div>
                                @endif

                                <div class="spec-item">
                                    <span class="spec-label">Trạng thái:</span>
                                    <span class="spec-value">
                                        @if ($medicine->is_active)
                                            <span class="badge badge-success">Còn hàng</span>
                                        @else
                                            <span class="badge badge-danger">Hết hàng</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Contact -->
                        <div class="sidebar-widget quick-contact wow fadeInUp" data-wow-delay="0.4s">
                            <h4 class="widget-title">Liên hệ nhanh</h4>
                            <div class="quick-contact-form">
                                <form id="quickContactForm">
                                    @csrf
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="name" placeholder="Họ tên *"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="tel" class="form-control" name="phone"
                                            placeholder="Số điện thoại *" required>
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" name="message" rows="3" placeholder="Câu hỏi về {{ $medicine->name }}"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane"></i>
                                        Gửi câu hỏi
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Related Products -->
                        @if (isset($relatedMedicines) && $relatedMedicines->count() > 0)
                            <div class="sidebar-widget related-products wow fadeInUp" data-wow-delay="0.5s">
                                <h4 class="widget-title">Sản phẩm liên quan</h4>
                                <div class="related-products-list">
                                    @foreach ($relatedMedicines as $related)
                                        <div class="related-item">
                                            <div class="related-image">
                                                <a
                                                    href="{{ route('frontend.medicines.show', $related->slug ?? $related->id) }}">
                                                    <img src="{{ $related->image_url }}" alt="{{ $related->name }}">
                                                </a>
                                            </div>
                                            <div class="related-info">
                                                <h6>
                                                    <a
                                                        href="{{ route('frontend.medicines.show', $related->slug ?? $related->id) }}">
                                                        {{ Str::limit($related->name, 40) }}
                                                    </a>
                                                </h6>
                                                <div class="related-price">
                                                    {{ $related->formatted_sale_price }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Medicine Detail End -->
@endsection

@push('csscustom')
    <style>
        /* Medicine Detail Styles */
        .medicine-detail-section {
            padding: 60px 0;
        }

        /* Main Image */
        .medicine-images {
            margin-bottom: 40px;
            position: relative;
        }

        .main-image {
            position: relative;
            text-align: center;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
        }

        .main-image img {
            max-height: 400px;
            width: auto;
            border-radius: 8px;
        }

        .medicine-status {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .medicine-status.expired {
            background: #dc3545;
        }

        .medicine-status.expiring {
            background: #ffc107;
            color: #000;
        }

        /* Medicine Info */
        .medicine-info {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .medicine-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .medicine-type-badge {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-primary {
            background: #007bff;
        }

        .medicine-expiry {
            color: #666;
            font-size: 14px;
        }

        .medicine-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .medicine-price {
            margin-bottom: 30px;
        }

        .current-price {
            font-size: 32px;
            font-weight: 700;
            color: #28a745;
        }

        .original-price {
            font-size: 20px;
            color: #999;
            text-decoration: line-through;
            margin-left: 15px;
        }

        .discount-percent {
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        .medicine-description {
            margin-bottom: 40px;
        }

        .medicine-description h3 {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        .description-content {
            color: #666;
            line-height: 1.7;
            font-size: 15px;
        }

        .description-content p {
            margin-bottom: 15px;
        }

        .description-content ul,
        .description-content ol {
            padding-left: 20px;
            margin-bottom: 15px;
        }

        .description-content li {
            margin-bottom: 5px;
        }

        /* Contact Info */
        .contact-info {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            border-left: 4px solid #007bff;
        }

        .contact-info h4 {
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .contact-details {
            margin-bottom: 25px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #666;
        }

        .contact-item i {
            color: #007bff;
            width: 20px;
            margin-right: 10px;
        }

        .contact-item a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        .contact-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .contact-actions .btn {
            flex: 1;
            min-width: 150px;
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 20px;
        }

        /* Sidebar */
        .medicine-sidebar {
            padding-left: 20px;
        }

        .sidebar-widget {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .widget-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }

        /* Medicine Specs */
        .specs-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .spec-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .spec-label {
            font-weight: 600;
            color: #666;
        }

        .spec-value {
            font-weight: 600;
            color: #333;
        }

        .spec-value .badge {
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-danger {
            background: #dc3545;
            color: white;
        }

        /* Quick Contact Form */
        .quick-contact-form .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            font-size: 14px;
        }

        .quick-contact-form .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .quick-contact-form .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 12px 20px;
        }

        /* Related Products */
        .related-products-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .related-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .related-item:hover {
            border-color: #007bff;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
        }

        .related-image {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .related-info h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .related-info h6 a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .related-info h6 a:hover {
            color: #007bff;
        }

        .related-price {
            font-size: 14px;
            font-weight: 600;
            color: #28a745;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .medicine-sidebar {
                padding-left: 0;
                margin-top: 40px;
            }

            .medicine-title {
                font-size: 24px;
            }

            .current-price {
                font-size: 28px;
            }

            .contact-actions {
                justify-content: center;
            }

            .contact-actions .btn {
                flex: none;
                width: 100%;
                margin-bottom: 10px;
            }
        }

        @media (max-width: 768px) {
            .medicine-detail-section {
                padding: 40px 0;
            }

            .medicine-info {
                padding: 20px;
            }

            .medicine-title {
                font-size: 22px;
            }

            .current-price {
                font-size: 24px;
            }

            .medicine-meta {
                justify-content: center;
                text-align: center;
            }

            .sidebar-widget {
                padding: 20px;
            }

            .contact-actions {
                flex-direction: column;
            }

            .spec-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }

            .main-image img {
                max-height: 300px;
            }
        }

        @media (max-width: 576px) {
            .medicine-title {
                font-size: 20px;
            }

            .current-price {
                font-size: 22px;
            }

            .original-price {
                font-size: 16px;
            }

            .medicine-info {
                padding: 15px;
            }

            .sidebar-widget {
                padding: 15px;
            }

            .main-image {
                padding: 15px;
            }

            .main-image img {
                max-height: 250px;
            }
        }
    </style>
@endpush

@push('jscustom')
    <script>
        $(document).ready(function() {
            // Quick Contact Form
            $('#quickContactForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let submitBtn = $(this).find('button[type="submit"]');
                let originalText = submitBtn.html();

                // Show loading
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang gửi...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('contact.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.type === 'success') {
                            notification('success', response.title, response.content);
                            $('#quickContactForm')[0].reset();
                        } else {
                            notification('error', 'Lỗi', 'Có lỗi xảy ra khi gửi thông tin');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMsg = '';
                            $.each(errors, function(key, value) {
                                errorMsg += value[0] + '\n';
                            });
                            notification('error', 'Lỗi validation', errorMsg);
                        } else {
                            notification('error', 'Lỗi',
                                'Có lỗi xảy ra khi gửi thông tin. Vui lòng thử lại!');
                        }
                    },
                    complete: function() {
                        // Restore button
                        submitBtn.html(originalText).prop('disabled', false);
                    }
                });
            });

            // Image zoom effect
            $('#mainImage').on('click', function() {
                let src = $(this).attr('src');
                Swal.fire({
                    imageUrl: src,
                    imageWidth: 600,
                    imageHeight: 400,
                    imageAlt: '{{ $medicine->name }}',
                    showCloseButton: true,
                    showConfirmButton: false,
                    customClass: {
                        image: 'img-fluid'
                    }
                });
            });

            // Smooth scroll for internal links
            $('a[href^="#"]').on('click', function(e) {
                e.preventDefault();
                let target = $(this.getAttribute('href'));
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 100
                    }, 500);
                }
            });
        });
    </script>
@endpush
