<script>
    // Private functions
    let search_table = '';
    let status_filter = '';

    // Load statistics on page load
    $(document).ready(function() {
        loadStatistics();
    });

    var dt = $("#kt_posts_table").DataTable({
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: "{{ route('posts.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.status_filter = status_filter;
            }
        },
        columns: [{
                data: 'null',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return '<div class="form-check form-check-sm form-check-custom form-check-solid">' +
                        '<input class="form-check-input" type="checkbox" value="' + row.id + '" />' +
                        '</div>';
                },
            },
            {
                data: 'featured_image_url',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return '<img src="' + data + '" class="post-thumbnail" alt="' + row.title + '">';
                }
            },
            {
                data: 'title',
                render: function(data, type, row, meta) {
                    return '<div class="d-flex flex-column">' +
                        '<div class="post-title">' + data + '</div>' +
                        '<div class="post-excerpt">' + (row.excerpt || 'Không có mô tả') + '</div>' +
                        '</div>';
                }
            },
            {
                data: 'slug',
                render: function(data, type, row, meta) {
                    return '<span class="post-slug">' + data + '</span>';
                }
            },
            {
                data: 'author_name',
                render: function(data, type, row, meta) {
                    return '<div class="author-info">' +
                        '<div class="author-name">' + data + '</div>' +
                        '</div>';
                }
            },
            {
                data: 'status',
                render: function(data, type, row, meta) {
                    return row.status_badge;
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
                    return '<span class="date-display">' + row.formatted_date + '</span>';
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
                        '</a> \n' +
                        '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true"> \n' +
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_post">Sửa</a> \n' +
                        '</div> \n' +
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-delete" data-kt-posts-table-filter="delete_row">Xóa</a> \n' +
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
        order: [
            [7, 'desc']
        ],
        pageLength: 10
    });

    dt.on('draw', function() {
        KTMenu.createInstances();
        updateBulkActions();
    });

    // Handle bulk selection
    $('[data-kt-check="true"]').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#kt_posts_table tbody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActions();
    });

    $(document).on('change', '#kt_posts_table tbody input[type="checkbox"]', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('#kt_posts_table tbody input[type="checkbox"]:checked').length;

        if (checkedCount > 0) {
            $('[data-kt-posts-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-posts-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-posts-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-posts-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-posts-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('posts.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {


            },
            error: function() {
                console.log('Error loading statistics');
            }
        });
    }

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

        // Reset TinyMCE
        if (typeof tinymce !== 'undefined' && tinymce.get('post_content')) {
            tinymce.get('post_content').setContent('');
        }

        // Reset slug field
        $('#post_slug').val('');

        // Reset checkboxes
        $('#post_status').prop('checked', true);
        $('#is_featured').prop('checked', false);

        updatePostSEOPreview();
    }

    // Function to load post data for edit
    function loadPostData(id) {
        // Show loading state
        const submitBtn = $('#kt_modal_add_post_submit');
        submitBtn.attr('data-kt-indicator', 'on');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: "{{ route('posts.getData', ':id') }}".replace(':id', id),
            type: 'GET',
            success: function(response) {
                if (response.type === 'success') {
                    let data = response.data;
                    let modal = $('#kt_modal_add_post_form');

                    // Set form values
                    modal.find('.modal-title').text('Sửa bài viết');
                    modal.find('input[name=id]').val(data.id);
                    modal.find('input[name=title]').val(data.title);
                    modal.find('input[name=slug]').val(data.slug);
                    modal.find('input[name=status]').prop('checked', data.status);
                    modal.find('input[name=is_featured]').prop('checked', data.is_featured);

                    // Set TinyMCE content
                    setTimeout(function() {
                        if (typeof tinymce !== 'undefined' && tinymce.get('post_content')) {
                            tinymce.get('post_content').setContent(data.content || '');
                        }
                    }, 500);

                    // Show current image if exists
                    if (data.has_image && data.featured_image_url) {
                        $('#current_image').attr('src', data.featured_image_url);
                        $('#current_image_preview').show();
                    }

                    updatePostSEOPreview();
                    console.log('Post data loaded successfully:', data);
                } else {
                    notification('error', 'Lỗi', response.message || 'Không thể tải dữ liệu');
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi tải dữ liệu';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                notification('error', 'Lỗi', message);
                console.error('Error loading post data:', xhr);
            },
            complete: function() {
                // Hide loading state
                submitBtn.removeAttr('data-kt-indicator');
                submitBtn.prop('disabled', false);
            }
        });
    }

    // Edit post
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        form_reset();

        let id = $(this).data('id');
        if (id) {
            console.log('Loading post data for ID:', id);
            loadPostData(id);
        } else {
            notification('error', 'Lỗi', 'Không tìm thấy ID bài viết');
        }
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

        // Sync TinyMCE content before submit
        if (typeof tinymce !== 'undefined' && tinymce.get('post_content')) {
            tinymce.get('post_content').save();
        }

        let formData = new FormData(this);
        let id = $('form#kt_modal_add_post_form input[name=id]').val();
        let url = "{{ route('posts.store') }}";
        let method = 'POST';

        if (parseInt(id)) {
            url = "{{ route('posts.update', ':id') }}".replace(':id', id);
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
                    loadStatistics();
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
                    let message = 'Có lỗi xảy ra';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    notification('error', 'Lỗi', message);
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
                    url: "{{ route('posts.destroy', ':id') }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        if (data.type == 'success') {
                            dt.ajax.reload(null, false);
                            loadStatistics();
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
            url: "{{ route('posts.toggle-featured', ':id') }}".replace(':id', id),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'PATCH',
            success: function(data) {
                notification(data.type, data.title, data.content);
                if (data.type == 'success') {
                    dt.ajax.reload(null, false);
                    loadStatistics();
                }
            },
            error: function(data) {
                notification('error', 'Lỗi', 'Có lỗi xảy ra');
            }
        });
    });

    // Handle bulk delete
    $(document).on('click', '[data-kt-posts-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_posts_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " bài viết đã chọn không?",
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
                    url: "{{ route('posts.destroy', 'bulk') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    data: {
                        ids: selectedIds
                    },
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        if (data.type == 'success') {
                            dt.ajax.reload(null, false);
                            loadStatistics();
                            $('[data-kt-check="true"]').prop('checked', false);
                            updateBulkActions();
                        }
                    },
                    error: function(data) {
                        notification('error', 'Lỗi', 'Có lỗi xảy ra khi xóa các bài viết');
                    }
                });
            }
        });
    });

    // Search functionality
    $(".search_table").on('change keyup', function() {
        let data = $(this).val();
        let filter = $(this).data('filter');

        if (filter === 'status') {
            status_filter = data;
        } else {
            search_table = data;
        }

        console.log('Search:', search_table, 'Status:', status_filter);
        dt.ajax.reload();
    });
</script>
