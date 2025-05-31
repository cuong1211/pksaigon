<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Medicine;
use App\Models\MedicineUsage;
use App\Services\VietQRService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExaminationController extends Controller
{
    protected $vietqrService;

    public function __construct(VietQRService $vietqrService)
    {
        $this->vietqrService = $vietqrService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.examination.main');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:patients,id',
                'examination_date' => 'required|date',
                'services' => 'nullable|json',
                'medicines' => 'nullable|json',
                'diagnosis' => 'nullable|string|max:1000',
                'symptoms' => 'nullable|string|max:1000',
                'treatment_plan' => 'nullable|string|max:2000',
                'next_appointment' => 'nullable|date|after:examination_date',
                'notes' => 'nullable|string|max:1000'
            ], [
                'patient_id.required' => 'Vui lòng chọn bệnh nhân',
                'patient_id.exists' => 'Bệnh nhân không tồn tại',
                'examination_date.required' => 'Ngày khám là bắt buộc',
                'examination_date.date' => 'Ngày khám không hợp lệ',
                'next_appointment.after' => 'Lịch tái khám phải sau ngày khám hiện tại',
                'diagnosis.max' => 'Chuẩn đoán không được vượt quá 1000 ký tự',
                'symptoms.max' => 'Triệu chứng không được vượt quá 1000 ký tự',
                'treatment_plan.max' => 'Kế hoạch điều trị không được vượt quá 2000 ký tự',
                'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            // Parse JSON data
            $services = $request->services ? json_decode($request->services, true) : [];
            $medicines = $request->medicines ? json_decode($request->medicines, true) : [];

            // Validate and calculate fees
            $serviceFee = $this->calculateServiceFee($services);
            $medicineFee = $this->calculateMedicineFee($medicines);

            // Check medicine stock availability
            $this->validateMedicineStock($medicines);

            // Create examination
            $examinationData = [
                'patient_id' => $request->patient_id,
                'examination_date' => $request->examination_date,
                'services' => $services,
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'treatment_plan' => $request->treatment_plan,
                'next_appointment' => $request->next_appointment,
                'service_fee' => $serviceFee,
                'medicine_fee' => $medicineFee,
                'total_fee' => $serviceFee + $medicineFee,
                'status' => 'completed',
                'payment_status' => 'pending',
                'notes' => $request->notes
            ];

            $examination = Examination::create($examinationData);

            // Create medicine usage records
            $this->createMedicineUsageRecords($examination->id, $medicines);

            DB::commit();

            Log::info('Examination created successfully', [
                'examination_id' => $examination->id,
                'patient_id' => $request->patient_id,
                'total_fee' => $examination->total_fee
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Tạo phiếu khám thành công!',
                'examination_id' => $examination->id,
                'examination_code' => $examination->examination_code
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating examination', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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
        switch ($id) {
            case 'get-list':
                return $this->getList();
            case 'get-statistics':
                return $this->getStatistics();
            case 'get-patients':
                return $this->getPatients();
            case 'get-services':
                return $this->getServices();
            case 'get-medicines':
                return $this->getMedicines();
            case 'get-data':
                return $this->getData();
            default:
                $examination = Examination::with(['patient'])->findOrFail($id);
                return response()->json([
                    'type' => 'success',
                    'data' => $examination
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $examination = Examination::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'patient_id' => 'required|exists:patients,id',
                'examination_date' => 'required|date',
                'services' => 'nullable|json',
                'medicines' => 'nullable|json',
                'diagnosis' => 'nullable|string|max:1000',
                'symptoms' => 'nullable|string|max:1000',
                'treatment_plan' => 'nullable|string|max:2000',
                'next_appointment' => 'nullable|date|after:examination_date',
                'payment_status' => 'nullable|in:pending,paid,cancelled',
                'notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $updateData = [
                'patient_id' => $request->patient_id,
                'examination_date' => $request->examination_date,
                'symptoms' => $request->symptoms,
                'diagnosis' => $request->diagnosis,
                'treatment_plan' => $request->treatment_plan,
                'next_appointment' => $request->next_appointment,
                'notes' => $request->notes
            ];

            // Update payment status if provided
            if ($request->has('payment_status')) {
                $updateData['payment_status'] = $request->payment_status;
                if ($request->payment_status === 'paid' && !$examination->payment_date) {
                    $updateData['payment_date'] = now();
                }
            }

            // Handle services update
            if ($request->has('services')) {
                $services = json_decode($request->services, true) ?: [];
                $serviceFee = $this->calculateServiceFee($services);
                $updateData['services'] = $services;
                $updateData['service_fee'] = $serviceFee;
            }

            // Handle medicines update
            if ($request->has('medicines')) {
                $medicines = json_decode($request->medicines, true) ?: [];

                // Delete old medicine usage records
                MedicineUsage::where('examination_id', $examination->id)->delete();

                // Validate new medicines stock
                $this->validateMedicineStock($medicines);

                // Calculate new medicine fee
                $medicineFee = $this->calculateMedicineFee($medicines);
                $updateData['medicine_fee'] = $medicineFee;

                // Create new medicine usage records
                $this->createMedicineUsageRecords($examination->id, $medicines);
            }

            // Recalculate total fee if service or medicine fee changed
            if (isset($updateData['service_fee']) || isset($updateData['medicine_fee'])) {
                $updateData['total_fee'] = ($updateData['service_fee'] ?? $examination->service_fee) +
                    ($updateData['medicine_fee'] ?? $examination->medicine_fee);
            }

            $examination->update($updateData);

            DB::commit();

            Log::info('Examination updated successfully', [
                'examination_id' => $examination->id,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật phiếu khám thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating examination', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

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

            $examination = Examination::findOrFail($id);

            DB::beginTransaction();

            // Delete medicine usage records
            MedicineUsage::where('examination_id', $examination->id)->delete();

            // Delete examination
            $examination->delete();

            DB::commit();

            Log::info('Examination deleted successfully', ['examination_id' => $id]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa phiếu khám thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting examination', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code for payment
     */
    public function generatePaymentQR($id)
    {
        try {
            $examination = Examination::findOrFail($id);

            if ($examination->payment_status !== 'pending') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Phiếu khám này đã được thanh toán hoặc bị hủy!'
                ], 400);
            }

            if (!$this->vietqrService->isConfigured()) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi cấu hình',
                    'content' => 'VietQR chưa được cấu hình. Vui lòng liên hệ quản trị viên!'
                ], 500);
            }

            $qrData = $this->vietqrService->generateQRCode(
                $examination->examination_code,
                $examination->total_fee,
                'Thanh toan phieu kham ' . $examination->examination_code
            );

            if ($qrData && isset($qrData['qrDataURL'])) {
                $examination->update([
                    'qr_code' => $qrData['qrDataURL'],
                    'qr_content' => $qrData['content'] ?? ''
                ]);

                Log::info('QR Code generated successfully', [
                    'examination_id' => $examination->id,
                    'examination_code' => $examination->examination_code
                ]);

                return response()->json([
                    'type' => 'success',
                    'title' => 'Thành công',
                    'content' => 'Tạo mã QR thành công!',
                    'qr_code' => $qrData['qrDataURL'],
                    'amount' => number_format($examination->total_fee, 0, '.', '.') . ' VNĐ'
                ]);
            } else {
                Log::error('Failed to generate QR code', [
                    'examination_id' => $examination->id,
                    'vietqr_response' => $qrData
                ]);

                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể tạo mã QR. Vui lòng thử lại sau!'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error generating QR code', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($id)
    {
        try {
            $examination = Examination::findOrFail($id);

            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'success',
                    'title' => 'Thông báo',
                    'content' => 'Phiếu khám này đã được thanh toán!',
                    'status' => 'paid'
                ]);
            }

            $result = $this->vietqrService->checkTransactionStatus($examination->examination_code);

            if ($result && isset($result['success']) && $result['success']) {
                $examination->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'transaction_id' => $result['transactionId'] ?? null
                ]);

                Log::info('Payment confirmed', [
                    'examination_id' => $examination->id,
                    'transaction_id' => $result['transactionId'] ?? null
                ]);

                return response()->json([
                    'type' => 'success',
                    'title' => 'Thành công',
                    'content' => 'Thanh toán thành công!',
                    'status' => 'paid'
                ]);
            } else {
                return response()->json([
                    'type' => 'info',
                    'title' => 'Thông báo',
                    'content' => 'Chưa có giao dịch thanh toán nào!',
                    'status' => 'pending'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test callback simulation for development
     */
    public function testCallbackSimulation($id)
    {
        try {
            $examination = Examination::findOrFail($id);

            if ($examination->payment_status !== 'pending') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Phiếu khám này đã được thanh toán hoặc bị hủy!'
                ], 400);
            }

            $result = $this->vietqrService->triggerTestCallback(
                $examination->examination_code,
                $examination->total_fee,
                $examination->qr_content
            );

            if ($result && isset($result['status']) && $result['status'] === 'SUCCESS') {
                return response()->json([
                    'type' => 'success',
                    'title' => 'Thành công',
                    'content' => 'Đã gửi test callback thành công! Vui lòng chờ xử lý...'
                ]);
            } else {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể gửi test callback: ' . ($result['message'] ?? 'Unknown error')
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error in test callback simulation', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================== PRIVATE METHODS ==================

    /**
     * Get examination list for DataTable
     */
    private function getList()
    {
        $query = Examination::with(['patient'])
            ->select([
                'id',
                'examination_code',
                'patient_id',
                'examination_date',
                'diagnosis',
                'total_fee',
                'payment_status',
                'status',
                'created_at'
            ]);

        // Apply search filter
        if (request()->has('search_table') && !empty(request()->search_table)) {
            $search = request()->search_table;
            $query->where(function ($q) use ($search) {
                $q->where('examination_code', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('patient_code', 'like', "%{$search}%");
                    });
            });
        }

        // Apply filters
        if (request()->has('status_filter') && !empty(request()->status_filter)) {
            $query->where('status', request()->status_filter);
        }

        if (request()->has('payment_filter') && !empty(request()->payment_filter)) {
            $query->where('payment_status', request()->payment_filter);
        }

        if (request()->has('date_filter') && !empty(request()->date_filter)) {
            $dateFilter = request()->date_filter;
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('examination_date', today());
                    break;
                case 'week':
                    $query->whereBetween('examination_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('examination_date', now()->month)
                        ->whereYear('examination_date', now()->year);
                    break;
            }
        }

        $examinations = $query->orderBy('created_at', 'desc')->get();

        return DataTables::of($examinations)
            ->addColumn('patient_info', function ($examination) {
                return [
                    'name' => $examination->patient->full_name ?? 'N/A',
                    'phone' => $examination->patient->phone ?? 'N/A'
                ];
            })
            ->addColumn('status_badge', function ($examination) {
                $statusClasses = [
                    'waiting' => 'badge-light-warning',
                    'examining' => 'badge-light-info',
                    'completed' => 'badge-light-success',
                    'cancelled' => 'badge-light-danger'
                ];
                $class = $statusClasses[$examination->status] ?? 'badge-light-secondary';
                return '<span class="badge ' . $class . '">' . $examination->status_name . '</span>';
            })
            ->addColumn('payment_badge', function ($examination) {
                $paymentClasses = [
                    'pending' => 'badge-light-warning',
                    'paid' => 'badge-light-success',
                    'cancelled' => 'badge-light-danger'
                ];
                $class = $paymentClasses[$examination->payment_status] ?? 'badge-light-secondary';
                return '<span class="badge ' . $class . '">' . $examination->payment_status_name . '</span>';
            })
            ->addColumn('formatted_total_fee', function ($examination) {
                return number_format($examination->total_fee, 0, '.', '.') . ' VNĐ';
            })
            ->addColumn('formatted_examination_date', function ($examination) {
                return $examination->examination_date ? $examination->examination_date->format('d/m/Y') : '';
            })
            ->rawColumns(['status_badge', 'payment_badge'])
            ->make(true);
    }

    /**
     * Get statistics for dashboard
     */
    private function getStatistics()
    {
        try {
            $total = Examination::count();
            $today = Examination::whereDate('examination_date', today())->count();
            $thisMonth = Examination::whereMonth('examination_date', now()->month)
                ->whereYear('examination_date', now()->year)
                ->count();

            $totalRevenue = Examination::where('payment_status', 'paid')->sum('total_fee');
            $monthRevenue = Examination::where('payment_status', 'paid')
                ->whereMonth('examination_date', now()->month)
                ->whereYear('examination_date', now()->year)
                ->sum('total_fee');

            $pending = Examination::where('payment_status', 'pending')->count();
            $paid = Examination::where('payment_status', 'paid')->count();

            return response()->json([
                'total' => $total,
                'today' => $today,
                'this_month' => $thisMonth,
                'total_revenue' => number_format($totalRevenue, 0, '.', '.') . ' VNĐ',
                'month_revenue' => number_format($monthRevenue, 0, '.', '.') . ' VNĐ',
                'pending_payment' => $pending,
                'paid' => $paid
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading statistics', ['error' => $e->getMessage()]);
            return response()->json([
                'total' => 0,
                'today' => 0,
                'this_month' => 0,
                'total_revenue' => '0 VNĐ',
                'month_revenue' => '0 VNĐ',
                'pending_payment' => 0,
                'paid' => 0
            ]);
        }
    }

    /**
     * Get patients for selection
     */
    private function getPatients()
    {
        $search = request()->get('search', '');

        $patients = Patient::where('is_active', true)
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('patient_code', 'like', "%{$search}%");
                }
            })
            ->limit(20)
            ->get(['id', 'patient_code', 'full_name', 'phone', 'address', 'date_of_birth']);

        return response()->json($patients);
    }

    /**
     * Get services for selection
     */
    private function getServices()
    {
        $services = Service::where('is_active', true)
            ->select('id', 'name', 'type', 'price')
            ->orderBy('name')
            ->get();

        return response()->json($services);
    }

    /**
     * Get medicines for selection
     */
    private function getMedicines()
    {
        $medicines = Medicine::where('is_active', true)
            ->select('id', 'name', 'type', 'sale_price')
            ->get()
            ->map(function ($medicine) {
                return [
                    'id' => $medicine->id,
                    'name' => $medicine->name,
                    'type' => $medicine->type,
                    'type_name' => $medicine->type_name,
                    'sale_price' => $medicine->sale_price,
                    'current_stock' => $medicine->current_stock,
                    'formatted_price' => number_format($medicine->sale_price, 0, '.', '.') . ' VNĐ'
                ];
            });

        return response()->json($medicines);
    }

    /**
     * Get examination data for editing
     */
    private function getData()
    {
        try {
            $id = request()->get('id');
            $examination = Examination::with(['patient', 'medicineUsages.medicine'])->findOrFail($id);

            // Prepare services data
            $services = [];
            if ($examination->services) {
                foreach ($examination->services as $serviceData) {
                    $service = Service::find($serviceData['service_id']);
                    if ($service) {
                        $services[] = [
                            'service_id' => $service->id,
                            'name' => $service->name,
                            'quantity' => $serviceData['quantity'],
                            'price' => $serviceData['price']
                        ];
                    }
                }
            }

            // Prepare medicines data from usage
            $medicines = [];
            foreach ($examination->medicineUsages as $usage) {
                $medicines[] = [
                    'medicine_id' => $usage->medicine_id,
                    'name' => $usage->medicine->name,
                    'quantity' => $usage->quantity_used,
                    'price' => $usage->unit_price,
                    'dosage' => $usage->dosage,
                    'note' => $usage->usage_note,
                    'current_stock' => $usage->medicine->current_stock
                ];
            }

            $data = [
                'id' => $examination->id,
                'examination_code' => $examination->examination_code,
                'patient_id' => $examination->patient_id,
                'patient' => [
                    'id' => $examination->patient->id,
                    'full_name' => $examination->patient->full_name,
                    'phone' => $examination->patient->phone,
                    'patient_code' => $examination->patient->patient_code
                ],
                'examination_date' => $examination->examination_date->format('Y-m-d'),
                'services' => $services,
                'medicines' => $medicines,
                'diagnosis' => $examination->diagnosis,
                'symptoms' => $examination->symptoms,
                'treatment_plan' => $examination->treatment_plan,
                'next_appointment' => $examination->next_appointment ? $examination->next_appointment->format('Y-m-d') : null,
                'service_fee' => $examination->service_fee,
                'medicine_fee' => $examination->medicine_fee,
                'total_fee' => $examination->total_fee,
                'payment_status' => $examination->payment_status,
                'status' => $examination->status,
                'notes' => $examination->notes,
                'qr_code' => $examination->qr_code,
                'has_qr' => !empty($examination->qr_code)
            ];

            return response()->json([
                'type' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting examination data', [
                'examination_id' => request()->get('id'),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete examinations
     */
    private function bulkDestroy()
    {
        try {
            $ids = request()->input('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không có phiếu khám nào được chọn!'
                ], 400);
            }

            DB::beginTransaction();

            // Delete medicine usage records
            MedicineUsage::whereIn('examination_id', $ids)->delete();

            // Delete examinations
            $deletedCount = Examination::whereIn('id', $ids)->delete();

            DB::commit();

            Log::info('Bulk delete examinations', [
                'deleted_count' => $deletedCount,
                'examination_ids' => $ids
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} phiếu khám thành công!"
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error bulk deleting examinations', [
                'examination_ids' => request()->input('ids', []),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate service fee from services array
     */
    private function calculateServiceFee(array $services): int
    {
        $totalFee = 0;

        foreach ($services as $serviceData) {
            if (!isset($serviceData['service_id'], $serviceData['quantity'], $serviceData['price'])) {
                continue;
            }

            // Validate service exists and price matches
            $service = Service::find($serviceData['service_id']);
            if (!$service || !$service->is_active) {
                throw new \Exception("Dịch vụ ID {$serviceData['service_id']} không tồn tại hoặc không hoạt động");
            }

            // Allow some flexibility in price (for discounts, etc.)
            if ($serviceData['price'] < 0) {
                throw new \Exception("Giá dịch vụ không được âm");
            }

            $quantity = max(1, intval($serviceData['quantity']));
            $price = floatval($serviceData['price']);
            $totalFee += $quantity * $price;
        }

        return $totalFee;
    }

    /**
     * Calculate medicine fee from medicines array
     */
    private function calculateMedicineFee(array $medicines): int
    {
        $totalFee = 0;

        foreach ($medicines as $medicineData) {
            if (!isset($medicineData['medicine_id'], $medicineData['quantity'])) {
                continue;
            }

            $medicine = Medicine::find($medicineData['medicine_id']);
            if (!$medicine || !$medicine->is_active) {
                throw new \Exception("Thuốc ID {$medicineData['medicine_id']} không tồn tại hoặc không hoạt động");
            }

            $quantity = max(1, intval($medicineData['quantity']));
            $totalFee += $quantity * $medicine->sale_price;
        }

        return $totalFee;
    }

    /**
     * Validate medicine stock availability
     */
    private function validateMedicineStock(array $medicines): void
    {
        foreach ($medicines as $medicineData) {
            if (!isset($medicineData['medicine_id'], $medicineData['quantity'])) {
                continue;
            }

            $medicine = Medicine::find($medicineData['medicine_id']);
            if (!$medicine) {
                throw new \Exception("Thuốc ID {$medicineData['medicine_id']} không tồn tại");
            }

            $requestedQuantity = intval($medicineData['quantity']);
            $currentStock = $medicine->current_stock;

            if ($currentStock < $requestedQuantity) {
                throw new \Exception(
                    "Thuốc '{$medicine->name}' không đủ số lượng trong kho. " .
                        "Còn lại: {$currentStock}, yêu cầu: {$requestedQuantity}"
                );
            }

            if ($requestedQuantity <= 0) {
                throw new \Exception("Số lượng thuốc '{$medicine->name}' phải lớn hơn 0");
            }
        }
    }

    /**
     * Create medicine usage records
     */
    private function createMedicineUsageRecords(int $examinationId, array $medicines): void
    {
        foreach ($medicines as $medicineData) {
            if (!isset($medicineData['medicine_id'], $medicineData['quantity'])) {
                continue;
            }

            $medicine = Medicine::find($medicineData['medicine_id']);
            if (!$medicine) {
                continue; // Skip if medicine not found (should be caught in validation)
            }

            $quantity = intval($medicineData['quantity']);
            if ($quantity <= 0) {
                continue;
            }

            MedicineUsage::create([
                'examination_id' => $examinationId,
                'medicine_id' => $medicine->id,
                'quantity_used' => $quantity,
                'unit_price' => $medicine->sale_price,
                'dosage' => $medicineData['dosage'] ?? '',
                'usage_note' => $medicineData['note'] ?? ''
            ]);

            Log::info('Medicine usage recorded', [
                'examination_id' => $examinationId,
                'medicine_id' => $medicine->id,
                'medicine_name' => $medicine->name,
                'quantity_used' => $quantity,
                'unit_price' => $medicine->sale_price
            ]);
        }
    }

    /**
     * Test VietQR with real data (for development/testing)
     */
    public function testVietQRWithRealData($id)
    {
        try {
            $examination = Examination::findOrFail($id);

            if (!$this->vietqrService->isConfigured()) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi cấu hình',
                    'content' => 'VietQR chưa được cấu hình đầy đủ'
                ], 500);
            }

            $result = $this->vietqrService->testPayment(
                $examination->examination_code,
                $examination->total_fee,
                $examination->qr_content
            );

            Log::info('VietQR Test Result', [
                'examination_id' => $examination->id,
                'examination_code' => $examination->examination_code,
                'result' => $result
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Test VietQR',
                'content' => 'Đã gửi test request. Kiểm tra log để xem kết quả.',
                'result' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Error in VietQR test', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get examination summary for reports
     */
    public function getExaminationSummary(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));

            $examinations = Examination::with(['patient'])
                ->whereBetween('examination_date', [$startDate, $endDate])
                ->get();

            $summary = [
                'total_examinations' => $examinations->count(),
                'total_revenue' => $examinations->where('payment_status', 'paid')->sum('total_fee'),
                'pending_payment' => $examinations->where('payment_status', 'pending')->sum('total_fee'),
                'by_status' => [
                    'completed' => $examinations->where('status', 'completed')->count(),
                    'cancelled' => $examinations->where('status', 'cancelled')->count(),
                ],
                'by_payment' => [
                    'paid' => $examinations->where('payment_status', 'paid')->count(),
                    'pending' => $examinations->where('payment_status', 'pending')->count(),
                    'cancelled' => $examinations->where('payment_status', 'cancelled')->count(),
                ],
                'top_diagnoses' => $examinations->whereNotNull('diagnosis')
                    ->groupBy('diagnosis')
                    ->map->count()
                    ->sortDesc()
                    ->take(10),
                'daily_stats' => $examinations->groupBy(function ($exam) {
                    return $exam->examination_date->format('Y-m-d');
                })->map(function ($dayExams) {
                    return [
                        'count' => $dayExams->count(),
                        'revenue' => $dayExams->where('payment_status', 'paid')->sum('total_fee')
                    ];
                })
            ];

            return response()->json([
                'type' => 'success',
                'data' => $summary,
                'period' => [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting examination summary', [
                'error' => $e->getMessage(),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date')
            ]);

            return response()->json([
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel examination
     */
    public function cancelExamination($id)
    {
        try {
            $examination = Examination::findOrFail($id);

            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể hủy phiếu khám đã thanh toán!'
                ], 400);
            }

            DB::beginTransaction();

            // Update examination status
            $examination->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // Note: We don't restore medicine stock here because the usage record
            // serves as an audit trail. If needed, this can be implemented separately.

            DB::commit();

            Log::info('Examination cancelled', [
                'examination_id' => $examination->id,
                'examination_code' => $examination->examination_code
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Đã hủy phiếu khám thành công!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error cancelling examination', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print examination receipt
     */
    public function printReceipt($id)
    {
        try {
            $examination = Examination::with(['patient', 'medicineUsages.medicine'])
                ->findOrFail($id);

            $services = [];
            if ($examination->services) {
                foreach ($examination->services as $serviceData) {
                    $service = Service::find($serviceData['service_id']);
                    if ($service) {
                        $services[] = [
                            'name' => $service->name,
                            'quantity' => $serviceData['quantity'],
                            'price' => $serviceData['price'],
                            'total' => $serviceData['quantity'] * $serviceData['price']
                        ];
                    }
                }
            }

            $medicines = $examination->medicineUsages->map(function ($usage) {
                return [
                    'name' => $usage->medicine->name,
                    'quantity' => $usage->quantity_used,
                    'price' => $usage->unit_price,
                    'total' => $usage->total_price,
                    'dosage' => $usage->dosage,
                    'note' => $usage->usage_note
                ];
            });

            $receiptData = [
                'examination' => $examination,
                'patient' => $examination->patient,
                'services' => $services,
                'medicines' => $medicines,
                'print_date' => now()->format('d/m/Y H:i:s')
            ];

            return response()->json([
                'type' => 'success',
                'data' => $receiptData
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating receipt', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
