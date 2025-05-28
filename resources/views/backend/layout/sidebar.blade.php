<div id="kt_aside" class="aside" data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_mobile_toggle">
    <!--begin::Aside Toolbarl-->
    <div class="aside-toolbar flex-column-auto" id="kt_aside_toolbar">
        <!--begin::User-->
        <div class="aside-user d-flex align-items-sm-center justify-content-center py-5">
            <!--begin::Wrapper-->
            <div class="aside-user-info flex-row-fluid flex-wrap ms-5">
                <!--begin::Section-->
                <div class="d-flex">
                    <!--begin::Info-->
                    <div class="flex-grow-1 me-2">
                        <!--begin::Username-->
                        <a href="#"
                            class="text-white text-hover-primary fs-6 fw-bold">{{ Auth::user()->name }}</a>
                        <!--end::Username-->
                        <!--begin::Description-->
                        <span class="text-gray-600 fw-bold d-block fs-8 mb-1"></span>
                        <!--end::Description-->
                        <!--begin::Label-->
                        <div class="d-flex align-items-center text-success fs-9">
                            <span class="bullet bullet-dot bg-success me-1"></span>online
                        </div>
                        <!--end::Label-->
                    </div>
                    <!--end::Info-->
                    <!--begin::User menu-->
                    <div class="me-n2">
                        <!--begin::Action-->
                        <a href="#" class="btn btn-icon btn-sm btn-active-color-primary mt-n2"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
                            data-kt-menu-overflow="true">
                            <i class="fas fa-cog text-white fs-4"></i>
                        </a>
                        <!--begin::Menu-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <!--begin::Username-->
                                    <div class="d-flex flex-column">
                                        <div class="fw-bolder d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                            <span
                                                class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Admin</span>
                                        </div>
                                    </div>
                                    <!--end::Username-->
                                </div>
                            </div>
                            <!--begin::Menu separator-->
                            <div class="separator my-2"></div>
                            <!--end::Menu separator-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-5">
                                <a href="{{ route('logout') }}" class="menu-link px-5">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Sign Out
                                </a>
                            </div>
                            <!--end::Menu item-->
                            <div class="separator my-2"></div>
                        </div>
                        <!--end::Menu-->
                        <!--end::Action-->
                    </div>
                    <!--end::User menu-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::User-->
    </div>
    <!--end::Aside Toolbarl-->
    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid">
        <!--begin::Aside Menu-->
        <div class="hover-scroll-overlay-y px-2 my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="{default: '#kt_aside_toolbar, #kt_aside_footer', lg: '#kt_header, #kt_aside_toolbar, #kt_aside_footer'}"
            data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="5px">
            <!--begin::Menu-->
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
                id="#kt_aside_menu" data-kt-menu="true">
                <div class="menu-item">
                    <div class="menu-content pb-2">
                        <span class="menu-section text-muted text-uppercase fs-8 ls-1">Chức năng</span>
                    </div>
                </div>

                <!-- Trang chủ -->
                <div class="menu-item {{ request()->routeIs('admin') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('admin') }}">
                        <span class="menu-icon">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="menu-title">Trang chủ</span>
                    </a>
                </div>



                <!-- Quản lý dịch vụ -->
                <div class="menu-item {{ request()->routeIs('service.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('service.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-concierge-bell"></i>
                        </span>
                        <span class="menu-title">Quản lý dịch vụ</span>
                    </a>
                </div>

                <!-- Quản lý thuốc -->
                <div class="menu-item {{ request()->routeIs('medicine.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('medicine.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-pills"></i>
                        </span>
                        <span class="menu-title">Quản lý thuốc</span>
                    </a>
                </div>

                <!-- Nhập thuốc -->
                <div class="menu-item {{ request()->routeIs('medicine-import.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('medicine-import.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-truck-loading"></i>
                        </span>
                        <span class="menu-title">Nhập thuốc</span>
                    </a>
                </div>
                <!-- Danh sách khám -->
                <div class="menu-item {{ request()->routeIs('examination.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('examination.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-stethoscope"></i>
                        </span>
                        <span class="menu-title">Danh sách khám</span>
                    </a>
                </div>
                <!-- Quản lý bệnh nhân -->
                <div class="menu-item {{ request()->routeIs('patient.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('patient.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-user-injured"></i>
                        </span>
                        <span class="menu-title">Quản lý bệnh nhân</span>
                    </a>
                </div>




                <!-- Quản lý đặt lịch -->
                <div class="menu-item {{ request()->routeIs('appointment.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('appointment.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                        <span class="menu-title">Quản lý đặt lịch</span>
                    </a>
                </div>
                <!-- Quản lý bài viết -->
                <div class="menu-item {{ request()->routeIs('posts.*') ? 'here' : '' }}">
                    <a class="menu-link" href="{{ route('posts.index') }}">
                        <span class="menu-icon">
                            <i class="fas fa-newspaper"></i>
                        </span>
                        <span class="menu-title">Quản lý bài viết</span>
                    </a>
                </div>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Aside Menu-->
    </div>
    <!--end::Aside menu-->
</div>

<style>
    /* Font Awesome Icons */
    .menu-icon i {
        font-size: 18px;
        color: #7E8299;
    }

    /* Active Menu */
    .menu-item.here .menu-link {
        background-color: #F7F9FC;
        border-radius: 0.475rem;
    }

    .menu-item.here .menu-link .menu-title {
        color: #009EF7;
        font-weight: 600;
    }

    .menu-item.here .menu-link .menu-icon i {
        color: #009EF7;
    }

    /* Hover */
    .menu-item:hover .menu-link .menu-title,
    .menu-item:hover .menu-link .menu-icon i {
        color: #009EF7;
    }
</style>
