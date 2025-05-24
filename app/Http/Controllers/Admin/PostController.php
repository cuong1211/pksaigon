<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $posts = Post::with('author')->select('posts.*');
            
            // Apply search filter
            if ($request->has('search_table') && !empty($request->search_table)) {
                $search = $request->search_table;
                $posts->where(function($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('content', 'like', "%{$search}%")
                          ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }
            
            // Apply status filter
            if ($request->has('status_filter') && !empty($request->status_filter)) {
                $posts->where('status', $request->status_filter);
            }
            
            return DataTables::of($posts)
                ->addColumn('action', function ($post) {
                    return true; // Sẽ được xử lý trong JS
                })
                ->addColumn('author_name', function ($post) {
                    return $post->author->name ?? 'N/A';
                })
                ->editColumn('created_at', function ($post) {
                    return $post->created_at->format('Y-m-d H:i:s');
                })
                ->editColumn('title', function ($post) {
                    return Str::limit($post->title, 50);
                })
                ->editColumn('excerpt', function ($post) {
                    return $post->excerpt ? Str::limit($post->excerpt, 100) : '';
                })
                ->addColumn('featured_image_url', function ($post) {
                    return $post->featured_image_url;
                })
                ->make(true);
        }

        return view('backend.pages.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Tiêu đề là bắt buộc',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'content.required' => 'Nội dung là bắt buộc',
            'excerpt.max' => 'Tóm tắt không được vượt quá 500 ký tự',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
            'featured_image.image' => 'File phải là hình ảnh',
            'featured_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'featured_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
        ]);

        try {
            $data = $request->all();
            $data['author_id'] = Auth::id(); // Lấy ID của người dùng đang đăng nhập
            $data['is_featured'] = $request->has('is_featured');

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
        $post = Post::findOrFail($id);
        return view('backend.pages.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|max:500',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Tiêu đề là bắt buộc',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
            'content.required' => 'Nội dung là bắt buộc',
            'excerpt.max' => 'Tóm tắt không được vượt quá 500 ký tự',
            'status.required' => 'Trạng thái là bắt buộc',
            'status.in' => 'Trạng thái không hợp lệ',
            'featured_image.image' => 'File phải là hình ảnh',
            'featured_image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
            'featured_image.max' => 'Kích thước hình ảnh không được vượt quá 2MB'
        ]);

        try {
            $post = Post::findOrFail($id);
            $data = $request->all();
            $data['is_featured'] = $request->has('is_featured');

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
     * Get posts statistics
     */
    public function getStats()
    {
        try {
            $stats = [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
                'featured' => Post::where('is_featured', true)->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'published' => 0,
                'draft' => 0,
                'featured' => 0,
            ]);
        }
    }
}