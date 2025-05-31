<script>
    $(document).ready(function() {
        // Load tất cả dữ liệu khi trang được tải
        loadOverviewStats();
        loadImportTrends();
        loadTopMedicines();
        loadExpiryReport();
        loadTypeStatistics();
    });

    // Biến global để lưu charts
    let medicineTypesChart, importTrendsChart;

    /**
     * Load thống kê tổng quan
     */
    function loadOverviewStats() {
        $.ajax({
            url: '/admin/medicine-statistics/overview',
            type: 'GET',
            success: function(response) {
                renderOverviewStats(response.overview, response.medicines_by_type);
            },
            error: function(xhr) {
                console.error('Error loading overview stats:', xhr);
                $('#overviewStatsContainer').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Không thể tải dữ liệu thống kê. Vui lòng thử lại.
                </div>
            `);
            }
        });
    }

    /**
     * Render thống kê tổng quan
     */
    function renderOverviewStats(overview, medicinesByType) {
        const html = `
        <div class="row g-5">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-primary p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary text-white me-4">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div>
                            <div class="stats-number text-primary">${overview.total_medicines}</div>
                            <div class="stats-label">Tổng số thuốc</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-success p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success text-white me-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="stats-number text-success">${overview.active_medicines}</div>
                            <div class="stats-label">Đang hoạt động</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-warning p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning text-white me-4">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="stats-number text-warning">${overview.expiring_soon}</div>
                            <div class="stats-label">Sắp hết hạn</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-danger p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-danger text-white me-4">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div>
                            <div class="stats-number text-danger">${overview.expired}</div>
                            <div class="stats-label">Đã hết hạn</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="separator my-6"></div>
        
        <div class="row g-5">
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-info p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info text-white me-4">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div>
                            <div class="stats-number text-info">${overview.total_imports}</div>
                            <div class="stats-label">Tổng phiếu nhập</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-primary p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary text-white me-4">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="stats-number text-primary">${overview.monthly_imports}</div>
                            <div class="stats-label">Nhập tháng này</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-success p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success text-white me-4">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div>
                            <div class="stats-number text-success" style="font-size: 1.5rem;">${overview.total_import_value}</div>
                            <div class="stats-label">Tổng giá trị nhập</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stats-card bg-light-warning p-4 rounded">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning text-white me-4">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div class="stats-number text-warning" style="font-size: 1.5rem;">${overview.monthly_import_value}</div>
                            <div class="stats-label">GT tháng này</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

        $('#overviewStatsContainer').html(html);

        // Render biểu đồ phân loại thuốc
        renderMedicineTypesChart(medicinesByType);
    }

    /**
     * Load xu hướng nhập kho
     */
    function loadImportTrends() {
        $.ajax({
            url: '/admin/medicine-statistics/import-trends',
            type: 'GET',
            success: function(response) {
                renderImportTrendsChart(response);
            },
            error: function(xhr) {
                console.error('Error loading import trends:', xhr);
            }
        });
    }

    /**
     * Load top thuốc nhập nhiều
     */
    function loadTopMedicines(period = 'all') {
        $.ajax({
            url: '/admin/medicine-statistics/top-medicines',
            type: 'GET',
            data: {
                period: period,
                limit: 10
            },
            success: function(response) {
                renderTopMedicines(response);
            },
            error: function(xhr) {
                console.error('Error loading top medicines:', xhr);
            }
        });
    }

    /**
     * Load báo cáo hạn sử dụng
     */
    function loadExpiryReport() {
        $.ajax({
            url: '/admin/medicine-statistics/expiry-report',
            type: 'GET',
            success: function(response) {
                renderExpiryReport(response);
            },
            error: function(xhr) {
                console.error('Error loading expiry report:', xhr);
            }
        });
    }

    /**
     * Load thống kê theo loại
     */
    function loadTypeStatistics() {
        $.ajax({
            url: '/admin/medicine-statistics/type-statistics',
            type: 'GET',
            success: function(response) {
                renderTypeStatistics(response);
            },
            error: function(xhr) {
                console.error('Error loading type statistics:', xhr);
            }
        });
    }

    /**
     * Render biểu đồ phân loại thuốc
     */
    function renderMedicineTypesChart(data) {
        const ctx = document.getElementById('medicineTypesChart').getContext('2d');

        // Destroy existing chart if exists
        if (medicineTypesChart) {
            medicineTypesChart.destroy();
        }

        const labels = [];
        const values = [];
        const colors = ['#007bff', '#28a745', '#ffc107'];

        // Convert data object to arrays
        if (data.supplement) {
            labels.push('TPCN');
            values.push(data.supplement);
        }
        if (data.medicine) {
            labels.push('Thuốc điều trị');
            values.push(data.medicine);
        }
        if (data.other) {
            labels.push('Khác');
            values.push(data.other);
        }

        medicineTypesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    /**
     * Render biểu đồ xu hướng nhập kho
     */
    function renderImportTrendsChart(data) {
        const ctx = document.getElementById('importTrendsChart').getContext('2d');

        // Destroy existing chart if exists
        if (importTrendsChart) {
            importTrendsChart.destroy();
        }

        const labels = data.map(item => item.month);
        const importCounts = data.map(item => item.total_imports);
        const importValues = data.map(item => item.total_value / 1000000); // Convert to millions

        importTrendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số lượng phiếu nhập',
                    data: importCounts,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: 'Giá trị nhập (triệu VNĐ)',
                    data: importValues,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Số lượng phiếu nhập'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Giá trị (triệu VNĐ)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    }

    /**
     * Render top thuốc nhập nhiều
     */
    function renderTopMedicines(data) {
        let html = '';

        if (data.length === 0) {
            html = '<div class="text-center text-muted py-4">Không có dữ liệu</div>';
        } else {
            html = '<div class="medicine-list">';
            data.forEach((medicine, index) => {
                const rankClass = index < 3 ? 'top-3' : '';
                const typeLabel = getTypeLabel(medicine.type);

                html += `
                <div class="medicine-item d-flex align-items-center">
                    <div class="medicine-rank ${rankClass} me-3">${index + 1}</div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark">${medicine.name}</div>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-light-${getTypeBadgeColor(medicine.type)} me-2">${typeLabel}</span>
                            <small class="text-muted">
                                ${parseInt(medicine.total_quantity).toLocaleString('vi-VN')} lần nhập • 
                                ${parseInt(medicine.total_value).toLocaleString('vi-VN')} VNĐ
                            </small>
                        </div>
                    </div>
                </div>
            `;
            });
            html += '</div>';
        }

        $('#topMedicinesContainer').html(html);
    }

    /**
     * Render báo cáo hạn sử dụng
     */
    function renderExpiryReport(data) {
        const totalExpired = data.expired.length;
        const totalExpiring7 = data.expiring_7_days.length;
        const totalExpiring30 = data.expiring_30_days.length;

        let html = `
        <div class="expiry-summary mb-4">
            <div class="row g-3">
                <div class="col-4">
                    <div class="text-center">
                        <div class="h4 text-danger mb-1">${totalExpired}</div>
                        <div class="small text-muted">Đã hết hạn</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <div class="h4 text-warning mb-1">${totalExpiring7}</div>
                        <div class="small text-muted">Hết hạn trong 7 ngày</div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="text-center">
                        <div class="h4 text-info mb-1">${totalExpiring30}</div>
                        <div class="small text-muted">Hết hạn trong 30 ngày</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="expiry-details" style="max-height: 300px; overflow-y: auto;">
    `;

        // Hiển thị một vài thuốc đã hết hạn
        if (totalExpired > 0) {
            html += '<div class="mb-3"><h6 class="text-danger">Đã hết hạn:</h6>';
            data.expired.slice(0, 3).forEach(medicine => {
                html += `
                <div class="expiry-item expiry-expired">
                    <div class="fw-bold">${medicine.name}</div>
                    <div class="small text-muted">
                        Hết hạn: ${formatDate(medicine.expiry_date)} • 
                        <span class="badge badge-light-${getTypeBadgeColor(medicine.type)}">${getTypeLabel(medicine.type)}</span>
                    </div>
                </div>
            `;
            });
            if (totalExpired > 3) {
                html += `<div class="small text-muted">và ${totalExpired - 3} thuốc khác...</div>`;
            }
            html += '</div>';
        }

        // Hiển thị thuốc sắp hết hạn trong 7 ngày
        if (totalExpiring7 > 0) {
            html += '<div class="mb-3"><h6 class="text-warning">Sắp hết hạn (7 ngày):</h6>';
            data.expiring_7_days.slice(0, 3).forEach(medicine => {
                html += `
                <div class="expiry-item expiry-warning">
                    <div class="fw-bold">${medicine.name}</div>
                    <div class="small text-muted">
                        Hết hạn: ${formatDate(medicine.expiry_date)} • 
                        <span class="badge badge-light-${getTypeBadgeColor(medicine.type)}">${getTypeLabel(medicine.type)}</span>
                    </div>
                </div>
            `;
            });
            if (totalExpiring7 > 3) {
                html += `<div class="small text-muted">và ${totalExpiring7 - 3} thuốc khác...</div>`;
            }
            html += '</div>';
        }

        html += '</div>';

        $('#expiryReportContainer').html(html);
    }

    /**
     * Render thống kê theo loại
     */
    function renderTypeStatistics(data) {
        let html = '<div class="row g-4">';

        data.forEach(typeData => {
            const totalPercent = typeData.total > 0 ? 100 : 0;
            const activePercent = typeData.total > 0 ? (typeData.active / typeData.total * 100).toFixed(1) : 0;
            const expiredPercent = typeData.total > 0 ? (typeData.expired / typeData.total * 100).toFixed(1) :
            0;

            html += `
            <div class="col-lg-4">
                <div class="type-stats-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-50px bg-light-${getTypeBadgeColor(typeData.type)} me-3">
                            <span class="symbol-label text-${getTypeBadgeColor(typeData.type)}">
                                <i class="${getTypeIcon(typeData.type)} fs-2x"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-1">${typeData.type_name}</h5>
                            <div class="text-muted fs-7">Tổng: ${typeData.total} thuốc</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fs-7">Đang hoạt động</span>
                            <span class="fs-7 fw-bold text-success">${typeData.active}</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom bg-success" style="width: ${activePercent}%"></div>
                        </div>
                    </div>
                    
                    ${typeData.expired > 0 ? `
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="fs-7">Đã hết hạn</span>
                            <span class="fs-7 fw-bold text-danger">${typeData.expired}</span>
                        </div>
                        <div class="progress-custom">
                            <div class="progress-bar-custom bg-danger" style="width: ${expiredPercent}%"></div>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="separator my-3"></div>
                    
                    <div class="row g-2 text-center">
                        <div class="col-4">
                            <div class="fs-6 fw-bold text-primary">${typeData.import_stats.import_count}</div>
                            <div class="fs-8 text-muted">Lần nhập</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-6 fw-bold text-info">${parseInt(typeData.import_stats.total_quantity).toLocaleString('vi-VN')}</div>
                            <div class="fs-8 text-muted">Số lượng</div>
                        </div>
                        <div class="col-4">
                            <div class="fs-6 fw-bold text-success">${formatCurrency(typeData.import_stats.total_value)}</div>
                            <div class="fs-8 text-muted">Giá trị</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        });

        html += '</div>';

        $('#typeStatisticsContainer').html(html);
    }

    /**
     * Helper functions
     */
    function getTypeLabel(type) {
        const labels = {
            'supplement': 'TPCN',
            'medicine': 'Thuốc điều trị',
            'other': 'Khác'
        };
        return labels[type] || type;
    }

    function getTypeBadgeColor(type) {
        const colors = {
            'supplement': 'info',
            'medicine': 'primary',
            'other': 'secondary'
        };
        return colors[type] || 'secondary';
    }

    function getTypeIcon(type) {
        const icons = {
            'supplement': 'fas fa-leaf',
            'medicine': 'fas fa-pills',
            'other': 'fas fa-box'
        };
        return icons[type] || 'fas fa-box';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN');
    }

    function formatCurrency(amount) {
        if (amount >= 1000000) {
            return (amount / 1000000).toFixed(1) + 'M';
        } else if (amount >= 1000) {
            return (amount / 1000).toFixed(0) + 'K';
        }
        return amount.toString();
    }

    /**
     * Event handlers
     */

    // Refresh overview stats
    $('#refreshOverview').on('click', function() {
        $(this).html('<i class="fas fa-sync-alt fa-spin"></i> Đang tải...');
        loadOverviewStats();
        setTimeout(() => {
            $(this).html('<i class="fas fa-sync-alt"></i> Làm mới');
        }, 1000);
    });

    // Change period for top medicines
    $('#topMedicinesPeriod').on('change', function() {
        const period = $(this).val();
        loadTopMedicines(period);
    });

    // View full expiry report
    $('#viewFullExpiryReport').on('click', function() {
        loadFullExpiryReport();
    });

    // Export functions
    $('#exportOverview').on('click', function() {
        exportReport('overview');
    });

    $('#exportExpiry').on('click', function() {
        exportReport('expiry');
    });

    $('#exportImports').on('click', function() {
        exportReport('imports');
    });

    /**
     * Load full expiry report for modal
     */
    function loadFullExpiryReport() {
        $.ajax({
            url: '/admin/medicine-statistics/expiry-report',
            type: 'GET',
            success: function(response) {
                renderFullExpiryReport(response);
                $('#expiryReportModal').modal('show');
            },
            error: function(xhr) {
                console.error('Error loading full expiry report:', xhr);
                notification('error', 'Lỗi', 'Không thể tải báo cáo chi tiết');
            }
        });
    }

    /**
     * Render full expiry report in modal
     */
    function renderFullExpiryReport(data) {
        let html = `
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs nav-line-tabs mb-5" id="expiryTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="expired-tab" data-bs-toggle="tab" href="#expired" role="tab">
                            Đã hết hạn (${data.expired.length})
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="expiring7-tab" data-bs-toggle="tab" href="#expiring7" role="tab">
                            Hết hạn trong 7 ngày (${data.expiring_7_days.length})
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="expiring30-tab" data-bs-toggle="tab" href="#expiring30" role="tab">
                            Hết hạn trong 30 ngày (${data.expiring_30_days.length})
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content" id="expiryTabContent">
                    <div class="tab-pane fade show active" id="expired" role="tabpanel">
                        ${renderExpiryTable(data.expired, 'expired')}
                    </div>
                    <div class="tab-pane fade" id="expiring7" role="tabpanel">
                        ${renderExpiryTable(data.expiring_7_days, 'warning')}
                    </div>
                    <div class="tab-pane fade" id="expiring30" role="tabpanel">
                        ${renderExpiryTable(data.expiring_30_days, 'info')}
                    </div>
                </div>
            </div>
        </div>
    `;

        $('#expiryReportModalContent').html(html);
    }

    /**
     * Render expiry table
     */
    function renderExpiryTable(medicines, type) {
        if (medicines.length === 0) {
            return '<div class="text-center text-muted py-5">Không có thuốc nào trong danh mục này</div>';
        }

        let html = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tên thuốc</th>
                        <th>Loại</th>
                        <th>Hạn sử dụng</th>
                        <th>Giá bán</th>
                        ${type === 'expired' ? '<th>Ngày hết hạn</th>' : '<th>Còn lại</th>'}
                    </tr>
                </thead>
                <tbody>
    `;

        medicines.forEach(medicine => {
            const expiryDate = new Date(medicine.expiry_date);
            const today = new Date();
            const diffTime = expiryDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            let statusText = '';
            let statusClass = '';

            if (diffDays < 0) {
                statusText = `Đã hết hạn ${Math.abs(diffDays)} ngày`;
                statusClass = 'text-danger';
            } else {
                statusText = `Còn ${diffDays} ngày`;
                statusClass = diffDays <= 7 ? 'text-warning' : 'text-info';
            }

            html += `
            <tr>
                <td class="fw-bold">${medicine.name}</td>
                <td><span class="badge badge-light-${getTypeBadgeColor(medicine.type)}">${getTypeLabel(medicine.type)}</span></td>
                <td>${formatDate(medicine.expiry_date)}</td>
                <td>${parseInt(medicine.sale_price).toLocaleString('vi-VN')} VNĐ</td>
                <td><span class="${statusClass}">${statusText}</span></td>
            </tr>
        `;
        });

        html += '</tbody></table></div>';

        return html;
    }

    /**
     * Export report
     */
    function exportReport(type) {
        const button = $(`#export${type.charAt(0).toUpperCase() + type.slice(1)}`);
        const originalText = button.html();

        button.html('<i class="fas fa-spinner fa-spin"></i> Đang xuất...');
        button.prop('disabled', true);

        $.ajax({
            url: '/admin/medicine-statistics/export',
            type: 'GET',
            data: {
                type: type
            },
            success: function(response) {
                if (response.success) {
                    // Convert data to CSV and download
                    downloadCSV(response.data,
                        `bao-cao-${type}-${new Date().toISOString().split('T')[0]}.csv`);
                    notification('success', 'Thành công', 'Đã xuất báo cáo thành công');
                } else {
                    notification('error', 'Lỗi', 'Không thể xuất báo cáo');
                }
            },
            error: function(xhr) {
                console.error('Error exporting report:', xhr);
                notification('error', 'Lỗi', 'Có lỗi xảy ra khi xuất báo cáo');
            },
            complete: function() {
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    }

    /**
     * Download CSV file
     */
    function downloadCSV(data, filename) {
        if (data.length === 0) {
            notification('warning', 'Cảnh báo', 'Không có dữ liệu để xuất');
            return;
        }

        // Convert object array to CSV
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${row[header] || ''}"`).join(','))
        ].join('\n');

        // Create and download file
        const blob = new Blob(['\ufeff' + csvContent], {
            type: 'text/csv;charset=utf-8;'
        });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
