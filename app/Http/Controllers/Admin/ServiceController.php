<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
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

            // Tạo slug nếu chưa có
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
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

        $service = Service::findOrFail($id);
        return response()->json($service);
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

            // Tạo slug nếu chưa có
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
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
     * Get list for DataTable
     */
    private function getList()
    {
        $query = Service::select(['id', 'name', 'description', 'price', 'duration', 'is_active', 'slug', 'image', 'created_at']);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if (request()->has('status_filter') && !empty(request()->status_filter)) {
            $query->where('is_active', request()->status_filter);
        }

        // Apply price range filter
        if (request()->has('price_range_filter') && !empty(request()->price_range_filter)) {
            $priceRange = request()->price_range_filter;
            if ($priceRange === '1000000+') {
                $query->where('price', '>=', 1000000);
            } else {
                $ranges = explode('-', $priceRange);
                if (count($ranges) === 2) {
                    $query->whereBetween('price', [(float)$ranges[0], (float)$ranges[1]]);
                }
            }
        }

        $services = $query->orderBy('created_at', 'desc')->get();
        return DataTables::of($services)
            ->addColumn('formatted_price', function ($service) {
                return number_format($service->price, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('status_badge', function ($service) {
                if ($service->is_active) {
                    return '<span class="badge badge-light-success">Hoạt động</span>';
                } else {
                    return '<span class="badge badge-light-danger">Tạm dừng</span>';
                }
            })
            ->addColumn('short_description', function ($service) {
                return Str::limit($service->description, 50);
            })
            ->rawColumns(['status_badge'])
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
            $averagePrice = Service::avg('price');

            return response()->json([
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'average_price' => $averagePrice ? number_format($averagePrice, 0, '.', '.') . ' VNĐ' : '0 VNĐ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'average_price' => '0 VNĐ'
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
}
