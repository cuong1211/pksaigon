<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="kt_modal_add_customer_form" enctype="multipart/form-data">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Modal title-->
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <h2 class="fw-bolder modal-title"></h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
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

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-12">
                                <!--begin::Input group - Chọn thuốc với Select2-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Chọn thuốc</label>
                                    <select class="form-select form-select-solid" name="medicine_id"
                                        id="medicine_select" data-control="select2"
                                        data-placeholder="Tìm và chọn thuốc..." data-allow-clear="true">
                                        <option value="">-- Chọn thuốc --</option>
                                    </select>
                                    <div class="form-text">Chọn thuốc cần nhập kho</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Medicine Info Display-->
                        <div id="medicine-info-display" class="row mb-7" style="display: none;">
                            <div class="col-12">
                                <div class="card bg-light-info">
                                    <div class="card-body py-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Tên thuốc:</small>
                                                <div class="fw-bold" id="selected-medicine-type">-</div>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Giá nhập:</small>
                                                <div class="fw-bold text-success" id="selected-medicine-price">-</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Medicine Info Display-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Số lượng-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Số lượng</label>
                                    <input type="number" class="form-control form-control-solid"
                                        placeholder="Nhập số lượng" name="quantity" id="quantity_input"
                                        min="1" />
                                    <div class="form-text">Nhập số lượng cần nhập kho</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Giá nhập đơn vị (readonly)-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Giá nhập đơn vị (VNĐ)</label>
                                    <input type="text" class="form-control form-control-solid bg-light-secondary"
                                        id="unit_price_display" readonly placeholder="Tự động hiển thị" />
                                    <input type="hidden" name="unit_price" id="unit_price_value" />
                                    <div class="form-text">Giá nhập từ thông tin thuốc</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Tổng tiền (readonly)-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Tổng tiền (VNĐ)</label>
                                    <input type="text" class="form-control form-control-solid bg-light-success"
                                        id="total_amount_display" readonly placeholder="Tự động tính" />
                                    <input type="hidden" name="total_amount" id="total_amount_value" />
                                    <div class="form-text">Tự động tính = Số lượng × Giá nhập</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Ngày nhập-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Ngày nhập</label>
                                    <input type="date" class="form-control form-control-solid" name="import_date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group - Ghi chú-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Ghi chú</label>
                            <textarea class="form-control form-control-solid" rows="4" placeholder="Nhập ghi chú (tùy chọn)"
                                name="notes"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Hình ảnh hóa đơn-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Hình ảnh hóa đơn</label>
                            <input type="file" class="form-control form-control-solid" name="invoice_image"
                                accept="image/*" onchange="previewInvoiceImage(this)" />
                            <div class="form-text">Chấp nhận: JPEG, PNG, JPG, GIF. Tối đa 5MB</div>

                            <!--begin::Image preview-->
                            <div id="invoice-preview-container" class="mt-3" style="display: none;">
                                <label class="fs-7 fw-bold text-gray-600">Preview hóa đơn:</label>
                                <div class="mt-2">
                                    <img id="invoice-preview" src="" class="img-fluid rounded"
                                        style="max-height: 250px; max-width: 350px;">
                                    <button type="button" class="btn btn-sm btn-light-danger mt-2"
                                        onclick="removeInvoiceImage()">
                                        Xóa ảnh
                                    </button>
                                </div>
                            </div>
                            <!--end::Image preview-->
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Hủy</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                        <span class="indicator-label">Xác nhận</span>
                        <span class="indicator-progress">Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>

<!-- Modal xem ảnh hóa đơn -->
<div class="modal fade image-preview-modal" id="kt_modal_invoice_preview" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem hóa đơn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="invoice-full-preview" src="" class="img-fluid" alt="Hóa đơn">
            </div>
        </div>
    </div>
</div>

@push('jscustom')
    <script>
        // Lưu thông tin thuốc đã chọn
        let selectedMedicineData = null;

        // Preview invoice image function
        function previewInvoiceImage(input) {
            const container = document.getElementById('invoice-preview-container');
            const preview = document.getElementById('invoice-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove invoice image function
        function removeInvoiceImage() {
            const input = document.querySelector('input[name="invoice_image"]');
            const container = document.getElementById('invoice-preview-container');
            const preview = document.getElementById('invoice-preview');

            input.value = '';
            preview.src = '';
            container.style.display = 'none';
        }

        // Show invoice preview in modal
        function showInvoicePreview(imageUrl) {
            $('#invoice-full-preview').attr('src', imageUrl);
            $('#kt_modal_invoice_preview').modal('show');
        }

        // Format số tiền
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount);
        }

        // Tính tổng tiền
        function calculateTotalAmount() {
            const quantity = parseInt($('#quantity_input').val()) || 0;
            const unitPrice = parseFloat($('#unit_price_value').val()) || 0;

            if (quantity > 0 && unitPrice > 0) {
                const totalAmount = quantity * unitPrice;
                $('#total_amount_display').val(formatCurrency(totalAmount) + ' VNĐ');
                $('#total_amount_value').val(totalAmount);
            } else {
                $('#total_amount_display').val('');
                $('#total_amount_value').val('');
            }
        }

        // Xử lý khi chọn thuốc
        function handleMedicineSelection(medicineData) {
            if (medicineData && medicineData.id) {
                selectedMedicineData = medicineData;

                // Hiển thị thông tin thuốc
                $('#selected-medicine-name').text(medicineData.name);
                $('#selected-medicine-type').text(medicineData.type_name);
                $('#selected-medicine-price').text(formatCurrency(medicineData.import_price) + ' VNĐ');
                $('#medicine-info-display').show();

                // Set giá nhập
                $('#unit_price_display').val(formatCurrency(medicineData.import_price) + ' VNĐ');
                $('#unit_price_value').val(medicineData.import_price);

                // Tính lại tổng tiền
                calculateTotalAmount();
            } else {
                // Reset nếu không chọn thuốc
                selectedMedicineData = null;
                $('#medicine-info-display').hide();
                $('#unit_price_display').val('');
                $('#unit_price_value').val('');
                $('#total_amount_display').val('');
                $('#total_amount_value').val('');
            }
        }

        // Initialize Select2 when modal is shown
        $('#kt_modal_add_customer').on('shown.bs.modal', function() {
            $('#medicine_select').select2({
                dropdownParent: $('#kt_modal_add_customer'),
                placeholder: "Tìm và chọn thuốc...",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{ route('medicine-import.show', 'get-medicines') }}",
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text,
                                    name: item.name,
                                    type_name: item.type_name,
                                    import_price: item.import_price
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Xử lý khi chọn thuốc
            $('#medicine_select').on('select2:select', function(e) {
                const data = e.params.data;
                handleMedicineSelection(data);
            });

            // Xử lý khi clear thuốc
            $('#medicine_select').on('select2:clear', function(e) {
                handleMedicineSelection(null);
            });
        });

        // Destroy Select2 when modal is hidden
        $('#kt_modal_add_customer').on('hidden.bs.modal', function() {
            if ($('#medicine_select').hasClass('select2-hidden-accessible')) {
                $('#medicine_select').select2('destroy');
            }
        });

        // Tính toán khi thay đổi số lượng
        $(document).on('input', '#quantity_input', function() {
            calculateTotalAmount();
        });

        // Tính toán khi thay đổi số lượng (sự kiện keyup để đảm bảo tính toán ngay lập tức)
        $(document).on('keyup', '#quantity_input', function() {
            calculateTotalAmount();
        });
    </script>
@endpush

@push('csscustom')
    <style>
        /* Custom CSS cho modal */
        .modal-dialog.mw-900px {
            max-width: 900px !important;
        }

        .form-control-solid {
            border: 1px solid #e4e6ef;
        }

        .form-control-solid:focus {
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.25);
        }

        .bg-light-secondary {
            background-color: #f8f9fa !important;
        }

        .bg-light-success {
            background-color: #e8f5e8 !important;
        }

        #invoice-preview {
            border: 2px solid #e4e6ef;
            border-radius: 0.475rem;
            padding: 5px;
        }

        .scroll-y {
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Select2 styling */
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + 1.3rem + 2px) !important;
            border: 1px solid #e4e6ef !important;
            border-radius: 0.475rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 1.3rem) !important;
            padding-left: 1rem !important;
            color: #5e6278 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1.3rem) !important;
            right: 1rem !important;
        }

        .select2-dropdown {
            border: 1px solid #e4e6ef !important;
            border-radius: 0.475rem !important;
        }

        /* Medicine info display */
        .card.bg-light-info {
            background-color: #e1f5fe !important;
            border: 1px solid #b3e5fc;
        }

        /* Image preview modal */
        .image-preview-modal .modal-dialog {
            max-width: 800px;
        }

        .image-preview-modal img {
            width: 100%;
            height: auto;
        }

        /* Highlight total amount field */
        #total_amount_display {
            font-weight: 600;
            font-size: 1.1em;
            color: #0d6efd !important;
        }

        /* Readonly fields styling */
        input[readonly] {
            background-color: #f8f9fa !important;
            opacity: 1;
        }

        /* Animation for calculation */
        .calculating {
            background: linear-gradient(90deg, #e8f5e8, #d4edda, #e8f5e8);
            background-size: 200% 100%;
            animation: shimmer 1s ease-in-out;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }
    </style>
@endpush
