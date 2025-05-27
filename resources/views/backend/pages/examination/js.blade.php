<script>
    // Private functions
    let search_table = '';
    let status_filter = '';
    let payment_filter = '';
    let date_filter = '';
    let currentStep = 1;
    let maxStep = 4;
    let services = [];
    let medicines = [];
    let currentExaminationId = null;

    // Load statistics and options on page load
    $(document).ready(function() {
        loadStatistics();
        loadServices();
        loadMedicines();
    });

    var dt = $("#kt_examinations_table").DataTable({
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
                    html += '<code class="patient-code">' + data.code + '</code>';
                    html += '<div class="patient-phone">' + data.phone + '</div>';
                    html += '</div>';
                    return html;
                }
            },
            {
                data: 'diagnosis',
                render: function(data, type, row, meta) {
                    return data ? (data.length > 50 ? data.substring(0, 50) + '...' : data) : '-';
                }
            },
            {
                data: 'total_fee',
                render: function(data, type, row, meta) {
                    return '<span class="price-display">' + new Intl.NumberFormat('vi-VN').format(
                        data) + ' VNĐ</span>';
                }
            },
            {
                data: 'formatted_examination_date'
            },
            {
                data: 'formatted_next_appointment'
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

                    if (row.payment_status === 'pending') {
                        actions += '<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id +
                            '" class="menu-link px-3 btn-generate-qr">Tạo QR thanh toán</a> \n' +
                            '</div> \n';
                    }

                    actions += '<div class="menu-item px-3"> \n' +
                        '<a href="" data-data=\'' + JSON.stringify(row) +
                        '\' class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_examination">Sửa</a> \n' +
                        '</div> \n';

                    if (row.payment_status !== 'paid') {
                        actions += '<div class="menu-item px-3"> \n' +
                            '<a href="#" data-id="' + row.id +
                            '" class="menu-link px-3 btn-delete" data-kt-examination-table-filter="delete_row">Xóa</a> \n' +
                            '</div> \n';
                    }

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

    // Handle bulk selection
    $('[data-kt-check="true"]').on('change', function() {
        var isChecked = $(this).is(':checked');
        $('#kt_examinations_table tbody input[type="checkbox"]').prop('checked', isChecked);
        updateBulkActions();
    });

    $(document).on('change', '#kt_examinations_table tbody input[type="checkbox"]', function() {
        updateBulkActions();
    });

    function updateBulkActions() {
        var checkedCount = $('#kt_examinations_table tbody input[type="checkbox"]:checked').length;

        if (checkedCount > 0) {
            $('[data-kt-examinations-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-examinations-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-examinations-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-examinations-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-examinations-table-toolbar="selected"]').addClass('d-none');
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
                                        <span class="symbol-label bg-light-warning">
                                            <i class="fas fa-clock text-warning fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.pending || 0}</span>
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
                                        <span class="symbol-label bg-light-info">
                                            <i class="fas fa-dollar-sign text-info fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.today_revenue || '0 VNĐ'}</span>
                                        <span class="text-muted fw-bold fs-7">DT hôm nay</span>
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
                                            <i class="fas fa-chart-line text-primary fs-2x"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bolder fs-2">${data.month_revenue || '0 VNĐ'}</span>
                                        <span class="text-muted fw-bold fs-7">DT tháng này</span>
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

    // Load services and medicines
    function loadServices() {
        $.ajax({
            url: "{{ route('service.show', 'get-list') }}",
            type: 'GET',
            success: function(response) {
                services = response.data || [];
                updateServiceOptions();
            }
        });
    }

    function loadMedicines() {
        $.ajax({
            url: "{{ route('medicine.show', 'get-list') }}",
            type: 'GET',
            success: function(response) {
                medicines = response.data || [];
                updateMedicineOptions();
            }
        });
    }

    function updateServiceOptions() {
        let options = '<option value="">-- Chọn dịch vụ --</option>';
        services.forEach(function(service) {
            if (service.is_active) {
                options +=
                    `<option value="${service.id}" data-price="${service.price}">${service.name} - ${new Intl.NumberFormat('vi-VN').format(service.price)} VNĐ</option>`;
            }
        });
        $('.service-select').html(options);
    }

    function updateMedicineOptions() {
        let options = '<option value="">-- Chọn thuốc --</option>';
        medicines.forEach(function(medicine) {
            if (medicine.is_active) {
                options +=
                    `<option value="${medicine.id}" data-price="${medicine.price}">${medicine.name} (${medicine.code}) - ${new Intl.NumberFormat('vi-VN').format(medicine.price)} VNĐ</option>`;
            }
        });
        $('.medicine-select').html(options);
    }

    // Step navigation
    function showStep(step) {
        $('.step-content').removeClass('active');
        $('.step-item').removeClass('active completed');

        for (let i = 1; i < step; i++) {
            $('.step-item[data-step="' + i + '"]').addClass('completed');
        }

        $('.step-item[data-step="' + step + '"]').addClass('active');
        $('#step-' + step).addClass('active');
        currentStep = step;

        // Update navigation buttons
        if (step === 1) {
            $('#prev-step').hide();
        } else {
            $('#prev-step').show();
        }

        if (step === maxStep) {
            $('#next-step').hide();
            $('#finish-examination').show();
        } else {
            $('#next-step').show();
            $('#finish-examination').hide();
        }
    }

    $('#next-step').on('click', function() {
        if (validateCurrentStep()) {
            if (currentStep < maxStep) {
                showStep(currentStep + 1);
                if (currentStep === 4) {
                    calculateTotalFees();
                }
            }
        }
    });

    $('#prev-step').on('click', function() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    function validateCurrentStep() {
        // Basic validation for each step
        switch (currentStep) {
            case 1:
                if (!$('input[name="patient_id"]').val() && !$('input[name="patient_name"]').val()) {
                    notification('error', 'Lỗi', 'Vui lòng chọn hoặc nhập thông tin bệnh nhân');
                    return false;
                }
                if (!$('input[name="patient_id"]').val() && !$('input[name="patient_phone"]').val()) {
                    notification('error', 'Lỗi', 'Vui lòng nhập số điện thoại bệnh nhân');
                    return false;
                }
                break;
            case 3:
                if (!$('textarea[name="diagnosis"]').val()) {
                    notification('error', 'Lỗi', 'Vui lòng nhập chuẩn đoán');
                    return false;
                }
                break;
        }
        return true;
    }

    // Patient search
    let searchTimeout;
    $('#patient-search').on('input', function() {
        clearTimeout(searchTimeout);
        let search = $(this).val();

        if (search.length < 2) {
            $('#patient-search-results').hide();
            return;
        }

        searchTimeout = setTimeout(function() {
            searchPatients(search);
        }, 500);
    });

    function searchPatients(search) {
        $.ajax({
            url: "{{ route('patient.show', 'search') }}",
            type: 'GET',
            data: {
                search: search
            },
            success: function(patients) {
                let html = '';
                if (patients.length > 0) {
                    html += '<div class="border rounded p-3">';
                    html += '<h6 class="mb-3">Kết quả tìm kiếm:</h6>';
                    patients.forEach(function(patient) {
                        html +=
                            `<div class="patient-result p-2 border-bottom cursor-pointer" data-patient='${JSON.stringify(patient)}'>`;
                        html += `<div class="fw-bold">${patient.full_name}</div>`;
                        html +=
                            `<div class="text-muted fs-7">${patient.patient_code} - ${patient.phone}</div>`;
                        if (patient.address) {
                            html += `<div class="text-muted fs-8">${patient.address}</div>`;
                        }
                        html += '</div>';
                    });
                    html += '</div>';

                    $('#patient-search-results').html(html).show();
                } else {
                    $('#patient-search-results').html(
                        '<div class="alert alert-info">Không tìm thấy bệnh nhân nào</div>').show();
                }
            }
        });
    }

    // Select patient from search results
    $(document).on('click', '.patient-result', function() {
        let patient = $(this).data('patient');
        selectPatient(patient);
    });

    function selectPatient(patient) {
        $('input[name="patient_id"]').val(patient.id);
        $('input[name="patient_name"]').val(patient.full_name);
        $('input[name="patient_phone"]').val(patient.phone);
        $('input[name="patient_address"]').val(patient.address || '');
        $('input[name="patient_dob"]').val(patient.date_of_birth || '');
        $('select[name="patient_gender"]').val(patient.gender || '');
        $('input[name="patient_citizen_id"]').val(patient.citizen_id || '');

        $('#patient-search').val(patient.full_name + ' - ' + patient.phone);
        $('#patient-search-results').hide();

        // Disable form fields since patient is selected
        $('input[name="patient_name"], input[name="patient_phone"], input[name="patient_address"], input[name="patient_dob"], select[name="patient_gender"], input[name="patient_citizen_id"]')
            .prop('disabled', true);
    }

    // Clear patient selection
    function clearPatientSelection() {
        $('input[name="patient_id"]').val('');
        $('#patient-search').val('');
        $('#patient-search-results').hide();
        $('input[name="patient_name"], input[name="patient_phone"], input[name="patient_address"], input[name="patient_dob"], select[name="patient_gender"], input[name="patient_citizen_id"]')
            .prop('disabled', false).val('');
        $('select[name="patient_gender"]').val('');
    }

    // Add service
    $('#add-service').on('click', function() {
        let template = $('#service-item-template').html();
        let serviceItem = $(template);
        $('#services-container').append(serviceItem);
        updateServiceOptions();
    });

    // Remove service
    $(document).on('click', '.remove-service', function() {
        $(this).closest('.service-item').remove();
        calculateTotalFees();
    });

    // Service change handlers
    $(document).on('change', '.service-select', function() {
        let price = $(this).find('option:selected').data('price') || 0;
        $(this).closest('.service-item').find('.service-price').val(price);
        calculateServiceTotal($(this).closest('.service-item'));
    });

    $(document).on('input', '.service-quantity, .service-price', function() {
        calculateServiceTotal($(this).closest('.service-item'));
    });

    function calculateServiceTotal(serviceItem) {
        let quantity = parseInt(serviceItem.find('.service-quantity').val()) || 0;
        let price = parseInt(serviceItem.find('.service-price').val()) || 0;
        let total = quantity * price;
        serviceItem.find('.service-total').text(new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ');
        calculateTotalFees();
    }

    // Add medicine
    $('#add-medicine').on('click', function() {
        let template = $('#medicine-item-template').html();
        let medicineItem = $(template);
        $('#medicines-container').append(medicineItem);
        updateMedicineOptions();
    });

    // Remove medicine
    $(document).on('click', '.remove-medicine', function() {
        $(this).closest('.medicine-item').remove();
        calculateTotalFees();
    });

    // Medicine change handlers
    $(document).on('change', '.medicine-select', function() {
        let price = $(this).find('option:selected').data('price') || 0;
        $(this).closest('.medicine-item').find('.medicine-price').val(price);
        calculateMedicineTotal($(this).closest('.medicine-item'));
    });

    $(document).on('input', '.medicine-quantity, .medicine-price', function() {
        calculateMedicineTotal($(this).closest('.medicine-item'));
    });

    function calculateMedicineTotal(medicineItem) {
        let quantity = parseInt(medicineItem.find('.medicine-quantity').val()) || 0;
        let price = parseInt(medicineItem.find('.medicine-price').val()) || 0;
        let total = quantity * price;
        medicineItem.find('.medicine-total').text(new Intl.NumberFormat('vi-VN').format(total) + ' VNĐ');
        calculateTotalFees();
    }

    // Calculate total fees
    function calculateTotalFees() {
        let serviceFee = 0;
        let medicineFee = 0;

        $('.service-item').each(function() {
            let quantity = parseInt($(this).find('.service-quantity').val()) || 0;
            let price = parseInt($(this).find('.service-price').val()) || 0;
            serviceFee += quantity * price;
        });

        $('.medicine-item').each(function() {
            let quantity = parseInt($(this).find('.medicine-quantity').val()) || 0;
            let price = parseInt($(this).find('.medicine-price').val()) || 0;
            medicineFee += quantity * price;
        });

        let totalFee = serviceFee + medicineFee;

        $('#total-service-fee').text(new Intl.NumberFormat('vi-VN').format(serviceFee) + ' VNĐ');
        $('#total-medicine-fee').text(new Intl.NumberFormat('vi-VN').format(medicineFee) + ' VNĐ');
        $('#total-fee').text(new Intl.NumberFormat('vi-VN').format(totalFee) + ' VNĐ');
        $('#final-total-fee').text(new Intl.NumberFormat('vi-VN').format(totalFee) + ' VNĐ');
    }

    // Form submission
    $('#kt_modal_add_examination_form').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData();
        let data = $(this).serializeArray();

        // Basic form data
        data.forEach(function(item) {
            formData.append(item.name, item.value);
        });

        // Services data
        let servicesData = [];
        $('.service-item').each(function() {
            let serviceId = $(this).find('.service-select').val();
            if (serviceId) {
                servicesData.push({
                    service_id: parseInt(serviceId),
                    quantity: parseInt($(this).find('.service-quantity').val()) || 1,
                    price: parseInt($(this).find('.service-price').val()) || 0
                });
            }
        });
        formData.append('services', JSON.stringify(servicesData));

        // Medicines data
        let medicinesData = [];
        $('.medicine-item').each(function() {
            let medicineId = $(this).find('.medicine-select').val();
            if (medicineId) {
                medicinesData.push({
                    medicine_id: parseInt(medicineId),
                    quantity: parseInt($(this).find('.medicine-quantity').val()) || 1,
                    dosage: $(this).find('.medicine-dosage').val() || '',
                    note: $(this).find('.medicine-note').val() || '',
                    price: parseInt($(this).find('.medicine-price').val()) || 0
                });
            }
        });
        formData.append('medicines', JSON.stringify(medicinesData));

        let url = "{{ route('examination.store') }}";
        let method = 'POST';

        // Show loading
        $('#finish-examination').attr('data-kt-indicator', 'on');
        $('#finish-examination').prop('disabled', true);

        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Form submission response:', response); // Debug log

                notification(response.type, response.title, response.content);
                if (response.type === 'success' && response.data) {
                    // ← ĐÂY LÀ CHỖ QUAN TRỌNG: Set currentExaminationId
                    currentExaminationId = response.data.examination_id;

                    console.log('Set currentExaminationId to:', currentExaminationId); // Debug log

                    $('#examination-code-display').text(response.data.examination_code);

                    dt.ajax.reload(null, false);
                    loadStatistics();

                    // Show step 4 (payment step)
                    showStep(4);

                    // Enable generate QR button
                    $('#generate-qr').prop('disabled', false).show();
                }
            },
            error: function(xhr) {
                console.error('Form submission error:', xhr); // Debug log

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    $('.print-error-msg ul').html(errorHtml);
                    $('.print-error-msg').show();
                } else {
                    notification('error', 'Lỗi', 'Có lỗi xảy ra khi tạo phiếu khám');
                }
            },
            complete: function() {
                $('#finish-examination').removeAttr('data-kt-indicator');
                $('#finish-examination').prop('disabled', false);
            }
        });
    });

    // Generate QR Code
    $('#generate-qr').on('click', function() {
        console.log('Generate QR clicked, currentExaminationId:', currentExaminationId);

        if (!currentExaminationId) {
            console.error('currentExaminationId is null!');
            notification('error', 'Lỗi', 'Không tìm thấy ID phiếu khám. Vui lòng thử lại.');
            return;
        }

        $(this).attr('data-kt-indicator', 'on');
        $(this).prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.generatePaymentQR', ':id') }}".replace(':id',
                currentExaminationId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('QR response:', response);

                if (response.type === 'success') {
                    showQRCode(response.data);
                } else {
                    notification(response.type, response.title, response.content);
                }
            },
            error: function(xhr) {
                console.error('QR generation error:', xhr);
                notification('error', 'Lỗi', 'Không thể tạo mã QR thanh toán');
            },
            complete: function() {
                $('#generate-qr').removeAttr('data-kt-indicator');
                $('#generate-qr').prop('disabled', false);
            }
        });
    });

    // Show QR Code function
    function showQRCode(data) {
        console.log('showQRCode called with data:', data);

        $('#qr-code-section').show();

        // Xử lý QR code image
        let qrCodeSrc = data.qr_code;
        if (data.qr_string && !data.qr_code.startsWith('http') && !data.qr_code.startsWith('data:image')) {
            // Tạo QR code từ string
            qrCodeSrc =
                `https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${encodeURIComponent(data.qr_string)}`;
        }

        $('#qr-code-image').attr('src', qrCodeSrc);
        $('#bank-name').text(data.bank_name || 'VietQR');
        $('#account-number').text(data.account_no || '');
        $('#account-name').text(data.account_name || '');
        $('#transfer-content').text(data.content || '');
        $('#transfer-amount').text(data.amount || '');

        // Thêm nút test payment trong môi trường development
        if (isDevelopmentEnvironment()) {
            if (!$('#test-payment-btn').length) {
                $('#qr-code-section').append(`
                <div class="mt-3">
                    <button type="button" class="btn btn-warning btn-sm" id="test-payment-btn">
                        <span class="indicator-label">
                            <i class="fas fa-flask"></i> Test Payment (Dev Mode)
                        </span>
                        <span class="indicator-progress">
                            Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <div class="text-muted fs-8 mt-2">
                        <i class="fas fa-info-circle"></i> Nút này chỉ xuất hiện trong môi trường development
                    </div>
                </div>
            `);
            }
        }

        // Bắt đầu auto-check payment status
        startPaymentStatusCheck();
    }


    // Check payment status
    let paymentCheckInterval;
    let paymentCheckCount = 0;
    const MAX_PAYMENT_CHECK = 60; // Check tối đa 60 lần (5 phút)

    function startPaymentStatusCheck() {
        if (!currentExaminationId) return;

        paymentCheckCount = 0;

        // Check ngay lập tức
        checkPaymentStatus();

        // Set interval check mỗi 5 giây
        paymentCheckInterval = setInterval(function() {
            paymentCheckCount++;

            if (paymentCheckCount >= MAX_PAYMENT_CHECK) {
                stopPaymentStatusCheck();
                showPaymentTimeout();
                return;
            }

            checkPaymentStatus();
        }, 5000);
    }

    function stopPaymentStatusCheck() {
        if (paymentCheckInterval) {
            clearInterval(paymentCheckInterval);
            paymentCheckInterval = null;
        }
    }

    $('#check-payment').on('click', function() {
        checkPaymentStatus();
    });

    function checkPaymentStatus() {
        if (!currentExaminationId) return;

        $.ajax({
            url: "{{ route('examination.checkPaymentStatus', ':id') }}".replace(':id', currentExaminationId),
            type: 'GET',
            success: function(response) {
                if (response.type === 'success' && response.data) {
                    if (response.data.is_paid) {
                        // Thanh toán thành công
                        $('#payment-success').show();
                        stopPaymentStatusCheck();

                        // Cập nhật UI
                        notification('success', 'Thành công', 'Thanh toán đã được xác nhận!');
                        dt.ajax.reload(null, false);
                        loadStatistics();

                        // Auto close modal sau 3 giây
                        setTimeout(function() {
                            $('#kt_modal_add_examination').modal('hide');
                            resetExaminationForm();
                        }, 3000);
                    }
                }
            },
            error: function() {
                console.log('Error checking payment status');
            }
        });
    }

    function showPaymentTimeout() {
        $('#qr-code-section').append(`
        <div class="alert alert-warning mt-3">
            <i class="fas fa-clock text-warning me-2"></i>
            <strong>Hết thời gian kiểm tra tự động</strong><br>
            Vui lòng click "Kiểm tra thanh toán" để kiểm tra thủ công hoặc liên hệ hỗ trợ.
        </div>
    `);
    }

    function isDevelopmentEnvironment() {
        return window.location.hostname === 'pksaigon.test' ||
            window.location.hostname === '127.0.0.1' ||
            window.location.hostname.includes('dev') ||
            window.location.hostname.includes('test') ||
            window.location.port !== '';
    }

    // Generate QR from table
    $(document).on('click', '.btn-generate-qr', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        console.log('id', id);
        if (!id) {
            notification('error', 'Lỗi', 'Không tìm thấy ID phiếu khám');
            return;
        }

        $(this).html('<i class="fas fa-spinner fa-spin"></i> Đang tạo...');
        if (isDevelopmentEnvironment()) {
            console.log('Development environment detected, adding test payment button');
            
            $('#qr-code-display').append(`
                <div class="mt-3">
                    <button type="button" class="btn btn-warning btn-sm" id="test-payment-btn">
                        <span class="indicator-label">
                            <i class="fas fa-flask"></i> Test Payment (Dev Mode)
                        </span>
                        <span class="indicator-progress">
                            Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <div class="text-muted fs-8 mt-2">
                        <i class="fas fa-info-circle"></i> Nút này chỉ xuất hiện trong môi trường development
                    </div>
                </div>
            `);

        }
        $.ajax({
            url: "{{ route('examination.generatePaymentQR', ':id') }}".replace(':id', id),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.type === 'success') {
                    showPaymentModal(id, response.data);
                } else {
                    notification(response.type, response.title, response.content);
                }
            },
            error: function() {
                notification('error', 'Lỗi', 'Không thể tạo mã QR thanh toán');
            },
            complete: function() {
                dt.ajax.reload(null, false);
            }
        });

    });
    $(document).on('click', '#test-payment-btn', function() {
        if (!currentExaminationId) return;

        $(this).attr('data-kt-indicator', 'on');
        $(this).prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.testPayment', ':id') }}".replace(':id', currentExaminationId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                notification(response.type, response.title, response.content);
                if (response.type === 'success') {
                    // Test thành công, sẽ được auto-check bắt lên
                    checkPaymentStatus();
                }
            },
            error: function() {
                notification('error', 'Lỗi', 'Không thể thực hiện test payment');
            },
            complete: function() {
                $('#test-payment-btn').removeAttr('data-kt-indicator');
                $('#test-payment-btn').prop('disabled', false);
            }
        });
    });

    function showPaymentModal(examinationId, data) {
        currentExaminationId = examinationId;

        $('#payment-examination-code').text(data.examination_code || '');
        $('#payment-total-amount').text(data.amount);

        // Xử lý QR code tương tự như trên
        let qrCodeSrc = data.qr_code;
        if (data.qr_code.startsWith('http')) {
            if (data.qr_string) {
                qrCodeSrc =
                    `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(data.qr_string)}`;
            }
        } else if (!data.qr_code.startsWith('data:image') && !data.qr_code.startsWith('http')) {
            qrCodeSrc =
                `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(data.qr_code)}`;
        }

        $('#payment-qr-image').attr('src', qrCodeSrc);
        $('#payment-bank-name').text(data.bank_name);
        $('#payment-account-number').text(data.account_no);
        $('#payment-account-name').text(data.account_name);
        $('#payment-transfer-content').text(data.content);

        // Thêm nút test payment trong modal
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
            if (!$('#modal-test-payment-btn').length) {
                $('.modal-footer').prepend(`
                <button type="button" class="btn btn-warning me-3" id="modal-test-payment-btn">
                    <i class="fas fa-test-tube"></i> Test Payment
                </button>
            `);
            }
        }

        $('#kt_modal_payment_qr').modal('show');
        startModalPaymentCheck();
    }
    $(document).on('click', '#modal-test-payment-btn', function() {
        if (!currentExaminationId) return;

        $(this).attr('data-kt-indicator', 'on');
        $(this).prop('disabled', true);

        $.ajax({
            url: "{{ route('examination.testPayment', ':id') }}".replace(':id', currentExaminationId),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.type === 'success') {
                    $('#payment-status').hide();
                    $('#payment-success-status').show();
                    stopModalPaymentCheck();

                    setTimeout(function() {
                        $('#kt_modal_payment_qr').modal('hide');
                        $('#success-examination-code').text($('#payment-examination-code')
                            .text());
                        $('#kt_modal_payment_success').modal('show');
                        dt.ajax.reload(null, false);
                        loadStatistics();
                    }, 2000);
                } else {
                    notification(response.type, response.title, response.content);
                }
            },
            error: function() {
                notification('error', 'Lỗi', 'Không thể thực hiện test payment');
            },
            complete: function() {
                $(this).removeAttr('data-kt-indicator');
                $(this).prop('disabled', false);
            }
        });
    });
    let modalPaymentCheckInterval;

    function startModalPaymentCheck() {
        modalPaymentCheckInterval = setInterval(function() {
            checkModalPaymentStatus();
        }, 5000);
    }

    function stopModalPaymentCheck() {
        if (modalPaymentCheckInterval) {
            clearInterval(modalPaymentCheckInterval);
        }
    }

    $('#check-payment-status').on('click', function() {
        checkModalPaymentStatus();
    });
    $('#kt_modal_add_examination').on('hidden.bs.modal', function() {
        stopPaymentStatusCheck();
    });

    function checkModalPaymentStatus() {
        if (!currentExaminationId) return;

        $('#check-payment-status').attr('data-kt-indicator', 'on');

        $.ajax({
            url: "{{ route('examination.checkPaymentStatus', ':id') }}".replace(':id', currentExaminationId),
            type: 'GET',
            success: function(response) {
                if (response.data.payment_status === 'paid') {
                    $('#payment-status').hide();
                    $('#payment-success-status').show();
                    stopModalPaymentCheck();

                    setTimeout(function() {
                        $('#kt_modal_payment_qr').modal('hide');
                        $('#success-examination-code').text($('#payment-examination-code').text());
                        $('#kt_modal_payment_success').modal('show');
                        dt.ajax.reload(null, false);
                        loadStatistics();
                    }, 2000);
                }
            },
            complete: function() {
                $('#check-payment-status').removeAttr('data-kt-indicator');
            }
        });
    }

    // Modal events
    $('#kt_modal_payment_qr').on('hidden.bs.modal', function() {
        stopModalPaymentCheck();
    });

    // Reset form
    function resetExaminationForm() {
        $('#kt_modal_add_examination_form')[0].reset();
        $('.print-error-msg').hide();
        $('#services-container').empty();
        $('#medicines-container').empty();
        $('#qr-code-section').hide();
        $('#payment-success').hide();
        currentStep = 1;
        currentExaminationId = null;
        showStep(1);
        clearPatientSelection();
        stopPaymentStatusCheck();
    }

    $('.btn-close, #examination-cancel').on('click', function() {
        resetExaminationForm();
        $('#kt_modal_add_examination').modal('hide');
    });

    $(document).on('click', '.btn-add', function(e) {
        e.preventDefault();
        resetExaminationForm();
        $('.modal-title').text('Tạo phiếu khám mới');
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
                        if (data.type === 'success') {
                            dt.ajax.reload(null, false);
                            loadStatistics();
                        }
                    },
                    error: function(data) {
                        notification('error', 'Lỗi', 'Có lỗi xảy ra khi xóa');
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
        } else if (filter === 'payment') {
            payment_filter = data;
        } else if (filter === 'date') {
            date_filter = data;
        } else {
            search_table = data;
        }

        dt.ajax.reload();
    });
</script>
