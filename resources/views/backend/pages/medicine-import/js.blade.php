{
data: 'quantity',
render: function(data, type, row, meta) {
return '
<script>
    // Private functions
    let search_table = '';
    let date_filter = '';
    let medicine_filter = '';

    // Load statistics and medicines on page load
    $(document).ready(function() {
        loadStatistics();
        loadMedicines();
    });

    var dt = $("#kt_customers_table").DataTable({
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: "{{ route('medicine-import.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.date_filter = date_filter;
                d.medicine_filter = medicine_filter;
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
                data: 'import_code',
                render: function(data, type, row, meta) {
                    return '<span class="import-code">' + data + '</span>';
                }
            },
            {
                data: 'medicine_info',
                render: function(data, type, row, meta) {
                    let html = '<div class="medicine-info">';
                    html += '<div class="medicine-name">' + data.name + '</div>';
                    html += '<span class="badge badge-light-info">' + (data.type === 'supplement' ?
                        'TPCN' : data.type === 'medicine' ? 'Thuốc' : 'Khác') + '</span>';
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'quantity',
                render: function(data, type, row, meta) {
                    return '<span class="quantity-display fw-bold">' + new Intl.NumberFormat('vi-VN')
                        .format(data) + '</span>';
                }
            },
            {
                data: 'formatted_total_amount',
                render: function(data, type, row, meta) {
                    return '<span class="price-display fw-bold">' + data + '</span>';
                }
            },
            {
                data: 'formatted_import_date',
                render: function(data, type, row, meta) {
                    return '<span class="text-muted">' + data + '</span>';
                }
            },
            {
                data: 'has_invoice',
                render: function(data, type, row, meta) {
                    if (data && row.invoice_image_url) {
                        return '<img src="' + row.invoice_image_url + '" class="invoice-preview" ' +
                            'onclick="showInvoicePreview(\'' + row.invoice_image_url + '\')" ' +
                            'title="Click để xem ảnh lớn">';
                    } else {
                        return '<span class="text-muted">Không có</span>';
                    }
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
            $('[data-kt-imports-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-imports-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-imports-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-imports-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-imports-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('medicine-import.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {
                $('#statsContainer').html(`
        <div class="col-xl-3">
            <div class="card stats-card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-primary">
                                <i class="fas fa-file-import text-primary fs-2x"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-bolder fs-2">${data.total_imports || 0}</span>
                            <span class="text-muted fw-bold fs-7">Tổng phiếu nhập</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card stats-card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-success">
                                <i class="fas fa-calendar-day text-success fs-2x"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-bolder fs-2">${data.today_imports || 0}</span>
                            <span class="text-muted fw-bold fs-7">Nhập hôm nay</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card stats-card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-info">
                                <i class="fas fa-calendar-alt text-info fs-2x"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-bolder fs-2">${data.month_imports || 0}</span>
                            <span class="text-muted fw-bold fs-7">Nhập tháng này</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card stats-card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-warning">
                                <i class="fas fa-boxes text-warning fs-2x"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-bolder fs-2">${data.total_value || '0 VNĐ'}</span>
                            <span class="text-muted fw-bold fs-7">Tổng giá trị</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card stats-card card-xl-stretch mb-xl-8">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-5">
                            <span class="symbol-label bg-light-success">
                                <i class="fas fa-calendar-check text-success fs-2x"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-bolder fs-2">${data.month_value || '0 VNĐ'}</span>
                            <span class="text-muted fw-bold fs-7">GT tháng này</span>
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

    // Load medicines for select dropdown
    function loadMedicines() {
        $.ajax({
            url: "{{ route('medicine-import.show', 'get-medicines') }}",
            type: 'GET',
            success: function(data) {
                let filterOptions = '<option value="">Tất cả thuốc</option>';

                $.each(data, function(index, medicine) {
                    filterOptions += '<option value="' + medicine.id + '">' + medicine.text +
                        '</option>';
                });

                $('#medicine-filter').html(filterOptions);
            },
            error: function() {
                console.log('Error loading medicines');
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
        $('#invoice-preview-container').hide();
        $('.print-error-msg').hide();

        // Reset Select2
        if ($('#medicine_select').hasClass('select2-hidden-accessible')) {
            $('#medicine_select').val(null).trigger('change');
        }

        // Set default date to today
        $('input[name="import_date"]').val('{{ date('Y-m-d') }}');
    }

    // Function to load import data for edit
    function loadImportData(id) {
        // Show loading state
        const submitBtn = $('#kt_modal_add_customer_submit');
        submitBtn.attr('data-kt-indicator', 'on');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: "{{ route('medicine-import.show', 'get-data') }}",
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                if (response.type === 'success') {
                    let data = response.data;
                    let modal = $('#kt_modal_add_customer_form');

                    // Set form values
                    modal.find('.modal-title').text('Sửa phiếu nhập thuốc');
                    modal.find('input[name=id]').val(data.id);
                    modal.find('input[name=quantity]').val(data.quantity);
                    modal.find('input[name=total_amount]').val(data.total_amount);
                    modal.find('input[name=import_date]').val(data.import_date);
                    modal.find('textarea[name=notes]').val(data.notes || '');

                    // Set medicine select value
                    if (data.medicine_id) {
                        // Create option and select it
                        let option = new Option(data.medicine_name, data.medicine_id, true, true);
                        $('#medicine_select').append(option).trigger('change');
                    }

                    // Show current invoice if exists
                    if (data.has_invoice && data.invoice_image_url) {
                        $('#invoice-preview').attr('src', data.invoice_image_url);
                        $('#invoice-preview-container').show();
                    }

                    console.log('Import data loaded successfully:', data);
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
                console.error('Error loading import data:', xhr);
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
            console.log('Loading import data for ID:', id);
            loadImportData(id);
        } else {
            notification('error', 'Lỗi', 'Không tìm thấy ID phiếu nhập');
        }
    });

    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        form_reset();
        let modal = $('#kt_modal_add_customer_form');
        modal.find('.modal-title').text('Thêm phiếu nhập thuốc');
        modal.find('input[name=id]').val('');

        // Load medicines for Select2
        $.ajax({
            url: "{{ route('medicine-import.show', 'get-medicines') }}",
            type: 'GET',
            success: function(data) {
                $('#medicine_select').empty().append('<option value="">-- Chọn thuốc --</option>');
                $.each(data, function(index, medicine) {
                    $('#medicine_select').append(new Option(medicine.text, medicine.id));
                });
            }
        });
    });

    $('#kt_modal_add_customer_form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let type = 'POST',
            url = "{{ route('medicine-import.store') }}",
            id = $('form#kt_modal_add_customer_form input[name=id]').val();

        if (parseInt(id)) {
            console.log('Updating import with ID:', id);
            type = 'POST';
            formData.append('_method', 'PUT');
            url = "{{ route('medicine-import.update', ':id') }}".replace(':id', id);
        } else {
            console.log('Creating new import');
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
                console.error('Error saving import:', xhr);
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
            text: "Bạn có muốn xóa phiếu nhập này không?",
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
                    url: "{{ route('medicine-import.destroy', ':id') }}".replace(':id', id),
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
                        let message = 'Có lỗi xảy ra khi xóa phiếu nhập';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        notification('error', 'Lỗi', message);
                        console.error('Error deleting import:', xhr);
                    }
                });
            }
        });
    });

    // Handle bulk delete
    $(document).on('click', '[data-kt-imports-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_customers_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " phiếu nhập đã chọn không?",
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
                    url: "{{ route('medicine-import.destroy', 'bulk') }}",
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
                        let message = 'Có lỗi xảy ra khi xóa các phiếu nhập';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        notification('error', 'Lỗi', message);
                        console.error('Error bulk deleting imports:', xhr);
                    }
                });
            }
        });
    });

    $(".search_table").on('change keyup', function() {
        let data = $(this).val();
        let filter = $(this).data('filter');

        if (filter === 'date') {
            date_filter = data;
        } else if (filter === 'medicine') {
            medicine_filter = data;
        } else {
            search_table = data;
        }

        console.log('Search:', search_table, 'Date:', date_filter, 'Medicine:', medicine_filter);
        dt.ajax.reload();
    });
</script>
