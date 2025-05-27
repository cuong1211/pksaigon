<!-- Modal Payment QR -->
<div class="modal fade" id="kt_modal_payment_qr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h2 class="fw-bolder text-white mb-0">
                    <i class="fas fa-qrcode me-2"></i>Thanh toán QR Code
                </h2>
                <div class="btn btn-icon btn-sm btn-active-icon-light" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="modal-body p-0">
                <!-- Header Info -->
                <div class="bg-light-primary p-6 text-center">
                    <div class="mb-4">
                        <h3 class="text-primary mb-2">
                            Mã phiếu khám: <span id="payment-examination-code" class="fw-bold"></span>
                        </h3>
                        <h4 class="text-success mb-0">
                            Tổng tiền: <span id="payment-total-amount" class="fw-bold"></span>
                        </h4>
                    </div>
                </div>

                <!-- QR Code Section -->
                <div class="px-6 py-4">
                    <div id="qr-code-display" class="text-center">
                        <!-- QR Image -->
                        <div class="qr-image-container mb-4">
                            <div class="position-relative d-inline-block">
                                <img id="payment-qr-image" src="" alt="QR Code"
                                    class="rounded border shadow-sm"
                                    style="max-width: 280px; background: white; padding: 15px;" />
                                <!-- VietQR Logo Overlay -->
                                <div class="position-absolute top-0 end-0 bg-primary text-white rounded-pill px-2 py-1"
                                    style="transform: translate(50%, -50%); font-size: 10px;">
                                    <i class="fas fa-qrcode"></i> VietQR
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information Card -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light-info">
                                <h5 class="card-title mb-0 text-info">
                                    <i class="fas fa-university me-2"></i>Thông tin chuyển khoản
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted fs-7 fw-bold">NGÂN HÀNG</span>
                                            <span id="payment-bank-name" class="text-dark fw-bold fs-6"></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted fs-7 fw-bold">SỐ TÀI KHOẢN</span>
                                            <span id="payment-account-number"
                                                class="text-dark fw-bold fs-6 font-monospace"></span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex flex-column">
                                            <span class="text-muted fs-7 fw-bold">CHỦ TÀI KHOẢN</span>
                                            <span id="payment-account-name" class="text-dark fw-bold fs-6"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="separator my-4"></div>

                                <!-- Transfer Content -->
                                <div class="text-center">
                                    <span class="text-muted fs-7 fw-bold">NỘI DUNG CHUYỂN KHOẢN</span>
                                    <div class="mt-2">
                                        <span id="payment-transfer-content"
                                            class="badge badge-light-primary fs-4 fw-bold font-monospace px-4 py-3"></span>
                                    </div>

                                    <!-- Important Notice -->
                                    <div class="alert alert-warning d-flex align-items-center mt-4">
                                        <i class="fas fa-exclamation-triangle text-warning fs-2x me-3"></i>
                                        <div class="text-start">
                                            <strong>Quan trọng:</strong> Vui lòng nhập chính xác nội dung chuyển khoản
                                            trên
                                            để hệ thống tự động xác nhận thanh toán.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light-success">
                                <h6 class="card-title mb-0 text-success">
                                    <i class="fas fa-mobile-alt me-2"></i>Hướng dẫn thanh toán
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="symbol symbol-30px me-3">
                                                <div class="symbol-label bg-light-primary text-primary fs-7 fw-bold">1
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-gray-800">Mở ứng dụng ngân hàng</div>
                                                <div class="text-muted fs-8">Trên điện thoại của bạn</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="symbol symbol-30px me-3">
                                                <div class="symbol-label bg-light-primary text-primary fs-7 fw-bold">2
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-gray-800">Quét mã QR</div>
                                                <div class="text-muted fs-8">Hoặc nhập thông tin thủ công</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="symbol symbol-30px me-3">
                                                <div class="symbol-label bg-light-primary text-primary fs-7 fw-bold">3
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-gray-800">Xác nhận giao dịch</div>
                                                <div class="text-muted fs-8">Kiểm tra thông tin và xác nhận</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bg-light-info rounded p-4">
                                            <div class="text-center">
                                                <i class="fas fa-clock text-info fs-2x mb-2"></i>
                                                <div class="fw-bold text-info">Tự động cập nhật</div>
                                                <div class="text-muted fs-8">
                                                    Hệ thống sẽ tự động xác nhận khi nhận được thanh toán
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div id="payment-status">
                            <div class="alert alert-warning d-flex align-items-center">
                                <div class="symbol symbol-50px me-4">
                                    <div class="symbol-label bg-warning">
                                        <i class="fas fa-clock text-white fs-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="text-warning mb-1">Đang chờ thanh toán</h5>
                                    <div class="text-muted">
                                        Hệ thống sẽ tự động cập nhật khi nhận được giao dịch từ ngân hàng
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <div class="spinner-border text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Success -->
                        <div id="payment-success-status" style="display: none;">
                            <div class="alert alert-success d-flex align-items-center">
                                <div class="symbol symbol-50px me-4">
                                    <div class="symbol-label bg-success">
                                        <i class="fas fa-check-circle text-white fs-2x"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="text-success mb-1">Thanh toán thành công!</h5>
                                    <div class="text-muted">
                                        Giao dịch đã được xác nhận và xử lý thành công
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <i class="fas fa-check-circle text-success fs-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <!-- VietQR Powered Badge -->
                    <span class="badge badge-light-primary me-3">
                        <i class="fas fa-qrcode me-1"></i>Powered by VietQR
                    </span>
                    <!-- Security Badge -->
                    <span class="badge badge-light-success">
                        <i class="fas fa-shield-alt me-1"></i>Bảo mật cao
                    </span>
                </div>

                <div class="d-flex">
                    <button type="button" class="btn btn-info me-3" id="check-payment-status">
                        <span class="indicator-label">
                            <i class="fas fa-sync-alt me-2"></i>Kiểm tra thanh toán
                        </span>
                        <span class="indicator-progress">
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Đang kiểm tra...
                        </span>
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Payment Success Notification -->
<div class="modal fade" id="kt_modal_payment_success" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-body text-center p-10">
                <!-- Success Animation -->
                <div class="mb-5">
                    <div class="symbol symbol-100px mx-auto mb-4">
                        <div class="symbol-label bg-light-success">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <h2 class="text-success mb-4">Thanh toán thành công!</h2>

                <div class="text-center mb-5">
                    <div class="text-gray-600 fs-5 mb-3">
                        Phiếu khám <strong id="success-examination-code" class="text-primary"></strong>
                        đã được thanh toán thành công.
                    </div>

                    <div class="separator my-4"></div>

                    <div class="d-flex justify-content-center">
                        <div class="d-flex align-items-center bg-light-success rounded p-3">
                            <i class="fas fa-calendar-check text-success fs-2x me-3"></i>
                            <div class="text-start">
                                <div class="fw-bold text-success">Trạng thái</div>
                                <div class="text-muted fs-7">Đã hoàn thành</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center">
                    <button type="button" class="btn btn-success me-3" data-bs-dismiss="modal">
                        <i class="fas fa-check me-2"></i>Xác nhận
                    </button>
                    <button type="button" class="btn btn-light-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>In phiếu khám
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for Payment Modal -->
<style>
    .qr-image-container {
        position: relative;
        display: inline-block;
    }

    .qr-image-container::before {
        content: '';
        position: absolute;
        inset: -10px;
        background: linear-gradient(45deg, #009ef7, #50cd89);
        border-radius: 12px;
        z-index: -1;
        opacity: 0.1;
    }

    #payment-qr-image {
        transition: transform 0.3s ease;
    }

    #payment-qr-image:hover {
        transform: scale(1.05);
    }

    .modal-content {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        border: none;
    }

    .card {
        border: 1px solid #e4e6ef;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .symbol-label {
        font-weight: 600;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
    }

    @keyframes pulse-success {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .alert-success .symbol-label {
        animation: pulse-success 2s infinite;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        #payment-qr-image {
            max-width: 220px;
        }

        .card-body {
            padding: 1rem;
        }

        .modal-footer {
            flex-direction: column;
            gap: 1rem;
        }

        .modal-footer .d-flex:first-child {
            justify-content: center;
        }
    }
</style>
