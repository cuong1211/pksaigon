@extends('backend.layout.index')

@section('title', 'Quản lý bài viết')

@section('breadcrumb')
<div class="page-title d-flex flex-column me-5">
    <h1 class="d-flex flex-column text-dark fw-bolder fs-3 mb-0">Quản lý bài viết</h1>
    <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 pt-1">
        <li class="breadcrumb-item text-muted">
            <a href="{{ route('admin') }}" class="text-muted text-hover-primary">Trang chủ</a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-200 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-dark">Quản lý bài viết</li>
    </ul>
</div>
@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="black"/>
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="black"/>
                                </svg>
                            </span>
                            <input type="text" data-kt-posts-table-filter="search" class="form-control form-control-solid w-250px ps-15 search_table" placeholder="Tìm kiếm bài viết"/>
                        </div>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-posts-table-toolbar="base">
                            <!-- Filter -->
                            <select class="form-select form-select-solid w-150px me-3 search_table" data-filter="status">
                                <option value="">Tất cả trạng thái</option>
                                <option value="published">Đã xuất bản</option>
                                <option value="draft">Bản nháp</option>
                                <option value="archived">Lưu trữ</option>
                            </select>

                            <!-- Add button -->
                            <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#kt_modal_add_post">
                                Thêm bài viết
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_posts_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">STT</th>
                                <th class="min-w-200px">Tiêu đề</th>
                                <th class="min-w-100px">Tác giả</th>
                                <th class="min-w-100px">Trạng thái</th>
                                <th class="min-w-100px">Lượt xem</th>
                                <th class="min-w-100px">Ngày tạo</th>
                                <th class="text-end min-w-100px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="fw-bold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Add/Edit Post -->
            @include('backend.pages.posts.modal')
        </div>
    </div>
</div>
@endsection

@push('jscustom')
<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>
@include('backend.pages.posts.js')
@endpush