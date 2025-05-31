<script>
    // Private functions
    let search_table = '';
    let date_filter = '';
    let service_filter = '';
    let status_filter = '';

    // Load statistics and services on page load
    $(document).ready(function() {
        loadStatistics();
        loadServices();
    });

    var dt = $("#kt_customers_table").DataTable({
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: "{{ route('appointment.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.date_filter = date_filter;
                d.service_filter = service_filter;
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
                data: 'patient_name',
                render: function(data, type, row, meta) {
                    let html = '<div class="d-flex flex-column">';
                    html += '<span class="text-dark fw-bolder">' + data + '</span>';
                    html += '<small class="text-muted">' + row.patient_phone + '</small>';
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'service_name',
                render: function(data, type, row, meta) {
                    if (data && data !== 'Không có') {
                        return '<span class="badge badge-light-info">' + data + '</span>';
                    } else {
                        return '<span class="text-muted">Không có</span>';
                    }
                }
            },
            {
                data: 'formatted_appointment_date',
                render: function(data, type, row, meta) {
                    return '<span class="text-dark fw-bold">' + data + '</span>';
                }
            },
            {
                data: 'status',
                render: function(data, type, row, meta) {
                    return row.status_badge;
                }
            },
            {
                data: 'source',
                render: function(data, type, row, meta) {
                    return row.source_badge;
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
                        '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-200px py-4" data-kt-menu="true"> \n' +
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">Sửa</a> \n' +
                        '</div> \n' +
                        (row.status === 'pending' ?
                            '<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id +
                            '" class="menu-link px-3 btn-confirm">Xác nhận</a> \n' +
                            '</div> \n' : '') +
                        (row.status !== 'completed' && row.status !== 'cancelled' ?
                            '<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id +
                            '" class="menu-link px-3 btn-complete">Hoàn thành</a> \n' +
                            '</div> \n' : '') +
                        (row.status !== 'cancelled' ?
                            '<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id +
                            '" class="menu-link px-3 btn-cancel">Hủy</a> \n' +
                            '</div> \n' : '') +
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
            $('[data-kt-appointments-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-appointments-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-appointments-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-appointments-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-appointments-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('appointment.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {
                $('#statsContainer').html(`
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-calendar-check text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.total || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Tổng lịch hẹn</span>
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
                                            <i class="fas fa-calendar-day text-success fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.today || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Hôm nay</span>
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
                                        <span class="text-dark fw-bolder fs-2">${data.pending || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Chờ xác nhận</span>
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
                                            <i class="fas fa-check-circle text-info fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.confirmed || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Đã xác nhận</span>
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
                                            <i class="fas fa-check-double text-success fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.completed || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Hoàn thành</span>
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
                                        <span class="text-dark fw-bolder fs-2">${data.cancelled || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Đã hủy</span>
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

    // Load services for filter and modal
    function loadServices() {
        $.ajax({
            url: "{{ route('appointment.show', 'get-services') }}",
            type: 'GET',
            success: function(data) {
                let options = '<option value="">-- Chọn dịch vụ --</option>';
                let filterOptions = '<option value="">Tất cả dịch vụ</option>';

                data.forEach(function(service) {
                    options += `<option value="${service.id}">${service.name}</option>`;
                    filterOptions += `<option value="${service.id}">${service.name}</option>`;
                });

                $('#service_select').html(options);
                $('#service-filter').html(filterOptions);
            },
            error: function() {
                console.log('Error loading services');
            }
        });
    }

    // Form reset
    function form_reset() {
        $("#kt_modal_add_customer").modal({
            'backdrop': 'static',
            'keyboard': false
        });
        $("#kt_modal_add_customer_form").trigger("reset");
        $('.print-error-msg').hide();
        // Set default values
        $('input[name="appointment_date"]').val(new Date().toISOString().split('T')[0]);
        $('input[name="appointment_time"]').val('09:00');
        $('select[name="status"]').val('pending');
        $('select[name="source"]').val('phone');
    }

    $('.btn-close').on('click', function() {
        form_reset();
        $('#kt_modal_add_customer').modal('hide');
    });

    $('#kt_modal_add_customer_cancel').on('click', function() {
        form_reset();
    });

    // Edit appointment
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        form_reset();

        let id = $(this).data('id');

        // Load appointment data
        $.ajax({
            url: "{{ route('appointment.show', ':id') }}".replace(':id', id),
            type: 'GET',
            success: function(response) {
                if (response.type === 'success') {
                    let data = response.data;
                    let modal = $('#kt_modal_add_customer_form');

                    modal.find('.modal-title').text('Sửa lịch hẹn');
                    modal.find('input[name=id]').val(data.id);
                    modal.find('input[name=patient_name]').val(data.patient_name);
                    modal.find('input[name=patient_phone]').val(data.patient_phone);
                    modal.find('select[name=service_id]').val(data.service_id || '');
                    modal.find('input[name=appointment_date]').val(data.appointment_date);
                    modal.find('input[name=appointment_time]').val(data.appointment_time);
                    modal.find('select[name=status]').val(data.status);
                    modal.find('select[name=source]').val(data.source);
                    modal.find('textarea[name=symptoms]').val(data.symptoms || '');
                    modal.find('textarea[name=notes]').val(data.notes || '');
                }
            },
            error: function() {
                notification('error', 'Lỗi', 'Không thể tải dữ liệu lịch hẹn');
            }
        });
    });

    // Add appointment
    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        form_reset();
        let modal = $('#kt_modal_add_customer_form');
        modal.find('.modal-title').text('Thêm lịch hẹn mới');
        modal.find('input[name=id]').val('');
    });

    // Form submission
    $('#kt_modal_add_customer_form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let type = 'POST',
            url = "{{ route('appointment.store') }}",
            id = $('form#kt_modal_add_customer_form input[name=id]').val();

        if (parseInt(id)) {
            type = 'POST';
            formData.append('_method', 'PUT');
            url = "{{ route('appointment.update', ':id') }}".replace(':id', id);
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
                    let errors = xhr.responseJSON?.errors || {};
                    console.log(errors);
                    $.each(errors, function(key, value) {
                        notification('error', 'Lỗi', value);
                    });
                }
            },
            complete: function() {
                $('#kt_modal_add_customer_submit').removeAttr('data-kt-indicator');
                $('#kt_modal_add_customer_submit').prop('disabled', false);
            }
        });
    });

    // Delete appointment
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            text: "Bạn có muốn xóa lịch hẹn này không?",
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
                    url: "{{ route('appointment.destroy', ':id') }}".replace(':id', id),
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
                        let errors = data.responseJSON.errors;
                        console.log(errors);
                        $.each(errors, function(key, value) {
                            notification('error', 'Lỗi', value);
                        });
                    }
                });
            }
        });
    });

    // Confirm appointment
    $(document).on('click', '.btn-confirm', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        Swal.fire({
            text: "Bạn có muốn xác nhận lịch hẹn này không?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Xác nhận!",
            cancelButtonText: "Hủy",
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
                cancelButton: "btn fw-bold btn-active-light-primary",
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('appointment.confirm', ':id') }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        if (data.type == 'success') {
                            dt.ajax.reload(null, false);
                            loadStatistics();
                        }
                    },
                    error: function(data) {
                        notification('error', 'Lỗi', 'Có lỗi xảy ra khi xác nhận lịch hẹn');
                    }
                });
            }
        });
    });

    // Complete appointment
    $(document).on('click', '.btn-complete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        Swal.fire({
            text: "Bạn có muốn hoàn thành lịch hẹn này không?",
            icon: "question",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Hoàn thành!",
            cancelButtonText: "Hủy",
            customClass: {
                confirmButton: "btn fw-bold btn-success",
                cancelButton: "btn fw-bold btn-active-light-primary",
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('appointment.complete', ':id') }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        if (data.type == 'success') {
                            dt.ajax.reload(null, false);
                            loadStatistics();
                        }
                    },
                    error: function(data) {
                        notification('error', 'Lỗi',
                            'Có lỗi xảy ra khi hoàn thành lịch hẹn');
                    }
                });
            }
        });
    });

    // Cancel appointment
    $(document).on('click', '.btn-cancel', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        Swal.fire({
            text: "Bạn có muốn hủy lịch hẹn này không?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Hủy lịch hẹn!",
            cancelButtonText: "Không",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary",
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('appointment.cancel', ':id') }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    success: function(data) {
                        notification(data.type, data.title, data.content);
                        if (data.type == 'success') {
                            dt.ajax.reload(null, false);
                            loadStatistics();
                        }
                    },
                    error: function(data) {
                        notification('error', 'Lỗi', 'Có lỗi xảy ra khi hủy lịch hẹn');
                    }
                });
            }
        });
    });

    // Handle bulk delete
    $(document).on('click', '[data-kt-appointments-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_customers_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " lịch hẹn đã chọn không?",
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
                    url: "{{ route('appointment.destroy', 'bulk') }}",
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
                        let errors = data.responseJSON.errors;
                        console.log(errors);
                        $.each(errors, function(key, value) {
                            notification('error', 'Lỗi', value);
                        });
                    }
                });
            }
        });
    });

    // Search filters
    $(".search_table").on('change keyup', function() {
        let data = $(this).val();
        let filter = $(this).data('filter');

        if (filter === 'date') {
            date_filter = data;
        } else if (filter === 'service') {
            service_filter = data;
        } else if (filter === 'status') {
            status_filter = data;
        } else {
            search_table = data;
        }

        console.log('Search:', search_table, 'Date:', date_filter, 'Service:', service_filter, 'Status:',
            status_filter);
        dt.ajax.reload();
    });
</script>
