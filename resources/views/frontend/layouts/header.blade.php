{{-- resources/views/frontend/layouts/header.blade.php --}}
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
                <div class="d-flex justify-content-between align-items-center w-100">
                    <!-- Logo Start -->
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <div class="brand-container">
                            <div class="logo-wrapper">
                                <img src="{{ asset('frontend/images/favicon.jpg') }}" alt="PKSG Logo" class="">
                                <div class="logo-glow"></div>
                            </div>
                            <div class="brand-content">
                                <div class="brand-main">Thu Hiền</div>
                                <div class="brand-subtitle">Phòng Khám Sản phụ Khoa</div>
                            </div>
                        </div>
                    </a>
                    <!-- Logo End -->

                    <!-- Desktop Menu Start -->
                    <div class="collapse navbar-collapse main-menu">
                        <div class="nav-menu-wrapper">
                            <ul class="navbar-nav mr-auto" id="menu">
                                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Về chúng tôi</a>
                                </li>

                                <!-- Services Dropdown -->
                                <li class="nav-item submenu">
                                    <a class="nav-link" href="{{ route('frontend.services') }}">Dịch vụ</a>
                                    <ul>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ route('frontend.services.type', 'procedure') }}">
                                                <i class="fas fa-stethoscope me-2"></i>Thủ thuật
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="{{ route('frontend.services.type', 'laboratory') }}">
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

                                <li class="nav-item"><a class="nav-link" href="{{ route('frontend.medicines') }}">Thực
                                        phẩm chức năng</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Liên hệ</a></li>
                                <li class="nav-item highlighted-menu">
                                    <a class="nav-link" href="{{ route('frontend.appointment') }}">Đặt lịch khám</a>
                                </li>
                            </ul>
                        </div>
                        <!-- Header Button Start -->
                        <div class="header-btn d-inline-flex">
                            <a href="{{ route('frontend.appointment') }}" class="btn-default">Đặt lịch khám</a>
                        </div>
                        <!-- Header Button End -->
                    </div>
                    <!-- Desktop Menu End -->

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle d-lg-none" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>

<!-- Mobile Drawer Overlay -->
<div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>

<!-- Mobile Drawer -->
<div class="mobile-drawer" id="mobileDrawer">
    <!-- Drawer Header -->
    <div class="drawer-header">
        <div class="drawer-brand">
            <img src="{{ asset('frontend/images/favicon.jpg') }}" alt="PKSG Logo">
            <div class="drawer-brand-text">
                <h4>Thu Hiền</h4>
            </div>
        </div>
        <button class="drawer-close" id="drawerClose">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Drawer Menu -->
    <ul class="drawer-menu">
        <li class="drawer-menu-item {{ Request::routeIs('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="drawer-menu-link">
                <i class="fas fa-home"></i>
                Trang chủ
            </a>
        </li>
        <li class="drawer-menu-item {{ Request::routeIs('about') ? 'active' : '' }}">
            <a href="{{ route('about') }}" class="drawer-menu-link">
                <i class="fas fa-info-circle"></i>
                Về chúng tôi
            </a>
        </li>
        <li class="drawer-menu-item drawer-submenu {{ Request::routeIs('frontend.services*') ? 'active' : '' }}">
            <a href="{{ route('frontend.services') }}" class="drawer-menu-link">
                <i class="fas fa-medical-kit"></i>
                Dịch vụ
            </a>
            <button class="drawer-submenu-toggle" type="button">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="drawer-submenu-list">
                <a href="{{ route('frontend.services.type', 'procedure') }}"
                    class="drawer-submenu-item {{ Request::fullUrl() == route('frontend.services.type', 'procedure') ? 'active' : '' }}">
                    <i class="fas fa-stethoscope"></i>
                    Thủ thuật
                </a>
                <a href="{{ route('frontend.services.type', 'laboratory') }}"
                    class="drawer-submenu-item {{ Request::fullUrl() == route('frontend.services.type', 'laboratory') ? 'active' : '' }}">
                    <i class="fas fa-microscope"></i>
                    Xét nghiệm
                </a>
                <a href="{{ route('frontend.services.type', 'other') }}"
                    class="drawer-submenu-item {{ Request::fullUrl() == route('frontend.services.type', 'other') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle"></i>
                    Dịch vụ khác
                </a>
            </div>
        </li>
        <li class="drawer-menu-item {{ Request::routeIs('frontend.medicines*') ? 'active' : '' }}">
            <a href="{{ route('frontend.medicines') }}" class="drawer-menu-link">
                <i class="fas fa-pills"></i>
                Thực phẩm chức năng
            </a>
        </li>
        <li class="drawer-menu-item {{ Request::routeIs('contact') ? 'active' : '' }}">
            <a href="{{ route('contact') }}" class="drawer-menu-link">
                <i class="fas fa-phone"></i>
                Liên hệ
            </a>
        </li>
        <li class="drawer-menu-item {{ Request::routeIs('frontend.appointment*') ? 'active' : '' }}">
            <a href="{{ route('frontend.appointment') }}" class="drawer-menu-link">
                <i class="fas fa-calendar-check"></i>
                Đặt lịch khám
            </a>
        </li>
    </ul>

    <!-- Drawer Footer -->
    <div class="drawer-footer">
        <div class="drawer-contact">
            <h6>Liên hệ</h6>
            <p><i class="fas fa-phone"></i> <a href="tel:0384518881">0384 518 881</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:pksg@gmail.com">pksg@gmail.com</a></p>
            <p><i class="fas fa-map-marker-alt"></i> 65 Hùng Vương, Q5, TP.HCM</p>
        </div>
    </div>
</div>
