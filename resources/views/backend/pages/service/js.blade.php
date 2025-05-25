<script>
    // Private functions
    let search_table = '';
    let status_filter = '';
    let price_range_filter = '';

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
            url: "{{ route('service.show', 'get-list') }}",
            type: 'GET',
            data: function(d) {
                d.search_table = search_table;
                d.status_filter = status_filter;
                d.price_range_filter = price_range_filter;
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
                data: 'name',
                render: function(data, type, row, meta) {
                    let image = row.image ? 
                        '<img src="' + row.image + '" class="service-thumbnail" alt="' + data + '">' :
                        '<div class="service-thumbnail"><i class="fas fa-cogs"></i></div>';
                    
                    return '<div class="service-info">' +
                           image +
                           '<div class="service-details">' +
                           '<div class="service-title">' + data + '</div>' +
                           '<div class="service-description">' + (row.short_description || 'Chưa có mô tả') + '</div>' +
                           '</div>' +
                           '</div>';
                }
            },
            {
                data: 'formatted_price',
                render: function(data, type, row, meta) {
                    return '<span class="price-display">' + data + '</span>';
                }
            },
            {
                data: 'duration',
                render: function(data, type, row, meta) {
                    return data ? '<span class="duration-display">' + data + '</span>' : 
                           '<span class="text-muted"><em>Chưa xác định</em></span>';
                }
            },
            {
                data: 'is_active',
                render: function(data, type, row, meta) {
                    if (data == 1) {
                        return '<span class="status-badge status-active">Hoạt động</span>';
                    } else {
                        return '<span class="status-badge status-inactive">Tạm dừng</span>';
                    }
                }
            },
            {
                data: 'created_at',
                render: function(data, type, row, meta) {
                    return '<span class="date-display">' + formatDate(data) + '</span>';
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
                        '<a href="" data-data=\'' + JSON.stringify(row) +
                        '\' class="menu-link px-3 btn-edit" data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">Sửa</a> \n' +
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
            $('[data-kt-services-table-toolbar="base"]').addClass('d-none');
            $('[data-kt-services-table-toolbar="selected"]').removeClass('d-none');
            $('[data-kt-services-table-select="selected_count"]').text(checkedCount);
        } else {
            $('[data-kt-services-table-toolbar="base"]').removeClass('d-none');
            $('[data-kt-services-table-toolbar="selected"]').addClass('d-none');
        }
    }

    // Load statistics
    function loadStatistics() {
        $.ajax({
            url: "{{ route('service.show', 'get-statistics') }}",
            type: 'GET',
            success: function(data) {
                $('#total-services').text(data.total || 0);
                $('#active-services').text(data.active || 0);
                $('#inactive-services').text(data.inactive || 0);
                $('#average-price').text(data.average_price || '0 VNĐ');
            },
            error: function() {
                console.log('Error loading statistics');
            }
        });
    }

    // Format date helper
    function formatDate(dateString) {
        if (!dateString) return '';
        let date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
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
    }
    
    $(document).on('click', '.btn-edit', function(e) {
        console.log('edit')
        e.preventDefault();
        form_reset();
        let data = $(this).data('data');
        let modal = $('#kt_modal_add_customer_form');
        modal.find('.modal-title').text('Sửa thông tin dịch vụ');
        modal.find('input[name=id]').val(data.id);
        modal.find('input[name=name]').val(data.name);
        modal.find('input[name=slug]').val(data.slug);
        modal.find('textarea[name=description]').val(data.description);
        modal.find('textarea[name=content]').val(data.content);
        modal.find('input[name=price]').val(data.price);
        modal.find('input[name=duration]').val(data.duration);
        modal.find('input[name=image]').val(data.image);
        modal.find('input[name=is_active]').prop('checked', data.is_active == 1);
    });
    
    $(document).on('click', '.btn-add', function(e) {
        console.log('add')
        e.preventDefault();
        form_reset();
        let modal = $('#kt_modal_add_customer_form');
        modal.find('.modal-title').text('Thêm dịch vụ mới');
        modal.find('input[name=id]').val('');
        modal.trigger('reset');
    });
    
    $('#kt_modal_add_customer_form').on('submit', function(e) {
        e.preventDefault();
        let data = $(this).serialize(),
            type = 'POST',
            url = "{{ route('service.store') }}",
            id = $('form#kt_modal_add_customer_form input[name=id]').val();
        
        if (parseInt(id)) {
            console.log('edit');
            type = 'PUT';
            url = "{{ route('service.update', ':id') }}".replace(':id', id);
        }
        
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: type,
            data: data,
            success: function(data) {
                notification(data.type, data.title, data.content);
                if (data.type == 'success') {
                    dt.ajax.reload(null, false);
                    $('#kt_modal_add_customer_form').trigger('reset');
                    $('#kt_modal_add_customer').modal('hide');
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
    });
    
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        Swal.fire({
            text: "Bạn có muốn xóa dịch vụ này không?",
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
                    url: "{{ route('service.destroy', ':id') }}".replace(':id', id),
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

    // Handle bulk delete
    $(document).on('click', '[data-kt-services-table-select="delete_selected"]', function(e) {
        e.preventDefault();
        let selectedIds = [];
        $('#kt_customers_table tbody input[type="checkbox"]:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        Swal.fire({
            text: "Bạn có muốn xóa " + selectedIds.length + " dịch vụ đã chọn không?",
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
                    url: "{{ route('service.destroy', 'bulk') }}",
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
    
    $(".search_table").on('change keyup', function() {
        let data = $(this).val();
        let filter = $(this).data('filter');
        
        if (filter === 'status') {
            status_filter = data;
        } else if (filter === 'price_range') {
            price_range_filter = data;
        } else {
            search_table = data;
        }
        
        console.log('Search:', search_table, 'Status:', status_filter, 'Price Range:', price_range_filter);
        dt.ajax.reload();
    });
</script>