<!-- CÁCH 1: Thêm vào file resources/views/frontend/layouts/source.blade.php -->

<!-- Meta -->
<base href="{{ asset('frontend') }}/">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="Awaiken">
<!-- Page Title -->
<title>PKSPKSG</title>
<!-- Favicon Icon -->
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.png">

<!-- Google Fonts - Be Vietnam Pro -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

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

/* Áp dụng font cho các element chính */
h1, h2, h3, h4, h5, h6 {
    font-family: 'Be Vietnam Pro', 'Poppins', sans-serif;
    font-weight: 600;
}

p, span, div, a, li {
    font-family: 'Be Vietnam Pro', 'Poppins', sans-serif;
}

/* Font weight classes */
.font-thin { font-weight: 100; }
.font-extralight { font-weight: 200; }
.font-light { font-weight: 300; }
.font-normal { font-weight: 400; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }
.font-extrabold { font-weight: 800; }
.font-black { font-weight: 900; }

/* Override existing Poppins font */
.navbar-brand .brand-text {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 700;
}

/* Navigation menu */
.navbar-nav .nav-link {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 500;
}

/* Buttons */
.btn-default {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

/* Section titles */
.section-title h1,
.section-title h2,
.section-title h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
}

/* Hero content */
.hero-content h1 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 700;
}

.hero-content p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
}

/* Service items */
.service-item h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

.service-item p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
}

/* Team member names */
.team-content h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

/* Testimonial content */
.testimonial-content p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
    font-style: normal;
}

/* Blog titles */
.post-item-body h2 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

/* Footer */
.footer-links h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

/* Contact info */
.contact-info-content p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
}

/* Counter numbers */
.counter-title h2 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 700;
}

.counter-title h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

/* Accordion */
.accordion-button {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 500;
}

.accordion-body p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
}

/* CTA Box */
.cta-box-content h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

.cta-box-content p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
}

/* Why Choose Us */
.why-choose-content h3 {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 600;
}

.why-choose-content p {
    font-family: 'Be Vietnam Pro', sans-serif !important;
    font-weight: 400;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    body {
        font-size: 14px;
        line-height: 1.5;
    }
    
    h1 { font-size: 24px; }
    h2 { font-size: 20px; }
    h3 { font-size: 18px; }
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
@endsection