<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="kt_modal_add_customer_form">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_customer_header">
                    <!--begin::Modal title-->
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <h2 class="fw-bolder modal-title"></h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                    transform="rotate(-45 6 17.3137)" fill="black" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                    transform="rotate(45 7.41422 6)" fill="black" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->
                <!--begin::Modal body-->
                <div class="modal-body py-10 px-lg-17">
                    <!--begin::Scroll-->
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                        data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                        <input type="hidden" name="id" value="">

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Tên bệnh nhân-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Tên bệnh nhân</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập tên bệnh nhân" name="patient_name" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Số điện thoại-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Số điện thoại</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập số điện thoại" name="patient_phone" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group - Dịch vụ khám-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Dịch vụ khám</label>
                            <select class="form-select form-select-solid" name="service_id" id="service_select">
                                <option value="">-- Chọn dịch vụ --</option>
                            </select>
                            <div class="form-text">Chọn dịch vụ cần khám (tùy chọn)</div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Ngày đặt lịch-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Ngày đặt lịch</label>
                                    <input type="date" class="form-control form-control-solid"
                                        name="appointment_date" min="{{ date('Y-m-d') }}" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Giờ đặt lịch-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Giờ đặt lịch</label>
                                    <input type="time" class="form-control form-control-solid"
                                        name="appointment_time" value="09:00" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Hủy</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
                        <span class="indicator-label">Xác nhận</span>
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
