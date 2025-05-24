<div class="modal fade" id="kt_modal_add_post" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form class="form" id="kt_modal_add_post_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-header" id="kt_modal_add_post_header">
                    <h2 class="fw-bolder modal-title"></h2>
                    <div id="kt_modal_add_post_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"/>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"/>
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
                                    <input type="text" class="form-control form-control-solid" placeholder="Nhập tiêu đề bài viết" name="title"/>
                                </div>

                                <!-- Excerpt -->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Tóm tắt</label>
                                    <textarea class="form-control form-control-solid" placeholder="Tóm tắt ngắn gọn về bài viết" name="excerpt" rows="3"></textarea>
                                    <div class="form-text">Tối đa 500 ký tự. Nếu để trống sẽ tự động tạo từ nội dung</div>
                                </div>

                                <!-- Content -->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Nội dung bài viết</label>
                                    <textarea class="form-control form-control-solid" placeholder="Nhập nội dung bài viết" name="content" rows="10" id="post_content"></textarea>
                                </div>

                                <!-- Featured Image -->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Ảnh đại diện</label>
                                    <input type="file" class="form-control form-control-solid" name="featured_image" accept="image/*"/>
                                    <div class="form-text">Chấp nhận: JPEG, PNG, JPG, GIF. Tối đa 2MB</div>
                                    
                                    <!-- Current image preview -->
                                    <div id="current_image_preview" class="mt-3" style="display: none;">
                                        <label class="fs-7 fw-bold text-gray-600">Ảnh hiện tại:</label>
                                        <div class="mt-2">
                                            <img id="current_image" src="" class="img-fluid rounded" style="max-height: 150px;">
                                        </div>
                                    </div>

                                    <!-- New image preview -->
                                    <div id="new_image_preview" class="mt-3" style="display: none;">
                                        <label class="fs-7 fw-bold text-gray-600">Ảnh mới:</label>
                                        <div class="mt-2">
                                            <img id="preview_img" src="" class="img-fluid rounded" style="max-height: 150px;">
                                            <button type="button" class="btn btn-sm btn-light-danger mt-2" onclick="removeNewImage()">Xóa ảnh</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Status -->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Trạng thái</label>
                                    <select name="status" class="form-select form-select-solid">
                                        <option value="draft">Bản nháp</option>
                                        <option value="published">Xuất bản</option>
                                        <option value="archived">Lưu trữ</option>
                                    </select>
                                </div>

                                <!-- Featured -->
                                <div class="fv-row mb-7">
                                    <div class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"/>
                                        <label class="form-check-label fw-bold fs-6" for="is_featured">
                                            Bài viết nổi bật
                                        </label>
                                    </div>
                                    <div class="form-text">Bài viết nổi bật sẽ hiển thị ưu tiên</div>
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
                                                <span id="seo_description">Mô tả bài viết...</span>
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
<script>
// Image preview functionality
$('input[name="featured_image"]').on('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview_img').attr('src', e.target.result);
            $('#new_image_preview').show();
        };
        reader.readAsDataURL(file);
    }
});

function removeNewImage() {
    $('input[name="featured_image"]').val('');
    $('#new_image_preview').hide();
}

// SEO Preview Updates
function updateSEOPreview() {
    const title = $('input[name="title"]').val() || 'Tiêu đề bài viết';
    const excerpt = $('textarea[name="excerpt"]').val() || 'Mô tả bài viết...';
    const slug = title.toLowerCase()
        .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
        .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
        .replace(/[ìíịỉĩ]/g, 'i')
        .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
        .replace(/[ùúụủũưừứựửữ]/g, 'u')
        .replace(/[ỳýỵỷỹ]/g, 'y')
        .replace(/đ/g, 'd')
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');

    $('#seo_title').text(title);
    $('#seo_url').text(`{{ url('/') }}/posts/${slug || 'slug'}`);
    $('#seo_description').text(excerpt.substring(0, 160) + (excerpt.length > 160 ? '...' : ''));
}

// Update SEO preview on input
$(document).on('keyup', 'input[name="title"], textarea[name="excerpt"]', updateSEOPreview);
</script>
@endpush