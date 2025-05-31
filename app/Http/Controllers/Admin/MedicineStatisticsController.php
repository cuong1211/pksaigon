<?php
// File: app/Http/Controllers/Admin/MedicineStatisticsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\MedicineImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicineStatisticsController extends Controller
{
    /**
     * Hiển thị trang thống kê
     */
    public function index()
    {
        return view('backend.pages.medicine-statistics.main');
    }

    /**
     * Lấy thống kê tổng quan
     */
    public function getOverviewStats()
    {
        try {
            // Tổng số loại thuốc
            $totalMedicines = Medicine::count();
            $activeMedicines = Medicine::where('is_active', true)->count();
            $inactiveMedicines = Medicine::where('is_active', false)->count();

            // Thuốc theo loại
            $medicinesByType = Medicine::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get()
                ->pluck('count', 'type');

            // Thuốc sắp hết hạn (trong 30 ngày)
            $expiringSoon = Medicine::whereDate('expiry_date', '<=', now()->addDays(30))
                ->whereDate('expiry_date', '>', now())
                ->count();

            // Thuốc đã hết hạn
            $expired = Medicine::whereDate('expiry_date', '<', now())->count();

            // Tổng giá trị nhập kho
            $totalImportValue = MedicineImport::sum('total_amount');

            // Tổng giá trị nhập trong tháng này
            $monthlyImportValue = MedicineImport::whereMonth('import_date', now()->month)
                ->whereYear('import_date', now()->year)
                ->sum('total_amount');

            // Số lượng phiếu nhập
            $totalImports = MedicineImport::count();
            $monthlyImports = MedicineImport::whereMonth('import_date', now()->month)
                ->whereYear('import_date', now()->year)
                ->count();

            return response()->json([
                'overview' => [
                    'total_medicines' => $totalMedicines,
                    'active_medicines' => $activeMedicines,
                    'inactive_medicines' => $inactiveMedicines,
                    'expiring_soon' => $expiringSoon,
                    'expired' => $expired,
                    'total_import_value' => number_format($totalImportValue, 0, '.', '.') . ' VNĐ',
                    'monthly_import_value' => number_format($monthlyImportValue, 0, '.', '.') . ' VNĐ',
                    'total_imports' => $totalImports,
                    'monthly_imports' => $monthlyImports,
                ],
                'medicines_by_type' => $medicinesByType
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy thống kê nhập kho theo tháng (12 tháng gần nhất)
     */
    public function getImportTrends()
    {
        try {
            $trends = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $month = $date->format('m/Y');

                $imports = MedicineImport::whereYear('import_date', $date->year)
                    ->whereMonth('import_date', $date->month)
                    ->get();

                $trends[] = [
                    'month' => $month,
                    'total_imports' => $imports->count(),
                    'total_value' => $imports->sum('total_amount'),
                    'total_quantity' => $imports->sum('quantity')
                ];
            }

            return response()->json($trends);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy top thuốc được nhập nhiều nhất
     */
    public function getTopImportedMedicines(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $period = $request->get('period', 'all'); // all, year, month

            $query = DB::table('medicine_imports')
                ->join('medicines', 'medicine_imports.medicine_id', '=', 'medicines.id')
                ->select(
                    'medicines.name',
                    'medicines.type',
                    DB::raw('SUM(medicine_imports.quantity) as total_quantity'),
                    DB::raw('SUM(medicine_imports.total_amount) as total_value'),
                    DB::raw('COUNT(medicine_imports.id) as import_count')
                )
                ->groupBy('medicines.id', 'medicines.name', 'medicines.type');

            // Áp dụng bộ lọc thời gian
            switch ($period) {
                case 'year':
                    $query->whereYear('medicine_imports.import_date', now()->year);
                    break;
                case 'month':
                    $query->whereYear('medicine_imports.import_date', now()->year)
                        ->whereMonth('medicine_imports.import_date', now()->month);
                    break;
            }

            $medicines = $query->orderBy('total_quantity', 'desc')
                ->limit($limit)
                ->get();

            return response()->json($medicines);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy thống kê thuốc sắp hết hạn
     */
    public function getExpiryReport()
    {
        try {
            // Thuốc đã hết hạn
            $expired = Medicine::where('is_active', true)
                ->whereDate('expiry_date', '<', now())
                ->select('id', 'name', 'type', 'expiry_date', 'sale_price')
                ->orderBy('expiry_date', 'desc')
                ->get();

            // Thuốc sắp hết hạn (7 ngày)
            $expiring7Days = Medicine::where('is_active', true)
                ->whereDate('expiry_date', '>', now())
                ->whereDate('expiry_date', '<=', now()->addDays(7))
                ->select('id', 'name', 'type', 'expiry_date', 'sale_price')
                ->orderBy('expiry_date', 'asc')
                ->get();

            // Thuốc sắp hết hạn (30 ngày)
            $expiring30Days = Medicine::where('is_active', true)
                ->whereDate('expiry_date', '>', now()->addDays(7))
                ->whereDate('expiry_date', '<=', now()->addDays(30))
                ->select('id', 'name', 'type', 'expiry_date', 'sale_price')
                ->orderBy('expiry_date', 'asc')
                ->get();

            return response()->json([
                'expired' => $expired,
                'expiring_7_days' => $expiring7Days,
                'expiring_30_days' => $expiring30Days
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Lấy thống kê theo loại thuốc
     */
    public function getTypeStatistics()
    {
        try {
            $types = ['supplement', 'medicine', 'other'];
            $statistics = [];

            foreach ($types as $type) {
                $medicines = Medicine::where('type', $type);

                $typeStats = [
                    'type' => $type,
                    'type_name' => $this->getTypeName($type),
                    'total' => $medicines->count(),
                    'active' => $medicines->where('is_active', true)->count(),
                    'inactive' => $medicines->where('is_active', false)->count(),
                    'expired' => $medicines->where('is_active', true)
                        ->whereDate('expiry_date', '<', now())
                        ->count(),
                    'expiring_soon' => $medicines->where('is_active', true)
                        ->whereDate('expiry_date', '>=', now())
                        ->whereDate('expiry_date', '<=', now()->addDays(30))
                        ->count(),
                ];

                // Thống kê nhập kho cho loại này
                $importStats = DB::table('medicine_imports')
                    ->join('medicines', 'medicine_imports.medicine_id', '=', 'medicines.id')
                    ->where('medicines.type', $type)
                    ->select(
                        DB::raw('SUM(medicine_imports.quantity) as total_quantity'),
                        DB::raw('SUM(medicine_imports.total_amount) as total_value'),
                        DB::raw('COUNT(medicine_imports.id) as import_count')
                    )
                    ->first();

                $typeStats['import_stats'] = [
                    'total_quantity' => $importStats->total_quantity ?: 0,
                    'total_value' => $importStats->total_value ?: 0,
                    'import_count' => $importStats->import_count ?: 0
                ];

                $statistics[] = $typeStats;
            }

            return response()->json($statistics);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Xuất báo cáo Excel
     */
    public function exportReport(Request $request)
    {
        try {
            $type = $request->get('type', 'overview'); // overview, expiry, imports

            // Tạo dữ liệu cho Excel export
            $data = [];

            switch ($type) {
                case 'overview':
                    $data = $this->getOverviewReportData();
                    break;
                case 'expiry':
                    $data = $this->getExpiryReportData();
                    break;
                case 'imports':
                    $data = $this->getImportsReportData();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Dữ liệu báo cáo đã được chuẩn bị',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getTypeName($type)
    {
        $names = [
            'supplement' => 'Thực phẩm chức năng',
            'medicine' => 'Thuốc điều trị',
            'other' => 'Khác'
        ];
        return $names[$type] ?? $type;
    }

    private function getOverviewReportData()
    {
        return Medicine::with('imports')
            ->select('id', 'name', 'type', 'sale_price', 'expiry_date', 'is_active', 'created_at')
            ->get()
            ->map(function ($medicine) {
                return [
                    'Tên thuốc' => $medicine->name,
                    'Loại' => $this->getTypeName($medicine->type),
                    'Giá bán' => number_format($medicine->sale_price, 0, '.', '.'),
                    'Hạn sử dụng' => $medicine->expiry_date ? $medicine->expiry_date->format('d/m/Y') : '',
                    'Trạng thái' => $medicine->is_active ? 'Hoạt động' : 'Ngưng hoạt động',
                    'Tổng số lần nhập' => $medicine->imports->count(),
                    'Tổng số lượng nhập' => $medicine->imports->sum('quantity'),
                    'Tổng giá trị nhập' => number_format($medicine->imports->sum('total_amount'), 0, '.', '.')
                ];
            });
    }

    private function getExpiryReportData()
    {
        return Medicine::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->select('name', 'type', 'expiry_date', 'sale_price')
            ->orderBy('expiry_date', 'asc')
            ->get()
            ->map(function ($medicine) {
                $daysUntilExpiry = $medicine->expiry_date->diffInDays(now(), false);
                $status = '';

                if ($daysUntilExpiry > 0) {
                    $status = 'Đã hết hạn';
                } elseif ($daysUntilExpiry >= -7) {
                    $status = 'Sắp hết hạn (7 ngày)';
                } elseif ($daysUntilExpiry >= -30) {
                    $status = 'Sắp hết hạn (30 ngày)';
                } else {
                    $status = 'Còn hạn';
                }

                return [
                    'Tên thuốc' => $medicine->name,
                    'Loại' => $this->getTypeName($medicine->type),
                    'Hạn sử dụng' => $medicine->expiry_date->format('d/m/Y'),
                    'Số ngày còn lại' => abs($daysUntilExpiry),
                    'Trạng thái' => $status,
                    'Giá bán' => number_format($medicine->sale_price, 0, '.', '.')
                ];
            });
    }

    private function getImportsReportData()
    {
        return MedicineImport::with('medicine')
            ->orderBy('import_date', 'desc')
            ->get()
            ->map(function ($import) {
                return [
                    'Mã phiếu nhập' => $import->import_code,
                    'Tên thuốc' => $import->medicine->name,
                    'Loại thuốc' => $this->getTypeName($import->medicine->type),
                    'Số lượng' => $import->quantity,
                    'Tổng tiền' => number_format($import->total_amount, 0, '.', '.'),
                    'Ngày nhập' => $import->import_date->format('d/m/Y'),
                    'Ghi chú' => $import->notes ?: ''
                ];
            });
    }
}
