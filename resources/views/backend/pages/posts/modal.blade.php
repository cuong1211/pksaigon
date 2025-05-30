<div class="modal fade" id="kt_modal_add_post" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form class="form" id="kt_modal_add_post_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" id="kt_modal_add_post_header">
                    <h2 class="fw-bolder modal-title"></h2>
                    <div id="kt_modal_add_post_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body py-10 px-lg-17">
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_post_scroll">
                        <input type="hidden" name="id" value="">

                        <!-- Alert container -->
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Tiêu đề bài viết</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập tiêu đề bài viết" name="title" id="post_title" />
                                </div>

                                <!-- Slug -->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Slug</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Tự động tạo từ tiêu đề" name="slug" id="post_slug" />
                                    <div class="form-text">Để trống để tự động tạo từ tiêu đề. Chỉ chứa chữ thường, số
                                        và dấu gạch ngang.</div>
                                </div>

                                <!-- Content with TinyMCE -->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Nội dung bài viết</label>
                                    <textarea class="form-control" id="post_content" name="content" rows="15"></textarea>
                                    <div class="form-text">Nội dung chi tiết của bài viết</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Status -->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Trạng thái</label>
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="status" id="post_status"
                                            checked />
                                        <label class="form-check-label fw-bold fs-6" for="post_status">
                                            Hiển thị bài viết
                                        </label>
                                    </div>
                                    <div class="form-text">Bật để hiển thị bài viết trên website</div>
                                </div>

                                <!-- Featured -->
                                <div class="fv-row mb-7">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_featured"
                                            id="is_featured" />
                                        <label class="form-check-label fw-bold fs-6" for="is_featured">
                                            Bài viết nổi bật
                                        </label>
                                    </div>
                                    <div class="form-text">Bài viết nổi bật sẽ hiển thị ưu tiên</div>
                                </div>

                                <!-- Featured Image -->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Ảnh đại diện</label>
                                    <input type="file" class="form-control form-control-solid" name="featured_image"
                                        accept="image/*" onchange="previewPostImage(this)" />
                                    <div class="form-text">Chấp nhận: JPEG, PNG, JPG, GIF. Tối đa 2MB</div>

                                    <!-- Current image preview -->
                                    <div id="current_image_preview" class="mt-3" style="display: none;">
                                        <label class="fs-7 fw-bold text-gray-600">Ảnh hiện tại:</label>
                                        <div class="mt-2">
                                            <img id="current_image" src="" class="img-fluid rounded"
                                                style="max-height: 150px;">
                                        </div>
                                    </div>

                                    <!-- New image preview -->
                                    <div id="new_image_preview" class="mt-3" style="display: none;">
                                        <label class="fs-7 fw-bold text-gray-600">Ảnh mới:</label>
                                        <div class="mt-2">
                                            <img id="preview_img" src="" class="img-fluid rounded"
                                                style="max-height: 150px;">
                                            <button type="button" class="btn btn-sm btn-light-danger mt-2"
                                                onclick="removeNewPostImage()">Xóa ảnh</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Preview -->
                                <div class="card bg-light">
                                    <div class="card-header border-0 pt-5">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bolder fs-6 mb-1">Preview SEO</span>
                                        </h3>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="seo-preview">
                                            <div class="seo-title text-primary fw-bold" style="font-size: 16px;">
                                                <span id="seo_title">Tiêu đề bài viết</span>
                                            </div>
                                            <div class="seo-url text-success mt-1" style="font-size: 13px;">
                                                <span id="seo_url">{{ url('/') }}/posts/slug</span>
                                            </div>
                                            <div class="seo-description text-muted mt-2" style="font-size: 13px;">
                                                <span id="seo_description">Mô tả bài viết sẽ được tạo từ nội
                                                    dung...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="reset" id="kt_modal_add_post_cancel" class="btn btn-light me-3">Hủy</button>
                    <button type="submit" id="kt_modal_add_post_submit" class="btn btn-primary">
                        <span class="indicator-label">Lưu</span>
                        <span class="indicator-progress">Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('jscustom')
    <script src="https://cdn.tiny.cloud/1/q9sm369apfbukdo5v87vua3kadrt69sdc8jtdci6pu72rmbr/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <script>
        // Khởi tạo TinyMCE khi modal mở
        function initPostTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove('#post_content');
                tinymce.init({
                    selector: '#post_content',
                    height: 400,
                    menubar: false,
                    plugins: [
                        'advlist autolink lists link image charmap print preview anchor',
                        'searchreplace visualblocks code fullscreen',
                        'insertdatetime media table paste code help wordcount'
                    ],
                    toolbar: 'undo redo | formatselect | bold italic backcolor | \
                             alignleft aligncenter alignright alignjustify | \
                             bullist numlist outdent indent | removeformat | link image | code | help',
                    content_style: 'body { font-family: "Be Vietnam Pro", sans-serif; font-size:14px }',
                    language: 'vi',
                    branding: false,
                    setup: function(editor) {
                        editor.on('change keyup', function() {
                            editor.save();
                            updatePostSEOPreview();
                        });
                    }
                });
            }
        }

        // Preview image function
        function previewPostImage(input) {
            const container = document.getElementById('new_image_preview');
            const preview = document.getElementById('preview_img');

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
        function removeNewPostImage() {
            const input = document.querySelector('input[name="featured_image"]');
            const container = document.getElementById('new_image_preview');
            const preview = document.getElementById('preview_img');

            input.value = '';
            preview.src = '';
            container.style.display = 'none';
        }

        // Auto generate slug from title
        $(document).on('input', '#post_title', function() {
            let title = $(this).val();
            if (title && $('#post_slug').val() === '') {
                // Tạo slug từ tiêu đề
                let slug = title.toLowerCase()
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
                $('#post_slug').val(slug);
            }
            updatePostSEOPreview();
        });

        // SEO Preview Updates
        function updatePostSEOPreview() {
            const title = $('#post_title').val() || 'Tiêu đề bài viết';
            const slug = $('#post_slug').val() || 'slug';

            // Lấy nội dung từ TinyMCE
            let content = '';
            if (typeof tinymce !== 'undefined' && tinymce.get('post_content')) {
                content = tinymce.get('post_content').getContent({
                    format: 'text'
                });
            }
            const description = content ? content.substring(0, 160) + (content.length > 160 ? '...' : '') :
                'Mô tả bài viết sẽ được tạo từ nội dung...';

            $('#seo_title').text(title);
            $('#seo_url').text(`{{ url('/') }}/posts/${slug}`);
            $('#seo_description').text(description);
        }

        // Update SEO preview on slug change
        $(document).on('keyup', '#post_slug', updatePostSEOPreview);

        // Event listeners cho modal
        $('#kt_modal_add_post').on('shown.bs.modal', function() {
            initPostTinyMCE();
            updatePostSEOPreview();
        });

        $('#kt_modal_add_post').on('hidden.bs.modal', function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove('#post_content');
            }
        });
    </script>
@endpush

@push('csscustom')
    <style>
        /* Custom CSS cho modal */
        .modal-dialog.modal-xl {
            max-width: 1200px !important;
        }

        .tox-tinymce {
            border-radius: 0.475rem !important;
            border: 1px solid #e4e6ef !important;
        }

        .form-control-solid {
            border: 1px solid #e4e6ef;
        }

        .form-control-solid:focus {
            border-color: #009ef7;
            box-shadow: 0 0 0 0.2rem rgba(0, 158, 247, 0.25);
        }

        #preview_img,
        #current_image {
            border: 2px solid #e4e6ef;
            border-radius: 0.475rem;
            padding: 5px;
        }

        .scroll-y {
            max-height: 70vh;
            overflow-y: auto;
        }

        .seo-preview {
            background: #fff;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e4e6ef;
        }

        .card.bg-light {
            background-color: #f8f9fa !important;
        }

        .form-check-input:checked {
            background-color: #009ef7;
            border-color: #009ef7;
        }

        .form-text {
            color: #a1a5b7;
            font-size: 12px;
        }
    </style>
@endpush
