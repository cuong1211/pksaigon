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
                <a class="navbar-brand" href="./"
                    style="display: flex; align-items: center; text-decoration: none;">
                    <img src="images/logo.png" alt="Logo" style="width: 60px; height: auto; margin-right: 15px;">
                    <span class="brand-text">SAIGON</span>
                </a>
                <!-- Logo End -->

                <!-- Main Menu Start -->
                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            <li class="nav-item"><a class="nav-link" href="./">Trang chủ</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="about.html">Về chúng tôi</a></li>
                            <li class="nav-item submenu"><a class="nav-link" href="#">Dịch vụ</a>
                                <ul>
                                    <li class="nav-item"><a class="nav-link" href="service-single.html">Dịch vụ khám bệnh</a></li>
                                    <li class="nav-item"><a class="nav-link" href="blog.html">Thuốc điều trị</a></li>
                                    <li class="nav-item"><a class="nav-link" href="blog.html">Thực phẩm bổ sung</a></li>
                                </ul>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="contact.html">Liên hệ</a></li>
                            <li class="nav-item highlighted-menu"><a class="nav-link" href="appointment.html">Đặt lịch khám</a></li>
                        </ul>
                    </div>
                    <!-- Let's Start Button Start -->
                    <div class="header-btn d-inline-flex">
                        <a href="appointment.html" class="btn-default">Đặt lịch khám</a>
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
            color: #007bff;
            text-decoration: none;
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
