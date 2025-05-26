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
                            <div class="col-md-6">
                                <!--begin::Input group - Tên thuốc-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Tên thuốc</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập tên thuốc" name="name" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Mã thuốc-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Mã thuốc</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập mã thuốc" name="code" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group - Mô tả-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Mô tả</label>
                            <textarea class="form-control form-control-solid" rows="3" placeholder="Nhập mô tả về thuốc" name="description"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Đơn vị-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Đơn vị</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="VD: viên, chai, hộp" name="unit" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Giá-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Giá (VNĐ)</label>
                                    <input type="number" class="form-control form-control-solid" placeholder="0"
                                        name="price" min="0" step="1000" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Số lượng-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Số lượng</label>
                                    <input type="number" class="form-control form-control-solid" placeholder="0"
                                        name="quantity" min="0" />
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
                                <!--begin::Input group - Số lượng tối thiểu-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Số lượng tối thiểu</label>
                                    <input type="number" class="form-control form-control-solid" placeholder="0"
                                        name="min_quantity" min="0" />
                                    <div class="form-text">Cảnh báo khi số lượng <= giá trị này</div>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6">
                                    <!--begin::Input group - Nhà sản xuất-->
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold mb-2">Nhà sản xuất</label>
                                        <input type="text" class="form-control form-control-solid"
                                            placeholder="Nhập nhà sản xuất" name="manufacturer" />
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
                                    <!--begin::Input group - Hạn sử dụng-->
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold mb-2">Hạn sử dụng</label>
                                        <input type="date" class="form-control form-control-solid"
                                            name="expiry_date" />
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6">
                                    <!--begin::Input group - Trạng thái-->
                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-bold mb-2">Trạng thái</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active"
                                                id="is_active" checked>
                                            <label class="form-check-label fs-6 fw-bold" for="is_active">
                                                Hoạt động
                                            </label>
                                        </div>
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->

                            <!--begin::Input group - Hình ảnh-->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-bold mb-2">Hình ảnh thuốc</label>
                                <input type="file" class="form-control form-control-solid" name="image"
                                    accept="image/*" onchange="previewImage(this)" />
                                <div class="form-text">Chấp nhận: JPEG, PNG, JPG, GIF. Tối đa 2MB</div>

                                <!--begin::Image preview-->
                                <div id="image-preview-container" class="mt-3" style="display: none;">
                                    <label class="fs-7 fw-bold text-gray-600">Preview:</label>
                                    <div class="mt-2">
                                        <img id="image-preview" src="" class="img-fluid rounded"
                                            style="max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-light-danger mt-2"
                                            onclick="removeImage()">
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
                        <button type="reset" id="kt_modal_add_customer_cancel"
                            class="btn btn-light me-3">Hủy</button>
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

@push('jscustom')
    <script>
        // Preview image function
        function previewImage(input) {
            const container = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Remove image function
        function removeImage() {
            const input = document.querySelector('input[name="image"]');
            const container = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');

            input.value = '';
            preview.src = '';
            container.style.display = 'none';
        }
    </script>
@endpush
