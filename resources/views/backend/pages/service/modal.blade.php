<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-750px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="kt_modal_add_customer_form">
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
                        
                        <!--begin::Input group - Tên dịch vụ-->
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-bold mb-2">Tên dịch vụ</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Nhập tên dịch vụ"
                                name="name" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Slug-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Slug</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Tự động tạo từ tên dịch vụ" name="slug"
                                readonly />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Mô tả ngắn-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Mô tả ngắn</label>
                            <textarea class="form-control form-control-solid" rows="3" placeholder="Nhập mô tả ngắn về dịch vụ"
                                name="description"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Nội dung chi tiết-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Nội dung chi tiết</label>
                            <textarea class="form-control form-control-solid" rows="5" placeholder="Nhập nội dung chi tiết về dịch vụ"
                                name="content"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Giá và thời gian-->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Giá dịch vụ (VNĐ)</label>
                                    <input type="number" class="form-control form-control-solid" placeholder="0"
                                        name="price" min="0" step="1000" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Thời gian thực hiện</label>
                                    <input type="text" class="form-control form-control-solid" placeholder="VD: 30 phút, 1 giờ"
                                        name="duration" />
                                </div>
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Hình ảnh-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Hình ảnh</label>
                            <input type="text" class="form-control form-control-solid" placeholder="Đường dẫn hình ảnh"
                                name="image" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Trạng thái-->
                        <div class="fv-row mb-7">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                                <label class="form-check-label fs-6 fw-bold" for="is_active">
                                    Trạng thái hoạt động
                                </label>
                            </div>
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
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
    {{-- get value form input name when change --}}
    <script>
        $('input[name="name"]').on('keyup', function() {
            var name = $(this).val();
            convertToSlug(name);
        });

        function convertToSlug(name) {
            $.ajax({
                type: 'get',
                url: '{{ route('slug') }}',
                dataType: 'json',
                data: {
                    name,
                    modelType: 'service'
                },
                success: function(response) {
                    console.log(response);
                    $('input[name="slug"]').val(response.slug);
                }
            });
        }
    </script>
@endpush