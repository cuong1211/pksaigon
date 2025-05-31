<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="kt_modal_add_customer_form">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Error Alert-->
                    <div class="alert alert-danger print-error-msg w-100" style="display:none">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mb-0"></ul>
                    </div>
                    <!--end::Error Alert-->

                    <!--begin::Modal title-->
                    <h2 class="fw-bolder modal-title text-gray-800">Thêm lịch hẹn mới</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->

                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">

                        <input type="hidden" name="id" value="">

                        <!--begin::Section - Thông tin bệnh nhân-->
                        <div class="mb-10">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-duotone ki-profile-user fs-2 text-primary">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-5 text-gray-800 fw-bold mb-1">Thông tin bệnh nhân</h3>
                                    <div class="text-muted fs-7">Nhập thông tin cơ bản của bệnh nhân</div>
                                </div>
                            </div>

                            <!--begin::Row-->
                            <div class="row g-5">
                                <!--begin::Col - Tên bệnh nhân-->
                                <div class="col-md-6">
                                    <div class="fv-row">
                                        <label class="required fs-6 fw-semibold mb-2 text-gray-700">Tên bệnh
                                            nhân</label>
                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Nhập họ tên đầy đủ" name="patient_name" />
                                        <div class="text-muted fs-7 mt-1">Vd: Nguyễn Văn A</div>
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col - Số điện thoại-->
                                <div class="col-md-6">
                                    <div class="fv-row">
                                        <label class="required fs-6 fw-semibold mb-2 text-gray-700">Số điện
                                            thoại</label>
                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Nhập số điện thoại" name="patient_phone" maxlength="11" />
                                        <div class="text-muted fs-7 mt-1">10-11 số, vd: 0901234567</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Section-->

                        <!--begin::Section - Thông tin lịch hẹn-->
                        <div class="mb-10">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-calendar fs-2 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-5 text-gray-800 fw-bold mb-1">Thông tin lịch hẹn</h3>
                                    <div class="text-muted fs-7">Chọn dịch vụ, ngày giờ khám</div>
                                </div>
                            </div>

                            <!--begin::Input group - Dịch vụ khám-->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 text-gray-700">Dịch vụ khám</label>
                                <select class="form-select form-select-solid" name="service_id" id="service_select"
                                    data-control="select2" data-placeholder="-- Chọn dịch vụ --"
                                    data-allow-clear="true">
                                    <option value="">-- Chọn dịch vụ --</option>
                                </select>
                                <div class="text-muted fs-7 mt-1">Chọn dịch vụ cần khám (có thể bỏ trống)</div>
                            </div>

                            <!--begin::Row - Ngày giờ-->
                            <div class="row g-5">
                                <!--begin::Col - Ngày hẹn-->
                                <div class="col-md-6">
                                    <div class="fv-row">
                                        <label class="required fs-6 fw-semibold mb-2 text-gray-700">Ngày hẹn</label>
                                        <input type="date" class="form-control form-control-solid"
                                            name="appointment_date" min="{{ date('Y-m-d') }}" />
                                        <div class="text-muted fs-7 mt-1">Chọn ngày khám</div>
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col - Giờ hẹn-->
                                <div class="col-md-6">
                                    <div class="fv-row">
                                        <label class="required fs-6 fw-semibold mb-2 text-gray-700">Giờ hẹn</label>
                                        <select class="form-select form-select-solid" name="appointment_time">
                                            <option value="08:00">08:00 - Sáng sớm</option>
                                            <option value="08:30">08:30</option>
                                            <option value="09:00" selected>09:00 - Giờ hành chính</option>
                                            <option value="09:30">09:30</option>
                                            <option value="10:00">10:00</option>
                                            <option value="10:30">10:30</option>
                                            <option value="11:00">11:00</option>
                                            <option value="11:30">11:30</option>
                                            <option value="13:30">13:30 - Chiều</option>
                                            <option value="14:00">14:00</option>
                                            <option value="14:30">14:30</option>
                                            <option value="15:00">15:00</option>
                                            <option value="15:30">15:30</option>
                                            <option value="16:00">16:00</option>
                                            <option value="16:30">16:30</option>
                                            <option value="17:00">17:00 - Cuối giờ</option>
                                        </select>
                                        <div class="text-muted fs-7 mt-1">Chọn khung giờ phù hợp</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Section-->

                        <!--begin::Section - Quản lý trạng thái-->
                        <div class="mb-10">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-info">
                                        <i class="ki-duotone ki-setting-2 fs-2 text-info">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-5 text-gray-800 fw-bold mb-1">Quản lý trạng thái</h3>
                                    <div class="text-muted fs-7">Thiết lập trạng thái và nguồn đặt lịch</div>
                                </div>
                            </div>

                            <!--begin::Row - Trạng thái và Nguồn-->
                            <div class="row g-5">
                                <!--begin::Col - Trạng thái-->
                                <div class="col-md-6">
                                    <div class="fv-row">
                                        <label class="fs-6 fw-semibold mb-2 text-gray-700">Trạng thái</label>
                                        <select class="form-select form-select-solid" name="status">
                                            <option value="pending">
                                                <i class="ki-duotone ki-time text-warning"></i>
                                                Chờ xác nhận
                                            </option>
                                            <option value="confirmed">
                                                <i class="ki-duotone ki-check text-info"></i>
                                                Đã xác nhận
                                            </option>
                                            <option value="completed">
                                                <i class="ki-duotone ki-check-circle text-success"></i>
                                                Đã hoàn thành
                                            </option>
                                            <option value="cancelled">
                                                <i class="ki-duotone ki-cross-circle text-danger"></i>
                                                Đã hủy
                                            </option>
                                        </select>
                                        <div class="text-muted fs-7 mt-1">Trạng thái hiện tại của lịch hẹn</div>
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col - Nguồn đặt lịch-->
                                <div class="col-md-6">
                                    <div class="fv-row">
                                        <label class="fs-6 fw-semibold mb-2 text-gray-700">Nguồn đặt lịch</label>
                                        <select class="form-select form-select-solid" name="source">
                                            <option value="phone">
                                                <i class="ki-duotone ki-phone text-primary"></i>
                                                Điện thoại
                                            </option>
                                            <option value="walk-in">
                                                <i class="ki-duotone ki-entrance-right text-success"></i>
                                                Đến trực tiếp
                                            </option>
                                            <option value="website">
                                                <i class="ki-duotone ki-global text-info"></i>
                                                Website
                                            </option>
                                        </select>
                                        <div class="text-muted fs-7 mt-1">Cách thức đặt lịch hẹn</div>
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->
                        </div>
                        <!--end::Section-->

                        <!--begin::Section - Thông tin y tế-->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-40px me-3">
                                    <div class="symbol-label bg-light-warning">
                                        <i class="ki-duotone ki-notepad fs-2 text-warning">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="fs-5 text-gray-800 fw-bold mb-1">Thông tin y tế</h3>
                                    <div class="text-muted fs-7">Mô tả triệu chứng và ghi chú thêm</div>
                                </div>
                            </div>

                            <!--begin::Input group - Triệu chứng-->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2 text-gray-700">Triệu chứng</label>
                                <textarea class="form-control form-control-solid" rows="4" name="symptoms"
                                    placeholder="Mô tả chi tiết triệu chứng của bệnh nhân để bác sĩ chuẩn bị tốt hơn..."></textarea>
                                <div class="text-muted fs-7 mt-1">
                                    <i class="ki-duotone ki-information-5 text-primary fs-6 me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    Thông tin này giúp bác sĩ chuẩn bị tốt hơn cho buổi khám
                                </div>
                            </div>

                            <!--begin::Input group - Ghi chú-->
                            <div class="fv-row mb-0">
                                <label class="fs-6 fw-semibold mb-2 text-gray-700">Ghi chú thêm</label>
                                <textarea class="form-control form-control-solid" rows="3" name="notes"
                                    placeholder="Ghi chú về yêu cầu đặc biệt, thuốc đang dùng, dị ứng..."></textarea>
                                <div class="text-muted fs-7 mt-1">
                                    <i class="ki-duotone ki-questionnaire-tablet text-info fs-6 me-1">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Các thông tin quan trọng khác mà bác sĩ cần biết
                                </div>
                            </div>
                        </div>
                        <!--end::Section-->

                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->

                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button Cancel-->
                    <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">
                        <i class="ki-duotone ki-cross fs-2 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Hủy bỏ
                    </button>
                    <!--end::Button Cancel-->

                    <!--begin::Button Submit-->
                    <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                        <span class="indicator-label">
                            <i class="ki-duotone ki-check fs-2 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Xác nhận
                        </span>
                        <span class="indicator-progress">
                            Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <!--end::Button Submit-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>

@push('csscustom')
    <style>
        /* Modal custom styles */
        .modal-dialog.mw-900px {
            max-width: 900px !important;
        }

        .modal-header {
            background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }

        .print-error-msg {
            position: absolute;
            top: 100%;
            left: 15px;
            right: 15px;
            z-index: 1;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #f1416c;
            box-shadow: 0 4px 10px rgba(241, 65, 108, 0.15);
        }

        .modal-title {
            font-size: 1.5rem;
            color: #181c32;
            font-weight: 700;
        }

        /* Section styling */
        .symbol-label {
            border-radius: 12px;
        }

        .form-control-solid {
            background-color: #f8f9fa;
            border: 1px solid #e4e6ef;
            color: #181c32;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control-solid:focus {
            background-color: #ffffff;
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.15);
        }

        .form-select-solid {
            background-color: #f8f9fa;
            border: 1px solid #e4e6ef;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-select-solid:focus {
            background-color: #ffffff;
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.15);
        }

        /* Label styling */
        .fw-semibold {
            font-weight: 600 !important;
        }

        .required::after {
            content: " *";
            color: #f1416c;
            font-weight: bold;
        }

        /* Text helper styling */
        .text-muted.fs-7 {
            font-size: 0.8rem;
            line-height: 1.4;
        }

        /* Section dividers */
        .mb-10:not(:last-child) {
            border-bottom: 1px solid #f1f3f6;
            padding-bottom: 1.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog.mw-900px {
                max-width: 95% !important;
                margin: 1rem auto;
            }

            .px-lg-17 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .pe-7 {
                padding-right: 0.5rem !important;
            }

            .me-n7 {
                margin-right: -0.5rem !important;
            }
        }

        /* Button styling */
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(90deg, #009ef7 0%, #0084d3 100%);
            border: none;
            box-shadow: 0 2px 8px rgba(0, 158, 247, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #0084d3 0%, #006bb3 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 158, 247, 0.4);
        }

        .btn-light {
            background-color: #f8f9fa;
            border: 1px solid #e4e6ef;
            color: #7e8299;
        }

        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #d3d6db;
            color: #5e6278;
            transform: translateY(-1px);
        }

        /* Icon styling */
        .ki-duotone {
            color: inherit;
        }

        /* Scroll styling */
        .scroll-y::-webkit-scrollbar {
            width: 6px;
        }

        .scroll-y::-webkit-scrollbar-track {
            background: #f1f3f6;
            border-radius: 3px;
        }

        .scroll-y::-webkit-scrollbar-thumb {
            background: #d1d3e0;
            border-radius: 3px;
        }

        .scroll-y::-webkit-scrollbar-thumb:hover {
            background: #b5b8c8;
        }

        /* Animation for modal appearance */
        .modal.fade .modal-dialog {
            transform: scale(0.8) translateY(-50px);
            transition: all 0.3s ease;
        }

        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
        }

        /* Select2 integration if needed */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: calc(1.5em + 1rem + 2px);
            border: 1px solid #e4e6ef;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 1rem);
            padding-left: 0.75rem;
            color: #181c32;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1rem);
            right: 10px;
        }
    </style>
@endpush

@push('jscustom')
    <script>
        // Initialize Select2 if available
        $(document).ready(function() {
            if (typeof $.fn.select2 !== 'undefined') {
                $('#service_select').select2({
                    dropdownParent: $('#kt_modal_add_customer'),
                    placeholder: "-- Chọn dịch vụ --",
                    allowClear: true,
                    width: '100%'
                });
            }
        });

        // Enhanced form validation styling
        $('#kt_modal_add_customer_form').on('submit', function(e) {
            // Clear previous validation states
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback').remove();
        });

        // Real-time phone number formatting
        $('input[name="patient_phone"]').on('input', function() {
            let value = $(this).val().replace(/\D/g, ''); // Remove non-digits
            if (value.length > 11) {
                value = value.substr(0, 11); // Limit to 11 digits
            }
            $(this).val(value);
        });

        // Enhanced error display
        function showFieldError(fieldName, message) {
            const field = $(`[name="${fieldName}"]`);
            field.addClass('is-invalid');

            // Remove existing error message
            field.next('.invalid-feedback').remove();

            // Add new error message
            field.after(`<div class="invalid-feedback d-block">${message}</div>`);
        }

        // Clear field error
        function clearFieldError(fieldName) {
            const field = $(`[name="${fieldName}"]`);
            field.removeClass('is-invalid');
            field.next('.invalid-feedback').remove();
        }

        // Auto-hide error messages when user starts typing
        $('#kt_modal_add_customer_form input, #kt_modal_add_customer_form select, #kt_modal_add_customer_form textarea').on(
            'input change',
            function() {
                clearFieldError($(this).attr('name'));
                $('.print-error-msg').fadeOut();
            });
    </script>
@endpush
