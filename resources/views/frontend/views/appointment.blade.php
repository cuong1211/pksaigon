@extends('frontend.layouts.index')

@section('content')

    <!-- Page Appointment Start -->
    <div class="page-book-appointment">
        <div class="container">
            <div class="book-appointment-form">
                <div class="row section-row">
                    <div class="col-lg-12">
                        <!-- Section Title Start -->
                        <div class="section-title">
                            <h3 class="wow fadeInUp">đặt lịch</h3>
                            <h2 class="text-anime-style-2" data-cursor="-opaque"><span>Đặt Lịch</span> Khám Bệnh</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.25s">Vui lòng điền đầy đủ thông tin để đặt lịch khám.
                                Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.</p>
                        </div>
                        <!-- Section Title End -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="appointment-form wow fadeInUp">
                            <!-- Form Start -->
                            <form id="appointment-form" method="POST" data-toggle="validator">
                                @csrf
                                <div class="row">
                                    <!-- Họ tên -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="full_name" class="form-label">Họ và tên <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="full_name" class="form-control" id="full_name"
                                            placeholder="Nhập họ và tên đầy đủ" required>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Số điện thoại -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="phone" class="form-label">Số điện thoại <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control" id="phone"
                                            placeholder="Nhập số điện thoại" required pattern="[0-9]{10,11}">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="email"
                                            placeholder="Nhập địa chỉ email">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Ngày sinh -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                        <input type="date" name="date_of_birth" class="form-control" id="date_of_birth">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Giới tính -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="gender" class="form-label">Giới tính</label>
                                        <select name="gender" class="form-control form-select" id="gender">
                                            <option value="">Chọn giới tính</option>
                                            <option value="female">Nữ</option>
                                            <option value="male">Nam</option>
                                            <option value="other">Khác</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Dịch vụ -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="service_id" class="form-label">Dịch vụ khám</label>
                                        <select name="service_id" class="form-control form-select" id="service_id">
                                            <option value="">Chọn dịch vụ</option>
                                            <!-- Sẽ được load bằng AJAX -->
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Ngày khám -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="appointment_date" class="form-label">Ngày khám <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="appointment_date" class="form-control"
                                            id="appointment_date" required>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Giờ khám -->
                                    <div class="form-group col-md-6 mb-4">
                                        <label for="appointment_time" class="form-label">Giờ khám <span
                                                class="text-danger">*</span></label>
                                        <select name="appointment_time" class="form-control form-select"
                                            id="appointment_time" required>
                                            <option value="">Chọn giờ khám</option>
                                            <option value="08:00">08:00 - 08:30</option>
                                            <option value="08:30">08:30 - 09:00</option>
                                            <option value="09:00">09:00 - 09:30</option>
                                            <option value="09:30">09:30 - 10:00</option>
                                            <option value="10:00">10:00 - 10:30</option>
                                            <option value="10:30">10:30 - 11:00</option>
                                            <option value="11:00">11:00 - 11:30</option>
                                            <option value="14:00">14:00 - 14:30</option>
                                            <option value="14:30">14:30 - 15:00</option>
                                            <option value="15:00">15:00 - 15:30</option>
                                            <option value="15:30">15:30 - 16:00</option>
                                            <option value="16:00">16:00 - 16:30</option>
                                            <option value="16:30">16:30 - 17:00</option>
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Địa chỉ -->
                                    <div class="form-group col-md-12 mb-4">
                                        <label for="address" class="form-label">Địa chỉ</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="Nhập địa chỉ hiện tại">
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Triệu chứng -->
                                    <div class="form-group col-md-12 mb-4">
                                        <label for="symptoms" class="form-label">Triệu chứng hiện tại</label>
                                        <textarea name="symptoms" class="form-control" id="symptoms" rows="3"
                                            placeholder="Mô tả triệu chứng hoặc lý do khám (không bắt buộc)"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <!-- Ghi chú -->
                                    <div class="form-group col-md-12 mb-4">
                                        <label for="notes" class="form-label">Ghi chú thêm</label>
                                        <textarea name="notes" class="form-control" id="notes" rows="2"
                                            placeholder="Ghi chú thêm (yêu cầu đặc biệt, thuốc đang dùng...)"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn-default" id="submitBtn">
                                            <span class="btn-text">Đặt lịch khám</span>

                                        </button>
                                        <div id="msgSubmit" class="h3 hidden mt-3"></div>
                                    </div>
                                </div>
                            </form>
                            <!-- Form End -->
                        </div>
                    </div>

                    <!-- Sidebar thông tin -->
                    <div class="col-lg-4">
                        <div class="appointment-sidebar">
                            <!-- Thông tin liên hệ -->
                            <div class="sidebar-widget contact-widget">
                                <h4>Thông tin liên hệ</h4>
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                                        <div class="content">
                                            <h5>Địa chỉ</h5>
                                            <p>123 Nguyễn Văn Cừ<br>Quận 1, TP. Hồ Chí Minh</p>
                                        </div>
                                    </div>
                                    <div class="contact-item">
                                        <div class="icon"><i class="fas fa-phone"></i></div>
                                        <div class="content">
                                            <h5>Hotline</h5>
                                            <p><a href="tel:+84123456789">(028) 3822 5678</a></p>
                                        </div>
                                    </div>
                                    <div class="contact-item">
                                        <div class="icon"><i class="fas fa-clock"></i></div>
                                        <div class="content">
                                            <h5>Giờ làm việc</h5>
                                            <p>Thứ 2 - Chủ nhật<br>7:00 - 19:00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Lưu ý quan trọng -->
                            <div class="sidebar-widget note-widget">
                                <h4>Lưu ý quan trọng</h4>
                                <ul class="note-list">
                                    <li><i class="fas fa-check-circle"></i> Vui lòng đến trước giờ hẹn 15 phút</li>
                                    <li><i class="fas fa-check-circle"></i> Mang theo giấy tờ tùy thân</li>
                                    <li><i class="fas fa-check-circle"></i> Thông báo trước nếu cần hủy lịch</li>
                                    <li><i class="fas fa-check-circle"></i> Chuẩn bị sơ yếu lý lịch bệnh án</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Appointment End -->

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-success">
                        <i class="fas fa-check-circle me-2"></i>Đặt lịch thành công!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="success-icon mb-3">
                        <i class="fas fa-calendar-check text-success" style="font-size: 48px;"></i>
                    </div>
                    <h4>Cảm ơn bạn đã đặt lịch!</h4>
                    <p class="mb-3">Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận lịch hẹn.</p>
                    <div class="appointment-details" id="appointmentDetails">
                        <!-- Thông tin lịch hẹn sẽ được hiển thị ở đây -->
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('csscustom')
    <style>
        .page-book-appointment {
            padding: 20px;
        }

        /* Form styling */
        .appointment-form {
            background: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1e85b4;
            box-shadow: 0 0 0 0.2rem rgba(30, 133, 180, 0.25);
        }

        .btn-default {
            background: linear-gradient(45deg, #1e85b4, #1a73a0);
            border: none;
            border-radius: 99px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 200px;
        }

        .btn-default:hover {
            background: linear-gradient(45deg, #1a73a0, #155e85);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 133, 180, 0.4);
        }

        /* Sidebar styling */

        .sidebar-widget {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #1e85b4;
        }

        .sidebar-widget h4 {
            color: #1e85b4;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .contact-item .icon {
            width: 40px;
            height: 40px;
            background: #1e85b4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            flex-shrink: 0;
        }

        .contact-item .content h5 {
            margin-bottom: 5px;
            color: #333;
            font-weight: 600;
        }

        .contact-item .content p {
            margin: 0;
            color: #666;
            line-height: 1.4;
        }

        .contact-item .content a {
            color: #1e85b4;
            text-decoration: none;
        }

        .note-list {
            list-style: none;
            padding: 0;
        }

        .note-list li {
            padding: 8px 0;
            color: #555;
        }

        .note-list li i {
            color: #28a745;
            margin-right: 10px;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .appointment-sidebar {
                padding-left: 0;
                margin-top: 40px;
            }
        }

        @media (max-width: 768px) {
            .appointment-form {
                padding: 25px;
            }

            .sidebar-widget {
                padding: 20px;
            }
        }

        /* Loading state */
        .btn-loading {
            display: inline-flex !important;
            align-items: center;
        }

        /* Success modal */
        .success-icon {
            animation: bounceIn 0.6s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }

            50% {
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .appointment-details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #1e85b4;
        }

        /* Custom notification */
        .custom-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            max-width: 500px;
            z-index: 9999;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 8px;
        }

        .custom-notification .btn-close {
            padding: 0.5rem;
        }

        .custom-notification i {
            font-size: 1.1em;
        }
    </style>
@endpush

@push('jscustom')
    <script>
        $(document).ready(function() {
            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            $('#appointment_date').attr('min', minDate);

            // Load services
            loadServices();

            // Load available time slots when date changes
            $('#appointment_date').on('change', function() {
                const selectedDate = $(this).val();
                if (selectedDate) {
                    loadAvailableTimeSlots(selectedDate);
                }
            });

            // Form submission
            $('#appointment-form').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const $submitBtn = $('#submitBtn');

                // Show loading state
                $submitBtn.prop('disabled', true).text('Đang xử lý...');

                $.ajax({
                    url: '{{ route('frontend.appointment.store') }}',
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        if (response.type === 'success') {
                            // Show success modal
                            showSuccessModal($form);
                            // Reset form
                            $form[0].reset();
                            // Reset time slots to default
                            resetTimeSlots();
                            // Show notification
                            showNotification('success', response.content);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra, vui lòng thử lại!';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            const firstError = Object.values(errors)[0];
                            message = Array.isArray(firstError) ? firstError[0] : firstError;
                        } else if (xhr.responseJSON && xhr.responseJSON.content) {
                            message = xhr.responseJSON.content;
                        }

                        showNotification('error', message);
                    },
                    complete: function() {
                        // Hide loading state
                        $submitBtn.prop('disabled', false).text('Đặt lịch khám');
                    }
                });
            });

            function loadServices() {
                $.ajax({
                    url: '{{ route('frontend.services.api') }}',
                    method: 'GET',
                    success: function(services) {
                        const $serviceSelect = $('#service_id');
                        $serviceSelect.empty().append('<option value="">Chọn dịch vụ</option>');

                        services.forEach(function(service) {
                            $serviceSelect.append(`
                                <option value="${service.id}">
                                    ${service.name} - ${formatCurrency(service.price)}
                                </option>
                            `);
                        });
                    },
                    error: function() {
                        console.log('Không thể tải danh sách dịch vụ');
                        // Fallback: hiển thị option mặc định
                        const $serviceSelect = $('#service_id');
                        $serviceSelect.empty().append('<option value="">Lỗi tải dịch vụ</option>');
                    }
                });
            }

            function loadAvailableTimeSlots(date) {
                // Hiển thị tất cả khung giờ (có thể cải thiện sau)
                const $timeSelect = $('#appointment_time');
                $timeSelect.empty().append('<option value="">Chọn giờ khám</option>');

                const allTimeSlots = [{
                        value: '08:00',
                        label: '08:00 - 08:30'
                    },
                    {
                        value: '08:30',
                        label: '08:30 - 09:00'
                    },
                    {
                        value: '09:00',
                        label: '09:00 - 09:30'
                    },
                    {
                        value: '09:30',
                        label: '09:30 - 10:00'
                    },
                    {
                        value: '10:00',
                        label: '10:00 - 10:30'
                    },
                    {
                        value: '10:30',
                        label: '10:30 - 11:00'
                    },
                    {
                        value: '11:00',
                        label: '11:00 - 11:30'
                    },
                    {
                        value: '14:00',
                        label: '14:00 - 14:30'
                    },
                    {
                        value: '14:30',
                        label: '14:30 - 15:00'
                    },
                    {
                        value: '15:00',
                        label: '15:00 - 15:30'
                    },
                    {
                        value: '15:30',
                        label: '15:30 - 16:00'
                    },
                    {
                        value: '16:00',
                        label: '16:00 - 16:30'
                    },
                    {
                        value: '16:30',
                        label: '16:30 - 17:00'
                    }
                ];

                allTimeSlots.forEach(function(slot) {
                    $timeSelect.append(`<option value="${slot.value}">${slot.label}</option>`);
                });
            }

            function resetTimeSlots() {
                const $timeSelect = $('#appointment_time');
                $timeSelect.empty().append('<option value="">Chọn giờ khám</option>');

                // Thêm lại tất cả options mặc định
                const defaultSlots = [
                    '08:00|08:00 - 08:30',
                    '08:30|08:30 - 09:00',
                    '09:00|09:00 - 09:30',
                    '09:30|09:30 - 10:00',
                    '10:00|10:00 - 10:30',
                    '10:30|10:30 - 11:00',
                    '11:00|11:00 - 11:30',
                    '14:00|14:00 - 14:30',
                    '14:30|14:30 - 15:00',
                    '15:00|15:00 - 15:30',
                    '15:30|15:30 - 16:00',
                    '16:00|16:00 - 16:30',
                    '16:30|16:30 - 17:00'
                ];

                defaultSlots.forEach(function(slot) {
                    const [value, label] = slot.split('|');
                    $timeSelect.append(`<option value="${value}">${label}</option>`);
                });
            }

            function showSuccessModal($form) {
                const formData = new FormData($form[0]);
                const appointmentDate = formData.get('appointment_date');
                const appointmentTime = formData.get('appointment_time');
                const fullName = formData.get('full_name');
                const phone = formData.get('phone');

                const details = `
                    <div class="row text-start">
                        <div class="col-6"><strong>Họ tên:</strong></div>
                        <div class="col-6">${fullName}</div>
                        <div class="col-6"><strong>Số điện thoại:</strong></div>
                        <div class="col-6">${phone}</div>
                        <div class="col-6"><strong>Ngày khám:</strong></div>
                        <div class="col-6">${formatDate(appointmentDate)}</div>
                        <div class="col-6"><strong>Giờ khám:</strong></div>
                        <div class="col-6">${appointmentTime}</div>
                    </div>
                `;

                $('#appointmentDetails').html(details);
                $('#successModal').modal('show');
            }

            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('vi-VN');
            }

            // Custom notification function
            function showNotification(type, message) {
                // Remove existing notifications
                $('.custom-notification').remove();

                const typeClass = {
                    'success': 'alert-success',
                    'error': 'alert-danger',
                    'info': 'alert-info',
                    'warning': 'alert-warning'
                };

                const iconClass = {
                    'success': 'fas fa-check-circle',
                    'error': 'fas fa-exclamation-circle',
                    'info': 'fas fa-info-circle',
                    'warning': 'fas fa-exclamation-triangle'
                };

                const notification = $(`
                    <div class="custom-notification alert ${typeClass[type]} alert-dismissible fade show" role="alert">
                        <i class="${iconClass[type]} me-2"></i>
                        <strong>${message}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                // Add to top of page
                $('body').prepend(notification);

                // Auto hide after 5 seconds
                setTimeout(function() {
                    notification.fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });
    </script>
@endpush
