<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.service.main');
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
    public function store(ServiceRequest $request)
    {
        try {
            $data = $request->validated();

            // Tạo slug nếu không có
            if (empty($data['slug'])) {
                $data['slug'] = Service::generateUniqueSlug($data['name']);
            } else {
                // Kiểm tra slug có trùng không
                $data['slug'] = Service::generateUniqueSlug($data['slug']);
            }

            // Xử lý upload ảnh
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('services', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            Service::create($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Thêm dịch vụ thành công!'
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

        // Route mới để lấy dữ liệu chi tiết cho edit
        if ($id == 'get-data') {
            return $this->getData($id);
        }

        $service = Service::findOrFail($id);
        return response()->json([
            'type' => 'success',
            'data' => $service
        ]);
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
    public function update(ServiceRequest $request, string $id)
    {
        try {
            $service = Service::findOrFail($id);
            $data = $request->validated();

            // Xử lý slug
            if (empty($data['slug'])) {
                $data['slug'] = Service::generateUniqueSlug($data['name'], $service->id);
            } else {
                $data['slug'] = Service::generateUniqueSlug($data['slug'], $service->id);
            }

            // Xử lý upload ảnh mới
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                }

                // Upload ảnh mới
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('services', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $service->update($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật dịch vụ thành công!'
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

            $service = Service::findOrFail($id);

            // Xóa ảnh nếu có
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $service->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa dịch vụ thành công!'
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
     * Lấy dữ liệu chi tiết cho edit
     */

    /**
     * Get list for DataTable
     */
    private function getList()
    {
        $query = Service::select([
            'id',
            'name',
            'slug',
            'description',
            'type',
            'price',
            'image',
            'is_active',
            'created_at'
        ]);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (request()->has('status_filter') && !empty(request()->status_filter)) {
            $query->where('is_active', request()->status_filter);
        }

        // Apply type filter
        if (request()->has('type_filter') && !empty(request()->type_filter)) {
            $query->where('type', request()->type_filter);
        }

        $services = $query->orderBy('created_at', 'desc')->get();

        return DataTables::of($services)
            ->addColumn('image_display', function ($service) {
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    $url = app()->environment('production')
                        ? url('public/storage/' . $service->image)
                        : url('storage/' . $service->image);
                    return $url;
                }
                $defaultUrl = app()->environment('production')
                    ? url('public/images/default-service.png')
                    : url('images/default-service.png');
                return $defaultUrl;
            })
            ->addColumn('status_badge', function ($service) {
                if ($service->is_active) {
                    return '<span class="badge badge-light-success">Hoạt động</span>';
                } else {
                    return '<span class="badge badge-light-danger">Ngưng hoạt động</span>';
                }
            })
            ->addColumn('type_badge', function ($service) {
                $typeLabels = [
                    'procedure' => ['label' => 'Thủ thuật', 'class' => 'badge-light-primary'],
                    'laboratory' => ['label' => 'Xét nghiệm', 'class' => 'badge-light-success'],
                    'other' => ['label' => 'Khác', 'class' => 'badge-light-info'],
                ];

                $type = $typeLabels[$service->type] ?? ['label' => $service->type, 'class' => 'badge-light-secondary'];
                return '<span class="badge ' . $type['class'] . '">' . $type['label'] . '</span>';
            })
            ->addColumn('formatted_price', function ($service) {
                return number_format($service->price, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('short_description', function ($service) {
                return $service->description ? Str::limit(strip_tags($service->description), 50) : '-';
            })
            ->rawColumns(['status_badge', 'type_badge'])
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $total = Service::count();
            $active = Service::where('is_active', true)->count();
            $inactive = Service::where('is_active', false)->count();
            $avgPrice = Service::avg('price');

            // Count by types
            $procedure = Service::where('type', 'procedure')->count();
            $laboratory = Service::where('type', 'laboratory')->count();
            $other = Service::where('type', 'other')->count();

            return response()->json([
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'avg_price' => number_format($avgPrice, 0, '.', '.') . ' VNĐ',
                'procedure' => $procedure,
                'laboratory' => $laboratory,
                'other' => $other
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'avg_price' => '0 VNĐ',
                'procedure' => 0,
                'laboratory' => 0,
                'other' => 0
            ]);
        }
    }

    /**
     * Bulk delete services
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có dịch vụ nào được chọn!'
                ], 400);
            }

            $services = Service::whereIn('id', $ids)->get();

            // Xóa ảnh của các dịch vụ
            foreach ($services as $service) {
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                }
            }

            $deletedCount = Service::whereIn('id', $ids)->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} dịch vụ thành công!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getData($id)
    {
        try {
            $service = Service::findOrFail($id);
            // Chuẩn bị dữ liệu trả về
            $data = [
                'id' => $service->id,
                'name' => $service->name,
                'slug' => $service->slug,
                'description' => $service->description,
                'type' => $service->type,
                'price' => (int)$service->price, // Chuyển về integer để bỏ .00
                'is_active' => $service->is_active,
                'image_url' => $service->image_url,
                'has_image' => $service->image && Storage::disk('public')->exists($service->image),
                'created_at' => $service->created_at->format('d/m/Y H:i'),
                'updated_at' => $service->updated_at->format('d/m/Y H:i')
            ];

            return response()->json([
                'type' => 'success',
                'data' => $data
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Không tìm thấy dịch vụ với ID: ' . $id
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
