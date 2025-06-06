<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicineRequest;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.medicine.main');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineRequest $request)
    {
        try {
            $data = $request->validated();

            // Xử lý upload ảnh
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('medicines', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            Medicine::create($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Thêm thuốc thành công!'
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

        if ($id == 'get-data') {
            return $this->getData();
        }

        $medicine = Medicine::findOrFail($id);
        return response()->json([
            'type' => 'success',
            'data' => $medicine
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicineRequest $request, string $id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $data = $request->validated();

            // Xử lý upload ảnh mới
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ
                if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
                    Storage::disk('public')->delete($medicine->image);
                }

                // Upload ảnh mới
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('medicines', $imageName, 'public');
                $data['image'] = $imagePath;
            }

            $medicine->update($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật thuốc thành công!'
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

            $medicine = Medicine::findOrFail($id);

            // Xóa ảnh nếu có
            if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
                Storage::disk('public')->delete($medicine->image);
            }

            $medicine->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa thuốc thành công!'
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
    public function getData($id = null)
    {
        try {
            if (!$id) {
                $id = request()->get('id');
            }

            $medicine = Medicine::findOrFail($id);

            $data = [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'type' => $medicine->type,
                'description' => $medicine->description,
                'import_price' => (int)$medicine->import_price,
                'sale_price' => (int)$medicine->sale_price,
                'expiry_date' => $medicine->expiry_date ? $medicine->expiry_date->format('Y-m-d') : null,
                'is_active' => $medicine->is_active,
                'image_url' => $medicine->image_url,
                'has_image' => $medicine->image && Storage::disk('public')->exists($medicine->image),
                'created_at' => $medicine->created_at->format('d/m/Y H:i'),
                'updated_at' => $medicine->updated_at->format('d/m/Y H:i')
            ];

            return response()->json([
                'type' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list for DataTable
     */
    private function getList()
    {
        $query = Medicine::select([
            'id',
            'name',
            'type',
            'description',
            'import_price',
            'sale_price',
            'expiry_date',
            'image',
            'is_active',
            'created_at'
        ]);

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

        // Apply type filter
        if (request()->has('type_filter') && !empty(request()->type_filter)) {
            $query->where('type', request()->type_filter);
        }

        // Apply alert filter
        if (request()->has('alert_filter') && !empty(request()->alert_filter)) {
            $alertType = request()->alert_filter;
            switch ($alertType) {
                case 'expiring':
                    $query->whereDate('expiry_date', '<=', now()->addDays(30))
                        ->whereDate('expiry_date', '>', now());
                    break;
                case 'expired':
                    $query->whereDate('expiry_date', '<', now());
                    break;
            }
        }

        $medicines = $query->orderBy('created_at', 'desc')->get();

        return DataTables::of($medicines)
            ->addColumn('image_display', function ($medicines) {
                if ($medicines->image && Storage::disk('public')->exists($medicines->image)) {
                    $url = app()->environment('production')
                        ? url('public/storage/' . $medicines->image)
                        : url('storage/' . $medicines->image);
                    return $url;
                }
                $defaultUrl = app()->environment('production')
                    ? url('public/images/default-service.png')
                    : url('images/default-service.png');
                return $defaultUrl;
            })
            ->addColumn('status_badge', function ($medicine) {
                if ($medicine->is_active) {
                    return '<span class="badge badge-light-success">Hoạt động</span>';
                } else {
                    return '<span class="badge badge-light-danger">Ngưng hoạt động</span>';
                }
            })
            ->addColumn('type_badge', function ($medicine) {
                $typeLabels = [
                    'supplement' => ['label' => 'TPCN', 'class' => 'badge-light-info'],
                    'medicine' => ['label' => 'Thuốc', 'class' => 'badge-light-primary'],
                    'other' => ['label' => 'Khác', 'class' => 'badge-light-secondary'],
                ];

                $type = $typeLabels[$medicine->type] ?? ['label' => $medicine->type, 'class' => 'badge-light-secondary'];
                return '<span class="badge ' . $type['class'] . '">' . $type['label'] . '</span>';
            })
            ->addColumn('alerts', function ($medicine) {
                $alerts = [];

                // Kiểm tra hạn sử dụng
                if ($medicine->expiry_date) {
                    $diffDays = now()->diffInDays($medicine->expiry_date, false);
                    if ($diffDays < 0) {
                        $alerts[] = '<span class="badge badge-danger">Hết hạn</span>';
                    } elseif ($diffDays <= 30) {
                        $alerts[] = '<span class="badge badge-warning">Sắp hết hạn</span>';
                    }
                }

                return implode(' ', $alerts);
            })
            ->addColumn('formatted_import_price', function ($medicine) {
                return number_format($medicine->import_price, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('formatted_sale_price', function ($medicine) {
                return number_format($medicine->sale_price, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('formatted_expiry_date', function ($medicine) {
                return $medicine->expiry_date ? $medicine->expiry_date->format('d/m/Y') : '';
            })
            ->addColumn('short_description', function ($medicine) {
                return $medicine->description ? Str::limit(strip_tags($medicine->description), 50) : '-';
            })
            ->rawColumns(['status_badge', 'type_badge', 'alerts'])
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $total = Medicine::count();
            $active = Medicine::where('is_active', true)->count();
            $inactive = Medicine::where('is_active', false)->count();
            $expiringSoon = Medicine::whereDate('expiry_date', '<=', now()->addDays(30))
                ->whereDate('expiry_date', '>', now())
                ->count();
            $expired = Medicine::whereDate('expiry_date', '<', now())->count();

            // Count by types
            $supplement = Medicine::where('type', 'supplement')->count();
            $medicine = Medicine::where('type', 'medicine')->count();
            $other = Medicine::where('type', 'other')->count();

            return response()->json([
                'total' => $total,
                'active' => $active,
                'inactive' => $inactive,
                'expiring_soon' => $expiringSoon,
                'expired' => $expired,
                'supplement' => $supplement,
                'medicine' => $medicine,
                'other' => $other
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'expiring_soon' => 0,
                'expired' => 0,
                'supplement' => 0,
                'medicine' => 0,
                'other' => 0
            ]);
        }
    }

    /**
     * Bulk delete medicines
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có thuốc nào được chọn!'
                ], 400);
            }

            $medicines = Medicine::whereIn('id', $ids)->get();

            // Xóa ảnh của các thuốc
            foreach ($medicines as $medicine) {
                if ($medicine->image && Storage::disk('public')->exists($medicine->image)) {
                    Storage::disk('public')->delete($medicine->image);
                }
            }

            $deletedCount = Medicine::whereIn('id', $ids)->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} thuốc thành công!"
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
