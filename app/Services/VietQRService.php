<?php
// File: app/Services/VietQRService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class VietQRService
{
    private $apiUrl;
    private $username;
    private $password;
    private $bankCode;
    private $bankAccount;
    private $accountName;

    public function __construct()
    {
        $this->apiUrl = env('VIETQR_API_URL', 'https://dev.vietqr.org');
        $this->username = env('VIETQR_USERNAME');
        $this->password = env('VIETQR_PASSWORD');
        $this->bankCode = env('VIETQR_BANK_CODE', 'MB');
        $this->bankAccount = env('VIETQR_BANK_ACCOUNT');
        $this->accountName = env('VIETQR_ACCOUNT_NAME');
    }

    /**
     * Bước 2.1: Lấy token từ VietQR API
     */
    public function getToken()
    {
        $cacheKey = 'vietqr_api_token';
        $token = Cache::get($cacheKey);

        if (!$token) {
            $token = $this->requestNewToken();
            if ($token) {
                // Cache token 4 phút (API có thời gian sống 5 phút)
                Cache::put($cacheKey, $token, 240);
            }
        }

        return $token;
    }

    /**
     * Request token mới từ VietQR
     */
    private function requestNewToken()
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password)
            ])->post($this->apiUrl . '/vqr/api/token_generate');
            if ($response->successful()) {
                $data = $response->json();
                Log::info('VietQR API Token generated successfully');
                return $data['access_token'] ?? null;
            }

            Log::error('VietQR API Token Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('VietQR API Token Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Bước 2.2: Tạo QR code thanh toán
     * FIX: Đảm bảo content được lưu chính xác để check transaction sau này
     */
    public function generateQRCode($orderId, $amount, $content = null)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }

            // Tạo nội dung chuyển khoản cố định
            if (!$content) {
                $content = 'TT ' . $orderId;
            }

            // Sanitize content theo yêu cầu VietQR (max 23 chars, no accents)
            $originalContent = $content; // Lưu content gốc
            $sanitizedContent = $this->sanitizeContent($content);

            $requestData = [
                'bankCode' => $this->bankCode,
                'bankAccount' => $this->bankAccount,
                'userBankName' => $this->accountName,
                'content' => $sanitizedContent, // Dùng content đã sanitize
                'qrType' => 0, // VietQR động
                'amount' => (int)$amount,
                'orderId' => $orderId,
                'transType' => 'C', // Credit - nhận tiền
                'note' => 'Thanh toan phieu kham ' . $orderId
            ];

            Log::info('VietQR Generate QR Request', $requestData);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post($this->apiUrl . '/vqr/api/qr/generate-customer', $requestData);
            if ($response->successful()) {
                $data = $response->json();
                Log::info('VietQR QR Code generated successfully', [
                    'orderId' => $orderId,
                    'response' => $data
                ]);
                // FIX: Lưu content thực tế được trả về từ API để dùng cho check transaction
                if (isset($data['content'])) {
                    // Cập nhật examination với content thực tế từ VietQR
                    $this->updateExaminationContent($orderId, $data['content']);
                }

                Log::info('VietQR QR Code generated successfully', [
                    'orderId' => $orderId,
                    'original_content' => $originalContent,
                    'sanitized_content' => $sanitizedContent,
                    'returned_content' => $data['content'] ?? null
                ]);

                return $data;
            }

            Log::error('VietQR Generate QR Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('VietQR Generate QR Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * FIX: Cập nhật content thực tế vào examination để check transaction chính xác
     */
    private function updateExaminationContent($orderId, $actualContent)
    {
        try {
            // Tìm examination theo examination_code
            $examination = \App\Models\Examination::where('examination_code', $orderId)->first();
            if ($examination) {
                $examination->update([
                    'qr_content' => $actualContent // Lưu content thực tế từ VietQR
                ]);
                Log::info('Updated examination with actual QR content', [
                    'examination_code' => $orderId,
                    'actual_content' => $actualContent
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error updating examination content: ' . $e->getMessage());
        }
    }

    /**
     * Bước 2.3: Check transaction status
     * FIX: Sử dụng content thực tế từ database và cải thiện tham số
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }

            // FIX: Lấy content thực tế từ examination
            $examination = \App\Models\Examination::where('examination_code', $orderId)->first();
            $actualContent = $examination->qr_content ?? ('TT ' . $orderId);

            // FIX: Tạo checkSum theo đúng format và đảm bảo không null
            $checkSum = md5($this->bankAccount . $this->username);
            $requestData = [
                'bankAccount' => $this->bankAccount,
                'type' => 0, // Check by orderId
                'value' => $orderId, // FIX: Dùng content thực tế thay vì orderId
                'checkSum' => $checkSum
            ];

            Log::info('VietQR Check Transaction Request', [
                'bankAccount' => $this->bankAccount,
                'orderId' => $orderId,
                'actualContent' => $actualContent,
                'checkSum' => $checkSum,
                'username' => $this->username
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post($this->apiUrl . '/vqr/api/transactions/check-order', $requestData);
            dd($response->body()); // FIX: Kiểm tra response body
            if ($response->successful()) {
                $data = $response->json(); // FIX: Kiểm tra dữ liệu trả về
                Log::info('VietQR Check Transaction Success', [
                    'orderId' => $orderId,
                    'response' => $data
                ]);
                return $data;
            }

            Log::error('VietQR Check Transaction Error: ' . $response->body());

            // FIX: Thử cách khác nếu check by content không thành công
            return $this->checkTransactionByOrderId($orderId, $token);
        } catch (\Exception $e) {
            Log::error('VietQR Check Transaction Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * FIX: Phương thức backup check transaction by orderId
     */
    private function checkTransactionByOrderId($orderId, $token)
    {
        try {
            $checkSum = md5($this->bankAccount . $this->username);

            $requestData = [
                'bankAccount' => $this->bankAccount,
                'type' => 2, // Check by orderId thay vì content
                'value' => $orderId,
                'checkSum' => $checkSum
            ];

            Log::info('VietQR Check Transaction by OrderId Request', $requestData);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post($this->apiUrl . '/vqr/api/transactions/check-order', $requestData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('VietQR Check Transaction by OrderId Success', [
                    'orderId' => $orderId,
                    'response' => $data
                ]);
                return $data;
            }

            Log::error('VietQR Check Transaction by OrderId Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('VietQR Check Transaction by OrderId Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test callback - FIX: Sử dụng content đúng
     */
    public function testPayment($orderId, $amount, $content = null)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }
            // FIX: Lấy content thực tế từ examination
            $examination = \App\Models\Examination::where('examination_code', $orderId)->first();
            $actualContent = $examination->qr_content ?? null;

            if (!$actualContent) {
                if (!$content) {
                    $content = 'TT ' . $orderId;
                }
                $actualContent = $this->sanitizeContent($content);
            }

            $requestData = [
                'bankAccount' => $this->bankAccount,
                'content' => $actualContent, // Dùng content thực tế
                'amount' => (int)$amount,
                'transType' => 'C',
                'bankCode' => $this->bankCode
            ];
            Log::info('VietQR Test Payment Request', [
                'orderId' => $orderId,
                'actualContent' => $actualContent,
                'amount' => $amount
            ]);
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post($this->apiUrl . '/vqr/bank/api/test/transaction-callback', $requestData);
            if ($response->successful()) {
                $data = $response->json();
                Log::info('VietQR Test Payment Success', ['orderId' => $orderId]);
                return $data;
            }

            Log::error('VietQR Test Payment Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('VietQR Test Payment Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * FIX: Sanitize content cho VietQR - cải thiện logic
     */

    /**
     * Remove Vietnamese accents
     */
    private function removeVietnameseAccents($str)
    {
        $vietnameseMap = [
            'à' => 'a',
            'á' => 'a',
            'ạ' => 'a',
            'ả' => 'a',
            'ã' => 'a',
            'â' => 'a',
            'ầ' => 'a',
            'ấ' => 'a',
            'ậ' => 'a',
            'ẩ' => 'a',
            'ẫ' => 'a',
            'ă' => 'a',
            'ằ' => 'a',
            'ắ' => 'a',
            'ặ' => 'a',
            'ẳ' => 'a',
            'ẵ' => 'a',
            'è' => 'e',
            'é' => 'e',
            'ẹ' => 'e',
            'ẻ' => 'e',
            'ẽ' => 'e',
            'ê' => 'e',
            'ề' => 'e',
            'ế' => 'e',
            'ệ' => 'e',
            'ể' => 'e',
            'ễ' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'ị' => 'i',
            'ỉ' => 'i',
            'ĩ' => 'i',
            'ò' => 'o',
            'ó' => 'o',
            'ọ' => 'o',
            'ỏ' => 'o',
            'õ' => 'o',
            'ô' => 'o',
            'ồ' => 'o',
            'ố' => 'o',
            'ộ' => 'o',
            'ổ' => 'o',
            'ỗ' => 'o',
            'ơ' => 'o',
            'ờ' => 'o',
            'ớ' => 'o',
            'ợ' => 'o',
            'ở' => 'o',
            'ỡ' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'ụ' => 'u',
            'ủ' => 'u',
            'ũ' => 'u',
            'ư' => 'u',
            'ừ' => 'u',
            'ứ' => 'u',
            'ự' => 'u',
            'ử' => 'u',
            'ữ' => 'u',
            'ỳ' => 'y',
            'ý' => 'y',
            'ỵ' => 'y',
            'ỷ' => 'y',
            'ỹ' => 'y',
            'đ' => 'd',
            // Uppercase
            'À' => 'A',
            'Á' => 'A',
            'Ạ' => 'A',
            'Ả' => 'A',
            'Ã' => 'A',
            'Â' => 'A',
            'Ầ' => 'A',
            'Ấ' => 'A',
            'Ậ' => 'A',
            'Ẩ' => 'A',
            'Ẫ' => 'A',
            'Ă' => 'A',
            'Ằ' => 'A',
            'Ắ' => 'A',
            'Ặ' => 'A',
            'Ẳ' => 'A',
            'Ẵ' => 'A',
            'È' => 'E',
            'É' => 'E',
            'Ẹ' => 'E',
            'Ẻ' => 'E',
            'Ẽ' => 'E',
            'Ê' => 'E',
            'Ề' => 'E',
            'Ế' => 'E',
            'Ệ' => 'E',
            'Ể' => 'E',
            'Ễ' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Ị' => 'I',
            'Ỉ' => 'I',
            'Ĩ' => 'I',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ọ' => 'O',
            'Ỏ' => 'O',
            'Õ' => 'O',
            'Ô' => 'O',
            'Ồ' => 'O',
            'Ố' => 'O',
            'Ộ' => 'O',
            'Ổ' => 'O',
            'Ỗ' => 'O',
            'Ơ' => 'O',
            'Ờ' => 'O',
            'Ớ' => 'O',
            'Ợ' => 'O',
            'Ở' => 'O',
            'Ỡ' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Ụ' => 'U',
            'Ủ' => 'U',
            'Ũ' => 'U',
            'Ư' => 'U',
            'Ừ' => 'U',
            'Ứ' => 'U',
            'Ự' => 'U',
            'Ử' => 'U',
            'Ữ' => 'U',
            'Ỳ' => 'Y',
            'Ý' => 'Y',
            'Ỵ' => 'Y',
            'Ỷ' => 'Y',
            'Ỹ' => 'Y',
            'Đ' => 'D'
        ];

        return strtr($str, $vietnameseMap);
    }

    /**
     * Check if VietQR is configured
     */
    public function isConfigured()
    {
        return !empty($this->username) &&
            !empty($this->password) &&
            !empty($this->bankAccount) &&
            !empty($this->accountName);
    }

    /**
     * Trigger test callback - gọi API của VietQR để họ gửi callback về cho chúng ta
     */
    public function triggerTestCallback($orderId, $amount, $content = null)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }

            if (!$content) {
                $content = 'TT ' . $orderId;
            }

            // Sanitize content - nhưng đừng cắt quá ngắn
            // $content = $this->sanitizeContent($content);

            // FIX: Thêm callback URL và các field có thể cần thiết
            $requestData = [
                'bankAccount' => $this->bankAccount,
                'content' => $content,
                'amount' => (string) $amount, // Thử cả string và number
                'bankCode' => $this->bankCode,
                'transType' => 'C',
                // Thêm callback URL - VietQR cần biết gửi callback về đâu
                'callbackUrl' => url('http://pksaigon.test/bank/api/transaction-sync'),
                // Thêm các field khác có thể cần
                'orderId' => $orderId,
                'description' => 'Test callback for examination ' . $orderId
            ];
            // Validate tất cả field
            foreach (['bankAccount', 'content', 'amount', 'bankCode', 'transType'] as $field) {
                if (empty($requestData[$field]) && $requestData[$field] !== 0) {
                    throw new \Exception("Required field {$field} is empty");
                }
            }

            Log::info('VietQR Trigger Test Callback with URL', [
                'orderId' => $orderId,
                'url' => $this->apiUrl . '/vqr/bank/api/test/transaction-callback',
                'requestData' => $requestData,
                'token_preview' => substr($token, 0, 20) . '...'
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'User-Agent' => 'Laravel/VietQR-Test',
                'Accept' => 'application/json'
            ])
                ->timeout(30)
                ->withoutVerifying() // Disable SSL verification for test
                ->post($this->apiUrl . '/vqr/bank/api/test/transaction-callback', $requestData);

            dd($response->body(), $requestData); // FIX: Kiểm tra response body
            Log::info('VietQR Response Details', [
                'status' => $response->status(),
                'reason' => $response->reason(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'json' => $response->json(),
                'transfer_time' => $response->transferStats?->getTransferTime()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Check nếu có lỗi trong response body
                if (isset($data['status']) && $data['status'] === 'FAILED') {
                    throw new \Exception('VietQR API Error: ' . ($data['message'] ?? 'Unknown error'));
                }

                return $data;
            }

            // Xử lý các HTTP error codes
            $errorMessage = 'HTTP ' . $response->status() . ' - ' . $response->reason();
            $responseBody = $response->body();

            if ($responseBody) {
                $errorMessage .= ': ' . $responseBody;
            }

            throw new \Exception($errorMessage);
        } catch (\Exception $e) {
            Log::error('VietQR Trigger Test Callback Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'orderId' => $orderId,
                'amount' => $amount,
                'content' => $content
            ]);

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cải thiện content sanitization - đừng cắt quá ngắn
     */
    public function sanitizeContent($content)
    {
        // Remove Vietnamese accents
        $content = $this->removeVietnameseAccents($content);

        // Remove special characters, keep only letters, numbers and spaces
        $content = preg_replace('/[^A-Za-z0-9\s]/', '', $content);

        // Remove extra spaces
        $content = preg_replace('/\s+/', ' ', $content);

        // FIX: Limit to 50 characters thay vì 23 để test
        if (strlen($content) > 50) {
            $content = substr($content, 0, 50);
        }

        return trim($content);
    }

    /**
     * FIX: Cải thiện method check configuration
     */
    public function debugConfiguration()
    {
        $config = [
            'api_url' => $this->apiUrl,
            'username' => $this->username ? 'SET (length: ' . strlen($this->username) . ')' : 'NOT SET',
            'password' => $this->password ? 'SET (length: ' . strlen($this->password) . ')' : 'NOT SET',
            'bank_code' => $this->bankCode ?: 'NOT SET',
            'bank_account' => $this->bankAccount ? 'SET (length: ' . strlen($this->bankAccount) . ')' : 'NOT SET',
            'account_name' => $this->accountName ? 'SET (length: ' . strlen($this->accountName) . ')' : 'NOT SET',
        ];

        // Validate critical fields
        $errors = [];
        if (empty($this->username)) $errors[] = 'VIETQR_USERNAME not configured';
        if (empty($this->password)) $errors[] = 'VIETQR_PASSWORD not configured';
        if (empty($this->bankAccount)) $errors[] = 'VIETQR_BANK_ACCOUNT not configured';
        if (empty($this->bankCode)) $errors[] = 'VIETQR_BANK_CODE not configured';
        if (empty($this->accountName)) $errors[] = 'VIETQR_ACCOUNT_NAME not configured';

        $config['errors'] = $errors;
        $config['is_configured'] = empty($errors);

        Log::info('VietQR Configuration Debug', $config);

        return $config;
    }
}
