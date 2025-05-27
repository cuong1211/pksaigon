<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VietQRWebhookController extends Controller
{
    /**
     * API 1: Generate Token cho VietQR
     * URL: https://yourdomain.com/api/token_generate
     */
    public function generateToken(Request $request)
    {
        try {
            // Lấy Authorization header
            $authHeader = $request->header('Authorization');

            if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'UNAUTHORIZED',
                    'toastMessage' => 'Thiếu thông tin xác thực'
                ], 401);
            }

            // Decode Basic Auth
            $encodedCredentials = substr($authHeader, 6);
            $decodedCredentials = base64_decode($encodedCredentials);

            if (!$decodedCredentials || !str_contains($decodedCredentials, ':')) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'INVALID_CREDENTIALS',
                    'toastMessage' => 'Thông tin xác thực không hợp lệ'
                ], 401);
            }

            [$username, $password] = explode(':', $decodedCredentials, 2);

            // Kiểm tra thông tin đăng nhập
            $validUsername = env('VIETQR_WEBHOOK_USERNAME', 'your_webhook_user');
            $validPassword = env('VIETQR_WEBHOOK_PASSWORD', 'your_webhook_pass');

            if ($username !== $validUsername || $password !== $validPassword) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'INVALID_CREDENTIALS',
                    'toastMessage' => 'Tên đăng nhập hoặc mật khẩu không đúng'
                ], 401);
            }

            // Tạo access token
            $accessToken = Str::random(64);
            $expiresIn = now()->addMinutes(5)->timestamp; // Token hết hạn sau 5 phút

            // Lưu token vào cache
            cache()->put("vietqr_webhook_token_{$accessToken}", [
                'username' => $username,
                'created_at' => now(),
                'expires_at' => $expiresIn
            ], 5); // 5 minutes

            return response()->json([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_in' => (string) $expiresIn
            ]);
        } catch (\Exception $e) {
            Log::error('VietQR Token Generate Error: ' . $e->getMessage());

            return response()->json([
                'error' => true,
                'errorReason' => 'INTERNAL_ERROR',
                'toastMessage' => 'Lỗi hệ thống'
            ], 500);
        }
    }

    /**
     * API 2: Transaction Sync - Nhận thông báo giao dịch từ VietQR
     * URL: https://yourdomain.com/api/bank/api/transaction-sync
     */
    public function transactionSync(Request $request)
    {
        try {
            Log::info('VietQR Transaction Sync Received', $request->all());

            // Validate token
            $authHeader = $request->header('Authorization');
            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'UNAUTHORIZED',
                    'toastMessage' => 'Thiếu Bearer Token'
                ], 401);
            }

            $token = substr($authHeader, 7);
            $tokenData = cache()->get("vietqr_webhook_token_{$token}");

            if (!$tokenData) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'INVALID_TOKEN',
                    'toastMessage' => 'Token không hợp lệ hoặc đã hết hạn'
                ], 401);
            }

            // Validate request data
            $request->validate([
                'bankaccount' => 'required|string',
                'amount' => 'required|string',
                'transType' => 'required|string|in:D,C',
                'content' => 'required|string'
            ]);

            $bankAccount = $request->input('bankaccount');
            $amount = (int) $request->input('amount');
            $transType = $request->input('transType');
            $content = $request->input('content');

            // Chỉ xử lý giao dịch nhận tiền (Credit)
            if ($transType !== 'C') {
                return response()->json([
                    'error' => false,
                    'errorReason' => '',
                    'toastMessage' => 'Giao dịch không phải nhận tiền',
                    'data' => []
                ]);
            }

            // Parse examination code từ content
            // Ví dụ: "TT PK20250527001" hoặc "Thanh toan PK20250527001"
            if (preg_match('/(?:TT|Thanh toan)\s+([A-Z0-9]+)/i', $content, $matches)) {
                $examinationCode = $matches[1];

                // Tìm phiếu khám
                $examination = Examination::where('examination_code', $examinationCode)
                    ->where('payment_status', 'pending')
                    ->first();

                if (!$examination) {
                    Log::warning('VietQR: Examination not found or already paid', [
                        'code' => $examinationCode,
                        'content' => $content
                    ]);

                    return response()->json([
                        'error' => false,
                        'errorReason' => '',
                        'toastMessage' => 'Phiếu khám không tìm thấy hoặc đã thanh toán',
                        'data' => []
                    ]);
                }

                // Kiểm tra số tiền
                if ($amount < $examination->total_fee) {
                    Log::warning('VietQR: Insufficient amount', [
                        'received' => $amount,
                        'required' => $examination->total_fee,
                        'examination_code' => $examinationCode
                    ]);

                    return response()->json([
                        'error' => false,
                        'errorReason' => '',
                        'toastMessage' => 'Số tiền chuyển không đủ',
                        'data' => []
                    ]);
                }

                // Cập nhật trạng thái thanh toán
                $examination->update([
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'status' => 'completed',
                    'payment_method' => 'vietqr',
                    'transaction_id' => 'VQR_' . time() . '_' . Str::random(6)
                ]);

                Log::info('VietQR Payment Success', [
                    'examination_code' => $examinationCode,
                    'amount' => $amount,
                    'transaction_id' => $examination->transaction_id
                ]);

                return response()->json([
                    'error' => false,
                    'errorReason' => '',
                    'toastMessage' => 'Thanh toán thành công',
                    'data' => [
                        'examination_id' => $examination->id,
                        'examination_code' => $examinationCode,
                        'amount' => $amount
                    ]
                ]);
            }

            Log::warning('VietQR: Invalid content format', ['content' => $content]);

            return response()->json([
                'error' => false,
                'errorReason' => '',
                'toastMessage' => 'Nội dung chuyển khoản không hợp lệ',
                'data' => []
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => true,
                'errorReason' => 'VALIDATION_ERROR',
                'toastMessage' => 'Dữ liệu không hợp lệ: ' . implode(', ', $e->validator->errors()->all()),
                'data' => []
            ], 422);
        } catch (\Exception $e) {
            Log::error('VietQR Transaction Sync Error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'error' => true,
                'errorReason' => 'PROCESSING_ERROR',
                'toastMessage' => 'Lỗi xử lý giao dịch',
                'data' => []
            ], 500);
        }
    }
}
