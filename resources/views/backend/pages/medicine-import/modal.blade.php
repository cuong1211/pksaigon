<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-750px">
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
                                <!--begin::Input group - Chọn thuốc-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Chọn thuốc</label>
                                    <select class="form-select form-select-solid" name="medicine_id"
                                        id="medicine_select">
                                        <option value="">-- Chọn thuốc --</option>
                                    </select>
                                    <div class="form-text">Chọn thuốc cần nhập kho</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Số lượng-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Số lượng</label>
                                    <input type="number" class="form-control form-control-solid"
                                        placeholder="Nhập số lượng" name="quantity" min="1" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Giá nhập-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Giá nhập (VNĐ)</label>
                                    <input type="number" class="form-control form-control-solid" placeholder="Nhập giá"
                                        name="unit_price" min="0" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
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

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Tổng tiền (tự động tính)-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Tổng tiền (VNĐ)</label>
                                    <input type="text" class="form-control form-control-solid bg-light"
                                        id="total_price_display" placeholder="0" readonly />
                                    <div class="form-text">Tự động tính = Số lượng × Giá nhập</div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group - Ghi chú-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Ghi chú</label>
                            <textarea class="form-control form-control-solid" rows="3" placeholder="Nhập ghi chú (tùy chọn)" name="notes"></textarea>
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
                                        style="max-height: 200px; max-width: 300px;">
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
        // Tự động tính tổng tiền
        function calculateTotalPrice() {
            const quantity = parseFloat($('input[name="quantity"]').val()) || 0;
            const unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
            const totalPrice = quantity * unitPrice;

            $('#total_price_display').val(new Intl.NumberFormat('vi-VN').format(totalPrice));
        }

        // Event listeners cho tính tổng tiền
        $('input[name="quantity"], input[name="unit_price"]').on('input', calculateTotalPrice);

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
    </script>
@endpush
