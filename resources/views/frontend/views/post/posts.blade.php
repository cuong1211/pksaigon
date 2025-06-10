@extends('frontend.layouts.index')
@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque"><span>Tin tức</span> & Blog</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Tin tức</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Blog Start -->
    <div class="page-blog">
        <div class="container">
            @if ($posts->count() > 0)
                <div class="row">
                    @foreach ($posts as $index => $post)
                        <div class="col-lg-4 col-md-6">
                            <!-- Blog Item Start -->
                            <div class="blog-item wow fadeInUp" data-wow-delay="{{ ($index % 3) * 0.2 }}s">
                                <!-- Post Featured Image Start-->
                                <div class="post-featured-image" data-cursor-text="Xem">
                                    <figure>
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}" class="image-anime">
                                            @if ($post->featured_image && file_exists(public_path('storage/' . $post->featured_image)))
                                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}">
                                            @else
                                                <img src="{{ asset('frontend/images/favicon_1.png') }}" alt="Default Image">
                                            @endif
                                        </a>
                                    </figure>
                                </div>
                                <!-- Post Featured Image End -->

                                <!-- post Item Body Start -->
                                <div class="post-item-body">
                                    <h2><a href="{{ route('frontend.posts.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h2>
                                    <p>{{ $post->excerpt }}</p>

                                    <!-- Post Meta Info (thêm thông tin) -->
                                    <div class="post-meta-info">
                                        <span class="post-author">
                                            <i class="fa-regular fa-user"></i> {{ $post->author->name ?? 'Admin' }}
                                        </span>
                                        <span class="post-date">
                                            <i class="fa-regular fa-clock"></i>
                                            {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                        </span>
                                        @if ($post->views_count > 0)
                                            <span class="post-views">
                                                <i class="fa-regular fa-eye"></i> {{ $post->views_count }} lượt xem
                                            </span>
                                        @endif
                                        @if ($post->is_featured)
                                            <span class="featured-badge">
                                                <i class="fa-solid fa-star"></i> Nổi bật
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <!-- Post Item Body End-->

                                <!-- Post Item Footer Start-->
                                <div class="post-item-footer">
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}" class="read-more-btn">Đọc
                                        thêm</a>
                                </div>
                                <!-- Post Item Footer End-->
                            </div>
                            <!-- Blog Item End -->
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Start -->
                @if ($posts->hasPages())
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Post Pagination Start -->
                            <div class="post-pagination wow fadeInUp" data-wow-delay="1.8s">
                                {{ $posts->links('frontend.pagination.custom') }}
                            </div>
                            <!-- Post Pagination End -->
                        </div>
                    </div>
                @endif
                <!-- Pagination End -->
            @else
                <!-- No Posts Message -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="no-posts-message text-center">
                            <div class="no-posts-icon">
                                <i class="fas fa-newspaper fa-5x text-muted"></i>
                            </div>
                            <h3 class="mt-4">Chưa có bài viết nào</h3>
                            <p class="text-muted">Hãy quay lại sau để xem những bài viết mới nhất từ chúng tôi.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Về trang chủ</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Page Blog End -->
@endsection

@push('csscustom')
    <style>
        /* Thêm CSS cho thông tin meta */
        .post-meta-info {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #f0f0f0;
            font-size: 0.85rem;
            color: #666;
        }

        .post-meta-info span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-meta-info i {
            color: #007bff;
            font-size: 0.8rem;
        }

        .featured-badge {
            background: linear-gradient(45deg, #ffd700, #ffed4e);
            color: #333;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(255, 215, 0, 0.3);
        }

        /* No posts styling */
        .no-posts-message {
            padding: 60px 20px;
        }

        .no-posts-icon {
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .post-meta-info {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
@endpush
