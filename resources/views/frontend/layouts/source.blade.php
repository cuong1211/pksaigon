<!-- CÁCH 1: Thêm vào file resources/views/frontend/layouts/source.blade.php -->

<!-- Meta -->
<base href="{{ asset('frontend') }}/">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Awaiken">
{{-- SEO Meta Tags --}}
@if (isset($seoHelper))
    {!! $seoHelper->renderMeta() !!}
@else
    <title>{{ $title ?? 'Phòng Khám Sài Gòn - Chăm sóc sức khỏe chuyên nghiệp' }}</title>
    <meta name="description"
        content="{{ $description ?? 'Phòng khám chuyên khoa sản phụ khoa với đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại tại TP.HCM' }}">
    <meta name="keywords"
        content="{{ $keywords ?? 'phòng khám sài gòn, sản phụ khoa, bác sĩ, khám thai, điều trị phụ khoa' }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $title ?? 'Phòng Khám Sài Gòn' }}">
    <meta property="og:description"
        content="{{ $description ?? 'Phòng khám chuyên khoa sản phụ khoa với đội ngũ bác sĩ giàu kinh nghiệm' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('frontend/images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Phòng Khám Sài Gòn">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Phòng Khám Sài Gòn' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Phòng khám chuyên khoa sản phụ khoa' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('frontend/images/logo.png') }}">
@endif

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">

{{-- Schema.org JSON-LD --}}
@if (isset($seoHelper))
    {!! $seoHelper->generateSchema('Organization') !!}
@endif

@stack('schema')

<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="images/favicon_1.png">

<!-- Google Fonts - Be Vietnam Pro -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet">

<!-- Bootstrap Css -->
<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
<!-- SlickNav Css -->
<link href="css/slicknav.min.css" rel="stylesheet">
<!-- Swiper Css -->
<link rel="stylesheet" href="css/swiper-bundle.min.css">
<!-- Font Awesome Icon Css-->
<link href="css/all.css" rel="stylesheet" media="screen">
<!-- Animated Css -->
<link href="css/animate.css" rel="stylesheet">
<!-- Magnific Popup Core Css File -->
<link rel="stylesheet" href="css/magnific-popup.css">
<!-- Mouse Cursor Css File -->
<link rel="stylesheet" href="css/mousecursor.css">
<!-- Main Custom Css -->
<link href="css/custom.css" rel="stylesheet" media="screen">

<!-- CSS Custom cho Font -->
<style>
    /* Thiết lập font Be Vietnam Pro làm font chính */
    html {
        font-display: swap;
    }

    body {
        font-family: 'Be Vietnam Pro', 'Poppins', sans-serif;
    }

    /* =====================
   NAVBAR BRAND STYLES
   ===================== */

    .navbar-brand {
        text-decoration: none !important;
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover {
        transform: translateY(-2px);
    }

    .brand-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    /* Logo Wrapper */
    .logo-wrapper {
        position: relative;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-logo {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
        background: #fff;
        padding: 5px;
    }

    .logo-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: linear-gradient(45deg, #007bff, #0056b3);
        border-radius: 50%;
        opacity: 0;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    .navbar-brand:hover .brand-logo {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    }

    .navbar-brand:hover .logo-glow {
        opacity: 0.2;
        transform: translate(-50%, -50%) scale(1.2);
    }

    /* Brand Text */
    .brand-content {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .brand-main {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e85b4;
        margin: 0;
        letter-spacing: 2px;
        background: linear-gradient(45deg, #1e85b4, #1e85b4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: all 0.3s ease;
    }

    .brand-subtitle {
        font-size: 0.75rem;
        color: var(--primary-color);
        margin: 0;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .navbar-brand:hover .brand-main {
        background: linear-gradient(45deg, #0056b3, #007bff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transform: translateX(3px);
    }

    .navbar-brand:hover .brand-subtitle {
        color: #007bff;
        transform: translateX(3px);
    }

    /* Pulse Animation */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }

    /* Medical Cross Icon Effect (nếu logo là medical) */
    .logo-wrapper::after {
        content: '+';
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border-radius: 50%;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
        z-index: 3;
    }

    .navbar-brand:hover .logo-wrapper::after {
        opacity: 1;
        transform: scale(1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .brand-container {
            gap: 10px;
        }

        .logo-wrapper {
            width: 45px;
            height: 45px;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
        }

        .brand-main {
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            font-size: 0.65rem;
        }
    }

    @media (max-width: 480px) {
        .brand-subtitle {
            display: none;
        }

        .brand-main {
            font-size: 1.2rem;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .brand-subtitle {
            color: var(--primary-color);
        }

        .navbar-brand:hover .brand-subtitle {
            color: #1e85b4;
        }
    }

    /* Special Medical Theme Variant */
    .navbar-brand.medical-theme .brand-main {
        background: linear-gradient(45deg, #28a745, #20c997);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .navbar-brand.medical-theme .brand-logo {
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .navbar-brand.medical-theme:hover .brand-logo {
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .navbar-brand.medical-theme .logo-glow {
        background: linear-gradient(45deg, #28a745, #20c997);
    }
</style>
<style>
    /* =====================
           FOOTER STYLES
           ===================== */

    .main-footer {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        position: relative;
        margin-top: 80px;
    }

    .main-footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    /* Footer Logo & Brand */
    .footer-logo img {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .brand-name {
        color: #ffffff;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 1px;
    }

    .brand-tagline {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        font-style: italic;
    }

    /* Footer Titles */
    .footer-title {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: white;
        border-radius: 2px;
    }

    /* Footer Links */
    .footer-links ul li {
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .footer-links ul li:hover {
        transform: translateX(5px);
    }

    .footer-links ul li a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        font-size: 0.95rem;
    }

    .footer-links ul li a:hover {
        color: #ffd93d;
        text-decoration: none;
    }

    .footer-links ul li a i {
        font-size: 0.8rem;
        color: #ff6b6b;
    }

    /* Working Hours */
    .working-hours {
        background: rgba(255, 255, 255, 0.1);
        padding: 15px;
        border-radius: 10px;
        margin-top: 20px;
        backdrop-filter: blur(10px);
    }

    .working-hours h6 {
        color: #ffd93d;
        font-weight: 600;
    }

    .time-schedule div {
        padding: 3px 0;
        font-size: 0.9rem;
    }

    /* Contact Items */
    .contact-item {
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .contact-item:last-child {
        border-bottom: none;
    }

    .contact-item i {
        color: #ffffff;
        font-size: 1.2rem;
        width: 20px;
    }

    .contact-item h6 {
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .contact-link {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .contact-link:hover {
        color: #ffd93d;
        text-decoration: none;
    }

    /* Social Links */
    .social-links .social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .social-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: all 0.5s ease;
    }

    .social-link:hover::before {
        left: 100%;
    }

    .social-link.facebook {
        background: #3b5998;
        color: white;
    }


    .social-link.zalo {
        background: #0068ff;
        color: white;
    }

    .social-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Newsletter Section */
    .newsletter-section {
        background: rgba(255, 255, 255, 0.1);
        padding: 30px;
        margin: 40px 0;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .newsletter-content h5 {
        color: #ffd93d;
        font-weight: 600;
    }

    .newsletter-form .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        border-radius: 25px 0 0 25px;
        padding: 12px 20px;
    }

    .newsletter-form .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .newsletter-form .form-control:focus {
        background: rgba(255, 255, 255, 0.15);
        border-color: #ffd93d;
        box-shadow: 0 0 0 0.2rem rgba(255, 217, 61, 0.25);
        color: white;
    }

    .newsletter-form .btn {
        border-radius: 0 25px 25px 0;
        background: linear-gradient(45deg, #ff6b6b, #ffd93d);
        border: none;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .newsletter-form .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    /* Footer Bottom */
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .footer-bottom-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: #ffd93d;
    }

    /* Back to Top Button *

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-footer {
            margin-top: 40px;
        }

        .newsletter-section {
            padding: 20px;
            margin: 20px 0;
        }

        .newsletter-form .form-control,
        .newsletter-form .btn {
            border-radius: 25px;
            margin-bottom: 10px;
        }

        .footer-bottom {
            text-align: center;
        }

        .footer-bottom-links {
            margin-top: 15px;
        }

        .social-links {
            text-align: center;
        }
    }

    /* Animation cho footer khi load */
    .main-footer {
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<style>
    /* Giữ lại CSS cũ cho brand */
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

    /* Dropdown Menu Styling - giữ nguyên */
    .nav-item.submenu .nav-link {
        position: relative;
    }

    .nav-item.submenu ul {
        background: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        background: none;
        border: none;
        font-size: 24px;
        color: #333;
        cursor: pointer;
        padding: 10px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .mobile-menu-toggle:hover {
        background: #f8f9fa;
        color: #1e85b4;
    }

    /* Mobile Drawer Overlay */
    .mobile-drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .mobile-drawer-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Mobile Drawer */
    .mobile-drawer {
        position: fixed;
        top: 0;
        right: -350px;
        width: 350px;
        height: 100%;
        background: white;
        z-index: 9999;
        transition: all 0.3s ease;
        overflow-y: auto;
        box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .mobile-drawer.active {
        right: 0;
    }

    /* Drawer Header */
    .drawer-header {
        padding: 20px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8f9fa;
    }

    .drawer-brand {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .drawer-brand img {
        width: 40px;
        height: 40px;
        border-radius: 8px;
    }

    .drawer-brand-text h4 {
        margin: 0;
        color: #1e85b4;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .drawer-brand-text p {
        margin: 0;
        color: #666;
        font-size: 0.8rem;
    }

    .drawer-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #666;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .drawer-close:hover {
        background: #e9ecef;
        color: #333;
    }

    /* Drawer Menu */
    .drawer-menu {
        padding: 0;
        margin: 0;
        list-style: none;
        flex: 1;
    }

    .drawer-menu-item {
        border-bottom: 1px solid #f0f0f0;
    }

    .drawer-menu-link {
        display: flex;
        align-items: center;
        padding: 18px 20px;
        color: #333;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .drawer-menu-link:hover {
        background: #f8f9fa;
        color: #1e85b4;
        text-decoration: none;
    }

    .drawer-menu-link i {
        margin-right: 12px;
        width: 20px;
        color: #1e85b4;
        font-size: 16px;
    }

    /* Submenu */
    .drawer-submenu {
        position: relative;
    }

    .drawer-submenu-toggle {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 5px;
    }

    .drawer-submenu-toggle:hover {
        color: #1e85b4;
    }

    .drawer-submenu.active .drawer-submenu-toggle {
        transform: translateY(-50%) rotate(180deg);
        color: #1e85b4;
    }

    .drawer-submenu-list {
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .drawer-submenu.active .drawer-submenu-list {
        max-height: 300px;
    }

    .drawer-submenu-item {
        padding: 15px 20px 15px 50px;
        color: #666;
        text-decoration: none;
        display: flex;
        align-items: center;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .drawer-submenu-item:hover {
        background: #e9ecef;
        color: #1e85b4;
        text-decoration: none;
    }

    .drawer-submenu-item i {
        margin-right: 8px;
        font-size: 12px;
        width: 16px;
    }

    /* Active state cho drawer menu */
    .drawer-menu-item.active>.drawer-menu-link {
        background: #e3f2fd;
        color: #1e85b4;
        font-weight: 600;
    }

    .drawer-menu-item.active>.drawer-menu-link i {
        color: #1e85b4;
    }

    /* Active state cho submenu items */
    .drawer-submenu-item.active {
        background: #e3f2fd;
        color: #1e85b4;
        font-weight: 600;
    }

    .drawer-submenu-item.active i {
        color: #1e85b4;
    }

    /* Highlighted Menu - sửa class name */
    .drawer-menu-item.drawer-menu-highlighted .drawer-menu-link {
        background: linear-gradient(45deg, #1e85b4, #2196F3);
        color: white;
    }

    .drawer-menu-item.drawer-menu-highlighted .drawer-menu-link i {
        color: white;
    }

    .drawer-menu-item.drawer-menu-highlighted .drawer-menu-link:hover {
        background: linear-gradient(45deg, #1a73a0, #1976D2);
    }

    /* Highlighted menu khi active */
    .drawer-menu-item.drawer-menu-highlighted.active .drawer-menu-link {
        background: linear-gradient(45deg, #1a73a0, #1976D2);
        color: white;
        font-weight: 600;
    }

    /* Drawer Footer */
    .drawer-footer {
        padding: 20px;
        border-top: 1px solid #eee;
        background: #f8f9fa;
    }

    .drawer-contact {
        text-align: center;
    }

    .drawer-contact h6 {
        color: #333;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .drawer-contact p {
        color: #666;
        margin: 8px 0;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .drawer-contact a {
        color: #1e85b4;
        text-decoration: none;
    }

    .drawer-contact i {
        font-size: 12px;
        color: #1e85b4;
    }

    /* Responsive */
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

        .mobile-drawer {
            width: 320px;
            right: -320px;
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

        .mobile-drawer {
            width: 300px;
            right: -300px;
        }
    }

    /* Body scroll lock */
    body.drawer-open {
        overflow: hidden;
    }
</style>
<style>
    /* Floating Buttons Container */
    .floating-buttons {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        z-index: 1000;
    }

    /* Base Button Style */
    .floating-btn {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: white;
        font-size: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .floating-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: all 0.5s ease;
    }

    .floating-btn:hover::before {
        left: 100%;
    }

    .floating-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
    }

    /* Messenger Button */
    .messenger-btn {
        background: linear-gradient(45deg, #0084ff, #00c6ff);
        animation: pulse-messenger 2s infinite;
    }

    .messenger-btn:hover {
        background: linear-gradient(45deg, #0070e0, #00b3e6);
    }

    /* Phone Button */
    .phone-btn {
        background: linear-gradient(45deg, #25d366, #128c7e);
        animation: pulse-phone 2s infinite 0.5s;
    }

    .phone-btn:hover {
        background: linear-gradient(45deg, #20ba5a, #0f7a6b);
    }

    /* Back to Top Button */
    .back-to-top {
        background: linear-gradient(45deg, #ff6b6b, #ffd93d);
        opacity: 0;
        visibility: hidden;
        animation: pulse-top 2s infinite 1s;
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        background: linear-gradient(45deg, #ff5252, #ffcc02);
    }

    /* Pulse Animations */
    @keyframes pulse-messenger {
        0% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(0, 132, 255, 0.7);
        }

        70% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 10px rgba(0, 132, 255, 0);
        }

        100% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(0, 132, 255, 0);
        }
    }

    @keyframes pulse-phone {
        0% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(37, 211, 102, 0.7);
        }

        70% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 10px rgba(37, 211, 102, 0);
        }

        100% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    @keyframes pulse-top {
        0% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(255, 107, 107, 0.7);
        }

        70% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 10px rgba(255, 107, 107, 0);
        }

        100% {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(255, 107, 107, 0);
        }
    }

    /* Tooltip */
    .floating-btn .tooltip {
        position: absolute;
        right: 70px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .floating-btn .tooltip::after {
        content: '';
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-left-color: rgba(0, 0, 0, 0.8);
    }

    .floating-btn:hover .tooltip {
        opacity: 1;
        visibility: visible;
        right: 75px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .floating-buttons {
            bottom: 20px;
            right: 20px;
            gap: 12px;
        }

        .floating-btn {
            width: 50px;
            height: 50px;
            font-size: 18px;
        }

        .floating-btn .tooltip {
            display: none;
        }
    }
</style>
@section('js')
    <!-- Jquery Library File -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap js file -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Validator js file -->
    <script src="js/validator.min.js"></script>
    <!-- SlickNav js file -->
    <script src="js/jquery.slicknav.js"></script>
    <!-- Swiper js file -->
    <script src="js/swiper-bundle.min.js"></script>
    <!-- Counter js file -->
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <!-- Magnific js file -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <!-- SmoothScroll -->
    <script src="js/SmoothScroll.js"></script>
    <!-- Parallax js -->
    <script src="js/parallaxie.js"></script>
    <!-- MagicCursor js file -->
    <script src="js/gsap.min.js"></script>
    <script src="js/magiccursor.js"></script>
    <!-- Text Effect js file -->
    <script src="js/SplitText.js"></script>
    <script src="js/ScrollTrigger.min.js"></script>
    <!-- YTPlayer js File -->
    <script src="js/jquery.mb.YTPlayer.min.js"></script>
    <!-- Wow js file -->
    <script src="js/wow.js"></script>
    <!-- Main Custom js file -->
    <script src="js/function.js"></script>
    <script>
        // Back to Top functionality - Thêm vào resources/views/frontend/layouts/source.blade.php
        document.addEventListener('DOMContentLoaded', function() {
            // Back to Top functionality
            window.addEventListener('scroll', function() {
                const backToTopButton = document.getElementById('backToTop');
                if (window.scrollY > 300) {
                    backToTopButton.classList.add('show');
                } else {
                    backToTopButton.classList.remove('show');
                }
            });

            // Xử lý sự kiện click cho nút "Back to Top"
            document.getElementById('backToTop').addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });

            // Thêm hiệu ứng ripple khi click
            document.querySelectorAll('.floating-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const ripple = document.createElement('div');
                    const rect = btn.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                left: ${x}px;
                top: ${y}px;
                width: ${size}px;
                height: ${size}px;
            `;

                    btn.appendChild(ripple);

                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });

        // CSS cho ripple animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
`;
        document.head.appendChild(style);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileDrawer = document.getElementById('mobileDrawer');
            const mobileDrawerOverlay = document.getElementById('mobileDrawerOverlay');
            const drawerClose = document.getElementById('drawerClose');

            // Toggle drawer
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileDrawer.classList.add('active');
                    mobileDrawerOverlay.classList.add('active');
                    document.body.classList.add('drawer-open');
                });
            }

            // Close drawer
            function closeDrawer() {
                mobileDrawer.classList.remove('active');
                mobileDrawerOverlay.classList.remove('active');
                document.body.classList.remove('drawer-open');
            }

            if (drawerClose) {
                drawerClose.addEventListener('click', closeDrawer);
            }

            if (mobileDrawerOverlay) {
                mobileDrawerOverlay.addEventListener('click', closeDrawer);
            }

            // Submenu toggle
            const submenuToggles = document.querySelectorAll('.drawer-submenu-toggle');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const submenu = this.closest('.drawer-submenu');
                    submenu.classList.toggle('active');
                });
            });

            // Auto expand submenu if any submenu item is active
            const activeSubmenuItem = document.querySelector('.drawer-submenu-item.active');
            if (activeSubmenuItem) {
                const parentSubmenu = activeSubmenuItem.closest('.drawer-submenu');
                if (parentSubmenu) {
                    parentSubmenu.classList.add('active');
                }
            }

            // Close drawer when clicking menu links
            const drawerLinks = document.querySelectorAll('.drawer-menu-link, .drawer-submenu-item');
            drawerLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (!this.closest('.drawer-submenu-toggle')) {
                        setTimeout(closeDrawer, 100);
                    }
                });
            });

            // ESC key to close
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeDrawer();
                }
            });

            // Close on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    closeDrawer();
                }
            });
        });
    </script>

    @include('backend.layout.js')
@endsection
