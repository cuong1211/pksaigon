@extends('frontend.layouts.index')
@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Contact<span> Us</span></h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">contact us</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Contact Start -->
    <div class="page-contact">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="contact-us-content">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">contact info</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Connecting</span> Near & Far </h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">For emergency dental care or to schedule an
                                appointment, contact our office at visit our clinic.</p>
                        </div>
                        <!-- Section Title End -->

                        <!-- Contact US Info Start -->
                        <div class="contact-us-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Contact US Item Start -->
                                    <div class="contact-us-item wow fadeInUp">
                                        <!-- Icon Box Start -->
                                        <div class="icon-box">
                                            <img src="images/icon-location.svg" alt="">
                                        </div>
                                        <!-- Icon Box End -->

                                        <!-- Contact Us Content Start -->
                                        <div class="contact-info-content">
                                            <h3>visit us on</h3>
                                            <p>24/11 Robert Road , New York , USA</p>
                                        </div>
                                        <!-- Contact Us Content End -->
                                    </div>
                                    <!-- Contact Us Item End -->
                                </div>

                                <div class="col-md-6">
                                    <!-- Contact US Item Start -->
                                    <div class="contact-us-item wow fadeInUp" data-wow-delay="0.25s">
                                        <!-- Icon Box Start -->
                                        <div class="icon-box">
                                            <img src="images/icon-phone.svg" alt="">
                                        </div>
                                        <!-- Icon Box End -->

                                        <!-- Contact Us Content Start -->
                                        <div class="contact-info-content">
                                            <h3>contact us</h3>
                                            <p>(+01) 789 854 856</p>
                                            <p>(+01) 789 854 856</p>
                                        </div>
                                        <!-- Contact Us Content End -->
                                    </div>
                                    <!-- Contact Us Item End -->
                                </div>

                                <div class="col-md-6">
                                    <!-- Contact US Item Start -->
                                    <div class="contact-us-item wow fadeInUp" data-wow-delay="0.5s">
                                        <!-- Icon Box Start -->
                                        <div class="icon-box">
                                            <img src="images/icon-clock.svg" alt="">
                                        </div>
                                        <!-- Icon Box End -->

                                        <!-- Contact Us Content Start -->
                                        <div class="contact-info-content">
                                            <h3>working hours</h3>
                                            <p>Mon to Fri : 10:00 To 6:00</p>
                                            <p>Sat : 10:00AM To 3:00PM</p>
                                        </div>
                                        <!-- Contact Us Content End -->
                                    </div>
                                    <!-- Contact Us Item End -->
                                </div>

                                <div class="col-md-6">
                                    <!-- Contact US Item Start -->
                                    <div class="contact-us-item wow fadeInUp" data-wow-delay="0.75s">
                                        <!-- Icon Box Start -->
                                        <div class="icon-box">
                                            <img src="images/icon-mail.svg" alt="">
                                        </div>
                                        <!-- Icon Box End -->

                                        <!-- Contact Us Content Start -->
                                        <div class="contact-info-content">
                                            <h3>email us</h3>
                                            <p>info@domainname.com</p>
                                            <p>sales@domainname.com</p>
                                        </div>
                                        <!-- Contact Us Content End -->
                                    </div>
                                    <!-- Contact Us Item End -->
                                </div>
                            </div>
                        </div>
                        <!-- Contact US Info End -->
                    </div>
                </div>
                <div class="col-lg-6">
                    <!-- Google Map Start -->
                    <div class="google-map">
                        <!-- Google Map Iframe Start -->
                        <div class="google-map-iframe">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d96737.10562045308!2d-74.08535042841811!3d40.739265258395164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1703158537552!5m2!1sen!2sin"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <!-- Google Map Iframe End -->
                    </div>
                    <!-- Google Map End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Contact End -->

    <!-- Contact Form Start -->
    <div class="contact-us-form">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <!-- Contact Us Image Start -->
                    <div class="contact-us-img">
                        <figure class="reveal image-anime">
                            <img src="images/contact-us-img.jpg" alt="">
                        </figure>
                    </div>
                    <!-- Contact Us Image End -->
                </div>
                <div class="col-lg-6">
                    <div class="contact-form">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">contact us</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Get</span> In Touch With Us</h2>
                        </div>
                        <!-- Section Title End -->

                        <form id="contactForm" action="#" method="POST" data-toggle="validator" class="wow fadeInUp"
                            data-wow-delay="0.25s">
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="name" class="form-control" id="fullname"
                                        placeholder="Enter Name" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="email" name="email" class="form-control" id="email"
                                        placeholder="Enter Email" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="phone" class="form-control" id="phone"
                                        placeholder="Phone Number" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="subject" class="form-control" id="subject"
                                        placeholder="Subject" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-12 mb-5">
                                    <textarea name="msg" class="form-control" id="msg" rows="5" placeholder="Your Message"
                                        required=""></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn-default">send message</button>
                                    <div id="msgSubmit" class="h3 hidden"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Form End -->
@endsection
