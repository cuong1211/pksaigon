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
     */
    public function generateQRCode($orderId, $amount, $content = null)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }

            // Tạo nội dung chuyển khoản
            if (!$content) {
                $content = 'TT ' . $orderId;
            }

            // Sanitize content theo yêu cầu VietQR (max 23 chars, no accents)
            $content = $this->sanitizeContent($content);

            $requestData = [
                'bankCode' => $this->bankCode,
                'bankAccount' => $this->bankAccount,
                'userBankName' => $this->accountName,
                'content' => $content,
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
                Log::info('VietQR QR Code generated successfully', ['orderId' => $orderId]);
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
     * Bước 2.3: Check transaction status
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }

            // Tạo checkSum theo đúng tài liệu VietQR:
            // checkSum = MD5(bankAccount + accessKey)
            // accessKey chính là username (theo tài liệu)
            $checkSum = md5($this->bankAccount . $this->username);

            $requestData = [
                'bankAccount' => $this->bankAccount,
                'type' => 1, // Check by orderId
                'value' => $orderId,
                'checkSum' => $checkSum
            ];

            Log::info('VietQR Check Transaction Request', [
                'bankAccount' => $this->bankAccount,
                'orderId' => $orderId,
                'checkSum' => $checkSum,
                'username' => $this->username
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])->post($this->apiUrl . '/vqr/api/transactions/check-order', $requestData);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('VietQR Check Transaction Success', [
                    'orderId' => $orderId,
                    'response' => $data
                ]);
                return $data;
            }

            Log::error('VietQR Check Transaction Error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('VietQR Check Transaction Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test callback - chỉ dùng trong môi trường test
     */
    public function testPayment($orderId, $amount, $content = null)
    {
        try {
            $token = $this->getToken();
            if (!$token) {
                throw new \Exception('Không thể lấy token từ VietQR');
            }

            if (!$content) {
                $content = 'TT ' . $orderId;
            }

            $content = $this->sanitizeContent($content);

            $requestData = [
                'bankAccount' => $this->bankAccount,
                'content' => $content,
                'amount' => (int)$amount,
                'transType' => 'C',
                'bankCode' => $this->bankCode
            ];

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
     * Sanitize content cho VietQR
     */
    private function sanitizeContent($content)
    {
        // Remove Vietnamese accents
        $content = $this->removeVietnameseAccents($content);

        // Remove special characters, keep only letters, numbers and spaces
        $content = preg_replace('/[^A-Za-z0-9\s]/', '', $content);

        // Limit to 23 characters
        if (strlen($content) > 23) {
            $content = substr($content, 0, 23);
        }

        return trim($content);
    }

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
}
