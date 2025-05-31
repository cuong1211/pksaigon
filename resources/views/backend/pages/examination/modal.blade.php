<div class="modal fade" id="kt_modal_examination" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-xl">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="kt_modal_examination_form">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_examination_header">
                    <!--begin::Modal title-->
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <h2 class="fw-bolder modal-title"></h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_examination_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
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
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_examination_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_examination_header"
                        data-kt-scroll-wrappers="#kt_modal_examination_scroll" data-kt-scroll-offset="300px">
                        <input type="hidden" name="id" value="">

                        <!--begin::Basic Information-->
                        <div class="card card-flush mb-7">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin cơ bản</h3>
                            </div>
                            <div class="card-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col - Patient Selection-->
                                    <div class="col-md-6">
                                        <div class="fv-row mb-7">
                                            <label class="required fs-6 fw-bold mb-2">Chọn bệnh nhân</label>
                                            <select class="form-select form-select-solid" name="patient_id"
                                                id="patient_select" data-control="select2"
                                                data-placeholder="Tìm và chọn bệnh nhân..." data-allow-clear="true">
                                                <option value="">-- Chọn bệnh nhân --</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col - Examination Date-->
                                    <div class="col-md-6">
                                        <div class="fv-row mb-7">
                                            <label class="required fs-6 fw-bold mb-2">Ngày khám</label>
                                            <input type="date" class="form-control form-control-solid"
                                                name="examination_date" value="{{ date('Y-m-d') }}" />
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Patient Info Display-->
                                <div id="patient-info-display" class="row mb-7" style="display: none;">
                                    <div class="col-12">
                                        <div class="card bg-light-info">
                                            <div class="card-body py-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Mã bệnh nhân:</small>
                                                        <div class="fw-bold" id="selected-patient-code">-</div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Số điện thoại:</small>
                                                        <div class="fw-bold" id="selected-patient-phone">-</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Patient Info Display-->

                                <!--begin::Row - Medical Info-->
                                <div class="row">
                                    <!--begin::Col - Symptoms-->
                                    <div class="col-md-6">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold mb-2">Triệu chứng</label>
                                            <textarea class="form-control form-control-solid" rows="3" placeholder="Mô tả triệu chứng" name="symptoms"></textarea>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col - Diagnosis-->
                                    <div class="col-md-6">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold mb-2">Chuẩn đoán</label>
                                            <textarea class="form-control form-control-solid" rows="3" placeholder="Chuẩn đoán bệnh" name="diagnosis"></textarea>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Row - Treatment & Next Appointment-->
                                <div class="row">
                                    <!--begin::Col - Treatment Plan-->
                                    <div class="col-md-8">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold mb-2">Kế hoạch điều trị</label>
                                            <textarea class="form-control form-control-solid" rows="3" placeholder="Kế hoạch điều trị"
                                                name="treatment_plan"></textarea>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                    <!--begin::Col - Next Appointment-->
                                    <div class="col-md-4">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold mb-2">Lịch tái khám</label>
                                            <input type="date" class="form-control form-control-solid"
                                                name="next_appointment" />
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->

                                <!--begin::Edit Mode - Payment Status-->
                                <div id="edit-mode-fields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="fv-row mb-7">
                                                <label class="fs-6 fw-bold mb-2">Trạng thái thanh toán</label>
                                                <select class="form-select form-select-solid" name="payment_status">
                                                    <option value="pending">Chờ thanh toán</option>
                                                    <option value="paid">Đã thanh toán</option>
                                                    <option value="cancelled">Đã hủy</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Edit Mode-->
                            </div>
                        </div>
                        <!--end::Basic Information-->

                        <!--begin::Services Section-->
                        <div class="card card-flush mb-7">
                            <div class="card-header">
                                <h3 class="card-title">Dịch vụ sử dụng</h3>
                            </div>
                            <div class="card-body">
                                <div class="dynamic-section" id="services-section">
                                    <div class="section-header">Dịch vụ</div>
                                    <div id="services-container">
                                        <!-- Services will be added here dynamically -->
                                    </div>
                                    <button type="button" class="add-item-btn" id="add-service-btn">
                                        <i class="fas fa-plus me-2"></i>Thêm dịch vụ
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Services Section-->

                        <!--begin::Medicines Section-->
                        <div class="card card-flush mb-7">
                            <div class="card-header">
                                <h3 class="card-title">Thuốc kê đơn</h3>
                            </div>
                            <div class="card-body">
                                <div class="dynamic-section" id="medicines-section">
                                    <div class="section-header">Thuốc</div>
                                    <div id="medicines-container">
                                        <!-- Medicines will be added here dynamically -->
                                    </div>
                                    <button type="button" class="add-item-btn" id="add-medicine-btn">
                                        <i class="fas fa-plus me-2"></i>Thêm thuốc
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!--end::Medicines Section-->

                        <!--begin::Total Fee Display-->
                        <div class="card card-flush mb-7" id="fee-summary" style="display: none;">
                            <div class="card-header">
                                <h3 class="card-title">Tổng chi phí</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between">
                                            <span>Tiền dịch vụ:</span>
                                            <span class="fw-bold" id="service-fee-display">0 VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between">
                                            <span>Tiền thuốc:</span>
                                            <span class="fw-bold" id="medicine-fee-display">0 VNĐ</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between border-top pt-2">
                                            <span class="fw-bold">Tổng cộng:</span>
                                            <span class="fw-bold text-primary fs-4" id="total-fee-display">0
                                                VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Total Fee Display-->

                        <!--begin::Notes-->
                        <div class="card card-flush">
                            <div class="card-header">
                                <h3 class="card-title">Ghi chú</h3>
                            </div>
                            <div class="card-body">
                                <div class="fv-row">
                                    <textarea class="form-control form-control-solid" rows="4" placeholder="Ghi chú thêm (tùy chọn)"
                                        name="notes"></textarea>
                                </div>
                            </div>
                        </div>
                        <!--end::Notes-->

                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_examination_cancel" class="btn btn-light me-3">Hủy</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_examination_submit" class="btn btn-primary">
                        <span class="indicator-label">Hoàn thành khám</span>
                        <span class="indicator-progress">Đang xử lý...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                    <!--end::Button-->
                </div>
                <!--end::Modal footer-->
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>

<!-- Templates for dynamic content -->
<template id="service-item-template">
    <div class="item-row" data-index="">
        <button type="button" class="remove-item" onclick="removeServiceItem(this)">×</button>
        <div class="row">
            <div class="col-md-6">
                <label class="fs-7 fw-bold mb-2">Dịch vụ</label>
                <select class="form-select form-select-sm service-select" name="services[][service_id]"
                    onchange="updateServicePrice(this)">
                    <option value="">-- Chọn dịch vụ --</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="fs-7 fw-bold mb-2">Số lượng</label>
                <input type="number" class="form-control form-control-sm service-quantity"
                    name="services[][quantity]" min="1" value="1"
                    onchange="calculateServiceTotal(this)">
            </div>
            <div class="col-md-2">
                <label class="fs-7 fw-bold mb-2">Đơn giá</label>
                <input type="text" class="form-control form-control-sm service-price" readonly>
                <input type="hidden" name="services[][price]" class="service-price-value">
            </div>
            <div class="col-md-2">
                <label class="fs-7 fw-bold mb-2">Thành tiền</label>
                <input type="text" class="form-control form-control-sm service-total" readonly>
            </div>
        </div>
    </div>
</template>

<template id="medicine-item-template">
    <div class="item-row" data-index="">
        <button type="button" class="remove-item" onclick="removeMedicineItem(this)">×</button>
        <div class="row">
            <div class="col-md-4">
                <label class="fs-7 fw-bold mb-2">Thuốc</label>
                <select class="form-select form-select-sm medicine-select" name="medicines[][medicine_id]"
                    onchange="updateMedicinePrice(this)">
                    <option value="">-- Chọn thuốc --</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="fs-7 fw-bold mb-2">Số lượng</label>
                <input type="number" class="form-control form-control-sm medicine-quantity"
                    name="medicines[][quantity]" min="1" value="1"
                    onchange="calculateMedicineTotal(this)">
            </div>
            <div class="col-md-2">
                <label class="fs-7 fw-bold mb-2">Đơn giá</label>
                <input type="text" class="form-control form-control-sm medicine-price" readonly>
            </div>
            <div class="col-md-2">
                <label class="fs-7 fw-bold mb-2">Thành tiền</label>
                <input type="text" class="form-control form-control-sm medicine-total" readonly>
            </div>
            <div class="col-md-6 mt-3">
                <label class="fs-7 fw-bold mb-2">Liều lượng</label>
                <input type="text" class="form-control form-control-sm" name="medicines[][dosage]"
                    placeholder="VD: 2 viên/ngày, sau ăn">
            </div>
            <div class="col-md-6 mt-3">
                <label class="fs-7 fw-bold mb-2">Ghi chú</label>
                <input type="text" class="form-control form-control-sm" name="medicines[][note]"
                    placeholder="Ghi chú cách dùng">
            </div>
        </div>
        <div class="stock-warning mt-2" style="display: none;">
            <small class="text-warning">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Tồn kho: <span class="stock-quantity">0</span>
            </small>
        </div>
    </div>
</template>
