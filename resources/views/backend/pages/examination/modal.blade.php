<div class="modal fade" id="kt_modal_add_examination" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-1000px">
        <div class="modal-content">
            <form class="form" id="kt_modal_add_examination_form">
                @csrf
                <div class="modal-header">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <h2 class="fw-bolder modal-title">Tạo phiếu khám mới</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
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

                <div class="modal-body py-10 px-lg-17">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="patient_id" value="">

                    <!-- Step Navigation -->
                    <div class="step-nav">
                        <div class="step-item active" data-step="1">
                            <div class="step-number">1</div>
                            <span>Thông tin bệnh nhân</span>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-number">2</div>
                            <span>Dịch vụ & Thuốc</span>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-number">3</div>
                            <span>Chuẩn đoán</span>
                        </div>
                        <div class="step-item" data-step="4">
                            <div class="step-number">4</div>
                            <span>Thanh toán</span>
                        </div>
                    </div>

                    <!-- Step 1: Patient Information -->
                    <div class="step-content active" id="step-1">
                        <h4 class="mb-5">Thông tin bệnh nhân</h4>

                        <!-- Search existing patient -->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Tìm bệnh nhân</label>
                            <input type="text" class="form-control form-control-solid" id="patient-search"
                                placeholder="Nhập tên, SĐT hoặc mã BN để tìm kiếm..." />
                            <div id="patient-search-results" class="mt-3" style="display: none;">
                                <!-- Search results will be populated here -->
                            </div>
                        </div>

                        <div class="separator separator-dashed my-7"></div>
                        <h5 class="text-gray-700 mb-5">Hoặc nhập thông tin bệnh nhân mới</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Họ tên</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập họ tên bệnh nhân" name="patient_name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Số điện thoại</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập số điện thoại" name="patient_phone" />
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Ngày sinh</label>
                                    <input type="date" class="form-control form-control-solid" name="patient_dob" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Giới tính</label>
                                    <select class="form-select form-select-solid" name="patient_gender">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male">Nam</option>
                                        <option value="female">Nữ</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Số căn cước</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập số CCCD" name="patient_citizen_id" />
                                </div>
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Địa chỉ</label>
                            <textarea class="form-control form-control-solid" rows="2" placeholder="Nhập địa chỉ" name="patient_address"></textarea>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Ngày khám</label>
                            <input type="date" class="form-control form-control-solid" name="examination_date"
                                value="{{ date('Y-m-d') }}" />
                        </div>
                    </div>

                    <!-- Step 2: Services & Medicines -->
                    <div class="step-content" id="step-2">
                        <h4 class="mb-5">Dịch vụ và thuốc</h4>

                        <!-- Services Section -->
                        <div class="mb-8">
                            <h5 class="text-gray-700 mb-5">Dịch vụ sử dụng</h5>
                            <button type="button" class="btn btn-light-primary btn-sm mb-5" id="add-service">
                                <i class="fas fa-plus"></i> Thêm dịch vụ
                            </button>
                            <div id="services-container">
                                <!-- Services will be added here -->
                            </div>
                        </div>

                        <div class="separator separator-dashed my-7"></div>

                        <!-- Medicines Section -->
                        <div class="mb-8">
                            <h5 class="text-gray-700 mb-5">Thuốc kê theo</h5>
                            <button type="button" class="btn btn-light-primary btn-sm mb-5" id="add-medicine">
                                <i class="fas fa-plus"></i> Thêm thuốc
                            </button>
                            <div id="medicines-container">
                                <!-- Medicines will be added here -->
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Diagnosis -->
                    <div class="step-content" id="step-3">
                        <h4 class="mb-5">Chuẩn đoán và điều trị</h4>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Triệu chứng</label>
                            <textarea class="form-control form-control-solid" rows="3" placeholder="Mô tả triệu chứng của bệnh nhân"
                                name="symptoms"></textarea>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-bold mb-2">Chuẩn đoán</label>
                            <textarea class="form-control form-control-solid" rows="3" placeholder="Nhập chuẩn đoán" name="diagnosis"></textarea>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Kế hoạch điều trị</label>
                            <textarea class="form-control form-control-solid" rows="4" placeholder="Mô tả kế hoạch điều trị"
                                name="treatment_plan"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Lịch tái khám</label>
                                    <input type="date" class="form-control form-control-solid"
                                        name="next_appointment" />
                                </div>
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Ghi chú</label>
                            <textarea class="form-control form-control-solid" rows="2" placeholder="Ghi chú khác" name="notes"></textarea>
                        </div>

                        <!-- Fee Summary -->
                        <div class="card bg-light-primary p-5">
                            <h5 class="text-primary mb-3">Tổng kết chi phí</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí dịch vụ:</span>
                                <span id="total-service-fee">0 VNĐ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí thuốc:</span>
                                <span id="total-medicine-fee">0 VNĐ</span>
                            </div>
                            <div class="separator my-3"></div>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Tổng cộng:</span>
                                <span id="total-fee" class="text-primary">0 VNĐ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Payment -->
                    <div class="step-content" id="step-4">
                        <h4 class="mb-5">Thanh toán</h4>

                        <div class="text-center">
                            <div class="mb-5">
                                <h5>Mã phiếu khám: <span id="examination-code-display"></span></h5>
                                <h6>Tổng tiền: <span id="final-total-fee" class="text-primary"></span></h6>
                            </div>

                            <button type="button" class="btn btn-success btn-lg" id="generate-qr">
                                <i class="fas fa-qrcode"></i> Tạo mã QR thanh toán
                            </button>

                            <div id="qr-code-section" class="mt-5" style="display: none;">
                                <div class="qr-code-container">
                                    <img id="qr-code-image" src="" alt="QR Code" />
                                    <div class="payment-info">
                                        <p><strong>Ngân hàng:</strong> <span id="bank-name"></span></p>
                                        <p><strong>Số tài khoản:</strong> <span id="account-number"></span></p>
                                        <p><strong>Chủ tài khoản:</strong> <span id="account-name"></span></p>
                                        <p><strong>Nội dung:</strong> <span id="transfer-content"></span></p>
                                        <p><strong>Số tiền:</strong> <span id="transfer-amount"></span></p>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-info" id="check-payment">
                                    <i class="fas fa-sync"></i> Kiểm tra thanh toán
                                </button>
                            </div>

                            <div id="payment-success" class="mt-5" style="display: none;">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Thanh toán thành công!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light me-3" id="prev-step" style="display: none;">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </button>
                    <button type="button" class="btn btn-light me-3" id="examination-cancel">Hủy</button>
                    <button type="button" class="btn btn-primary" id="next-step">
                        Tiếp theo <i class="fas fa-arrow-right"></i>
                    </button>
                    <button type="submit" class="btn btn-success" id="finish-examination" style="display: none;">
                        <span class="indicator-label">
                            <i class="fas fa-check"></i> Hoàn thành
                        </span>
                        <span class="indicator-progress">Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Service Item Template -->
<template id="service-item-template">
    <div class="service-item border rounded p-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <select class="form-select service-select" name="services[][service_id]">
                    <option value="">-- Chọn dịch vụ --</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control service-quantity" name="services[][quantity]"
                    placeholder="SL" value="1" min="1" />
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control service-price" name="services[][price]" placeholder="Giá"
                    min="0" />
            </div>
            <div class="col-md-2">
                <span class="service-total">0 VNĐ</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-light-danger remove-service">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<!-- Medicine Item Template -->
<template id="medicine-item-template">
    <div class="medicine-item border rounded p-3 mb-3">
        <div class="row align-items-center">
            <div class="col-md-3">
                <select class="form-select medicine-select" name="medicines[][medicine_id]">
                    <option value="">-- Chọn thuốc --</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control medicine-quantity" name="medicines[][quantity]"
                    placeholder="SL" value="1" min="1" />
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control medicine-dosage" name="medicines[][dosage]"
                    placeholder="Liều dùng" />
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control medicine-price" name="medicines[][price]"
                    placeholder="Giá" min="0" />
            </div>
            <div class="col-md-2">
                <span class="medicine-total">0 VNĐ</span>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-sm btn-light-danger remove-medicine">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <input type="text" class="form-control medicine-note" name="medicines[][note]"
                    placeholder="Ghi chú cách dùng" />
            </div>
        </div>
    </div>
</template>
