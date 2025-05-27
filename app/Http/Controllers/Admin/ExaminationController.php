<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExaminationRequest;
use App\Models\Examination;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;

class ExaminationController extends Controller
{
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

            // Tạo phiếu khám
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
                'status' => 'waiting'
            ]);

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Tạo phiếu khám thành công!',
                'data' => [
                    'examination_id' => $examination->id,
                    'examination_code' => $examination->examination_code,
                    'total_fee' => $examination->total_fee
                ]
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

            // Tính lại phí
            $serviceFee = 0;
            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceItem) {
                    $serviceFee += ($serviceItem['quantity'] ?? 1) * ($serviceItem['price'] ?? 0);
                }
            }

            $medicineFee = 0;
            if (!empty($data['medicines'])) {
                foreach ($data['medicines'] as $medicineItem) {
                    $medicineFee += ($medicineItem['quantity'] ?? 1) * ($medicineItem['price'] ?? 0);
                }
            }

            $examination->update([
                'services' => $data['services'] ?? null,
                'medicines' => $data['medicines'] ?? null,
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

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Cập nhật phiếu khám thành công!'
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

            $examination = Examination::findOrFail($id);

            // Kiểm tra nếu đã thanh toán thì không cho xóa
            if ($examination->payment_status === 'paid') {
                return response()->json([
                    'type' => 'error',
                    'title' => 'Lỗi',
                    'content' => 'Không thể xóa phiếu khám đã thanh toán!'
                ], 400);
            }

            $examination->delete();

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => 'Xóa phiếu khám thành công!'
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
     * Generate QR code for payment
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

            // Thông tin ngân hàng (cấu hình trong env)
            $bankId = env('BANK_ID', '970422'); // VietinBank
            $accountNo = env('BANK_ACCOUNT', '113366668888');
            $accountName = env('BANK_ACCOUNT_NAME', 'PHONG KHAM ABC');
            
            // Tạo nội dung chuyển khoản
            $transferContent = "TT {$examination->examination_code}";
            $amount = $examination->total_fee;

            // Tạo QR VietQR
            $qrData = "00020101021238540010A00000072701270006{$bankId}0114{$accountNo}0208QRIBFTTA53037045802VN6304";
            $qrData .= sprintf("%04d", strlen($accountName)) . $accountName;
            $qrData .= "6304"; // Transaction currency
            $qrData .= sprintf("%04d", strlen($amount)) . $amount;
            $qrData .= "62" . sprintf("%02d", strlen($transferContent) + 4) . "05" . sprintf("%02d", strlen($transferContent)) . $transferContent;
            
            // Tạo QR code bằng API miễn phí
            $qrUrl = "https://api.vietqr.io/v2/generate";
            
            $response = Http::post($qrUrl, [
                'accountNo' => $accountNo,
                'accountName' => $accountName,
                'acqId' => $bankId,
                'amount' => $amount,
                'addInfo' => $transferContent,
                'format' => 'text',
                'template' => 'compact'
            ]);

            if ($response->successful()) {
                $qrCode = $response->json()['data']['qrDataURL'] ?? null;
                
                // Lưu QR code vào database
                $examination->update([
                    'qr_code' => $qrCode,
                    'payment_method' => 'bank_transfer'
                ]);

                return response()->json([
                    'type' => 'success',
                    'title' => 'Thành công',
                    'content' => 'Tạo mã QR thanh toán thành công!',
                    'data' => [
                        'qr_code' => $qrCode,
                        'amount' => $examination->formatted_total_fee,
                        'content' => $transferContent,
                        'bank_name' => 'VietinBank',
                        'account_no' => $accountNo,
                        'account_name' => $accountName
                    ]
                ]);
            } else {
                throw new \Exception('Không thể tạo mã QR');
            }

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'title' => 'Lỗi',
                'content' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment webhook
     */
    public function handlePaymentWebhook(Request $request)
    {
        try {
            // Xác thực webhook (tùy theo ngân hàng)
            $transactionId = $request->input('transaction_id');
            $amount = $request->input('amount');
            $content = $request->input('content');
            
            // Tìm examination code từ nội dung chuyển khoản
            preg_match('/TT ([A-Z0-9]+)/', $content, $matches);
            if (empty($matches[1])) {
                return response()->json(['status' => 'error', 'message' => 'Invalid content']);
            }
            
            $examinationCode = $matches[1];
            $examination = Examination::where('examination_code', $examinationCode)->first();
            
            if (!$examination) {
                return response()->json(['status' => 'error', 'message' => 'Examination not found']);
            }
            
            if ($examination->payment_status === 'paid') {
                return response()->json(['status' => 'success', 'message' => 'Already paid']);
            }
            
            // Kiểm tra số tiền
            if ($amount < $examination->total_fee) {
                return response()->json(['status' => 'error', 'message' => 'Insufficient amount']);
            }
            
            // Cập nhật trạng thái thanh toán
            $examination->update([
                'payment_status' => 'paid',
                'transaction_id' => $transactionId,
                'payment_date' => now(),
                'status' => 'completed'
            ]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            
            return response()->json([
                'type' => 'success',
                'data' => [
                    'payment_status' => $examination->payment_status,
                    'payment_status_name' => $examination->payment_status_name,
                    'payment_date' => $examination->payment_date,
                    'transaction_id' => $examination->transaction_id
                ]
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
        $query = Examination::with('patient')
                           ->select(['id', 'examination_code', 'patient_id', 'diagnosis', 
                                   'total_fee', 'payment_status', 'status', 'examination_date', 
                                   'next_appointment', 'created_at']);

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

            return response()->json([
                'type' => 'success',
                'title' => 'Thành công',
                'content' => "Đã xóa {$deletedCount} phiếu khám thành công!"
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