<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExaminationRequest;
use App\Models\Examination;
use App\Models\Patient;
use App\Services\VietQRService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class ExaminationController extends Controller
{
    protected $vietQRService;

    public function __construct(VietQRService $vietQRService)
    {
        $this->vietQRService = $vietQRService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.pages.examination.main');
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
    public function store(ExaminationRequest $request)
    {
        try {
            $data = $request->validated();

            // Tạo hoặc tìm bệnh nhân
            if (!empty($data['patient_id'])) {
                $patient = Patient::findOrFail($data['patient_id']);
            } else {
                // Tạo bệnh nhân mới
                $patient = Patient::create([
                    'full_name' => $data['patient_name'],
                    'phone' => $data['patient_phone'],
                    'address' => $data['patient_address'] ?? null,
                    'date_of_birth' => $data['patient_dob'] ?? null,
                    'gender' => $data['patient_gender'] ?? null,
                    'citizen_id' => $data['patient_citizen_id'] ?? null,
                ]);
            }

            // Tính phí dịch vụ
            $serviceFee = 0;
            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceItem) {
                    $serviceFee += ($serviceItem['quantity'] ?? 1) * ($serviceItem['price'] ?? 0);
                }
            }

            // Tính phí thuốc
            $medicineFee = 0;
            if (!empty($data['medicines'])) {
                foreach ($data['medicines'] as $medicineItem) {
                    $medicineFee += ($medicineItem['quantity'] ?? 1) * ($medicineItem['price'] ?? 0);
                }
            }

            // Tạo phiếu khám với trạng thái chờ thanh toán
            $examination = Examination::create([
                'patient_id' => $patient->id,
                'services' => $data['services'] ?? null,
                'medicines' => $data['medicines'] ?? null,
                'diagnosis' => $data['diagnosis'] ?? null,
                'symptoms' => $data['symptoms'] ?? null,
                'treatment_plan' => $data['treatment_plan'] ?? null,
                'next_appointment' => $data['next_appointment'] ?? null,
                'service_fee' => $serviceFee,
                'medicine_fee' => $medicineFee,
                'examination_date' => $data['examination_date'] ?? today(),
                'notes' => $data['notes'] ?? null,
                'status' => 'waiting',
                'payment_status' => 'pending' // Trạng thái chờ thanh toán
            ]);

            Log::info('Examination created', [
                'examination_id' => $examination->id,
                'examination_code' => $examination->examination_code,
                'patient_id' => $patient->id,
                'total_fee' => $examination->total_fee
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Tạo phiếu khám thành công!',
                'data' => [
                    'examination_id' => $examination->id,
                    'examination_code' => $examination->examination_code,
                    'total_fee' => $examination->total_fee,
                    'payment_status' => $examination->payment_status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Examination creation error: ' . $e->getMessage());

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

        $examination = Examination::with('patient')->findOrFail($id);
        return response()->json([
            'type' => 'success',
            'data' => $examination
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
    public function update(ExaminationRequest $request, string $id)
    {
        try {
            $examination = Examination::findOrFail($id);
            $data = $request->validated();

            // Kiểm tra nếu đã thanh toán thì không cho sửa
            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể sửa phiếu khám đã thanh toán!'
                ], 400);
            }

            // Tính lại phí dịch vụ
            $serviceFee = 0;
            if (!empty($data['services']) && is_array($data['services'])) {
                foreach ($data['services'] as $serviceItem) {
                    $serviceFee += ($serviceItem['quantity'] ?? 1) * ($serviceItem['price'] ?? 0);
                }
            }

            // Tính lại phí thuốc
            $medicineFee = 0;
            if (!empty($data['medicines']) && is_array($data['medicines'])) {
                foreach ($data['medicines'] as $medicineItem) {
                    $medicineFee += ($medicineItem['quantity'] ?? 1) * ($medicineItem['price'] ?? 0);
                }
            }

            $examination->update([
                'services' => !empty($data['services']) ? $data['services'] : null,
                'medicines' => !empty($data['medicines']) ? $data['medicines'] : null,
                'diagnosis' => $data['diagnosis'] ?? null,
                'symptoms' => $data['symptoms'] ?? null,
                'treatment_plan' => $data['treatment_plan'] ?? null,
                'next_appointment' => $data['next_appointment'] ?? null,
                'service_fee' => $serviceFee,
                'medicine_fee' => $medicineFee,
                'examination_date' => $data['examination_date'] ?? $examination->examination_date,
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'] ?? $examination->status
            ]);

            Log::info('Examination updated', [
                'examination_id' => $examination->id,
                'examination_code' => $examination->examination_code
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật phiếu khám thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Examination update error: ' . $e->getMessage());

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

            // Kiểm tra nếu đã thanh toán thì không cho xóa
            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể xóa phiếu khám đã thanh toán!'
                ], 400);
            }

            Log::info('Examination deleted', [
                'examination_id' => $examination->id,
                'examination_code' => $examination->examination_code
            ]);

            $examination->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa phiếu khám thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Examination deletion error: ' . $e->getMessage());

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate QR code for payment using VietQR API
     */
    public function generatePaymentQR($id)
    {
        try {
            $examination = Examination::with('patient')->findOrFail($id);

            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Phiếu khám đã được thanh toán!'
                ], 400);
            }

            // Kiểm tra cấu hình VietQR
            if (!$this->vietQRService->isConfigured()) {
                throw new \Exception('VietQR chưa được cấu hình. Vui lòng kiểm tra file .env');
            }

            // Gọi VietQR Service để tạo QR code
            $qrData = $this->vietQRService->generateQRCode(
                $examination->examination_code,
                $examination->total_fee,
                'TT ' . $examination->examination_code
            );

            if (!$qrData) {
                throw new \Exception('Không thể tạo mã QR thanh toán từ VietQR');
            }

            // Lưu thông tin QR vào database
            $examination->update([
                'qr_code' => $qrData['qrCode'] ?? null,
                'payment_method' => 'vietqr',
                'transaction_ref_id' => $qrData['transactionRefId'] ?? null,
                'examination_code' => $qrData['content']
            ]);

            Log::info('QR Payment Generated', [
                'examination_code' => $examination->examination_code,
                'amount' => $examination->total_fee,
                'transaction_ref_id' => $qrData['transactionRefId'] ?? null
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Tạo mã QR thanh toán thành công!',
                'data' => [
                    'examination_code' => $examination->examination_code,
                    'qr_code' => $qrData['qrLink'] ?? null, // Link để hiển thị QR
                    'qr_string' => $qrData['qrCode'], // String để tạo QR image
                    'amount' => $examination->formatted_total_fee,
                    'content' => $qrData['content'],
                    'bank_name' => $qrData['bankName'] ?? env('VIETQR_BANK_NAME', 'VietQR'),
                    'account_no' => $qrData['bankAccount'] ?? env('VIETQR_BANK_ACCOUNT'),
                    'account_name' => $qrData['userBankName'] ?? env('VIETQR_ACCOUNT_NAME'),
                    'transaction_ref_id' => $qrData['transactionRefId'] ?? null,
                    'order_id' => $qrData['orderId'] ?? $examination->examination_code
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('VietQR Generate Error', [
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
     * Check payment status via VietQR API
     */
    public function checkPaymentStatus($id)
    {
        try {
            $examination = Examination::findOrFail($id);

            // Kiểm tra trong database trước
            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'success',
                    'data' => [
                        'payment_status' => 'paid',
                        'payment_status_name' => 'Đã thanh toán',
                        'payment_date' => $examination->payment_date,
                        'transaction_id' => $examination->transaction_id,
                        'is_paid' => true
                    ]
                ]);
            }

            // Gọi API VietQR để check transaction status
            $transactionData = $this->vietQRService->checkTransactionStatus($examination->examination_code);

            if ($transactionData && isset($transactionData['status']) && $transactionData['status'] === 'SUCCESS') {
                // Có giao dịch thành công, kiểm tra data
                if (isset($transactionData['data']) && is_array($transactionData['data']) && count($transactionData['data']) > 0) {
                    foreach ($transactionData['data'] as $transaction) {
                        // Kiểm tra amount và content
                        $amount = (int) ($transaction['amount'] ?? 0);
                        $content = $transaction['content'] ?? '';

                        if (
                            $amount >= $examination->total_fee &&
                            strpos($content, $examination->examination_code) !== false
                        ) {

                            // Cập nhật trạng thái thanh toán
                            $examination->update([
                                'payment_status' => 'paid',
                                'payment_date' => now(),
                                'status' => 'completed',
                                'transaction_id' => $transaction['referenceNumber'] ?? ('CHK_' . time())
                            ]);

                            Log::info('Payment Status Updated via API Check', [
                                'examination_code' => $examination->examination_code,
                                'transaction_data' => $transaction
                            ]);

                            return response()->json([
                                'type' => 'success',
                                'data' => [
                                    'payment_status' => 'paid',
                                    'payment_status_name' => 'Đã thanh toán',
                                    'payment_date' => $examination->payment_date,
                                    'transaction_id' => $examination->transaction_id,
                                    'is_paid' => true
                                ]
                            ]);
                        }
                    }
                }
            }

            // Chưa có giao dịch thành công
            return response()->json([
                'type' => 'success',
                'data' => [
                    'payment_status' => 'pending',
                    'payment_status_name' => 'Chờ thanh toán',
                    'payment_date' => null,
                    'transaction_id' => null,
                    'is_paid' => false
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Check Payment Status Error', [
                'examination_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra khi kiểm tra trạng thái thanh toán: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test payment callback (Development/Testing only)
     */
    public function testPaymentCallback($id)
    {
        try {
            // Chỉ cho phép trong môi trường development
            if (!app()->environment(['local', 'testing'])) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Chức năng test payment chỉ khả dụng trong môi trường development!'
                ], 403);
            }

            $examination = Examination::findOrFail($id);

            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Phiếu khám đã được thanh toán!'
                ], 400);
            }

            // Gọi VietQR Service test payment
            $result = $this->vietQRService->testPayment(
                $examination->examination_code,
                $examination->total_fee,
                $examination->examination_code
            );

            if ($result && isset($result['status']) && $result['status'] === 'SUCCESS') {
                // Cập nhật trạng thái thanh toán
                $examination->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'status' => 'completed',
                    'transaction_id' => $result['message'] ?? 'TEST_' . time()
                ]);

                Log::info('Test Payment Success', [
                    'examination_code' => $examination->examination_code,
                    'result' => $result
                ]);

                return response()->json([
                    'type' => 'success',
                    'title' => 'Thành công',
                    'content' => 'Test thanh toán thành công!'
                ]);
            }

            throw new \Exception('Test payment không thành công: ' . ($result['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Test Payment Error', [
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
     * Get list for DataTable
     */
    private function getList()
    {
        $query = Examination::with('patient')
            ->select([
                'id',
                'examination_code',
                'patient_id',
                'diagnosis',
                'total_fee',
                'payment_status',
                'status',
                'examination_date',
                'next_appointment',
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

        // Apply status filter
        if (request()->has('status_filter') && !empty(request()->status_filter)) {
            $query->where('status', request()->status_filter);
        }

        // Apply payment status filter
        if (request()->has('payment_filter') && !empty(request()->payment_filter)) {
            $query->where('payment_status', request()->payment_filter);
        }

        // Apply date filter
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
                    'code' => $examination->patient->patient_code ?? 'N/A',
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
            ->addColumn('formatted_examination_date', function ($examination) {
                return $examination->examination_date->format('d/m/Y');
            })
            ->addColumn('formatted_next_appointment', function ($examination) {
                return $examination->next_appointment ? $examination->next_appointment->format('d/m/Y') : '-';
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
            $pending = Examination::where('payment_status', 'pending')->count();
            $completed = Examination::where('status', 'completed')->count();

            $todayRevenue = Examination::whereDate('examination_date', today())
                ->where('payment_status', 'paid')
                ->sum('total_fee');

            $monthRevenue = Examination::whereMonth('examination_date', now()->month)
                ->whereYear('examination_date', now()->year)
                ->where('payment_status', 'paid')
                ->sum('total_fee');

            return response()->json([
                'total' => $total,
                'today' => $today,
                'pending' => $pending,
                'completed' => $completed,
                'today_revenue' => number_format($todayRevenue, 0, '.', '.') . ' VNĐ',
                'month_revenue' => number_format($monthRevenue, 0, '.', '.') . ' VNĐ'
            ]);
        } catch (\Exception $e) {
            Log::error('Statistics error: ' . $e->getMessage());

            return response()->json([
                'total' => 0,
                'today' => 0,
                'pending' => 0,
                'completed' => 0,
                'today_revenue' => '0 VNĐ',
                'month_revenue' => '0 VNĐ'
            ]);
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

            // Kiểm tra phiếu đã thanh toán
            $paidCount = Examination::whereIn('id', $ids)
                ->where('payment_status', 'paid')
                ->count();

            if ($paidCount > 0) {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Có ' . $paidCount . ' phiếu khám đã thanh toán, không thể xóa!'
                ], 400);
            }

            $deletedCount = Examination::whereIn('id', $ids)->delete();

            Log::info('Bulk examinations deleted', [
                'deleted_count' => $deletedCount,
                'ids' => $ids
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} phiếu khám thành công!"
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk deletion error: ' . $e->getMessage());

            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
