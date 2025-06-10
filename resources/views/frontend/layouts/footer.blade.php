<footer class="main-footer" style="margin-top: 0">
    <div class="container">
        <div class="row">
            <!-- About Section -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="about-footer">
                    <!-- Footer Logo & Brand Start -->
                    <div class="footer-logo d-flex align-items-center mb-3">
                        <img src="{{ asset('frontend/images/favicon.jpg') }}" alt="Thu Hiền Logo" class="me-3">
                        <div class="brand-text">
                            <h4 class="brand-name mb-0">THU HIỀN</h4>
                            <p class="brand-tagline mb-0">Phòng Khám Phụ Sản</p>
                        </div>
                    </div>
                    <!-- Footer Logo & Brand End -->

                    <!-- About Footer Content Start -->
                    <div class="about-footer-content">
                        <p class="mb-3">Chuyên khoa sản phụ khoa với đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị
                            hiện đại.
                            Chăm sóc sức khỏe phụ nữ toàn diện từ khám thai, điều trị phụ khoa đến tư vấn sức khỏe sinh
                            sản.</p>

                        <!-- Working Hours -->
                        <div class="working-hours">
                            <h6 class="mb-2"><i class="fas fa-clock me-2"></i>Giờ làm việc</h6>
                            <div class="time-schedule">
                                <div class="d-flex justify-content-between">
                                    <span>Thứ 2 - Chủ nhật:</span>
                                    <span>7:00 - 19:00</span>
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
                        <li><a href="{{ route('frontend.posts') }}"><i class="fas fa-chevron-right me-2"></i>Tin tức sức
                                khỏe</a></li>
                        <li><a href="{{ route('frontend.appointment') }}"><i class="fas fa-chevron-right me-2"></i>Đặt
                                lịch hẹn</a></li>
                        <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right me-2"></i>Liên hệ</a></li>
                    </ul>
                </div>
            </div>

            <!-- Services -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footer-links footer-services-links">
                    <h5 class="footer-title">Dịch vụ chuyên khoa</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('frontend.services.type', 'procedure') }}"><i
                                    class="fas fa-stethoscope me-2"></i>Khám thai định kỳ</a></li>
                        <li><a href="{{ route('frontend.services.type', 'procedure') }}"><i
                                    class="fas fa-baby me-2"></i>Siêu âm thai</a></li>
                        <li><a href="{{ route('frontend.services.type', 'procedure') }}"><i
                                    class="fas fa-heartbeat me-2"></i>Điều trị phụ khoa</a></li>
                        <li><a href="{{ route('frontend.services.type', 'laboratory') }}"><i
                                    class="fas fa-microscope me-2"></i>Xét nghiệm phụ khoa</a></li>
                        <li><a href="{{ route('frontend.services.type', 'other') }}"><i
                                    class="fas fa-user-md me-2"></i>Tư vấn sức khỏe</a></li>
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
                                <p class="mb-0">65 Hùng Vương, Phường 4,<br>Quận 5, TP. Hồ Chí Minh</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone me-3"></i>
                            <div>
                                <h6 class="mb-1">Hotline</h6>
                                <a href="tel:+84384518881" class="contact-link">0384 518 881</a>
                                <br>
                                <a href="tel:+84988669292" class="contact-link">0988 669 292</a>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope me-3"></i>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <a href="mailto:info@phongkhamthuhien.com"
                                    class="contact-link">info@phongkhamthuhien.com</a>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="social-links mt-4">
                        <h6 class="mb-3 text-white">Kết nối với chúng tôi</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/pk.sanphukhoasaigon" class="social-link facebook"
                                title="Facebook" target="_blank" rel="noopener">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://zalo.me/0384518881" class="social-link zalo" title="Zalo" target="_blank"
                                rel="noopener">
                                <i class="fas fa-comment"></i>
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
                        <p class="mb-0">© {{ date('Y') }} Phòng Khám Phụ Sản Thu Hiền. Bảo lưu mọi quyền.
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <div class="footer-bottom-links">
                        Created by <a href="https://github.com/cuong1211" target="_blank" rel="noopener">tvc.dev</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <div class="floating-buttons">
        <!-- Messenger Button -->
        <a href="https://m.me/pk.sanphukhoasaigon" class="floating-btn messenger-btn" target="_blank"
            rel="noopener">
            <i class="fab fa-facebook-messenger"></i>
            <span class="tooltip">Chat với chúng tôi</span>
        </a>

        <!-- Phone Button -->
        <a href="tel:+84384518881" class="floating-btn phone-btn">
            <i class="fas fa-phone"></i>
            <span class="tooltip">Gọi: 0384 518 881</span>
        </a>

        <!-- Zalo Button -->
        <a href="https://zalo.me/0384518881" class="floating-btn zalo-btn" target="_blank" rel="noopener">
            <i class="fas fa-comment"></i>
            <span class="tooltip">Chat Zalo</span>
        </a>

        <!-- Back to Top Button -->
        <div class="floating-btn back-to-top" id="backToTop">
            <i class="fas fa-chevron-up"></i>
            <span class="tooltip">Lên đầu trang</span>
        </div>
    </div>

    <!-- Schema.org JSON-LD for Footer -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "ContactPage",
        "mainEntity": {
            "@type": "MedicalClinic",
            "name": "Phòng Khám Phụ Sản Thu Hiền",
            "telephone": ["+84384518881", "+84988669292"],
            "email": "info@phongkhamthuhien.com",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "65 Hùng Vương, Phường 4",
                "addressLocality": "Quận 5",
                "addressRegion": "TP. Hồ Chí Minh",
                "addressCountry": "VN"
            },
            "openingHours": "Mo-Su 07:00-19:00",
            "sameAs": [
                "https://www.facebook.com/pk.sanphukhoasaigon",
                "https://zalo.me/0384518881"
            ]
        }
    }
    </script>

    <style>
        /* Zalo Button */
        .zalo-btn {
            background: linear-gradient(45deg, #0068ff, #0084ff);
            animation: pulse-zalo 2s infinite 1s;
        }

        .zalo-btn:hover {
            background: linear-gradient(45deg, #0056d3, #0070e0);
        }

        @keyframes pulse-zalo {
            0% {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(0, 104, 255, 0.7);
            }

            70% {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 10px rgba(0, 104, 255, 0);
            }

            100% {
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2), 0 0 0 0 rgba(0, 104, 255, 0);
            }
        }

        /* Enhanced footer links for better SEO */
        .footer-services-links a {
            transition: all 0.3s ease;
        }

        .footer-services-links a:hover {
            color: #ffd93d;
            padding-left: 10px;
        }

        /* Structured data for contact info */
        .contact-item[itemprop] {
            display: block;
        }

        /* Better contrast for accessibility */
        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.9);
        }

        .footer-bottom-links a:hover {
            color: #ffd93d;
            text-decoration: underline;
        }
    </style>
</footer>
