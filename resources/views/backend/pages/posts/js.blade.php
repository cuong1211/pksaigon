<script>
// Private functions
let search_table = '';
let status_filter = '';

var dt = $("#kt_posts_table").DataTable({
    serverSide: true,
    select: {
        style: 'multi',
        selector: 'td:first-child',
        className: 'row-selected'
    },
    ajax: {
        url: "/admin/posts",
        type: 'GET',
        data: function(d) {
            d.search_table = search_table;
            d.status_filter = status_filter;
        }
    },
    columns: [
        {
            data: 'null',
            orderable: false,
            searchable: false,
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
        },
        {
            data: 'title',
            render: function(data, type, row, meta) {
                return '<div class="d-flex flex-column">' +
                    '<a href="#" class="text-gray-800 text-hover-primary fw-bolder">' + data + '</a>' +
                    '<span class="text-muted fs-7">' + (row.excerpt || 'Không có tóm tắt') + '</span>' +
                    '</div>';
            }
        },
        {
            data: 'author_name',
            render: function(data, type, row, meta) {
                return data || 'N/A';
            }
        },
        {
            data: 'status',
            render: function(data, type, row, meta) {
                let badgeClass = '';
                let text = '';
                switch(data) {
                    case 'published':
                        badgeClass = 'badge-light-success';
                        text = 'Đã xuất bản';
                        break;
                    case 'draft':
                        badgeClass = 'badge-light-warning';
                        text = 'Bản nháp';
                        break;
                    case 'archived':
                        badgeClass = 'badge-light-secondary';
                        text = 'Lưu trữ';
                        break;
                    default:
                        badgeClass = 'badge-light-dark';
                        text = 'Không xác định';
                }
                
                let featuredBadge = row.is_featured ? '<span class="badge badge-light-primary ms-2">Nổi bật</span>' : '';
                
                return '<span class="badge ' + badgeClass + '">' + text + '</span>' + featuredBadge;
            }
        },
        {
            data: 'views_count',
            render: function(data, type, row, meta) {
                return '<span class="badge badge-light-info">' + (data || 0) + '</span>';
            }
        },
        {
            data: 'created_at',
            render: function(data, type, row, meta) {
                return new Date(data).toLocaleDateString('vi-VN');
            }
        },
        {
            data: null,
            className: 'text-end',
            render: function(data, type, row, meta) {
                return '<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Thao tác \n' +
                    '<span class="svg-icon svg-icon-5 m-0"> \n' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> \n' +
                    '<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" /> \n' +
                    '</svg> \n' +
                    '</span> \n' +
                    '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true"> \n' +
                    '<div class="menu-item px-3"> \n' +
                    '<a href="" data-data=\'' + JSON.stringify(row) +
                    '\' class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_post">Sửa</a> \n' +
                    '</div> \n' +
                    '<div class="menu-item px-3"> \n' +
                    '<a href="#" data-id="' + row.id +
                    '" class="menu-link px-3 btn-delete" data-kt-posts-table-filter="delete_row">Xoá</a> \n' +
                    '</div> \n' +
                    (row.is_featured ? 
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-toggle-featured">Bỏ nổi bật</a> \n' +
                        '</div> \n' :
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-toggle-featured">Đặt nổi bật</a> \n' +
                        '</div> \n'
                    ) +
                    '</div>';
            }
        }
    ],
    order: [[5, 'desc']],
    pageLength: 10
});

dt.on('draw', function() {
    KTMenu.createInstances();
});

// Close modal functions
$('.btn-close, #kt_modal_add_post_close').on('click', function() {
    form_reset();
    $('#kt_modal_add_post').modal('hide');
});

$('#kt_modal_add_post_cancel').on('click', function() {
    form_reset();
});

function form_reset() {
    $("#kt_modal_add_post").modal({
        'backdrop': 'static',
        'keyboard': false
    });
    $("#kt_modal_add_post_form").trigger("reset");
    $('#current_image_preview').hide();
    $('#new_image_preview').hide();
    $('.print-error-msg').hide();
    updateSEOPreview();
}

// Edit post
$(document).on('click', '.btn-edit', function(e) {
    e.preventDefault();
    form_reset();
    let data = $(this).data('data');
    let modal = $('#kt_modal_add_post_form');
    
    modal.find('.modal-title').text('Sửa bài viết');
    modal.find('input[name=id]').val(data.id);
    modal.find('input[name=title]').val(data.title);
    modal.find('textarea[name=excerpt]').val(data.excerpt);
    modal.find('textarea[name=content]').val(data.content);
    modal.find('select[name=status]').val(data.status);
    modal.find('input[name=is_featured]').prop('checked', data.is_featured);
    
    // Show current image if exists
    if (data.featured_image) {
        $('#current_image').attr('src', data.featured_image_url);
        $('#current_image_preview').show();
    }
    
    updateSEOPreview();
});

// Add post
$(document).on('click', '.btn-add', function(e) {
    e.preventDefault();
    form_reset();
    let modal = $('#kt_modal_add_post_form');
    modal.find('.modal-title').text('Thêm bài viết mới');
    modal.find('input[name=id]').val('');
});

// Submit form
$('#kt_modal_add_post_form').on('submit', function(e) {
    e.preventDefault();
    
    let formData = new FormData(this);
    let id = $('form#kt_modal_add_post_form input[name=id]').val();
    let url = "/admin/posts";
    let method = 'POST';
    
    if (parseInt(id)) {
        url = "/admin/posts/" + id;
        formData.append('_method', 'PUT');
    }
    
    // Show loading
    $('#kt_modal_add_post_submit').attr('data-kt-indicator', 'on');
    $('#kt_modal_add_post_submit').prop('disabled', true);
    
    $.ajax({
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: method,
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            notification(data.type, data.title, data.content);
            if (data.type == 'success') {
                dt.ajax.reload(null, false);
                $('#kt_modal_add_post_form').trigger('reset');
                $('#kt_modal_add_post').modal('hide');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorHtml = '';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';
                });
                $('.print-error-msg ul').html(errorHtml);
                $('.print-error-msg').show();
            } else {
                notification('error', 'Lỗi', 'Có lỗi xảy ra khi lưu bài viết');
            }
        },
        complete: function() {
            $('#kt_modal_add_post_submit').removeAttr('data-kt-indicator');
            $('#kt_modal_add_post_submit').prop('disabled', false);
        }
    });
});

// Delete post
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    
    Swal.fire({
        text: "Bạn có muốn xóa bài viết này không?",
        icon: "warning",
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonText: "Có!",
        cancelButtonText: "Không",
        customClass: {
            confirmButton: "btn fw-bold btn-danger",
            cancelButton: "btn fw-bold btn-active-light-primary",
        }
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: "/admin/posts/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'DELETE',
                success: function(data) {
                    notification(data.type, data.title, data.content);
                    if (data.type == 'success') {
                        dt.ajax.reload(null, false);
                    }
                },
                error: function(data) {
                    notification('error', 'Lỗi', 'Có lỗi xảy ra khi xóa bài viết');
                }
            });
        }
    });
});

// Toggle featured
$(document).on('click', '.btn-toggle-featured', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    
    $.ajax({
        url: `/admin/posts/${id}/toggle-featured`,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: 'PATCH',
        success: function(data) {
            notification(data.type, data.title, data.content);
            if (data.type == 'success') {
                dt.ajax.reload(null, false);
            }
        },
        error: function(data) {
            notification('error', 'Lỗi', 'Có lỗi xảy ra');
        }
    });
});

// Search functionality
$(".search_table").on('change keyup', function() {
    if ($(this).data('filter') === 'status') {
        status_filter = $(this).val();
    } else {
        search_table = $(this).val();
    }
    dt.ajax.reload();
});
</script>