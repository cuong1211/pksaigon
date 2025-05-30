@extends('frontend.layouts.index')
@section('content')
    <!-- Page Header Start -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-2" data-cursor="-opaque">{{ $post->title }}</h1>
                        <div class="post-single-meta wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                </li>
                                @if ($post->views_count > 0)
                                    <li class="breadcrumb-item">
                                        <i class="fa-regular fa-eye"></i> {{ $post->views_count }} lượt xem
                                    </li>
                                @endif
                            </ol>
                        </div>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Page Single Post Start -->
    <div class="page-single-post">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Post Featured Image Start -->
                    @if ($post->featured_image)
                        <div class="post-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}">
                            </figure>
                        </div>
                    @endif
                    <!-- Post Featured Image End -->

                    <!-- Post Single Content Start -->
                    <div class="post-content">
                        <!-- Post Entry Start -->
                        <div class="post-entry">
                            {!! $post->content !!}
                        </div>
                        <!-- Post Entry End -->

                        <!-- Post Tag Links Start -->
                        <div class="post-tag-links">
                            <div class="row align-items-center">
                                <div class="col-lg-6">
                                    <!-- Post Info Start -->
                                    <div class="post-info wow fadeInUp" data-wow-delay="0.5s">
                                      <div class="post-details">
                                              @if ($post->updated_at->ne($post->created_at))
                                                <span class="detail-item">
                                                    <strong>Cập nhật:</strong> {{ $post->updated_at->format('d/m/Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Post Info End -->
                                </div>

                                <div class="col-lg-6">
                                    <!-- Post Social Links Start -->
                                    <div class="post-social-sharing wow fadeInUp" data-wow-delay="0.5s">
                                        <span class="share-label">Chia sẻ:</span>
                                        <ul>
                                            <li>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                    target="_blank" title="Chia sẻ Facebook">
                                                    <i class="fa-brands fa-facebook-f"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="copyToClipboard()"
                                                    title="Sao chép link">
                                                    <i class="fa-solid fa-link"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- Post Social Links End -->
                                </div>
                            </div>
                        </div>
                        <!-- Post Tag Links End -->

                        <!-- Navigation Links Start -->
                        <div class="post-navigation">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="nav-previous">
                                        <a href="{{ route('frontend.posts') }}" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-arrow-left"></i> Về trang tin tức
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="nav-appointment">
                                        <a href="{{ route('frontend.appointment') }}" class="btn btn-primary">
                                            <i class="fa-solid fa-calendar-check"></i> Đặt lịch khám
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Navigation Links End -->

                        <!-- Related Posts Start -->
                        @if ($relatedPosts->count() > 0)
                            <div class="related-posts">
                                <h3 class="section-title">Bài viết liên quan</h3>
                                <div class="row">
                                    @foreach ($relatedPosts->take(3) as $relatedPost)
                                        <div class="col-lg-4 col-md-6">
                                            <div class="related-post-item">
                                                <div class="post-thumbnail">
                                                    <a href="{{ route('frontend.posts.show', $relatedPost->slug) }}">
                                                        <img src="{{ $relatedPost->featured_image_url }}"
                                                            alt="{{ $relatedPost->title }}">
                                                    </a>
                                                </div>
                                                <div class="post-info">
                                                    <h4>
                                                        <a href="{{ route('frontend.posts.show', $relatedPost->slug) }}">
                                                            {{ Str::limit($relatedPost->title, 60) }}
                                                        </a>
                                                    </h4>
                                                    <div class="post-meta">
                                                        <span>
                                                            <i class="fa-regular fa-calendar"></i>
                                                            {{ $relatedPost->published_at ? $relatedPost->published_at->format('d/m/Y') : $relatedPost->created_at->format('d/m/Y') }}
                                                        </span>
                                                        <span>
                                                            <i class="fa-regular fa-eye"></i>
                                                            {{ $relatedPost->views_count }} lượt xem
                                                        </span>
                                                    </div>
                                                    <p>{{ Str::limit($relatedPost->excerpt, 100) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- Related Posts End -->
                    </div>
                    <!-- Post Single Content End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Single Post End -->
@endsection

@push('jscustom')
    <script>
        // Copy to clipboard function
        function copyToClipboard() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                // Show success message - có thể dùng toast notification nếu có
                alert('Đã sao chép liên kết vào clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
                alert('Không thể sao chép liên kết. Vui lòng thử lại!');
            });
        }
    </script>
@endpush

@push('csscustom')
    <style>
        /* Post Meta trong breadcrumb */
        .breadcrumb .breadcrumb-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: rgba(255, 255, 255, 0.9);
        }

        .breadcrumb .breadcrumb-item i {
            color: #ffd93d;
        }

        .breadcrumb .breadcrumb-item.featured-post {
            background: rgba(255, 215, 0, 0.2);
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
        }

        /* Post Details */
        .post-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .detail-item {
            font-size: 0.9rem;
            color: #666;
        }

        .detail-item strong {
            color: #333;
        }

        /* Social Sharing */
        .post-social-sharing {
            text-align: right;
        }

        .share-label {
            color: #666;
            font-weight: 600;
            margin-right: 15px;
        }

        .post-social-sharing ul {
            display: inline-flex;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .post-social-sharing ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            background: #007bff;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .post-social-sharing ul li a:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        /* Post Tag Links */
        .post-tag-links {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }

        /* Navigation */
        .post-navigation {
            margin: 30px 0;
            padding: 20px 0;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background: #0056b3;
            border-color: #0056b3;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            background: transparent;
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        /* Related Posts */
        .related-posts {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 3px;
            background: #007bff;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
        }

        .related-post-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .related-post-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .post-thumbnail {
            height: 180px;
            overflow: hidden;
        }

        .post-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .post-thumbnail:hover img {
            transform: scale(1.05);
        }

        .related-post-item .post-info {
            padding: 20px;
        }

        .related-post-item .post-info h4 {
            margin: 0 0 10px 0;
            font-size: 1rem;
            line-height: 1.4;
        }

        .related-post-item .post-info h4 a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .related-post-item .post-info h4 a:hover {
            color: #007bff;
        }

        .related-post-item .post-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 0.8rem;
            color: #666;
        }

        .related-post-item .post-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .related-post-item .post-meta i {
            color: #007bff;
        }

        .related-post-item p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .post-social-sharing {
                text-align: left;
                margin-top: 20px;
            }

            .post-details {
                margin-bottom: 20px;
            }

            .post-navigation .text-end {
                text-align: left !important;
                margin-top: 15px;
            }

            .breadcrumb .breadcrumb-item {
                font-size: 0.85rem;
            }
        }
    </style>
@endpush
