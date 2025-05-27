<script>
    // Private functions
    let search_table = '';
    let status_filter = '';
    let gender_filter = '';

    // Load statistics on page load
    $(document).ready(function() {
        loadStatistics();
    });

    var dt = $("#kt_patients_table").DataTable({
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: "{{ route('patient.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.status_filter = status_filter;
                d.gender_filter = gender_filter;
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
                data: 'patient_code',
                render: function(data, type, row, meta) {
                    return '<span class="fw-bold text-primary">' + data + '</span>';
                }
            },
            {
                data: 'full_name',
                render: function(data, type, row, meta) {
                    let html = '<div class="patient-info">';
                    html += '<div class="patient-name">' + data + '</div>';
                    html += '<div class="patient-phone">' + row.phone + '</div>';
                    if (row.address) {
                        html += '<div class="text-muted fs-8">' + (row.address.length > 50 ? row.address
                            .substring(0, 50) + '...' : row.address) + '</div>';
                    }
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'age_display'
            },
            {
                data: 'gender',
                render: function(data, type, row, meta) {
                    return row.gender_badge;
                }
            },
            {
                data: 'examination_count',
                render: function(data, type, row, meta) {
                    return '<span class="badge badge-light-info">' + data + ' lần</span>';
                }
            },
            {
                data: 'last_examination'
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
                        '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true"> \n' +
                        '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-view-history">Lịch sử khám</a> \n' +
                        '</div> \n' +
                        '<div class="menu-item px-3"> \n' +
                        '<a href="" data-data=\'' + JSON.stringify(row) +
                        '\' class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_patient">Sửa</a> \n' +
                        '</div> \n';

                    if (row.examination_count === 0) {
                        return +'<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id +
                            '" class="menu-link px-3 btn-delete" data-kt-patient-table-filter="delete_row">Xóa</a> \n' +
                            '</div> \n';
                    }

                    return +'</div>';
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
        $('#kt_patients_table tbody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActions();
    });

    $(document).on('change', '#kt_patients_table tbody input[type="checkbox"]', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('#kt_patients_table tbody input[type="checkbox"]:checked').length;

        if (row.examination_count === 0) {
            actions += '<div class="menu-item px-3"> \n' +
                '<a href="#" data-id="' + row.id +
                '" class="menu-link px-3 btn-delete" data-kt-patient-table-filter="delete_row">Xóa</a> \n' +
                '</div> \n';
        }

        actions += '</div>';
        return actions;
    }


    dt.on('draw', function() {
        KTMenu.createInstances();
        updateBulkActions();
    });

    // Handle bulk selection
    $('[data-kt-check="true"]').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#kt_patients_table tbody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActions();
    });

    $(document).on('change', '#kt_patients_table tbody input[type="checkbox"]', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('#kt_patients_table tbody input[type="checkbox"]:checked').length;

        if (checkedCount > 0) {
            $('[data-kt-patients-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-patients-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-patients-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-patients-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-patients-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('patient.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {
                $('#statsContainer').html(`
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-users text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.total || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Tổng bệnh nhân</span>
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
                                            <i class="fas fa-user-check text-success fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.active || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Đang hoạt động</span>
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
                                            <i class="fas fa-user-plus text-info fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.new_this_month || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Mới tháng này</span>
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
                                            <i class="fas fa-mars text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.male || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Nam</span>
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
                                            <i class="fas fa-venus text-info fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.female || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Nữ</span>
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
                                            <i class="fas fa-child text-warning fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.children || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Trẻ em (<18)</span>
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

    // Form reset
    function form_reset() {
        $("#kt_modal_add_patient").modal({
            'backdrop': 'static',
            'keyboard': false
        });
        $("#kt_modal_add_patient_form").trigger("reset");
        $('input[name="is_active"]').prop('checked', true);
        $('.print-error-msg').hide();
    }

    $('.btn-close').on('click', function() {
        form_reset();
        $('#kt_modal_add_patient').modal('hide');
    });

    $('#kt_modal_add_patient_cancel').on('click', function() {
        form_reset();
    });

    // Edit patient
    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        form_reset();
        let data = $(this).data('data');
        let modal = $('#kt_modal_add_patient_form');
        modal.find('.modal-title').text('Sửa thông tin bệnh nhân');
        modal.find('input[name=id]').val(data.id);
        modal.find('input[name=full_name]').val(data.full_name);
        modal.find('input[name=phone]').val(data.phone);
        modal.find('textarea[name=address]').val(data.address);
        modal.find('input[name=citizen_id]').val(data.citizen_id);
        modal.find('input[name=date_of_birth]').val(data.date_of_birth);
        modal.find('select[name=gender]').val(data.gender);
        modal.find('input[name=email]').val(data.email);
        modal.find('input[name=emergency_contact]').val(data.emergency_contact);
        modal.find('input[name=emergency_phone]').val(data.emergency_phone);
        modal.find('textarea[name=allergies]').val(data.allergies);
        modal.find('textarea[name=medical_history]').val(data.medical_history);
        modal.find('textarea[name=notes]').val(data.notes);
        modal.find('input[name=is_active]').prop('checked', data.is_active == 1);
    });

    // Add patient
    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        form_reset();
        let modal = $('#kt_modal_add_patient_form');
        modal.find('.modal-title').text('Thêm bệnh nhân mới');
        modal.find('input[name=id]').val('');
    });

    // Form submission
    $('#kt_modal_add_patient_form').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let type = 'POST',
            url = "{{ route('patient.store') }}",
            id = $('form#kt_modal_add_patient_form input[name=id]').val();

        if (parseInt(id)) {
            type = 'POST';
            formData.append('_method', 'PUT');
            url = "{{ route('patient.update', ':id') }}".replace(':id', id);
        }

        // Show loading
        $('#kt_modal_add_patient_submit').attr('data-kt-indicator', 'on');
        $('#kt_modal_add_patient_submit').prop('disabled', true);

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
                    $('#kt_modal_add_patient_form').trigger('reset');
                    $('#kt_modal_add_patient').modal('hide');
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
                $('#kt_modal_add_patient_submit').removeAttr('data-kt-indicator');
                $('#kt_modal_add_patient_submit').prop('disabled', false);
            }
        });
    });

    // Delete patient
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            text: "Bạn có muốn xóa bệnh nhân này không?",
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
                    url: "{{ route('patient.destroy', ':id') }}".replace(':id', id),
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

    // View examination history
    $(document).on('click', '.btn-view-history', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        $.ajax({
            url: "{{ route('patient.history', ':id') }}".replace(':id', id),
            type: 'GET',
            success: function(response) {
                if (response.type === 'success') {
                    showPatientHistory(response.data);
                }
            },
            error: function() {
                notification('error', 'Lỗi', 'Không thể tải lịch sử khám');
            }
        });
    });

    function showPatientHistory(data) {
        let patient = data.patient;
        let examinations = data.examinations;

        let historyHtml = `
            <div class="mb-5">
                <h4>${patient.full_name} (${patient.patient_code})</h4>
                <p class="text-muted">${patient.phone} - ${patient.age ? patient.age + ' tuổi' : 'Chưa có thông tin tuổi'}</p>
            </div>
        `;

        if (examinations.length > 0) {
            historyHtml += '<div class="timeline timeline-border-dashed">';
            examinations.forEach(function(exam, index) {
                let statusClass = {
                    'waiting': 'badge-light-warning',
                    'examining': 'badge-light-info',
                    'completed': 'badge-light-success',
                    'cancelled': 'badge-light-danger'
                } [exam.status] || 'badge-light-secondary';

                let paymentClass = {
                    'pending': 'badge-light-warning',
                    'paid': 'badge-light-success',
                    'cancelled': 'badge-light-danger'
                } [exam.payment_status] || 'badge-light-secondary';

                historyHtml += `
                    <div class="timeline-item">
                        <div class="timeline-line w-40px"></div>
                        <div class="timeline-icon symbol symbol-circle symbol-40px">
                            <div class="symbol-label bg-light">
                                <i class="fas fa-file-medical text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="timeline-content mb-10 mt-n2">
                            <div class="overflow-auto pe-3">
                                <div class="fs-5 fw-bold mb-2">${exam.examination_code}</div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Ngày khám:</div>
                                    <div class="text-gray-800 me-5">${new Date(exam.examination_date).toLocaleDateString('vi-VN')}</div>
                                </div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Chuẩn đoán:</div>
                                    <div class="text-gray-800">${exam.diagnosis || 'Chưa có'}</div>
                                </div>
                                <div class="d-flex align-items-center mt-1 fs-6">
                                    <div class="text-muted me-2 fs-7">Tổng tiền:</div>
                                    <div class="text-primary fw-bold">${new Intl.NumberFormat('vi-VN').format(exam.total_fee)} VNĐ</div>
                                </div>
                                <div class="d-flex align-items-center mt-1 fs-6 mb-2">
                                    <span class="badge ${statusClass} me-2">${exam.status_name}</span>
                                    <span class="badge ${paymentClass}">${exam.payment_status_name}</span>
                                </div>
                                ${exam.next_appointment ? `<div class="text-muted fs-7">Tái khám: ${new Date(exam.next_appointment).toLocaleDateString('vi-VN')}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            });
            historyHtml += '</div>';
        } else {
            historyHtml += '<div class="alert alert-info">Bệnh nhân chưa có lịch sử khám nào.</div>';
        }

        // Show in modal
        $('#patient-history-content').html(historyHtml);
        $('#kt_modal_patient_history').modal('show');
    }

    // Handle bulk delete
    $(document).on('click', '[data-kt-patients-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_patients_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " bệnh nhân đã chọn không?",
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
                    url: "{{ route('patient.destroy', 'bulk') }}",
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

        if (filter === 'status') {
            status_filter = data;
        } else if (filter === 'gender') {
            gender_filter = data;
        } else {
            search_table = data;
        }

        console.log('Search:', search_table, 'Status:', status_filter, 'Gender:', gender_filter);
        dt.ajax.reload();
    });
</script>
