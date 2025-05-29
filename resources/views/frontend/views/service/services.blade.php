@extends('frontend.layouts.index')
@section('content')
    <!-- Services Filter Start -->
    <div class="services-filter">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="filter-tabs text-center">
                        <ul class="nav nav-pills justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link {{ (!isset($currentType) || $currentType == 'all') ? 'active' : '' }}" 
                                   href="{{ route('frontend.services') }}">
                                    Tất cả ({{ $stats['total'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (isset($currentType) && $currentType == 'procedure') ? 'active' : '' }}" 
                                   href="{{ route('frontend.services.type', 'procedure') }}">
                                    Thủ thuật ({{ $stats['procedure'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (isset($currentType) && $currentType == 'laboratory') ? 'active' : '' }}" 
                                   href="{{ route('frontend.services.type', 'laboratory') }}">
                                    Xét nghiệm ({{ $stats['laboratory'] ?? 0 }})
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ (isset($currentType) && $currentType == 'other') ? 'active' : '' }}" 
                                   href="{{ route('frontend.services.type', 'other') }}">
                                    Dịch vụ khác ({{ $stats['other'] ?? 0 }})
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Filter End -->

    <!-- Page Services Start -->
    <div class="page-services">
        <div class="container">
            <div class="row">
                @if($services->count() > 0)
                    @foreach($services as $index => $service)
                        <div class="col-lg-4 col-md-6">
                            <!-- Service Item Start -->
                            <div class="service-item wow fadeInUp" data-wow-delay="{{ ($index % 8) * 0.2 }}s">
                                <div class="service-image">
                                    @if($service->image && file_exists(public_path('storage/' . $service->image)))
                                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                                    @else
                                        @if($service->type == 'procedure')
                                            <img src="{{ asset('frontend/images/icon-services-1.svg') }}" alt="">
                                        @elseif($service->type == 'laboratory')
                                            <img src="{{ asset('frontend/images/icon-services-2.svg') }}" alt="">
                                        @else
                                            <img src="{{ asset('frontend/images/icon-services-3.svg') }}" alt="">
                                        @endif
                                    @endif
                                </div>
                                <div class="service-body">
                                    <h3>{{ $service->name }}</h3>
                                    <p>{!! 'Dịch vụ chất lượng cao với đội ngũ chuyên nghiệp.' !!}</p>
                                </div>
                                <div class="read-more-btn">
                                    <a href="{{ route('frontend.services.show', $service->slug) }}">xem chi tiết</a>
                                </div>
                            </div>
                            <!-- Service Item End -->
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center no-services">
                            <h3>Hiện tại chưa có dịch vụ nào</h3>
                            <p>Vui lòng quay lại sau để xem các dịch vụ mới nhất.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Page Services End -->
@endsection

@push('csscustom')
<style>
/* Services Filter */
.services-filter {
    padding: 40px 0;
    background: #f8f9fa;
}

.services-filter .nav-pills {
    gap: 15px;
}

.services-filter .nav-link {
    color: #666;
    text-decoration: none;
    font-weight: 600;
    border-radius: 25px;
    padding: 10px 20px;
    border: 2px solid #e9ecef;
    background: white;
    transition: all 0.3s ease;
}

.services-filter .nav-link:hover {
    background: #f8f9fa;
    border-color: #1e85b4;
    color: #1e85b4;
    text-decoration: none;
}

.services-filter .nav-link.active {
    background: #1e85b4;
    border-color: #1e85b4;
    color: white;
}

/* Service Items */
.service-item {
    position: relative;
    overflow: hidden;
}

.service-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    margin-bottom: 20px;
    border-radius: 8px;
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.service-item:hover .service-image img {
    transform: scale(1.05);
}

/* No services */
.no-services {
    padding: 60px 0;
}

/* Responsive */
@media (max-width: 768px) {
    .services-filter .nav-pills {
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .services-filter .nav-link {
        padding: 8px 15px;
        font-size: 14px;
    }
    
    .service-image {
        height: 150px;
    }
}
</style>
@endpush