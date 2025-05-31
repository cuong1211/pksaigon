<script>
    // Private variables
    let search_table = '';
    let status_filter = '';
    let payment_filter = '';
    let date_filter = '';

    // Data caches
    let servicesData = [];
    let medicinesData = [];
    let selectedPatientData = null;

    // Counters for dynamic items
    let serviceCounter = 0;
    let medicineCounter = 0;

    // Load data on page load
    $(document).ready(function() {
        loadStatistics();
        loadServicesData();
        loadMedicinesData();
    });

    // DataTable initialization
    var dt = $("#kt_examination_table").DataTable({
        serverSide: true,
        select: {
            style: 'multi',
            selector: 'td:first-child',
            className: 'row-selected'
        },
        ajax: {
            url: "{{ route('examination.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.status_filter = status_filter;
                d.payment_filter = payment_filter;
                d.date_filter = date_filter;
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
                data: 'examination_code',
                render: function(data, type, row, meta) {
                    return '<span class="examination-code">' + data + '</span>';
                }
            },
            {
                data: 'patient_info',
                render: function(data, type, row, meta) {
                    let html = '<div class="patient-info">';
                    html += '<div class="patient-name">' + data.name + '</div>';
                    html += '<div class="patient-phone">' + data.phone + '</div>';
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'formatted_examination_date'
            },
            {
                data: 'diagnosis',
                render: function(data, type, row, meta) {
                    if (!data) return '<span class="text-muted">Chưa có</span>';
                    return data.length > 50 ? data.substring(0, 50) + '...' : data;
                }
            },
            {
                data: 'formatted_total_fee',
                render: function(data, type, row, meta) {
                    return '<span class="price-display fw-bold">' + data + '</span>';
                }
            },
            {
                data: 'status',
                render: function(data, type, row, meta) {
                    return row.status_badge;
                }
            },
            {
                data: 'payment_status',
                render: function(data, type, row, meta) {
                    return row.payment_badge;
                }
            },
            {
                data: null,
                className: 'text-end',
                render: function(data, type, row, meta) {
                    let actions =
                        '<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Thao tác \n' +
                        '<span class="svg-icon svg-icon-5 m-0"> \n' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"> \n' +
                        '<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="black" /> \n' +
                        '</svg> \n' +
                        '</span> \n' +
                        '</a> \n' +
                        '<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true"> \n';

                    // QR Payment action
                    if (row.payment_status === 'pending') {
                        actions += '<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id + '" data-examination-code="' + row
                            .examination_code +
                            '" data-patient-name="' + row.patient_info.name + '" data-total-fee="' + row
                            .formatted_total_fee +
                            '" class="menu-link px-3 btn-payment-qr">Thanh toán QR</a> \n' +
                            '</div> \n';
                    }

                    // Edit action
                    actions += '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_examination">Sửa</a> \n' +
                        '</div> \n';

                    // Delete action
                    actions += '<div class="menu-item px-3"> \n' +
                        '<a href="#" data-id="' + row.id +
                        '" class="menu-link px-3 btn-delete">Xóa</a> \n' +
                        '</div> \n';

                    actions += '</div>';
                    return actions;
                }
            }
        ]
    });

    dt.on('draw', function() {
        KTMenu.createInstances();
        updateBulkActions();
    });

    // Bulk selection handlers
    $('[data-kt-check="true"]').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#kt_examination_table tbody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActions();
    });

    $(document).on('change', '#kt_examination_table tbody input[type="checkbox"]', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('#kt_examination_table tbody input[type="checkbox"]:checked').length;

        if (checkedCount > 0) {
            $('[data-kt-examination-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-examination-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-examination-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-examination-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-examination-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('examination.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {
                $('#statsContainer').html(`
                    <div class="col-xl-2">
                        <div class="card stats-card card-xl-stretch mb-xl-8">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-5">
                                        <span class="symbol-label bg-light-primary">
                                            <i class="fas fa-file-medical text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.total || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Tổng phiếu khám</span>
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
                                        <span class="text-muted fw-bold fs-7">Khám hôm nay</span>
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
                                            <i class="fas fa-calendar-alt text-info fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.this_month || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Khám tháng này</span>
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
                                        <span class="text-dark fw-bolder fs-2">${data.pending_payment || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Chờ thanh toán</span>
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
                                        <span class="text-dark fw-bolder fs-2">${data.paid || 0}</span>
                                        <span class="text-muted fw-bold fs-7">Đã thanh toán</span>
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
                                            <i class="fas fa-money-bill-wave text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-1">${data.total_revenue || '0 VNĐ'}</span>
                                        <span class="text-muted fw-bold fs-7">Tổng doanh thu</span>
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

    // Load services data
    function loadServicesData() {
        $.ajax({
            url: "{{ route('examination.show', 'get-services') }}",
            type: 'GET',
            success: function(data) {
                servicesData = data;
            },
            error: function() {
                console.log('Error loading services data');
            }
        });
    }

    // Load medicines data
    function loadMedicinesData() {
        $.ajax({
            url: "{{ route('examination.show', 'get-medicines') }}",
            type: 'GET',
            success: function(data) {
                medicinesData = data;
            },
            error: function() {
                console.log('Error loading medicines data');
            }
        });
    }

    // Modal management
    $('.btn-close').on('click', function() {
        form_reset();
        $('#kt_modal_examination').modal('hide');
    });

    $('#kt_modal_examination_cancel').on('click', function() {
        form_reset();
    });

    function form_reset() {
        $("#kt_modal_examination").modal({
            'backdrop': 'static',
            'keyboard': false
        });
        $("#kt_modal_examination_form").trigger("reset");
        $('.print-error-msg').hide();

        // Reset dynamic sections
        $('#services-container').empty();
        $('#medicines-container').empty();
        $('#fee-summary').hide();
        $('#patient-info-display').hide();
        $('#edit-mode-fields').hide();

        // Reset counters
        serviceCounter = 0;
        medicineCounter = 0;
        selectedPatientData = null;

        // Reset patient select2
        if ($('#patient_select').hasClass('select2-hidden-accessible')) {
            $('#patient_select').val(null).trigger('change');
        }

        // Set default date
        $('input[name="examination_date"]').val('{{ date('Y-m-d') }}');

        updateTotalFee();
    }

    // Patient selection handling
    $('#kt_modal_examination').on('shown.bs.modal', function() {
        $('#patient_select').select2({
            dropdownParent: $('#kt_modal_examination'),
            placeholder: "Tìm và chọn bệnh nhân...",
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('examination.show', 'get-patients') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id,
                                text: item.full_name + ' (' + item.phone + ')',
                                patient_code: item.patient_code,
                                full_name: item.full_name,
                                phone: item.phone,
                                address: item.address
                            };
                        })
                    };
                },
                cache: true
            }
        });

        $('#patient_select').on('select2:select', function(e) {
            const data = e.params.data;
            selectedPatientData = data;

            $('#selected-patient-code').text(data.patient_code);
            $('#selected-patient-phone').text(data.phone);
            $('#patient-info-display').show();
        });

        $('#patient_select').on('select2:clear', function(e) {
            selectedPatientData = null;
            $('#patient-info-display').hide();
        });
    });

    $('#kt_modal_examination').on('hidden.bs.modal', function() {
        if ($('#patient_select').hasClass('select2-hidden-accessible')) {
            $('#patient_select').select2('destroy');
        }
    });

    // Add/Remove services
    $('#add-service-btn').on('click', function() {
        addServiceItem();
    });

    function addServiceItem(serviceData = null) {
        const template = document.getElementById('service-item-template');
        const clone = template.content.cloneNode(true);

        // Set index
        const index = serviceCounter++;
        clone.querySelector('.item-row').setAttribute('data-index', index);

        // Populate services dropdown
        const select = clone.querySelector('.service-select');
        servicesData.forEach(service => {
            const option = document.createElement('option');
            option.value = service.id;
            option.textContent = service.name + ' - ' + formatCurrency(service.price) + ' VNĐ';
            option.setAttribute('data-price', service.price);
            select.appendChild(option);
        });

        // Set data if provided (for edit mode)
        if (serviceData) {
            select.value = serviceData.service_id;
            clone.querySelector('.service-quantity').value = serviceData.quantity;
            clone.querySelector('.service-price').value = formatCurrency(serviceData.price) + ' VNĐ';
            clone.querySelector('.service-price-value').value = serviceData.price;

            const total = serviceData.quantity * serviceData.price;
            clone.querySelector('.service-total').value = formatCurrency(total) + ' VNĐ';
        }

        $('#services-container').append(clone);
        updateTotalFee();
    }

    function removeServiceItem(button) {
        $(button).closest('.item-row').remove();
        updateTotalFee();
    }

    function updateServicePrice(select) {
        const row = $(select).closest('.item-row');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;

        row.find('.service-price').val(formatCurrency(price) + ' VNĐ');
        row.find('.service-price-value').val(price);

        calculateServiceTotal(row.find('.service-quantity')[0]);
    }

    function calculateServiceTotal(input) {
        const row = $(input).closest('.item-row');
        const quantity = parseInt(input.value) || 0;
        const price = parseFloat(row.find('.service-price-value').val()) || 0;
        const total = quantity * price;

        row.find('.service-total').val(formatCurrency(total) + ' VNĐ');
        updateTotalFee();
    }

    // Add/Remove medicines
    $('#add-medicine-btn').on('click', function() {
        addMedicineItem();
    });

    function addMedicineItem(medicineData = null) {
        const template = document.getElementById('medicine-item-template');
        const clone = template.content.cloneNode(true);

        // Set index
        const index = medicineCounter++;
        clone.querySelector('.item-row').setAttribute('data-index', index);

        // Populate medicines dropdown
        const select = clone.querySelector('.medicine-select');
        medicinesData.forEach(medicine => {
            const option = document.createElement('option');
            option.value = medicine.id;
            option.textContent = medicine.name + ' - ' + medicine.formatted_price + ' (Tồn: ' + medicine
                .current_stock + ')';
            option.setAttribute('data-price', medicine.sale_price);
            option.setAttribute('data-stock', medicine.current_stock);
            select.appendChild(option);
        });

        // Set data if provided (for edit mode)
        if (medicineData) {
            select.value = medicineData.medicine_id;
            clone.querySelector('.medicine-quantity').value = medicineData.quantity;
            clone.querySelector('.medicine-price').value = formatCurrency(medicineData.price) + ' VNĐ';
            clone.querySelector('input[name="medicines[][dosage]"]').value = medicineData.dosage || '';
            clone.querySelector('input[name="medicines[][note]"]').value = medicineData.note || '';

            const total = medicineData.quantity * medicineData.price;
            clone.querySelector('.medicine-total').value = formatCurrency(total) + ' VNĐ';

            // Show stock warning if needed
            const stock = parseInt(medicineData.current_stock) || 0;
            if (stock < medicineData.quantity) {
                const warning = clone.querySelector('.stock-warning');
                warning.style.display = 'block';
                warning.querySelector('.stock-quantity').textContent = stock;
            }
        }

        $('#medicines-container').append(clone);
        updateTotalFee();
    }

    function removeMedicineItem(button) {
        $(button).closest('.item-row').remove();
        updateTotalFee();
    }

    function updateMedicinePrice(select) {
        const row = $(select).closest('.item-row');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;

        row.find('.medicine-price').val(formatCurrency(price) + ' VNĐ');

        // Show/hide stock warning
        const warning = row.find('.stock-warning');
        const quantity = parseInt(row.find('.medicine-quantity').val()) || 1;

        if (stock < quantity) {
            warning.show();
            warning.find('.stock-quantity').text(stock);
        } else {
            warning.hide();
        }

        calculateMedicineTotal(row.find('.medicine-quantity')[0]);
    }

    function calculateMedicineTotal(input) {
        const row = $(input).closest('.item-row');
        const quantity = parseInt(input.value) || 0;
        const select = row.find('.medicine-select')[0];
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const total = quantity * price;

        row.find('.medicine-total').val(formatCurrency(total) + ' VNĐ');

        // Show/hide stock warning
        const warning = row.find('.stock-warning');
        if (stock < quantity && selectedOption.value) {
            warning.show();
            warning.find('.stock-quantity').text(stock);
        } else {
            warning.hide();
        }

        updateTotalFee();
    }

    // Calculate total fees
    function updateTotalFee() {
        let serviceFee = 0;
        let medicineFee = 0;

        // Calculate service fee
        $('#services-container .item-row').each(function() {
            const quantity = parseInt($(this).find('.service-quantity').val()) || 0;
            const price = parseFloat($(this).find('.service-price-value').val()) || 0;
            serviceFee += quantity * price;
        });

        // Calculate medicine fee
        $('#medicines-container .item-row').each(function() {
            const quantity = parseInt($(this).find('.medicine-quantity').val()) || 0;
            const select = $(this).find('.medicine-select')[0];
            const selectedOption = select.options[select.selectedIndex];
            const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            medicineFee += quantity * price;
        });

        const totalFee = serviceFee + medicineFee;

        // Update display
        $('#service-fee-display').text(formatCurrency(serviceFee) + ' VNĐ');
        $('#medicine-fee-display').text(formatCurrency(medicineFee) + ' VNĐ');
        $('#total-fee-display').text(formatCurrency(totalFee) + ' VNĐ');

        // Show/hide fee summary
        if (totalFee > 0) {
            $('#fee-summary').show();
        } else {
            $('#fee-summary').hide();
        }
    }

    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount);
    }

    // Add/Edit examination handlers
    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        form_reset();
        let modal = $('#kt_modal_examination_form');
        modal.find('.modal-title').text('Tạo phiếu khám mới');
        modal.find('input[name=id]').val('');
        $('#kt_modal_examination_submit .indicator-label').text('Hoàn thành khám');
    });

    $(document).on('click', '.btn-edit', function(e) {
        e.preventDefault();
        form_reset();

        let id = $(this).data('id');
        if (id) {
            console.log('Loading examination data for ID:', id);
            loadExaminationData(id);
        } else {
            notification('error', 'Lỗi', 'Không tìm thấy ID phiếu khám');
        }
    });

    function loadExaminationData(id) {
        const submitBtn = $('#kt_modal_examination_submit');
        submitBtn.attr('data-kt-indicator', 'on');
        submitBtn.prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.show', 'get-data') }}",
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                if (response.type === 'success') {
                    let data = response.data;
                    let modal = $('#kt_modal_examination_form');

                    // Set form values
                    modal.find('.modal-title').text('Sửa phiếu khám');
                    modal.find('input[name=id]').val(data.id);
                    modal.find('input[name=examination_date]').val(data.examination_date);
                    modal.find('textarea[name=symptoms]').val(data.symptoms || '');
                    modal.find('textarea[name=diagnosis]').val(data.diagnosis || '');
                    modal.find('textarea[name=treatment_plan]').val(data.treatment_plan || '');
                    modal.find('input[name=next_appointment]').val(data.next_appointment || '');
                    modal.find('textarea[name=notes]').val(data.notes || '');

                    // Show edit mode fields
                    $('#edit-mode-fields').show();
                    modal.find('select[name=payment_status]').val(data.payment_status);
                    $('#kt_modal_examination_submit .indicator-label').text('Cập nhật');

                    // Set patient
                    if (data.patient) {
                        let option = new Option(
                            data.patient.full_name + ' (' + data.patient.phone + ')',
                            data.patient.id,
                            true,
                            true
                        );
                        $('#patient_select').append(option);

                        selectedPatientData = {
                            id: data.patient.id,
                            patient_code: data.patient_code,
                            full_name: data.patient.full_name,
                            phone: data.patient.phone
                        };

                        $('#selected-patient-code').text(data.patient_code);
                        $('#selected-patient-phone').text(data.patient.phone);
                        $('#patient-info-display').show();
                    }

                    // Add services
                    if (data.services && data.services.length > 0) {
                        data.services.forEach(service => {
                            addServiceItem(service);
                        });
                    }

                    // Add medicines
                    if (data.medicines && data.medicines.length > 0) {
                        data.medicines.forEach(medicine => {
                            addMedicineItem(medicine);
                        });
                    }

                    updateTotalFee();

                    console.log('Examination data loaded successfully:', data);
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
                console.error('Error loading examination data:', xhr);
            },
            complete: function() {
                submitBtn.removeAttr('data-kt-indicator');
                submitBtn.prop('disabled', false);
            }
        });
    }

    // Form submission
    $('#kt_modal_examination_form').on('submit', function(e) {
        e.preventDefault();

        // Validate required fields
        if (!selectedPatientData) {
            notification('error', 'Lỗi', 'Vui lòng chọn bệnh nhân');
            return;
        }

        // Prepare form data
        let formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        // Basic fields
        formData.append('patient_id', selectedPatientData.id);
        formData.append('examination_date', $('input[name="examination_date"]').val());
        formData.append('symptoms', $('textarea[name="symptoms"]').val());
        formData.append('diagnosis', $('textarea[name="diagnosis"]').val());
        formData.append('treatment_plan', $('textarea[name="treatment_plan"]').val());
        formData.append('next_appointment', $('input[name="next_appointment"]').val());
        formData.append('notes', $('textarea[name="notes"]').val());

        // Payment status for edit mode
        if ($('#edit-mode-fields').is(':visible')) {
            formData.append('payment_status', $('select[name="payment_status"]').val());
        }

        // Services
        const services = [];
        $('#services-container .item-row').each(function() {
            const serviceId = $(this).find('.service-select').val();
            const quantity = parseInt($(this).find('.service-quantity').val()) || 0;
            const price = parseFloat($(this).find('.service-price-value').val()) || 0;

            if (serviceId && quantity > 0) {
                services.push({
                    service_id: serviceId,
                    quantity: quantity,
                    price: price
                });
            }
        });
        formData.append('services', JSON.stringify(services));

        // Medicines
        const medicines = [];
        $('#medicines-container .item-row').each(function() {
            const medicineId = $(this).find('.medicine-select').val();
            const quantity = parseInt($(this).find('.medicine-quantity').val()) || 0;
            const dosage = $(this).find('input[name="medicines[][dosage]"]').val();
            const note = $(this).find('input[name="medicines[][note]"]').val();

            if (medicineId && quantity > 0) {
                medicines.push({
                    medicine_id: medicineId,
                    quantity: quantity,
                    dosage: dosage,
                    note: note
                });
            }
        });
        formData.append('medicines', JSON.stringify(medicines));

        // Determine URL and method
        let url = "{{ route('examination.store') }}";
        let method = 'POST';
        const id = $('input[name="id"]').val();

        if (parseInt(id)) {
            formData.append('_method', 'PUT');
            url = "{{ route('examination.update', ':id') }}".replace(':id', id);
        }

        // Show loading
        $('#kt_modal_examination_submit').attr('data-kt-indicator', 'on');
        $('#kt_modal_examination_submit').prop('disabled', true);

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                notification(data.type, data.title, data.content);
                if (data.type == 'success') {
                    dt.ajax.reload(null, false);
                    $('#kt_modal_examination_form').trigger('reset');
                    $('#kt_modal_examination').modal('hide');
                    loadStatistics();

                    // Show success message with QR option for new examinations
                    if (data.examination_id && !parseInt(id)) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: 'Phiếu khám đã được tạo. Bạn có muốn tạo mã QR thanh toán không?',
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonText: 'Tạo QR',
                            cancelButtonText: 'Bỏ qua'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Trigger QR generation
                                // We need to reload the table first to get the new examination data
                                setTimeout(() => {
                                    $(`[data-id="${data.examination_id}"].btn-payment-qr`)
                                        .click();
                                }, 1000);
                            }
                        });
                    }
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
                console.error('Error saving examination:', xhr);
            },
            complete: function() {
                $('#kt_modal_examination_submit').removeAttr('data-kt-indicator');
                $('#kt_modal_examination_submit').prop('disabled', false);
            }
        });
    });

    // Delete examination
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');

        Swal.fire({
            text: "Bạn có muốn xóa phiếu khám này không?",
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
                    url: "{{ route('examination.destroy', ':id') }}".replace(':id', id),
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
                        let message = 'Có lỗi xảy ra khi xóa phiếu khám';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        notification('error', 'Lỗi', message);
                        console.error('Error deleting examination:', xhr);
                    }
                });
            }
        });
    });

    // Payment QR Modal handlers
    $(document).on('click', '.btn-payment-qr', function(e) {
        e.preventDefault();

        const id = $(this).data('id');
        const examinationCode = $(this).data('examination-code');
        const patientName = $(this).data('patient-name');
        const totalFee = $(this).data('total-fee');

        // Set payment info
        $('#payment-examination-code').text(examinationCode);
        $('#payment-patient-name').text(patientName);
        $('#payment-amount').text(totalFee);

        // Store examination ID for later use
        $('#kt_modal_payment_qr').data('examination-id', id);

        // Reset modal state
        $('#qr-code-section').hide();
        $('#no-qr-section').show();
        $('#check-payment-btn').hide();
        $('#test-callback-btn').hide();

        // Show modal
        $('#kt_modal_payment_qr').modal('show');
    });

    // Generate QR Code
    $('#generate-qr-btn').on('click', function() {
        const examinationId = $('#kt_modal_payment_qr').data('examination-id');
        const btn = $(this);

        btn.attr('data-kt-indicator', 'on');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.generatePaymentQR', ':id') }}".replace(':id', examinationId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.type === 'success') {
                    $('#qr-code-image').attr('src', response.qr_code);
                    $('#no-qr-section').hide();
                    $('#qr-code-section').show();
                    $('#check-payment-btn').show();
                    $('#test-callback-btn').show();

                    notification(response.type, response.title, response.content);
                } else {
                    notification(response.type, response.title, response.content);
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi tạo mã QR';
                if (xhr.responseJSON && xhr.responseJSON.content) {
                    message = xhr.responseJSON.content;
                }
                notification('error', 'Lỗi', message);
            },
            complete: function() {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);
            }
        });
    });

    // Check Payment Status
    $('#check-payment-btn').on('click', function() {
        const examinationId = $('#kt_modal_payment_qr').data('examination-id');
        const btn = $(this);

        btn.attr('data-kt-indicator', 'on');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.checkPaymentStatus', ':id') }}".replace(':id', examinationId),
            type: 'GET',
            success: function(response) {
                notification(response.type, response.title, response.content);

                if (response.status === 'paid') {
                    $('#payment-status-pending').hide();
                    $('#payment-status-paid').show();
                    $('#check-payment-btn').hide();
                    $('#test-callback-btn').hide();

                    // Reload table to update payment status
                    dt.ajax.reload(null, false);
                    loadStatistics();
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi kiểm tra thanh toán';
                if (xhr.responseJSON && xhr.responseJSON.content) {
                    message = xhr.responseJSON.content;
                }
                notification('error', 'Lỗi', message);
            },
            complete: function() {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);
            }
        });
    });

    // Test Callback (for testing)
    $('#test-callback-btn').on('click', function() {
        const examinationId = $('#kt_modal_payment_qr').data('examination-id');
        const btn = $(this);

        btn.attr('data-kt-indicator', 'on');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.testCallbackSimulation', ':id') }}".replace(':id',
                examinationId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                notification(response.type, response.title, response.content);
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra khi test callback';
                if (xhr.responseJSON && xhr.responseJSON.content) {
                    message = xhr.responseJSON.content;
                }
                notification('error', 'Lỗi', message);
            },
            complete: function() {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);
            }
        });
    });

    // Search filters
    $(".search_table").on('change keyup', function() {
        let data = $(this).val();
        let filter = $(this).data('filter');

        if (filter === 'status') {
            status_filter = data;
        } else if (filter === 'payment') {
            payment_filter = data;
        } else if (filter === 'date') {
            date_filter = data;
        } else {
            search_table = data;
        }

        console.log('Search:', search_table, 'Status:', status_filter, 'Payment:', payment_filter, 'Date:',
            date_filter);
        dt.ajax.reload();
    });

    // Bulk delete
    $(document).on('click', '[data-kt-examination-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_examination_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " phiếu khám đã chọn không?",
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
                    url: "{{ route('examination.destroy', 'bulk') }}",
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
                        let message = 'Có lỗi xảy ra khi xóa các phiếu khám';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        notification('error', 'Lỗi', message);
                    }
                });
            }
        });
    });
</script>
