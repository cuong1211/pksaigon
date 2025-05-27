<div class="modal fade" id="kt_modal_add_patient" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Form-->
            <form class="form" id="kt_modal_add_patient_form">
                @csrf
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_patient_header">
                    <!--begin::Modal title-->
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <h2 class="fw-bolder modal-title"></h2>
                    <!--end::Modal title-->
                    <!--begin::Close-->
                    <div id="kt_modal_add_patient_close" class="btn btn-icon btn-sm btn-active-icon-primary btn-close">
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
                    <div class="scroll-y me-n7 pe-7" id="kt_modal_add_patient_scroll" data-kt-scroll="true"
                        data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                        data-kt-scroll-dependencies="#kt_modal_add_patient_header"
                        data-kt-scroll-wrappers="#kt_modal_add_patient_scroll" data-kt-scroll-offset="300px">
                        <input type="hidden" name="id" value="">

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Họ tên-->
                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-bold mb-2">Họ tên</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập họ tên bệnh nhân" name="full_name" />
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
                                        placeholder="Nhập số điện thoại" name="phone" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Ngày sinh-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Ngày sinh</label>
                                    <input type="date" class="form-control form-control-solid"
                                        name="date_of_birth" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Giới tính-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Giới tính</label>
                                    <select class="form-select form-select-solid" name="gender">
                                        <option value="">-- Chọn giới tính --</option>
                                        <option value="male">Nam</option>
                                        <option value="female">Nữ</option>
                                        <option value="other">Khác</option>
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-4">
                                <!--begin::Input group - Số căn cước-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Số căn cước công dân</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập số CCCD" name="citizen_id" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Input group - Địa chỉ-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Địa chỉ</label>
                            <textarea class="form-control form-control-solid" rows="2" placeholder="Nhập địa chỉ" name="address"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Email-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Email</label>
                                    <input type="email" class="form-control form-control-solid"
                                        placeholder="Nhập email" name="email" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Trạng thái-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Trạng thái</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            id="is_active" checked>
                                        <label class="form-check-label fs-6 fw-bold" for="is_active">
                                            Hoạt động
                                        </label>
                                    </div>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-7"></div>
                        <!--end::Separator-->

                        <!--begin::Section title-->
                        <h4 class="text-gray-700 fw-bolder mb-7">Thông tin liên hệ khẩn cấp</h4>
                        <!--end::Section title-->

                        <!--begin::Row-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - Người liên hệ khẩn cấp-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">Người liên hệ khẩn cấp</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập tên người liên hệ" name="emergency_contact" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-md-6">
                                <!--begin::Input group - SĐT khẩn cấp-->
                                <div class="fv-row mb-7">
                                    <label class="fs-6 fw-bold mb-2">SĐT khẩn cấp</label>
                                    <input type="text" class="form-control form-control-solid"
                                        placeholder="Nhập số điện thoại khẩn cấp" name="emergency_phone" />
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->

                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-7"></div>
                        <!--end::Separator-->

                        <!--begin::Section title-->
                        <h4 class="text-gray-700 fw-bolder mb-7">Thông tin y tế</h4>
                        <!--end::Section title-->

                        <!--begin::Input group - Dị ứng-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Dị ứng</label>
                            <textarea class="form-control form-control-solid" rows="2" placeholder="Nhập thông tin dị ứng (nếu có)"
                                name="allergies"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Tiền sử bệnh-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Tiền sử bệnh</label>
                            <textarea class="form-control form-control-solid" rows="3" placeholder="Nhập tiền sử bệnh"
                                name="medical_history"></textarea>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Ghi chú-->
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold mb-2">Ghi chú</label>
                            <textarea class="form-control form-control-solid" rows="2" placeholder="Nhập ghi chú khác" name="notes"></textarea>
                        </div>
                        <!--end::Input group-->

                    </div>
                    <!--end::Scroll-->
                </div>
                <!--end::Modal body-->
                <!--begin::Modal footer-->
                <div class="modal-footer flex-center">
                    <!--begin::Button-->
                    <button type="reset" id="kt_modal_add_patient_cancel" class="btn btn-light me-3">Hủy</button>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" id="kt_modal_add_patient_submit" class="btn btn-primary">
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
