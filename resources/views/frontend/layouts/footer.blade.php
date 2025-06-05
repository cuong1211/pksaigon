<footer class="main-footer">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="about-footer">
                    <!-- Footer Logo & Brand Start -->
                    <div class="footer-logo d-flex align-items-center mb-3">
                        <img src="{{ asset('frontend/images/logo.png') }}" alt="PKSG Logo" class="me-3">
                        <div class="brand-text">
                            <h4 class="brand-name mb-0">PKSG</h4>
                            <p class="brand-tagline mb-0">Phòng Khám Sài Gòn</p>
                        </div>
                    </div>
                    <!-- Footer Logo & Brand End -->

                    <!-- About Footer Content Start -->
                    <div class="about-footer-content">
                        <p class="mb-3">Mục tiêu của chúng tôi là cung cấp dịch vụ chăm sóc sức khỏe chuyên nghiệp,
                            thân thiện với chất lượng cao nhất trong khám bệnh tổng quát và chuyên khoa.</p>

                        <!-- Working Hours -->
                        <div class="working-hours">
                            <h6 class="mb-2"><i class="fas fa-clock me-2"></i>Giờ làm việc</h6>
                            <div class="time-schedule">
                                <div class="d-flex justify-content-between">
                                    <span>Thứ 2 - Chủ nhật:</span>
                                    <span>8:00 - 17:00</span>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <!-- About Footer Content End -->
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <div class="footer-links footer-quick-links">
                    <h5 class="footer-title">Liên kết nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right me-2"></i>Trang chủ</a></li>
                        <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right me-2"></i>Giới thiệu</a></li>
                        <li><a href="{{ route('frontend.services') }}"><i class="fas fa-chevron-right me-2"></i>Dịch
                                vụ</a></li>
                        <li><a href="{{ route('frontend.posts') }}"><i class="fas fa-chevron-right me-2"></i>Tin tức</a>
                        </li>
                        <li><a href="{{ route('frontend.appointment') }}"><i class="fas fa-chevron-right me-2"></i>Đặt
                                lịch
                                hẹn</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right me-2"></i>Liên hệ</a></li>
                    </ul>
                </div>
            </div>

            <!-- Services -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-links footer-services-links">
                    <h5 class="footer-title">Dịch vụ nổi bật</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-stethoscope me-2"></i>Khám tổng quát</a></li>
                    </ul>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-links footer-contact-info">
                    <h5 class="footer-title">Thông tin liên hệ</h5>

                    <!-- Address -->
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-3 mt-1"></i>
                            <div>
                                <h6 class="mb-1">Địa chỉ</h6>
                                <p class="mb-0">65 Hùng vương, Phường 4,<br>Quận 5, TP. Hồ Chí Minh</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone me-3"></i>
                            <div>
                                <h6 class="mb-1">Hotline</h6>
                                <a href="tel:+84123456789" class="contact-link"> 0384518881</a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="social-links mt-4">
                        <h6 class="mb-3 text-white">Kết nối với chúng tôi</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/pk.sanphukhoasaigon" class="social-link facebook" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="footer-copyright-text">
                        <p class="mb-0">© {{ date('Y') }} Phòng Khám Sài Gòn (PKSG) created by tvc.dev.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer-bottom-links text-lg-end">
                        <a href="#" class="me-3">Chính sách bảo mật</a>
                        <a href="#" class="me-3">Điều khoản sử dụng</a>
                        <a href="#">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-chevron-up"></i>
    </div>
</footer>

    


    
