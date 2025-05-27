<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Examination;

class VietQRCallbackController extends Controller
{
    /**
     * Endpoint nhận callback từ VietQR với Bearer Token authentication
     * Route: POST /api/bank/api/transaction-sync
     */
    public function transactionSync(Request $request)
    {
        try {
            Log::info('=== VietQR Transaction Sync Received ===', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'raw_body' => $request->getContent(),
                'ip' => $request->ip()
            ]);

            // Kiểm tra Bearer Token
            $authHeader = $request->header('Authorization');
            if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
                Log::error('Missing Bearer Token', [
                    'authorization_header' => $authHeader
                ]);

                return response()->json([
                    'error' => true,
                    'errorReason' => 'UNAUTHORIZED',
                    'toastMessage' => 'Thiếu Bearer Token'
                ], 401);
            }

            // Lấy token và validate
            $token = substr($authHeader, 7); // Remove "Bearer "
            $tokenData = cache()->get("vietqr_token_{$token}");

            if (!$tokenData) {
                Log::error('Invalid or expired token', [
                    'token_length' => strlen($token)
                ]);

                return response()->json([
                    'error' => true,
                    'errorReason' => 'INVALID_TOKEN',
                    'toastMessage' => 'Token không hợp lệ hoặc đã hết hạn'
                ], 401);
            }

            // Kiểm tra token có hết hạn không
            if ($tokenData['expires_at'] < now()->timestamp) {
                cache()->forget("vietqr_token_{$token}");

                Log::error('Token expired', [
                    'expires_at' => $tokenData['expires_at'],
                    'current_time' => now()->timestamp
                ]);

                return response()->json([
                    'error' => true,
                    'errorReason' => 'TOKEN_EXPIRED',
                    'toastMessage' => 'Token đã hết hạn'
                ], 401);
            }

            // Validate request theo format VietQR
            $request->validate([
                'bankAccount' => 'required|string',
                'amount' => 'required|string',
                'transType' => 'required|string|in:C,D',
                'content' => 'required|string'
            ]);

            $bankAccount = $request->input('bankAccount');
            $amount = (int) $request->input('amount');
            $transType = $request->input('transType');
            $content = $request->input('content');

            Log::info('VietQR Transaction Data', [
                'bankAccount' => $bankAccount,
                'amount' => $amount,
                'transType' => $transType,
                'content' => $content
            ]);

            // Chỉ xử lý giao dịch đến (Credit)
            if ($transType !== 'C') {
                Log::info('Transaction ignored - not credit', [
                    'transType' => $transType
                ]);

                return response()->json([
                    'error' => false,
                    'errorReason' => '',
                    'toastMessage' => 'Transaction type ignored',
                    'data' => [
                        'refTransactionId' => 'IGN_' . time()
                    ]
                ]);
            }

            // Tìm examination phù hợp
            $examination = $this->findExaminationByContent($content);

            if (!$examination) {
                Log::warning('No matching examination found', [
                    'content' => $content,
                    'amount' => $amount
                ]);

                return response()->json([
                    'error' => true,
                    'errorReason' => 'NO_MATCH',
                    'toastMessage' => 'Không tìm thấy phiếu khám phù hợp',
                    'data' => []
                ]);
            }

            // Kiểm tra số tiền
            if ($amount < $examination->total_fee) {
                Log::warning('Amount insufficient', [
                    'examination_code' => $examination->examination_code,
                    'expected' => $examination->total_fee,
                    'received' => $amount
                ]);

                return response()->json([
                    'error' => true,
                    'errorReason' => 'AMOUNT_INSUFFICIENT',
                    'toastMessage' => 'Số tiền không đủ',
                    'data' => []
                ]);
            }

            // Kiểm tra đã thanh toán chưa
            if ($examination->payment_status === 'paid') {
                Log::info('Examination already paid', [
                    'examination_code' => $examination->examination_code
                ]);

                return response()->json([
                    'error' => false,
                    'errorReason' => '',
                    'toastMessage' => 'Giao dịch đã được xử lý',
                    'data' => [
                        'refTransactionId' => $examination->transaction_id
                    ]
                ]);
            }

            // Cập nhật trạng thái thanh toán
            $transactionId = 'VIETQR_' . time() . '_' . \Illuminate\Support\Str::random(6);

            $examination->update([
                'payment_status' => 'paid',
                'payment_date' => now(),
                'status' => 'completed',
                'transaction_id' => $transactionId
            ]);

            Log::info('Payment processed successfully', [
                'examination_code' => $examination->examination_code,
                'amount' => $amount,
                'content' => $content,
                'transaction_id' => $transactionId
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
            Log::error('VietQR Callback Validation Error', [
                'errors' => $e->validator->errors()->all(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => true,
                'errorReason' => 'VALIDATION_ERROR',
                'toastMessage' => 'Dữ liệu không hợp lệ: ' . implode(', ', $e->validator->errors()->all()),
                'data' => []
            ], 422);
        } catch (\Exception $e) {
            Log::error('VietQR Callback Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => true,
                'errorReason' => 'PROCESSING_ERROR',
                'toastMessage' => 'Lỗi xử lý giao dịch',
                'data' => []
            ], 500);
        }
    }

    /**
     * Tìm examination theo content với nhiều strategies
     */
    private function findExaminationByContent($content)
    {
        Log::info('Finding examination by content', ['content' => $content]);

        // Method 1: Exact match với qr_content
        $examination = Examination::where('qr_content', $content)
            ->where('payment_status', 'pending')
            ->first();
        if ($examination) {
            Log::info('Found by exact QR content match', [
                'examination_code' => $examination->examination_code
            ]);
            return $examination;
        }

        // Method 2: Tìm examination_code trong content (PK...)
        if (preg_match('/PK\d+/', $content, $matches)) {
            $examCode = $matches[0];
            $examination = Examination::where('examination_code', $examCode)
                ->where('payment_status', 'pending')
                ->first();
            if ($examination) {
                Log::info('Found by examination code pattern', [
                    'examination_code' => $examCode
                ]);
                return $examination;
            }
        }

        // Method 3: Fuzzy matching
        $examinations = Examination::where('payment_status', 'pending')->get();

        foreach ($examinations as $exam) {
            // Check nếu examination code có trong content
            if (strpos($content, $exam->examination_code) !== false) {
                Log::info('Found by examination code in content', [
                    'examination_code' => $exam->examination_code
                ]);
                return $exam;
            }

            // Check similarity với qr_content
            if ($exam->qr_content) {
                $similarity = 0;
                similar_text($exam->qr_content, $content, $similarity);
                if ($similarity > 70) {
                    Log::info('Found by similarity matching', [
                        'examination_code' => $exam->examination_code,
                        'similarity' => $similarity
                    ]);
                    return $exam;
                }
            }
        }

        return null;
    }
}
