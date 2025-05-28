<script>
    // Private functions
    let search_table = '';
    let status_filter = '';
    let type_filter = '';
    let alert_filter = '';

    // Load statistics on page load
    $(document).ready(function() {
        loadStatistics();
    });

    var dt = $("#kt_customers_table").DataTable({
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: "{{ route('medicine.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.status_filter = status_filter;
                d.type_filter = type_filter;
                d.alert_filter = alert_filter;
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
                data: 'image_display',
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return '<img src="' + data + '" class="medicine-thumbnail" alt="' + row.name + '">';
                }
            },
            {
                data: 'name',
                render: function(data, type, row, meta) {
                    let html = '<div class="medicine-info">';
                    html += '<div class="medicine-name">' + data + '</div>';
                    html += row.type_badge;
                    if (row.short_description && row.short_description !== '-') {
                        html += '<div class="text-muted fs-7 mt-1">' + row.short_description + '</div>';
                    }
                    if (row.alerts) {
                        html += '<div class="alert-badges mt-2">' + row.alerts + '</div>';
                    }
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'formatted_import_price',
                render: function(data, type, row, meta) {
                    return '<span class="price-display">' + data + '</span>';
                }
            },
            {
                data: 'formatted_sale_price',
                render: function(data, type, row, meta) {
                    return '<span class="price-display fw-bold">' + data + '</span>';
                }
            },
            {
                data: 'formatted_expiry_date',
                render: function(data, type, row, meta) {
                    if (!data) return '<span class="text-muted">-</span>';

                    // Kiểm tra hạn sử dụng
                    let expiryDate = new Date(row.expiry_date);
                    let today = new Date();
                    let diffTime = expiryDate - today;
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    let badgeClass = 'badge-light-primary';
                    if (diffDays < 0) {
                        badgeClass = 'badge-light-danger';
                    } else if (diffDays <= 30) {
                        badgeClass = 'badge-light-warning';
                    }

                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            {
                data: 'is_active',
                render: function(data, type, row, meta) {
                    return row.status_badge;
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
                        '" class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">Sửa</a> \n' +
                        '</div> \n' +
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-delete" data-kt-customer-table-filter="delete_row">Xóa</a> \n' +
                        '</div> \n' +
                        '</div>';
                }
            }
        ]
    });

    dt.on('draw', function() {
        KTMenu.createInstances();
        updateBulkActions();
    });

    // Handle bulk selection
    $('[data-kt-check="true"]').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#kt_customers_table tbody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActions();
    });

    $(document).on('change', '#kt_customers_table tbody input[type="checkbox"]', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('#kt_customers_table tbody input[type="checkbox"]:checked').length;

        if (checkedCount > 0) {
            $('[data-kt-medicines-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-medicines-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-medicines-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-medicines-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-medicines-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('medicine.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {
                $('#statsContainer').html(`
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-pills text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.total || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Tổng thuốc</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-success">
                                            <i class="fas fa-check-circle text-success fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.active || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Hoạt động</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-info">
                                            <i class="fas fa-leaf text-info fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.supplement || 0}</span>
                                        <span class="text-muted fw-bold fs-7">TPCN</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-capsules text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.medicine || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Thuốc điều trị</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-warning">
                                            <i class="fas fa-clock text-warning fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.expiring_soon || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Sắp hết hạn</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-danger">
                                            <i class="fas fa-times-circle text-danger fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.expired || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Hết hạn</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            },
            error: function() {
                console.log('Error loading statistics');
            }
        });
    }

    $('.btn-close').on('click', function() {
        form_reset();
        $('#kt_modal_add_customer').modal('hide');
    });

    $('#kt_modal_add_customer_cancel').on('click', function() {
        form_reset();
    });

    function form_reset() {
        $("#kt_modal_add_customer").modal({
            'backdrop': 'static',
            'keyboard': false
        });
        $("#kt_modal_add_customer_form").trigger("reset");
        $('input[name="is_active"]').prop('checked', true);
        $('#image-preview-container').hide();
        $('.print-error-msg').hide();

        // Reset TinyMCE
        if (typeof tinymce !== 'undefined') {
            tinymce.get('medicine_description')?.setContent('');
        }
    }

    // Function to load medicine data for edit
    function loadMedicineData(id) {
        // Show loading state
        const submitBtn = $('#kt_modal_add_customer_submit');
        submitBtn.attr('data-kt-indicator', 'on');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: "{{ route('medicine.show', 'get-data') }}",
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                if (response.type === 'success') {
                    let data = response.data;
                    let modal = $('#kt_modal_add_customer_form');

                    // Set form values
                    modal.find('.modal-title').text('Sửa thông tin thuốc');
                    modal.find('input[name=id]').val(data.id);
                    modal.find('input[name=name]').val(data.name);
                    modal.find('select[name=type]').val(data.type);
                    modal.find('input[name=import_price]').val(data.import_price);
                    modal.find('input[name=sale_price]').val(data.sale_price);
                    modal.find('input[name=expiry_date]').val(data.expiry_date || '');
                    modal.find('input[name=is_active]').prop('checked', data.is_active);

                    // Set TinyMCE content
                    setTimeout(function() {
                        if (typeof tinymce !== 'undefined' && tinymce.get('medicine_description')) {
                            tinymce.get('medicine_description').setContent(data.description || '');
                        }
                    }, 500);

                    // Show current image if exists
                    if (data.has_image && data.image_url) {
                        $('#image-preview').attr('src', data.image_url);
                        $('#image-preview-container').show();
                    }

                    console.log('Medicine data loaded successfully:', data);
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
                console.error('Error loading medicine data:', xhr);
            },
            complete: function() {
                // Hide loading state
                submitBtn.removeAttr('data-kt-indicator');
                submitBtn.prop('disabled', false);
            }
        });
    }

    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        form_reset();

        let id = $(this).data('id');
        if (id) {
            console.log('Loading medicine data for ID:', id);
            loadMedicineData(id);
        } else {
            notification('error', 'Lỗi', 'Không tìm thấy ID thuốc');
        }
    });

    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        form_reset();
        let modal = $('#kt_modal_add_customer_form');
        modal.find('.modal-title').text('Thêm thuốc mới');
        modal.find('input[name=id]').val('');
    });

    $('#kt_modal_add_customer_form').on('submit', function(e) {
        e.preventDefault();

        // Sync TinyMCE content before submit
        if (typeof tinymce !== 'undefined' && tinymce.get('medicine_description')) {
            tinymce.get('medicine_description').save();
        }

        let formData = new FormData(this);
        let type = 'POST',
            url = "{{ route('medicine.store') }}",
            id = $('form#kt_modal_add_customer_form input[name=id]').val();

        if (parseInt(id)) {
            console.log('Updating medicine with ID:', id);
            type = 'POST';
            formData.append('_method', 'PUT');
            url = "{{ route('medicine.update', ':id') }}".replace(':id', id);
        } else {
            console.log('Creating new medicine');
        }

        // Show loading
        $('#kt_modal_add_customer_submit').attr('data-kt-indicator', 'on');
        $('#kt_modal_add_customer_submit').prop('disabled', true);

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: type,
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                notification(data.type, data.title, data.content);
                if (data.type == 'success') {
                    dt.ajax.reload(null, false);
                    $('#kt_modal_add_customer_form').trigger('reset');
                    $('#kt_modal_add_customer').modal('hide');
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
                console.error('Error saving medicine:', xhr);
            },
            complete: function() {
                $('#kt_modal_add_customer_submit').removeAttr('data-kt-indicator');
                $('#kt_modal_add_customer_submit').prop('disabled', false);
            }
        });
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            text: "Bạn có muốn xóa thuốc này không?",
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
                    url: "{{ route('medicine.destroy', ':id') }}".replace(':id', id),
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
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra khi xóa thuốc';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        notification('error', 'Lỗi', message);
                        console.error('Error deleting medicine:', xhr);
                    }
                });
            }
        });
    });

    // Handle bulk delete
    $(document).on('click', '[data-kt-medicines-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_customers_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " thuốc đã chọn không?",
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
                    url: "{{ route('medicine.destroy', 'bulk') }}",
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
                    error: function(xhr) {
                        let message = 'Có lỗi xảy ra khi xóa các thuốc';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        notification('error', 'Lỗi', message);
                        console.error('Error bulk deleting medicines:', xhr);
                    }
                });
            }
        });
    });

    $(".search_table").on('change keyup', function() {
        let data = $(this).val();
        let filter = $(this).data('filter');

        if (filter === 'status') {
            status_filter = data;
        } else if (filter === 'type') {
            type_filter = data;
        } else if (filter === 'alert') {
            alert_filter = data;
        } else {
            search_table = data;
        }

        console.log('Search:', search_table, 'Status:', status_filter, 'Type:', type_filter, 'Alert:',
            alert_filter);
        dt.ajax.reload();
    });
</script>
