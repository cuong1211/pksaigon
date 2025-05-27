<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MedicineImportRequest;
use App\Models\MedicineImport;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        $import = MedicineImport::with('medicine')->findOrFail($id);
        return response()->json([
            'type' => 'success',
            'data' => $import
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

            // Xóa ảnh hóa đơn nếu có
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
     * Get list for DataTable
     */
    private function getList()
    {
        $query = MedicineImport::with('medicine')
                              ->select(['id', 'import_code', 'medicine_id', 'quantity', 'unit_price', 
                                       'total_price', 'import_date', 'invoice_image', 'created_at']);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('import_code', 'like', "%{$search}%")
                  ->orWhereHas('medicine', function ($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
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

        $imports = $query->orderBy('created_at', 'desc')->get();
        
        return DataTables::of($imports)
            ->addColumn('medicine_info', function ($import) {
                return [
                    'name' => $import->medicine->name ?? 'N/A',
                    'code' => $import->medicine->code ?? 'N/A',
                    'unit' => $import->medicine->unit ?? 'N/A'
                ];
            })
            ->addColumn('formatted_unit_price', function ($import) {
                return number_format($import->unit_price, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('formatted_total_price', function ($import) {
                return number_format($import->total_price, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('formatted_import_date', function ($import) {
                return $import->import_date->format('d/m/Y');
            })
            ->addColumn('import_date_value', function ($import) {
                return $import->import_date->format('Y-m-d'); // Thêm format cho input date
            })
            ->addColumn('invoice_image_url', function ($import) {
                return $import->invoice_image_url;
            })
            ->addColumn('has_invoice', function ($import) {
                return $import->invoice_image ? true : false;
            })
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $total_imports = MedicineImport::count();
            $today_imports = MedicineImport::whereDate('import_date', today())->count();
            $month_imports = MedicineImport::whereMonth('import_date', now()->month)
                                          ->whereYear('import_date', now()->year)
                                          ->count();
            $total_value = MedicineImport::sum('total_price');
            $month_value = MedicineImport::whereMonth('import_date', now()->month)
                                        ->whereYear('import_date', now()->year)
                                        ->sum('total_price');
            $total_quantity = MedicineImport::sum('quantity');

            return response()->json([
                'total_imports' => $total_imports,
                'today_imports' => $today_imports,
                'month_imports' => $month_imports,
                'total_value' => number_format($total_value, 0, '.', '.') . ' VNĐ',
                'month_value' => number_format($month_value, 0, '.', '.') . ' VNĐ',
                'total_quantity' => number_format($total_quantity),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'total_imports' => 0,
                'today_imports' => 0,
                'month_imports' => 0,
                'total_value' => '0 VNĐ',
                'month_value' => '0 VNĐ',
                'total_quantity' => 0,
            ]);
        }
    }

    /**
     * Get medicines list for select dropdown
     */
    private function getMedicines()
    {
        $medicines = Medicine::where('is_active', true)
                           ->select('id', 'name', 'code', 'unit')
                           ->orderBy('name')
                           ->get();

        return response()->json($medicines);
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
            
            // Xóa ảnh hóa đơn của các phiếu nhập
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