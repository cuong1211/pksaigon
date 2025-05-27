<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VietQRController extends Controller
{
    /**
     * API 1: Generate Token cho VietQR
     * POST /api/token_generate
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
            $encodedCredentials = substr($authHeader, 6); // Remove "Basic "
            $decodedCredentials = base64_decode($encodedCredentials);
            if (!$decodedCredentials || !str_contains($decodedCredentials, ':')) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'INVALID_CREDENTIALS',
                    'toastMessage' => 'Thông tin xác thực không hợp lệ'
                ], 401);
            }

            [$username, $password] = explode(':', $decodedCredentials, 2);
            // Kiểm tra thông tin đăng nhập (cấu hình trong .env)
            $validUsername = env('VIETQR_USERNAME', 'vietqr_user');
            $validPassword = env('VIETQR_PASSWORD', 'vietqr_password');

            if ($username !== $validUsername || $password !== $validPassword) {
                return response()->json([
                    'error' => true,
                    'errorReason' => 'INVALID_CREDENTIALS', 
                    'toastMessage' => 'Tên đăng nhập hoặc mật khẩu không đúng'
                ], 401);
            }

            // Tạo access token
            $accessToken = Str::random(64);
            $expiresIn = Carbon::now()->addHours(24)->timestamp; // Token hết hạn sau 24h

            // Lưu token vào cache hoặc database
            cache()->put("vietqr_token_{$accessToken}", [
                'username' => $username,
                'created_at' => now(),
                'expires_at' => $expiresIn
            ], 24 * 60); // 24 hours

            return response()->json([
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_in' => (string) $expiresIn
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'errorReason' => 'INTERNAL_ERROR',
                'toastMessage' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API 2: Transaction Sync từ VietQR
     * POST /bank/api/transaction-sync
     */
    public function transactionSync(Request $request)
    {
        try {
            // Validate request data
            $request->validate([
                'bankaccount' => 'required|string',
                'amount' => 'required|string',
                'transType' => 'required|string|in:D,C', // D: Debit, C: Credit
                'content' => 'required|string'
            ]);

            $bankAccount = $request->input('bankaccount');
            $amount = $request->input('amount');
            $transType = $request->input('transType');
            $content = $request->input('content');

            // Tạo transaction ID unique
            $transactionId = 'TXN_' . date('YmdHis') . '_' . Str::random(6);

            // Lưu giao dịch vào database
            $transactionData = [
                'transaction_id' => $transactionId,
                'bank_account' => $bankAccount,
                'amount' => $amount,
                'trans_type' => $transType,
                'content' => $content,
                'status' => 'SUCCESS',
                'processed_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Lưu vào bảng transactions (giả sử bạn có bảng này)
            DB::table('vietqr_transactions')->insert($transactionData);

            // Xử lý logic nghiệp vụ theo nội dung giao dịch
            $this->processTransactionContent($content, $amount, $transType, $bankAccount);

            // Log transaction
            Log::info('VietQR Transaction Sync', [
                'transaction_id' => $transactionId,
                'bank_account' => $bankAccount,
                'amount' => $amount,
                'trans_type' => $transType,
                'content' => $content
            ]);

            return response()->json([
                'error' => false,
                'errorReason' => '',
                'toastMessage' => 'Giao dịch đã được xử lý thành công',
                'data' => [
                    'refTransactionId' => $transactionId
                ]
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
                'toastMessage' => 'Lỗi xử lý giao dịch: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Xử lý nội dung giao dịch theo nghiệp vụ
     */
    private function processTransactionContent($content, $amount, $transType, $bankAccount)
    {
        try {
            // Parse nội dung giao dịch để lấy thông tin
            // Ví dụ: "Thanh toan don hang #DH001" hoặc "Nap tien vao tai khoan #TK001"
            
            if (str_contains(strtolower($content), 'don hang') || str_contains(strtolower($content), 'đơn hàng')) {
                // Xử lý thanh toán đơn hàng
                $this->processOrderPayment($content, $amount, $transType);
                
            } elseif (str_contains(strtolower($content), 'nap tien') || str_contains(strtolower($content), 'nạp tiền')) {
                // Xử lý nạp tiền vào tài khoản
                $this->processWalletTopup($content, $amount, $transType);
                
            } elseif (str_contains(strtolower($content), 'hoa don') || str_contains(strtolower($content), 'hóa đơn')) {
                // Xử lý thanh toán hóa đơn
                $this->processInvoicePayment($content, $amount, $transType);
                
            } else {
                // Giao dịch chung
                Log::info('VietQR General Transaction', [
                    'content' => $content,
                    'amount' => $amount,
                    'type' => $transType
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Process Transaction Content Error', [
                'error' => $e->getMessage(),
                'content' => $content
            ]);
        }
    }

    /**
     * Xử lý thanh toán đơn hàng
     */
    private function processOrderPayment($content, $amount, $transType)
    {
        // Extract order ID từ content
        preg_match('/#([A-Za-z0-9]+)/', $content, $matches);
        
        if (!empty($matches[1])) {
            $orderId = $matches[1];
            
            // Cập nhật trạng thái đơn hàng (nếu có bảng orders)
            /*
            \DB::table('orders')
                ->where('order_code', $orderId)
                ->update([
                    'payment_status' => 'PAID',
                    'paid_amount' => $amount,
                    'paid_at' => now()
                ]);
            */
            
            Log::info('Order Payment Processed', [
                'order_id' => $orderId,
                'amount' => $amount
            ]);
        }
    }

    /**
     * Xử lý nạp tiền vào ví
     */
    private function processWalletTopup($content, $amount, $transType)
    {
        // Extract user/account ID từ content
        preg_match('/#([A-Za-z0-9]+)/', $content, $matches);
        
        if (!empty($matches[1])) {
            $accountId = $matches[1];
            
            // Cộng tiền vào ví người dùng (nếu có bảng wallets)
            /*
            \DB::table('user_wallets')
                ->where('account_code', $accountId)
                ->increment('balance', $amount);
            */
            
            Log::info('Wallet Topup Processed', [
                'account_id' => $accountId,
                'amount' => $amount
            ]);
        }
    }

    /**
     * Xử lý thanh toán hóa đơn
     */
    private function processInvoicePayment($content, $amount, $transType)
    {
        // Extract invoice ID từ content
        preg_match('/#([A-Za-z0-9]+)/', $content, $matches);
        
        if (!empty($matches[1])) {
            $invoiceId = $matches[1];
            
            // Cập nhật trạng thái hóa đơn (nếu có bảng invoices)
            /*
            \DB::table('invoices')
                ->where('invoice_code', $invoiceId)
                ->update([
                    'status' => 'PAID',
                    'paid_amount' => $amount,
                    'paid_at' => now()
                ]);
            */
            
            Log::info('Invoice Payment Processed', [
                'invoice_id' => $invoiceId,
                'amount' => $amount
            ]);
        }
    }
}