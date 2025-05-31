<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicineImportRequest;
use App\Models\Medicine;
use App\Models\MedicineImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MedicineImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.medicine-import.main');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineImportRequest $request)
    {
        try {
            $data = $request->validated();

            // Xử lý upload ảnh hóa đơn
            if ($request->hasFile('invoice_image')) {
                $image = $request->file('invoice_image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('medicine-imports', $imageName, 'public');
                $data['invoice_image'] = $imagePath;
            }

            MedicineImport::create($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Thêm phiếu nhập thuốc thành công!'
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

        if ($id == 'get-medicines') {
            return $this->getMedicines();
        }

        if ($id == 'get-data') {
            return $this->getData();
        }

        $import = MedicineImport::with('medicine')->findOrFail($id);
        return response()->json([
            'type' => 'success',
            'data' => $import
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicineImportRequest $request, string $id)
    {
        try {
            $import = MedicineImport::findOrFail($id);
            $data = $request->validated();

            // Xử lý upload ảnh hóa đơn mới
            if ($request->hasFile('invoice_image')) {
                // Xóa ảnh cũ
                if ($import->invoice_image && Storage::disk('public')->exists($import->invoice_image)) {
                    Storage::disk('public')->delete($import->invoice_image);
                }

                // Upload ảnh mới
                $image = $request->file('invoice_image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('medicine-imports', $imageName, 'public');
                $data['invoice_image'] = $imagePath;
            }

            $import->update($data);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật phiếu nhập thuốc thành công!'
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

            $import = MedicineImport::findOrFail($id);

            // Xóa ảnh nếu có
            if ($import->invoice_image && Storage::disk('public')->exists($import->invoice_image)) {
                Storage::disk('public')->delete($import->invoice_image);
            }

            $import->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa phiếu nhập thuốc thành công!'
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

            $import = MedicineImport::with('medicine')->findOrFail($id);

            $data = [
                'id' => $import->id,
                'import_code' => $import->import_code,
                'medicine_id' => $import->medicine_id,
                'medicine_name' => $import->medicine->name ?? '',
                'quantity' => $import->quantity,
                'total_amount' => $import->total_amount,
                'import_date' => $import->import_date->format('Y-m-d'),
                'notes' => $import->notes,
                'has_invoice' => $import->invoice_image && Storage::disk('public')->exists($import->invoice_image),
                'invoice_image_url' => $import->invoice_image_url,
                'created_at' => $import->created_at->format('d/m/Y H:i'),
                'updated_at' => $import->updated_at->format('d/m/Y H:i')
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
        $query = MedicineImport::with('medicine')
            ->select([
                'id',
                'import_code',
                'medicine_id',
                'quantity',
                'total_amount',
                'import_date',
                'invoice_image',
                'notes',
                'created_at'
            ]);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('import_code', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('medicine', function ($mq) use ($search) {
                        $mq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply date filter
        if (request()->has('date_filter') && !empty(request()->date_filter)) {
            $dateFilter = request()->date_filter;
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('import_date', today());
                    break;
                case 'week':
                    $query->whereBetween('import_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('import_date', now()->month)
                        ->whereYear('import_date', now()->year);
                    break;
            }
        }

        // Apply medicine filter
        if (request()->has('medicine_filter') && !empty(request()->medicine_filter)) {
            $query->where('medicine_id', request()->medicine_filter);
        }

        $imports = $query->orderBy('import_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return DataTables::of($imports)
            ->addColumn('medicine_info', function ($import) {
                return [
                    'name' => $import->medicine->name ?? 'N/A',
                    'type' => $import->medicine->type ?? 'other'
                ];
            })
            ->addColumn('formatted_total_amount', function ($import) {
                return number_format($import->total_amount, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('formatted_import_date', function ($import) {
                return $import->import_date->format('d/m/Y');
            })
            ->addColumn('has_invoice', function ($import) {
                return $import->invoice_image && Storage::disk('public')->exists($import->invoice_image);
            })
            ->addColumn('invoice_image_url', function ($import) {
                if ($import->invoice_image && Storage::disk('public')->exists($import->invoice_image)) {
                    return asset('storage/' . $import->invoice_image);
                }
                return null;
            })
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $totalImports = MedicineImport::count();
            $todayImports = MedicineImport::whereDate('import_date', today())->count();
            $monthImports = MedicineImport::whereMonth('import_date', now()->month)
                ->whereYear('import_date', now()->year)
                ->count();

            $totalValue = MedicineImport::sum('total_amount');
            $monthValue = MedicineImport::whereMonth('import_date', now()->month)
                ->whereYear('import_date', now()->year)
                ->sum('total_amount');

            return response()->json([
                'total_imports' => $totalImports,
                'today_imports' => $todayImports,
                'month_imports' => $monthImports,
                'total_value' => number_format($totalValue, 0, '.', '.') . ' VNĐ',
                'month_value' => number_format($monthValue, 0, '.', '.') . ' VNĐ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total_imports' => 0,
                'today_imports' => 0,
                'month_imports' => 0,
                'total_value' => '0 VNĐ',
                'month_value' => '0 VNĐ'
            ]);
        }
    }

    /**
     * Get medicines list for select dropdown
     */
    private function getMedicines()
    {
        $medicines = Medicine::where('is_active', true)
            ->select('id', 'name', 'type', 'import_price')
            ->orderBy('name')
            ->get();

        $formattedMedicines = $medicines->map(function ($medicine) {
            $typeNames = [
                'supplement' => 'TPCN',
                'medicine' => 'Thuốc',
                'other' => 'Khác'
            ];

            return [
                'id' => $medicine->id,
                'text' => $medicine->name . ' (' . ($typeNames[$medicine->type] ?? 'Khác') . ') - ' . number_format($medicine->import_price, 0, '.', '.') . ' VNĐ',
                'name' => $medicine->name,
                'type_name' => $typeNames[$medicine->type] ?? 'Khác',
                'import_price' => $medicine->import_price
            ];
        });

        return response()->json($formattedMedicines);
    }

    /**
     * Bulk delete imports
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có phiếu nhập nào được chọn!'
                ], 400);
            }

            $imports = MedicineImport::whereIn('id', $ids)->get();

            // Xóa ảnh của các phiếu nhập
            foreach ($imports as $import) {
                if ($import->invoice_image && Storage::disk('public')->exists($import->invoice_image)) {
                    Storage::disk('public')->delete($import->invoice_image);
                }
            }

            $deletedCount = MedicineImport::whereIn('id', $ids)->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} phiếu nhập thành công!"
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
