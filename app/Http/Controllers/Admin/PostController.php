<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        try {
            $data = $request->validated();
            $data['author_id'] = Auth::id();

            // Tạo slug nếu không có
            if (empty($data['slug'])) {
                $data['slug'] = Post::generateUniqueSlug($data['title']);
            } else {
                $data['slug'] = Post::generateUniqueSlug($data['slug']);
            }

            // Xử lý upload ảnh
            if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('posts', $imageName, 'public');
                $data['featured_image'] = $imagePath;
            }

            Post::create($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Bài viết đã được tạo thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if ($id == 'get-list') {
            return $this->getList();
        }

        if ($id == 'get-statistics') {
            return $this->getStatistics();
        }

        try {
            $post = Post::with('author')->findOrFail($id);
            
            return response()->json([
                'type' => 'success',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy bài viết'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, string $id)
    {
        try {
            $post = Post::findOrFail($id);
            $data = $request->validated();

            // Xử lý slug
            if (empty($data['slug'])) {
                $data['slug'] = Post::generateUniqueSlug($data['title'], $post->id);
            } else {
                $data['slug'] = Post::generateUniqueSlug($data['slug'], $post->id);
            }

            // Xử lý upload ảnh mới
            if ($request->hasFile('featured_image')) {
                // Xóa ảnh cũ
                if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                    Storage::disk('public')->delete($post->featured_image);
                }

                // Upload ảnh mới
                $image = $request->file('featured_image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('posts', $imageName, 'public');
                $data['featured_image'] = $imagePath;
            }

            $post->update($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Bài viết đã được cập nhật thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if ($id === 'bulk') {
                return $this->bulkDestroy();
            }

            $post = Post::findOrFail($id);

            // Xóa ảnh đại diện nếu có
            if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $post->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Bài viết đã được xóa thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data for edit
     */
    public function getData($id)
    {
        try {
            $post = Post::findOrFail($id);

            $data = [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'content' => $post->content,
                'status' => $post->status,
                'is_featured' => $post->is_featured,
                'featured_image_url' => $post->featured_image_url,
                'has_image' => $post->featured_image && Storage::disk('public')->exists($post->featured_image),
                'author_name' => $post->author->name ?? 'N/A',
                'views_count' => $post->views_count,
                'created_at' => $post->created_at->format('d/m/Y H:i'),
                'updated_at' => $post->updated_at->format('d/m/Y H:i')
            ];

            return response()->json([
                'type' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy bài viết với ID: ' . $id
            ], 404);
        }
    }

    /**
     * Get list for DataTable
     */
    private function getList()
    {
        $query = Post::with('author')->select([
            'id',
            'title',
            'slug', 
            'content',
            'featured_image',
            'status',
            'is_featured',
            'views_count',
            'author_id',
            'created_at'
        ]);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
      

        $posts = $query->orderBy('created_at', 'desc')->get();

        return DataTables::of($posts)
            ->addColumn('author_name', function ($post) {
                return $post->author->name ?? 'N/A';
            })
            ->addColumn('excerpt', function ($post) {
                return Str::limit(strip_tags($post->content), 100);
            })
            ->addColumn('featured_image_url', function ($post) {
                return $post->featured_image_url;
            })
            ->addColumn('status_badge', function ($post) {
                if ($post->status) {
                    return '<span class="badge badge-light-success">Hiện</span>';
                } else {
                    return '<span class="badge badge-light-danger">Ẩn</span>';
                }
            })
            ->addColumn('formatted_date', function ($post) {
                return $post->created_at->format('d/m/Y H:i');
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    /**
     * Get statistics
     */
    private function getStatistics()
    {
        try {
            $total = Post::count();
            $visible = Post::where('status', true)->count();
            $hidden = Post::where('status', false)->count();
            $featured = Post::where('is_featured', true)->count();
            $totalViews = Post::sum('views_count');

            return response()->json([
                'total' => $total,
                'visible' => $visible,
                'hidden' => $hidden,
                'featured' => $featured,
                'total_views' => $totalViews
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'visible' => 0,
                'hidden' => 0,
                'featured' => 0,
                'total_views' => 0
            ]);
        }
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(string $id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->update(['is_featured' => !$post->is_featured]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Trạng thái nổi bật đã được cập nhật!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete posts
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có bài viết nào được chọn!'
                ], 400);
            }

            $posts = Post::whereIn('id', $ids)->get();

            // Xóa ảnh của các bài viết
            foreach ($posts as $post) {
                if ($post->featured_image && Storage::disk('public')->exists($post->featured_image)) {
                    Storage::disk('public')->delete($post->featured_image);
                }
            }

            $deletedCount = Post::whereIn('id', $ids)->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} bài viết thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}