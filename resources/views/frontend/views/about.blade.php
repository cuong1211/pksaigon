@extends('frontend.layouts.index')
@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque"><span>About</span> Us</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">about us</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page About Us Start -->
    <div class="about-us page-about-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- About Image Start -->
                    <div class="about-image">
                        <div class="about-img-1">
                            <figure class="image-anime reveal">
                                <img src="images/about-us-img-1.jpg" alt="">
                            </figure>
                        </div>

                        <div class="about-img-2">
                            <figure class="image-anime reveal">
                                <img src="images/about-us-img-2.jpg" alt="">
                            </figure>
                        </div>

                        <!-- About Experience Circle Start -->
                        <div class="about-experience">
                            <figure>
                                <img src="images/about-experience-circle.png" alt="">
                            </figure>
                        </div>
                        <!-- About Experience Circle End -->
                    </div>
                    <!-- About Image End -->
                </div>

                <div class="col-lg-6">
                    <!-- About Content Start -->
                    <div class="about-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">about us</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Your Journey</span> to a Healthier
                                Smile Begins Here</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">The goal of our clinic is to provide friendly,
                                caring dentistry and the highest level of general, cosmetic, and specialist dental
                                treatments. With dental practices throughout the world.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- About Us Body Start -->
                        <div class="about-us-body wow fadeInUp" data-wow-delay="0.5s">
                            <ul>
                                <li>experienced team</li>
                                <li>comprehensive services</li>
                                <li>state-of-the-art technology</li>
                                <li>emergency dental services</li>
                            </ul>
                        </div>
                        <!-- About Us Body End -->
                    </div>
                    <!-- About Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page About Us End -->

    <!-- Insurance Company Logo Slider Start -->
    <div class="insurance-company-carousel">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="insurance-carousel-title">
                        <h3>Trusted by the industry's leading insurance provider</h3>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- company Carousel Start -->
                    <div class="company-carousel">
                        <div class="swiper companies_logo_slider">
                            <div class="swiper-wrapper">
                                <!-- company Logo Start -->
                                <div class="swiper-slide">
                                    <div class="company-logo">
                                        <img src="images/client-logo-1.svg" alt="">
                                    </div>
                                </div>
                                <!-- company Logo End -->

                                <!-- company Logo Start -->
                                <div class="swiper-slide">
                                    <div class="company-logo">
                                        <img src="images/client-logo-2.svg" alt="">
                                    </div>
                                </div>
                                <!-- company Logo End -->

                                <!-- company Logo Start -->
                                <div class="swiper-slide">
                                    <div class="company-logo">
                                        <img src="images/client-logo-3.svg" alt="">
                                    </div>
                                </div>
                                <!-- company Logo End -->

                                <!-- company Logo Start -->
                                <div class="swiper-slide">
                                    <div class="company-logo">
                                        <img src="images/client-logo-1.svg" alt="">
                                    </div>
                                </div>
                                <!-- company Logo End -->

                                <!-- company Logo Start -->
                                <div class="swiper-slide">
                                    <div class="company-logo">
                                        <img src="images/client-logo-2.svg" alt="">
                                    </div>
                                </div>
                                <!-- company Logo End -->

                                <!-- company Logo Start -->
                                <div class="swiper-slide">
                                    <div class="company-logo">
                                        <img src="images/client-logo-3.svg" alt="">
                                    </div>
                                </div>
                                <!-- company Logo End -->
                            </div>
                        </div>
                    </div>
                    <!-- company Carousel End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Insurance Company Logo Slider End -->

    <!-- How It Work Start -->
    <div class="how-it-work about-how-it-work">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="how-it-work-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">how it work</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>What We Do</span> for Your Teeth
                            </h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">We are committed to sustainability. Our clinic
                                practices eco-friendly initiatives like digital records to reduce paper waste and
                                energy-efficient equipment.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- How Work Accordion Start -->

                        <!-- FAQ Accordion Start -->
                        <div class="faq-accordion how-work-accordion" id="accordion">
                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp">
                                <div class="icon-box">
                                    <img src="images/icon-how-it-work-1.svg" alt="">
                                </div>
                                <h2 class="accordion-header" id="heading1">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                        book an appointment
                                    </button>
                                </h2>
                                <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>The goal of our clinic is to provide friendly, caring dentistry and the
                                            highest level of general, cosmetic, ents.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.25s">
                                <div class="icon-box">
                                    <img src="images/icon-how-it-work-2.svg" alt="">
                                </div>
                                <h2 class="accordion-header" id="heading2">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        What conditions can manual therapy treat?
                                    </button>
                                </h2>
                                <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>The goal of our clinic is to provide friendly, caring dentistry and the
                                            highest level of general, cosmetic, ents.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->

                            <!-- FAQ Item Start -->
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.5s">
                                <div class="icon-box">
                                    <img src="images/icon-how-it-work-3.svg" alt="">
                                </div>
                                <h2 class="accordion-header" id="heading3">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        expert care
                                    </button>
                                </h2>
                                <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3"
                                    data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        <p>The goal of our clinic is to provide friendly, caring dentistry and the
                                            highest level of general, cosmetic, ents.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- FAQ Item End -->
                        </div>
                        <!-- FAQ Accordion End -->
                        <!-- How Work Accordion End -->
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- How It Work Image Start -->
                    <div class="how-it-work-img">
                        <figure class="reveal image-anime">
                            <img src="images/how-it-work-img.jpg" alt="">
                        </figure>
                    </div>
                    <!-- How It Work Image End -->
                </div>
            </div>
        </div>
    </div>
    <!-- How It Work End -->

    <!-- Why Choose Us Section Start -->
    <div class="why-choose-us">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">why choose us</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Diagnosis of</span> Dental Diseases
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">We are committed to sustainability. eco-friendly
                            initiatives.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 order-1">
                    <!-- Why Choose Box Start -->
                    <div class="why-choose-box-1">
                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-1.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>experienced doctor</h3>
                                <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-2.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>personalized care</h3>
                                <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-3.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>flexible payment options</h3>
                                <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->
                    </div>
                    <!-- Why Choose Box Start -->
                </div>

                <div class="col-lg-4 order-lg-1 order-md-2 order-1">
                    <!-- Why Choose Image Start -->
                    <div class="why-choose-image wow fadeInUp">
                        <figure>
                            <img src="images/why-choose-us-img.png" alt="">
                        </figure>
                    </div>
                    <!-- Why Choose Image End -->
                </div>

                <div class="col-lg-4 col-md-6 order-lg-2 order-md-1 order-2">
                    <!-- Why Choose Box Start -->
                    <div class="why-choose-box-2">
                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-4.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>emergency services</h3>
                                <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-5.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>positive patient reviews</h3>
                                <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->

                        <!-- Why Choose Item Start -->
                        <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                            <!-- Icon Box Start -->
                            <div class="icon-box">
                                <img src="images/icon-why-us-6.svg" alt="">
                            </div>
                            <!-- Icon Box End -->

                            <!-- Why Choose Content Start -->
                            <div class="why-choose-content">
                                <h3>latest technology</h3>
                                <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                            </div>
                            <!-- Why Choose Content End -->
                        </div>
                        <!-- Why Choose Item End -->
                    </div>
                    <!-- Why Choose Box Start -->
                </div>
            </div>
        </div>
        <!-- Icon Start Image Start -->
        <div class="icon-star-image">
            <img src="images/icon-star.svg" alt="">
        </div>
        <!-- Icon Start Image End -->
    </div>
    <!-- Why Choose Us Section End -->

    <!-- Dental Process Start -->
    <div class="dental-process">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">dental implant process</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Guiding</span> you to optimal Teeth
                        </h2>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Dental Process Item Start -->
                    <div class="dental-process-item wow fadeInUp">
                        <!-- Dental Process Image Start -->
                        <div class="dental-process-image">
                            <div class="dental-process-img">
                                <figure class="image-anime">
                                    <img src="images/dental-process-img-1.jpg" alt="">
                                </figure>
                            </div>

                            <div class="dental-process-number">
                                <h3>01</h3>
                            </div>
                        </div>
                        <!-- Dental Process Image End -->

                        <!-- Dental Process Content Start -->
                        <div class="dental-process-content">
                            <h3>initial examination</h3>
                            <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                        </div>
                        <!-- Dental Process Content End -->
                    </div>
                    <!-- Dental Process Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Dental Process Item Start -->
                    <div class="dental-process-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Dental Process Image Start -->
                        <div class="dental-process-image">
                            <div class="dental-process-img">
                                <figure class="image-anime">
                                    <img src="images/dental-process-img-2.jpg" alt="">
                                </figure>
                            </div>

                            <div class="dental-process-number">
                                <h3>02</h3>
                            </div>
                        </div>
                        <!-- Dental Process Image End -->

                        <!-- Dental Process Content Start -->
                        <div class="dental-process-content">
                            <h3>tooth extraction</h3>
                            <p>Highest level of general, cosmetic, and specialist dental treatments. </p>
                        </div>
                        <!-- Dental Process Content End -->
                    </div>
                    <!-- Dental Process Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Dental Process Item Start -->
                    <div class="dental-process-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Dental Process Image Start -->
                        <div class="dental-process-image">
                            <div class="dental-process-img">
                                <figure class="image-anime">
                                    <img src="images/dental-process-img-3.jpg" alt="">
                                </figure>
                            </div>

                            <div class="dental-process-number">
                                <h3>03</h3>
                            </div>
                        </div>
                        <!-- Dental Process Image End -->

                        <!-- Dental Process Content Start -->
                        <div class="dental-process-content">
                            <h3>bone grafting</h3>
                            <p>Dental practices throughout the world.</p>
                        </div>
                        <!-- Dental Process Content End -->
                    </div>
                    <!-- Dental Process Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Dental Process Item Start -->
                    <div class="dental-process-item wow fadeInUp" data-wow-delay="0.75s">
                        <!-- Dental Process Image Start -->
                        <div class="dental-process-image">
                            <div class="dental-process-img">
                                <figure class="image-anime">
                                    <img src="images/dental-process-img-4.jpg" alt="">
                                </figure>
                            </div>

                            <div class="dental-process-number">
                                <h3>04</h3>
                            </div>
                        </div>
                        <!-- Dental Process Image End -->

                        <!-- Dental Process Content Start -->
                        <div class="dental-process-content">
                            <h3>crown placement</h3>
                            <p>The goal of our clinic is to provide friendly, caring dentistry and the.</p>
                        </div>
                        <!-- Dental Process Content End -->
                    </div>
                    <!-- Dental Process Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Dental Process End -->

    <!-- Our Serviceds Section Start -->
    <div class="our-services">
        <!-- Expertise Experience Section Start -->
        <div class="expertise-experience">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <!-- Expertise Experience Content Start -->
                        <div class="expertise-experience-content">
                            <!-- Section Title Start -->
                            <div class="section-title">
                                <h3 class="wow fadeInUp">expertise experience</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Expert Dentists</span>
                                    Providing Quality Care</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.25s">We are committed to sustainability. Our
                                    clinic practices eco-friendly initiatives like digital records to reduce paper waste
                                    and energy-efficient equipment.We are committed to sustainability. Our clinic
                                    practices eco-friendly initiatives like digital records to reduce paper waste and
                                    energy.</p>
                            </div>
                            <!-- Section Title End -->

                            <!-- Expertise Experience Body Start -->
                            <div class="expertise-experience-body wow fadeInUp" data-wow-delay="0.5s">
                                <ul>
                                    <li>experienced team</li>
                                    <li>comprehensive services</li>
                                    <li>state-of-the-art technology</li>
                                    <li>emergency dental services</li>
                                </ul>
                            </div>
                            <!-- Expertise Experience Body Body End -->
                        </div>
                        <!-- Expertise Experience Content End -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Expertise Experience Image Start -->
                        <div class="expertise-experience-img">
                            <figure class="image-anime reveal">
                                <img src="images/expertise-experience-img.jpg" alt="">
                            </figure>
                        </div>
                        <!-- Expertise Experience Image End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Expertise Experience Section End -->

        <!-- Intro Clinic Video Section Start -->
        <div class="intro-clinic-video">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Visit Clinic Start -->
                        <div class="visit-clinic parallaxie">
                            <!-- Visit Clinic Content Start -->
                            <div class="visit-clinic-content">
                                <!-- Section Title Start -->
                                <div class="section-title">
                                    <h3 class="wow fadeInUp">visit clinic</h3>
                                    <h2 class="text-anime-style-2" data-cursor="-opaque">Comprehensive Dental Care For
                                        All Ages</h2>
                                </div>
                                <!-- Section Title End -->

                                <!-- Visit Clinic Btn Start -->
                                <div class="visit-clinic-btn wow fadeInUp" data-wow-delay="0.25s"
                                    data-cursor-text="Play">
                                    <a href="https://www.youtube.com/watch?v=Y-x0efG1seA"
                                        class="popup-video play-btn">play video</a>
                                </div>
                                <!-- Visit Clinic Btn End -->
                            </div>
                            <!-- Visit Clinic Content End -->
                        </div>
                        <!-- Visit Clinic End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Intro Clinic Video Section End -->

        <!-- Our Counter Section Start -->
        <div class="our-counter">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <!-- Counter Item Start -->
                        <div class="counter-item">
                            <!-- Counter Title Start -->
                            <div class="counter-title">
                                <h2><span class="counter">75</span>+</h2>
                                <h3>insurance covered</h3>
                            </div>
                            <!-- Counter Title End -->

                            <!-- Counter Content Start -->
                            <div class="counter-content">
                                <p>Our team loves dental trivia. Did you know that tooth enamel.</p>
                            </div>
                            <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <!-- Counter Item Start -->
                        <div class="counter-item">
                            <!-- Counter Title Start -->
                            <div class="counter-title">
                                <h2><span class="counter">02</span>K</h2>
                                <h3>realized projects</h3>
                            </div>
                            <!-- Counter Title End -->

                            <!-- Counter Content Start -->
                            <div class="counter-content">
                                <p>Our team loves dental trivia. Did you know that tooth enamel.</p>
                            </div>
                            <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <!-- Counter Item Start -->
                        <div class="counter-item">
                            <!-- Counter Title Start -->
                            <div class="counter-title">
                                <h2><span class="counter">22</span>K</h2>
                                <h3>happy customers</h3>
                            </div>
                            <!-- Counter Title End -->

                            <!-- Counter Content Start -->
                            <div class="counter-content">
                                <p>Our team loves dental trivia. Did you know that tooth enamel.</p>
                            </div>
                            <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <!-- Counter Item Start -->
                        <div class="counter-item">
                            <!-- Counter Title Start -->
                            <div class="counter-title">
                                <h2><span class="counter">18</span>+</h2>
                                <h3>experience doctors</h3>
                            </div>
                            <!-- Counter Title End -->

                            <!-- Counter Content Start -->
                            <div class="counter-content">
                                <p>Our team loves dental trivia. Did you know that tooth enamel.</p>
                            </div>
                            <!-- Counter Content End -->
                        </div>
                        <!-- Counter Item End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Our Counter Section End -->

        <!-- Icon Start Image Start -->
        <div class="icon-star-image">
            <img src="images/icon-star.svg" alt="">
        </div>
        <!-- Icon Start Image End -->
    </div>
    <!-- Our Serviceds Section End -->

    <!-- Our Team Start -->
    <div class="our-team">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">our team</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Our Friendly</span> Dentists Team
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">We are committed to sustainability. eco-friendly
                            initiatives.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/team-1.jpg" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->
                            <div class="team-social-icon">
                                <ul>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>dr.johan joe</h3>
                            <p>lead dentist</p>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/team-2.jpg" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->
                            <div class="team-social-icon">
                                <ul>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>dr.mike johnson</h3>
                            <p>senior dantist</p>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/team-3.jpg" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->
                            <div class="team-social-icon">
                                <ul>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>dr. alison banson</h3>
                            <p>orthodontist</p>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>

                <div class="col-lg-3 col-md-6">
                    <!-- Team Member Item Start -->
                    <div class="team-member-item wow fadeInUp" data-wow-delay="0.75s">
                        <!-- Team Image Start -->
                        <div class="team-image">
                            <figure class="image-anime">
                                <img src="images/team-4.jpg" alt="">
                            </figure>

                            <!-- Team Social Icon Start -->
                            <div class="team-social-icon">
                                <ul>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-facebook-f"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
                                    </li>
                                    <li><a href="#" class="social-icon"><i class="fa-brands fa-x-twitter"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <!-- Team Social Icon End -->
                        </div>
                        <!-- Team Image End -->

                        <!-- Team Content Start -->
                        <div class="team-content">
                            <h3>dr.christopher case</h3>
                            <p>periodontist</p>
                        </div>
                        <!-- Team Content End -->
                    </div>
                    <!-- Team Member Item End -->
                </div>
            </div>
        </div>
        <!-- Icon Start Image Start -->
        <div class="icon-star-image">
            <img src="images/icon-star.svg" alt="">
        </div>
        <!-- Icon Start Image End -->
    </div>
    <!-- Our Team End -->

    <!-- Our Testiminial Start -->
    <div class="our-testimonials">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">testimonial</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque"><span>What our</span> Client Say</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.25s">We are committed to sustainability. eco-friendly
                            initiatives.</p>
                    </div>
                    <!-- Section Title End -->
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-lg-5">
                    <!-- Testiminial Image Start -->
                    <div class="testimonial-image">
                        <div class="testimonial-img">
                            <figure class="reveal image-anime">
                                <img src="images/testimonials-img.jpg" alt="">
                            </figure>
                        </div>

                        <!-- Terstimonial Rating Box Start -->
                        <div class="testimonial-rating-box">
                            <!-- Counter Item Start -->
                            <div class="rating-counter-item">
                                <div class="rating-counter-number">
                                    <h3><span class="counter">4.7</span>/5</h3>
                                </div>

                                <div class="rating-counter-content">
                                    <p>This rate is given by user after visiting our location</p>
                                </div>
                            </div>
                            <!-- Counter Item End -->

                            <!-- Service Rating Start -->
                            <div class="service-rating">
                                <ul>
                                    <li>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                    </li>
                                    <li>for excellence services</li>
                                </ul>
                            </div>
                            <!-- Service Rating End -->
                        </div>
                        <!-- Terstimonial Rating Box End -->
                    </div>
                    <!-- Testiminial Image End -->
                </div>

                <div class="col-lg-7">
                    <!-- Testimonial Slider Start -->
                    <div class="testimonial-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper" data-cursor-text="Drag">
                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-quote-image">
                                                <img src="images/icon-testimonial-quote.svg" alt="">
                                            </div>
                                            <div class="testimonial-content">
                                                <p>"I want to say thank you to my doctor Steve! Vivamus sagittis massa
                                                    vitae bibendum rhoncus. Duis cursus.” “Thank you for helping me
                                                    overcome my fear of the dentist! Vivamus sagittis massa vitae
                                                    bibendum rhoncus. Duis cursus."</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="images/author-1.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>robert lee</h3>
                                                <p>software engineer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-quote-image">
                                                <img src="images/icon-testimonial-quote.svg" alt="">
                                            </div>
                                            <div class="testimonial-content">
                                                <p>"The best dental experience I've ever had! The team was professional
                                                    and friendly, and the results were amazing. Highly recommend!"Dr.
                                                    Smith and his staff are fantastic! They made me feel comfortable and
                                                    at ease during my visit."</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="images/author-2.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>banson doe</h3>
                                                <p>teacher</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->

                                <!-- Testimonial Slide Start -->
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="testimonial-header">
                                            <div class="testimonial-quote-image">
                                                <img src="images/icon-testimonial-quote.svg" alt="">
                                            </div>
                                            <div class="testimonial-content">
                                                <p>"Excellent service and care. The staff is knowledgeable and always
                                                    willing to answer questions. I wouldn't go anywhere else for my
                                                    dental needs.""From the moment I walked in, I felt welcomed and cared
                                                    for. staff is exceptional."</p>
                                            </div>
                                        </div>
                                        <div class="testimonial-body">
                                            <div class="author-image">
                                                <figure class="image-anime">
                                                    <img src="images/author-3.jpg" alt="">
                                                </figure>
                                            </div>
                                            <div class="author-content">
                                                <h3>thomas linda</h3>
                                                <p>designer</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial Slide End -->
                            </div>
                            <div class="testimonial-btn">
                                <div class="testimonial-button-prev"></div>
                                <div class="testimonial-button-next"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Slider End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Our Testiminial End -->
@endsection
