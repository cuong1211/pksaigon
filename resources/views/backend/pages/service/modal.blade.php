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
                            <div class="col-md-8">
                                <!--begin::Input group - Tên dịch vụ-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Tên dịch vụ</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập tên dịch vụ" name="name" id="service_name" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Slug-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Slug</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Tự động tạo" name="slug" id="service_slug" />
                                    <div class="form-text">Để trống để tự động tạo từ tên dịch vụ</div>
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
                                <!--begin::Input group - Loại dịch vụ-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Loại dịch vụ</label>
                                    <select class="form-select form-select-solid" name="type">
                                        <option value="">Chọn loại dịch vụ</option>
                                        <option value="procedure">Thủ thuật</option>
                                        <option value="laboratory">Xét nghiệm</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Giá-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Giá dịch vụ (VNĐ)</label>
                                    <input type="number" class="form-control form-control-solid" placeholder="0"
                                        name="price" min="0" step="1000" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group - Mô tả với TinyMCE-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Mô tả chi tiết</label>
                            <textarea class="form-control" id="service_description" name="description" rows="10">
                            </textarea>
                            <div class="form-text">Mô tả chi tiết về dịch vụ, quy trình thực hiện, lưu ý...</div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Trạng thái-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            checked>
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
                            <label class="fs-6 fw-bold mb-2">Hình ảnh dịch vụ</label>
                            <input type="file" class="form-control form-control-solid" name="image"
                                accept="image/*" onchange="previewImage(this)" />
                            <div class="form-text">Chấp nhận: JPEG, PNG, JPG, GIF. Tối đa 2MB</div>

                            <!--begin::Image preview-->
                            <div id="image-preview-container" class="mt-3" style="display: none;">
                                <label class="fs-7 fw-bold text-gray-600">Preview:</label>
                                <div class="mt-2">
                                    <img id="image-preview" src="" class="img-fluid rounded"
                                        style="max-height: 200px;">
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
@push('jscustom')
    <script src="https://cdn.tiny.cloud/1/q9sm369apfbukdo5v87vua3kadrt69sdc8jtdci6pu72rmbr/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        // Khởi tạo TinyMCE khi modal mở
        function initTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove('#service_description');
                tinymce.init({
                    selector: '#service_description',
                    height: 300,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | bold italic backcolor | \
                             alignleft aligncenter alignright alignjustify | \
                             bullist numlist outdent indent | removeformat | help',
                    content_style: 'body { font-family: "Be Vietnam Pro", sans-serif; font-size:14px }',
                    language: 'vi',
                    branding: false,
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    }
                });
            }
        }

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

        // Auto generate slug from name
        $(document).on('input', '#service_name', function() {
            let name = $(this).val();
            if (name && $('#service_slug').val() === '') {
                // Tạo slug từ tên
                let slug = name.toLowerCase()
                    .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
                    .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
                    .replace(/[ìíịỉĩ]/g, 'i')
                    .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
                    .replace(/[ùúụủũưừứựửữ]/g, 'u')
                    .replace(/[ỳýỵỷỹ]/g, 'y')
                    .replace(/đ/g, 'd')
                    .replace(/[^a-z0-9\s]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                $('#service_slug').val(slug);
            }
        });

        // Event listeners cho modal
        $('#kt_modal_add_customer').on('shown.bs.modal', function() {
            initTinyMCE();
        });

        $('#kt_modal_add_customer').on('hidden.bs.modal', function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove('#service_description');
            }
        });
    </script>
@endpush
@push('csscustom')
    <style>
        /* Custom CSS cho modal */
        .modal-dialog.mw-900px {
            max-width: 900px !important;
        }

        .tox-tinymce {
            border-radius: 0.475rem !important;
        }

        .form-control-solid {
            border: 1px solid #e4e6ef;
        }

        .form-control-solid:focus {
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.25);
        }

        #image-preview {
            border: 2px solid #e4e6ef;
            border-radius: 0.475rem;
            padding: 5px;
        }

        .scroll-y {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
@endpush
