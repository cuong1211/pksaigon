@extends('frontend.layouts.index')

@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">Liên hệ với chúng tôi</h1>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Contact Us Section Start -->
    <div class="contact-us">
        <div class="container">
            <div class="row">
                <!-- Contact Information -->
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-box wow fadeInUp">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Địa chỉ</h3>
                                <p>65 Hùng Vương, Phường 4<br>Quận 5, TP. Hồ Chí Minh</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-box wow fadeInUp" data-wow-delay="0.2s">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Điện thoại</h3>
                                <p><a href="tel:03845188881">0384 518 8881</a></p>
                                <small>BS.Nguyễn Thị Thu Hiền</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-box wow fadeInUp" data-wow-delay="0.4s">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Giờ làm việc</h3>
                                <p>Thứ 2 - Chủ nhật<br>8:00 AM - 5:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row section-row">
                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="contact-form-section wow fadeInUp" data-wow-delay="0.6s">
                        <div class="section-header">
                            <h2 class="text-anime-style-3">Gửi tin nhắn cho chúng tôi</h2>
                            <p>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy để lại thông tin và chúng tôi sẽ liên hệ
                                lại sớm nhất.</p>
                        </div>

                        <!-- Contact Form Start -->
                        <div class="contact-form">
                            <form id="contactForm" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Họ và tên *" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control" placeholder="Email *"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="tel" name="phone" class="form-control"
                                                placeholder="Số điện thoại">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" name="subject" class="form-control" placeholder="Chủ đề *"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <textarea name="message" class="form-control" rows="6" placeholder="Nội dung tin nhắn *" required></textarea>
                                </div>

                                <div class="form-group text-center">
                                    <button type="submit" class="btn-default" id="submitBtn">
                                        <span class="btn-text">Gửi tin nhắn</span>
                                        <span class="btn-loading" style="display: none;">
                                            <i class="fas fa-spinner fa-spin"></i> Đang gửi...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Contact Form End -->
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="col-lg-4">
                    <div class="contact-sidebar wow fadeInUp" data-wow-delay="0.8s">
                        <div class="sidebar-widget">
                            <h3 class="widget-title">Phòng Khám Sài Gòn</h3>
                            <div class="widget-content">
                                <p>Chúng tôi cung cấp dịch vụ chăm sóc sức khỏe chuyên nghiệp với đội ngũ bác sĩ giàu kinh
                                    nghiệm và trang thiết bị hiện đại.</p>

                                <div class="contact-features">
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Khám bệnh tổng quát</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Xét nghiệm chuyên khoa</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Tư vấn sức khỏe 24/7</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle"></i>
                                        <span>Đặt lịch hẹn online</span>
                                    </div>
                                </div>

                                <div class="emergency-contact">
                                    <div class="emergency-box">
                                        <div class="emergency-icon">
                                            <i class="fas fa-ambulance"></i>
                                        </div>
                                        <div class="emergency-content">
                                            <h4>Cấp cứu 24/7</h4>
                                            <p><a href="tel:03845188881">0384 518 8881</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Section -->
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="contact-map wow fadeInUp" data-wow-delay="1s">
                        <div class="section-header text-center">
                            <h2 class="text-anime-style-3">Vị trí phòng khám</h2>
                            <p>Chúng tôi tọa lạc tại vị trí thuận tiện, dễ dàng di chuyển bằng các phương tiện công cộng.
                            </p>
                        </div>

                        <div class="map-container">
                            <!-- Google Maps Embed -->

                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d560.7132917885511!2d106.67725972899653!3d10.762753230234482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1dee235cc9%3A0x844785fe99581167!2zNjUgxJAuIEjDuW5nIFbGsMahbmcsIFBoxrDhu51uZyA0LCBRdeG6rW4gNSwgSOG7kyBDaMOtIE1pbmg!5e1!3m2!1sen!2s!4v1748595644802!5m2!1sen!2s"
                                width="100%" height="400" style="border:0; border-radius: 10px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Us Section End -->
@endsection

@push('csscustom')
    <style>
        /* Contact Section Styles */
        .page-header{
            padding: 20px 0;
        }
        .contact-us {
            padding: 20px 0;
            background: #f8f9fa;
        }

        .section-row {
            margin-top: 60px;
        }

        /* Contact Info Boxes */
        .contact-info-box {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
            height: 100%;
        }

        .contact-info-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .contact-info-item {
            text-align: center;
        }

        .contact-info-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #1e85b4, #2196F3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            transition: all 0.3s ease;
        }

        .contact-info-icon i {
            font-size: 28px;
            color: white;
        }

        .contact-info-box:hover .contact-info-icon {
            transform: scale(1.1);
        }

        .contact-info-content h3 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .contact-info-content p {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 5px;
        }

        .contact-info-content a {
            color: #1e85b4;
            text-decoration: none;
            font-weight: 600;
        }

        .contact-info-content a:hover {
            color: #2196F3;
        }

        .contact-info-content small {
            color: #999;
            font-size: 14px;
        }

        /* Contact Form */
        .contact-form-section {
            background: white;
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-header h2 {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .section-header p {
            font-size: 16px;
            color: #666;
            line-height: 1.6;
        }

        .contact-form .form-group {
            margin-bottom: 25px;
        }

        .contact-form .form-control {
            height: 55px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .contact-form textarea.form-control {
            height: auto;
            resize: vertical;
            min-height: 120px;
        }

        .contact-form .form-control:focus {
            border-color: #1e85b4;
            box-shadow: 0 0 0 0.2rem rgba(30, 133, 180, 0.25);
            background: white;
        }

        .contact-form .btn-default {
            background: linear-gradient(45deg, #1e85b4, #2196F3);
            color: white;
            border: none;

            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-form .btn-default:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 133, 180, 0.4);
        }

        .contact-form .btn-default:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Contact Sidebar */
        .contact-sidebar {
            padding-left: 30px;
        }

        .sidebar-widget {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .widget-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .widget-content p {
            font-size: 15px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .contact-features {
            margin-bottom: 30px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 0;
        }

        .feature-item i {
            color: #28a745;
            font-size: 16px;
            margin-right: 15px;
            width: 20px;
        }

        .feature-item span {
            font-size: 15px;
            color: #555;
            font-weight: 500;
        }

        .emergency-contact {
            margin-top: 30px;
        }

        .emergency-box {
            background: linear-gradient(45deg, #dc3545, #e74c3c);
            border-radius: 15px;
            padding: 25px;
            color: white;
            text-align: center;
        }

        .emergency-icon {
            margin-bottom: 15px;
        }

        .emergency-icon i {
            font-size: 40px;
            color: white;
        }

        .emergency-content h4 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .emergency-content p {
            margin: 0;
        }

        .emergency-content a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
        }

        /* Map Section */
        .contact-map {
            text-align: center;
        }

        .map-container {
            margin-top: 40px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .contact-sidebar {
                padding-left: 0;
                margin-top: 40px;
            }

            .contact-form-section {
                padding: 30px 25px;
            }

            .section-header h2 {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .contact-us {
                padding: 60px 0;
            }

            .contact-info-box {
                padding: 30px 20px;
            }

            .contact-form-section {
                padding: 25px 20px;
            }

            .sidebar-widget {
                padding: 30px 20px;
            }

            .section-header h2 {
                font-size: 24px;
            }

            .contact-info-content h3 {
                font-size: 20px;
            }
        }

        /* Animation cho form success */
        .alert {
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: none;
        }

        .alert-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(45deg, #dc3545, #e74c3c);
            color: white;
        }
    </style>
@endpush

@push('jscustom')
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                // Disable submit button
                const submitBtn = $('#submitBtn');
                const btnText = submitBtn.find('.btn-text');
                const btnLoading = submitBtn.find('.btn-loading');

                submitBtn.prop('disabled', true);
                btnText.hide();
                btnLoading.show();

                // Clear previous alerts
                $('.alert').remove();

                // Submit form via AJAX
                $.ajax({
                    url: "{{ route('contact.store') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Show success message
                        const alertHtml = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        ${response.content}
                    </div>
                `;
                        $('#contactForm').prepend(alertHtml);

                        // Reset form
                        $('#contactForm')[0].reset();

                        // Show success notification if available
                        if (typeof notification === 'function') {
                            notification(response.type, response.title, response.content);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Có lỗi xảy ra. Vui lòng thử lại!';

                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            let errorList = '<ul>';
                            $.each(errors, function(key, messages) {
                                $.each(messages, function(index, message) {
                                    errorList += '<li>' + message + '</li>';
                                });
                            });
                            errorList += '</ul>';
                            errorMessage = errorList;
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        const alertHtml = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>${errorMessage}</div>
                    </div>
                `;
                        $('#contactForm').prepend(alertHtml);

                        // Show error notification if available
                        if (typeof notification === 'function') {
                            notification('error', 'Lỗi', 'Có lỗi xảy ra khi gửi tin nhắn');
                        }
                    },
                    complete: function() {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false);
                        btnText.show();
                        btnLoading.hide();

                        // Scroll to top of form to show alert
                        $('html, body').animate({
                            scrollTop: $('#contactForm').offset().top - 100
                        }, 500);
                    }
                });
            });

            // Phone number formatting
            $('input[name="phone"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.length > 10) {
                    value = value.substring(0, 11);
                }
                $(this).val(value);
            });
        });
    </script>
@endpush
