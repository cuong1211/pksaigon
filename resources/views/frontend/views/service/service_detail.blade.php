@extends('frontend.layouts.index')
@section('content')
    <!-- Page Header Start -->
    {{-- <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $service->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('frontend.services') }}">Dịch vụ</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Page Header End -->

    <!-- Page Service Single Start -->
    <!-- Page Service Single Start -->
    <div class="page-service-single">
        <div class="container">
            <div class="row">
                <!-- Sidebar - Order 2 trên mobile, Order 1 trên desktop -->
                <div class="col-lg-4 order-2 order-lg-1">
                    <!-- Service Sidebar Start -->
                    <div class="service-sidebar">
                        <!-- Service Info Card Start -->
                        <div class="service-info-card wow fadeInUp">
                            <div class="service-price-box">
                                <h3>Giá dịch vụ</h3>
                                <div class="price">{{ $service->formatted_price }}</div>
                            </div>
                            <div class="service-actions">
                                <a href="{{ route('frontend.appointment') }}" class="btn-default">Đặt lịch khám</a>
                                <a href="{{ route('contact') }}" class="btn-outline">Tư vấn miễn phí</a>
                            </div>
                        </div>
                        <!-- Service Info Card End -->

                        <!-- Service Categories List Start -->
                        <div class="service-categories-list wow fadeInUp" data-wow-delay="0.25s">
                            <h3>Danh mục dịch vụ</h3>

                            @if (isset($allServices['procedure']) && $allServices['procedure']->count() > 0)
                                <div class="service-category-group">
                                    <h4><i class="fas fa-stethoscope"></i> Thủ thuật</h4>
                                    <ul>
                                        @foreach ($allServices['procedure'] as $procedureService)
                                            <li class="{{ $procedureService->id == $service->id ? 'active' : '' }}">
                                                <a href="{{ route('frontend.services.show', $procedureService->slug) }}">
                                                    {{ $procedureService->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (isset($allServices['laboratory']) && $allServices['laboratory']->count() > 0)
                                <div class="service-category-group">
                                    <h4><i class="fas fa-microscope"></i> Xét nghiệm</h4>
                                    <ul>
                                        @foreach ($allServices['laboratory'] as $labService)
                                            <li class="{{ $labService->id == $service->id ? 'active' : '' }}">
                                                <a href="{{ route('frontend.services.show', $labService->slug) }}">
                                                    {{ $labService->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (isset($allServices['other']) && $allServices['other']->count() > 0)
                                <div class="service-category-group">
                                    <h4><i class="fas fa-plus-circle"></i> Dịch vụ khác</h4>
                                    <ul>
                                        @foreach ($allServices['other'] as $otherService)
                                            <li class="{{ $otherService->id == $service->id ? 'active' : '' }}">
                                                <a href="{{ route('frontend.services.show', $otherService->slug) }}">
                                                    {{ $otherService->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <!-- Service Categories List End -->

                        <!-- Sidebar CTA Box Start -->
                        <div class="sidebar-cta-box wow fadeInUp" data-wow-delay="0.5s">
                            <div class="icon-box">
                                <img src="{{ asset('frontend/images/icon-cta.svg') }}" alt="">
                            </div>
                            <div class="cta-content">
                                <h3>Cần hỗ trợ?</h3>
                                <p>Đặt lịch tư vấn miễn phí ngay hôm nay và bắt đầu hành trình chăm sóc sức khỏe. Liên hệ
                                    với chúng tôi ngay!</p>
                            </div>
                            <div class="cta-contact-btn">
                                <a href="{{ route('contact') }}" class="btn-default">Liên hệ ngay</a>
                            </div>
                        </div>
                        <!-- Sidebar CTA Box End -->
                    </div>
                    <!-- Service Sidebar End -->
                </div>

                <!-- Main Content - Order 1 trên mobile, Order 2 trên desktop -->
                <div class="col-lg-8 order-1 order-lg-2">
                    <!-- Service Single Content Start -->
                    <div class="service-single-content">
                        <!-- Service Featured Image Start -->
                        <div class="service-featured-image">
                            <figure class="reveal image-anime">
                                @if ($service->image || !file_exists(public_path('storage/' . $service->image)))
                                    <img src="{{ $service->image_url }}" alt="{{ $service->name }}">
                                @else
                                    @php
                                        $iconMap = [
                                            'procedure' => 'frontend/images/favicon_1.png',
                                            'laboratory' => 'frontend/images/favicon_1.png',
                                            'other' => 'frontend/images/favicon_1.png',
                                        ];
                                        $iconPath = $iconMap[$service->type] ?? $iconMap['other'];
                                        $iconUrl = app()->environment('production')
                                            ? url('public/' . $iconPath)
                                            : url($iconPath);
                                    @endphp
                                    <img src="{{ $iconUrl }}" alt="{{ $service->name }}">
                                @endif
                            </figure>
                            <div class="service-overlay-info">
                                <div class="service-type-large">
                                    <span class="badge badge-{{ $service->type }}">{{ $service->type_name }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Service Featured Image End -->

                        <!-- Service Entry Content Start -->
                        <div class="service-entry">
                            <div class="service-header">
                                <h1 class="wow fadeInUp">{{ $service->name }}</h1>
                                <div class="service-meta wow fadeInUp" data-wow-delay="0.2s">
                                    {{-- <span class="service-price-meta">{{ $service->formatted_price }}</span> --}}
                                    <span class="service-type-meta">{{ $service->type_name }}</span>
                                </div>
                            </div>

                            <div class="service-description">
                                <p class="wow fadeInUp" data-wow-delay="0.4s">{!! $service->description !!}</p>
                            </div>

                            <!-- Service Benefits Start -->
                            <div class="service-benefits wow fadeInUp" data-wow-delay="0.6s">
                                <h3>Lợi ích của dịch vụ</h3>
                                <ul>
                                    @if ($service->type == 'procedure')
                                        <li>Thủ thuật an toàn với công nghệ hiện đại</li>
                                        <li>Đội ngũ bác sĩ giàu kinh nghiệm</li>
                                        <li>Quy trình chuẩn y khoa quốc tế</li>
                                        <li>Theo dõi sát sao sau thủ thuật</li>
                                    @elseif($service->type == 'laboratory')
                                        <li>Kết quả xét nghiệm chính xác</li>
                                        <li>Máy móc hiện đại nhập khẩu</li>
                                        <li>Thời gian trả kết quả nhanh</li>
                                        <li>Tư vấn kết quả chi tiết</li>
                                    @else
                                        <li>Dịch vụ chất lượng cao</li>
                                        <li>Đội ngũ chuyên nghiệp</li>
                                        <li>Trang thiết bị hiện đại</li>
                                        <li>Chăm sóc tận tình</li>
                                    @endif
                                </ul>
                            </div>
                            <!-- Service Benefits End -->

                            <!-- Service Process Start -->
                            <div class="service-process wow fadeInUp" data-wow-delay="0.8s">
                                <h3>Quy trình thực hiện</h3>
                                <div class="process-steps">
                                    <div class="process-step">
                                        <div class="step-number">1</div>
                                        <div class="step-content">
                                            <h4>Đặt lịch hẹn</h4>
                                            <p>Liên hệ đặt lịch qua hotline hoặc website</p>
                                        </div>
                                    </div>
                                    <div class="process-step">
                                        <div class="step-number">2</div>
                                        <div class="step-content">
                                            <h4>Khám sơ bộ</h4>
                                            <p>Bác sĩ thăm khám và tư vấn chi tiết</p>
                                        </div>
                                    </div>
                                    <div class="process-step">
                                        <div class="step-number">3</div>
                                        <div class="step-content">
                                            <h4>Thực hiện dịch vụ</h4>
                                            <p>Tiến hành {{ strtolower($service->type_name) }} theo quy trình chuẩn</p>
                                        </div>
                                    </div>
                                    <div class="process-step">
                                        <div class="step-number">4</div>
                                        <div class="step-content">
                                            <h4>Theo dõi và tư vấn</h4>
                                            <p>Hướng dẫn chăm sóc và hẹn tái khám</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Service Process End -->
                        </div>
                        <!-- Service Entry Content End -->
                    </div>
                    <!-- Service Single Content End -->

                    <!-- Related Services Start -->
                    @if ($relatedServices->count() > 0)
                        <div class="related-services">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">Dịch vụ liên quan</h3>
                            </div>
                            <div class="row">
                                @foreach ($relatedServices as $index => $relatedService)
                                    <div class="col-lg-6 col-md-6 mb-4">
                                        <div class="related-service-item wow fadeInUp"
                                            data-wow-delay="{{ $index * 0.2 }}s">
                                            <div class="related-service-image">
                                                <figure class="image-anime">
                                                    <img src="{{ $relatedService->image_url }}"
                                                        alt="{{ $relatedService->name }}">
                                                </figure>
                                            </div>
                                            <div class="related-service-content">
                                                <h4>{{ $relatedService->name }}</h4>
                                                <p>{{ Str::limit($relatedService->description, 80) }}</p>
                                                <div class="related-service-footer">
                                                    <span class="price">{{ $relatedService->formatted_price }}</span>
                                                    <a href="{{ route('frontend.services.show', $relatedService->slug) }}"
                                                        class="view-btn">Xem chi tiết</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- Related Services End -->

                    <!-- Service FAQ Start -->
                    <div class="service-faqs">
                        <div class="faqs-section-title">
                            <h2 class="text-anime-style-2" data-cursor="-opaque">Câu hỏi thường gặp</h2>
                        </div>

                        <!-- FAQ Accordion Start -->
                        <div class="faq-accordion" id="accordion">
                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp">
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        Dịch vụ {{ strtolower($service->name) }} có an toàn không?
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Dịch vụ được thực hiện bởi đội ngũ bác sĩ có chuyên môn cao và kinh nghiệm lâu
                                            năm, đảm bảo an toàn tuyệt đối cho bệnh nhân.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.25s">
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        Thời gian thực hiện dịch vụ là bao lâu?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Thời gian thực hiện tùy thuộc vào từng trường hợp cụ thể. Bác sĩ sẽ tư vấn chi
                                            tiết về thời gian dự kiến khi khám.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.5s">
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        Có cần chuẩn bị gì trước khi thực hiện dịch vụ?
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Tùy vào loại dịch vụ, có thể cần một số chuẩn bị đặc biệt. Nhân viên sẽ hướng dẫn
                                            chi tiết khi đặt lịch hẹn.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.75s">
                                <h2 class="accordion-header" id="heading4">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        Phòng khám có hỗ trợ bảo hiểm y tế không?
                                    </button>
                                </h2>
                                <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>Chúng tôi hỗ trợ thanh toán qua bảo hiểm y tế và các hình thức thanh toán khác.
                                            Vui lòng liên hệ để biết thêm chi tiết.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->
                        </div>
                        <!-- FAQ Accordion End -->
                    </div>
                    <!-- Service FAQ End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Service Single End -->
    <!-- Page Service Single End -->
@endsection

@push('csscustom')
    <style>
        /* Service Info Card */
        .service-info-card {
            background: #1e85b4;
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            text-align: center;
        }

        .service-price-box h3 {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .service-price-box .price {
            font-size: 2em;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .service-type .badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .service-actions {
            margin-top: 25px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .service-actions .btn-default,
        .service-actions .btn-outline {
            padding: 12px 20px;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .service-actions .btn-default {
            background: white;
            color: #1e85b4;
        }

        .service-actions .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .service-actions .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
        }

        /* Service Categories */
        .service-categories-list {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .service-categories-list h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .service-category-group {
            margin-bottom: 25px;
        }

        .service-category-group h4 {
            color: #1e85b4;
            font-size: 1.1em;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f0f0f0;
        }

        .service-category-group h4 i {
            margin-right: 8px;
        }

        .service-category-group ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-category-group ul li {
            margin-bottom: 8px;
        }

        .service-category-group ul li a {
            color: #666;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            display: block;
            transition: all 0.3s ease;
        }

        .service-category-group ul li a:hover,
        .service-category-group ul li.active a {
            background: #f8f9fa;
            color: #1e85b4;
            text-decoration: none;
        }

        /* Service Single Content */
        .service-featured-image {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .service-overlay-info {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .service-type-large .badge {
            padding: 10px 20px;
            font-size: 1em;
            border-radius: 25px;
        }

        .service-header {
            margin-bottom: 30px;
        }

        .service-header h1 {
            color: #333;
            margin-bottom: 15px;
        }

        .service-meta {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .service-price-meta {
            font-size: 1.5em;
            font-weight: 700;
            color: #1e85b4;
        }

        .service-type-meta {
            background: #f8f9fa;
            color: #666;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        /* Service Benefits */
        .service-benefits {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            margin: 30px 0;
        }

        .service-benefits h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .service-benefits ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .service-benefits ul li {
            padding: 10px 0;
            position: relative;
            padding-left: 30px;
        }

        .service-benefits ul li:before {
            content: '✓';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #1e85b4;
            font-weight: bold;
        }

        /* Service Process */
        .service-process {
            margin: 30px 0;
        }

        .service-process h3 {
            color: #333;
            margin-bottom: 25px;
        }

        .process-steps {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .process-step {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: #1e85b4;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2em;
            flex-shrink: 0;
        }

        .step-content h4 {
            color: #333;
            margin-bottom: 8px;
        }

        .step-content p {
            color: #666;
            margin: 0;
        }

        /* Service CTA */
        .service-cta {
            margin: 40px 0;
        }

        .cta-box {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
        }

        .cta-box h3 {
            color: white;
            margin-bottom: 15px;
        }

        .cta-box p {
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 25px;
        }

        .cta-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-actions .btn-default,
        .cta-actions .btn-outline {
            padding: 12px 60px;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .cta-actions .btn-default {
            background: white;
            color: #28a745;
        }

        .cta-actions .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .cta-actions .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
        }

        /* Related Services */
        .related-services {
            margin: 50px 0;
            padding: 40px 0;
            border-top: 1px solid #e9ecef;
        }

        .related-service-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            height: 100%;
        }

        .related-service-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .related-service-image {
            height: 150px;
            overflow: hidden;
        }

        .related-service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-service-content {
            padding: 20px;
        }

        .related-service-content h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.1em;
        }

        .related-service-content p {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .related-service-content>a {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        .related-service-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .related-service-footer .price {
            color: #1e85b4;
            font-weight: 700;
        }

        .related-service-footer .view-btn {
            color: #1e85b4;
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 600;
        }

        .related-service-footer .view-btn:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .service-meta {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .process-step {
                flex-direction: row;
                text-align: center;
            }

            .cta-actions {
                flex-direction: column;
            }

            .related-service-footer {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            .service-faqs {
                padding-bottom: 30px;
            }
        }
    </style>
@endpush
