<!-- Modal Payment QR -->
<div class="modal fade" id="kt_modal_payment_qr" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bolder">Thanh toán QR Code</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                transform="rotate(-45 6 17.3137)" fill="black" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                transform="rotate(45 7.41422 6)" fill="black" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="modal-body text-center">
                <!-- Payment Info -->
                <div class="mb-5">
                    <h4>Mã phiếu khám: <span id="payment-examination-code" class="text-primary"></span></h4>
                    <h5>Tổng tiền: <span id="payment-total-amount" class="text-success"></span></h5>
                </div>

                <!-- QR Code Section -->
                <div id="qr-code-display" class="qr-code-container">
                    <img id="payment-qr-image" src="" alt="QR Code" style="max-width: 300px;" />
                    
                    <div class="payment-info bg-light-info p-4 rounded mt-4">
                        <div class="row text-start">
                            <div class="col-6">
                                <p class="mb-2"><strong>Ngân hàng:</strong></p>
                                <p class="mb-2"><strong>Số TK:</strong></p>
                                <p class="mb-2"><strong>Chủ TK:</strong></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-2"><span id="payment-bank-name"></span></p>
                                <p class="mb-2"><span id="payment-account-number"></span></p>
                                <p class="mb-2"><span id="payment-account-name"></span></p>
                            </div>
                        </div>
                        <div class="separator my-3"></div>
                        <div class="text-center">
                            <p class="mb-1"><strong>Nội dung chuyển khoản:</strong></p>
                            <p class="text-primary fw-bold" id="payment-transfer-content"></p>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div id="payment-status" class="mt-5">
                    <div class="alert alert-warning d-flex align-items-center">
                        <span class="svg-icon svg-icon-2hx svg-icon-warning me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"/>
                                <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"/>
                            </svg>
                        </span>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-warning">Chờ thanh toán</h4>
                            <span>Vui lòng quét mã QR hoặc chuyển khoản theo thông tin trên</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Success -->
                <div id="payment-success-status" class="mt-5" style="display: none;">
                    <div class="alert alert-success d-flex align-items-center">
                        <span class="svg-icon svg-icon-2hx svg-icon-success me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L9.25 14.25C9.66421 14.6642 10.3358 14.6642 10.75 14.25L17.25 7.75C17.6642 7.33579 17.6642 6.66421 17.25 6.25C16.8358 5.83579 16.1642 5.83579 15.75 6.25L10.4343 12.4343Z" fill="currentColor"/>
                            </svg>
                        </span>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 text-success">Thanh toán thành công!</h4>
                            <span>Giao dịch đã được xác nhận</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-info me-3" id="check-payment-status">
                    <span class="indicator-label">
                        <i class="fas fa-sync"></i> Kiểm tra thanh toán
                    </span>
                    <span class="indicator-progress">
                        Đang kiểm tra...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Payment Success Notification -->
<div class="modal fade" id="kt_modal_payment_success" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-500px">
        <div class="modal-content">
            <div class="modal-body text-center p-10">
                <div class="mb-5">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h3 class="text-success mb-3">Thanh toán thành công!</h3>
                <p class="text-gray-600 fs-5 mb-5">
                    Phiếu khám <strong id="success-examination-code"></strong> đã được thanh toán thành công.
                </p>
                <div class="text-center">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                        <i class="fas fa-check"></i> Xác nhận
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>