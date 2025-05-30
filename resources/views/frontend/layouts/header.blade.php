<div class="preloader">
    <div class="loading-container">
        <div class="loading"></div>
        <div id="loading-icon"><img src="images/loader.png" alt=""></div>
    </div>
</div>
<!-- Preloader End -->

<!-- Header Start -->
<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo Start -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <div class="brand-container">
                        <div class="logo-wrapper">
                            <img src="{{ asset('frontend/images/logo.png') }}" alt="PKSG Logo" class="brand-logo">
                            <div class="logo-glow"></div>
                        </div>
                        <div class="brand-content">
                            <div class="brand-main">PKSG</div>
                            <div class="brand-subtitle">Phòng Khám Sài Gòn</div>
                        </div>
                    </div>
                </a>
                <!-- Logo End -->

                <!-- Main Menu Start -->
                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Về chúng tôi</a></li>
                            
                            <!-- Services Dropdown -->
                            <li class="nav-item submenu">
                                <a class="nav-link" href="{{ route('frontend.services') }}">Dịch vụ</a>
                                <ul>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('frontend.services.type', 'procedure') }}">
                                            <i class="fas fa-stethoscope me-2"></i>Thủ thuật
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('frontend.services.type', 'laboratory') }}">
                                            <i class="fas fa-microscope me-2"></i>Xét nghiệm
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('frontend.services.type', 'other') }}">
                                            <i class="fas fa-plus-circle me-2"></i>Dịch vụ khác
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- Products Dropdown -->
                            <li class="nav-item"><a class="nav-link" href="{{ route('frontend.medicines') }}">Thực phẩm chức năng</a></li>

                            <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
                            <li class="nav-item highlighted-menu">
                                <a class="nav-link" href="{{ route('frontend.appointment') }}">Đặt lịch khám</a>
                            </li>
                        </ul>
                    </div>
                    <!-- Let's Start Button Start -->
                    <div class="header-btn d-inline-flex">
                        <a href="{{ route('frontend.appointment') }}" class="btn-default">Đặt lịch khám</a>
                    </div>
                    <!-- Let's Start Button End -->
                </div>
                <!-- Main Menu End -->
                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>

@push('csscustom')
<style>
    .brand-text {
        font-family: 'Poppins', sans-serif;
        font-size: 32px;
        font-weight: 700;
        color: #333;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover .brand-text {
        color: #1e85b4;
        text-decoration: none;
    }

    /* Dropdown Menu Styling */
    .nav-item.submenu .nav-link {
        position: relative;
    }

    .nav-item.submenu ul {
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 8px;
        padding: 10px 0;
        min-width: 220px;
    }

    .nav-item.submenu ul li a {
        padding: 10px 20px;
        color: #666;
        font-size: 14px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .nav-item.submenu ul li a:hover {
        background: #f8f9fa;
        color: #1e85b4;
        padding-left: 25px;
    }

    .nav-item.submenu ul li a i {
        color: #1e85b4;
        width: 16px;
        font-size: 12px;
    }

    /* Responsive cho mobile */
    @media (max-width: 768px) {
        .brand-text {
            font-size: 24px;
            margin-right: 0;
        }

        .navbar-brand {
            flex-direction: column !important;
            align-items: center !important;
        }

        .navbar-brand img {
            margin-right: 0 !important;
            margin-bottom: 5px;
        }

        .nav-item.submenu ul {
            position: static;
            box-shadow: none;
            background: #f8f9fa;
            margin-top: 10px;
            border-radius: 5px;
        }
    }

    @media (max-width: 480px) {
        .brand-text {
            font-size: 20px;
        }

        .navbar-brand img {
            width: 50px;
        }
    }
</style>
@endpush