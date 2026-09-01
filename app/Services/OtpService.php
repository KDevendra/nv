<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OtpService
{
    protected $userId;
    protected $password;
    protected $senderId;
    protected $templateId;
    protected $lowBalanceTemplateId;
    protected $entityId;

    public function __construct()
    {
        $this->userId               = config('services.otp.user_id');
        $this->password             = config('services.otp.password');
        $this->senderId             = config('services.otp.sender_id');
        $this->templateId           = config('services.otp.template_id');
        $this->entityId             = config('services.otp.entity_id');
    }

    /**
     * Check if OTP service is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->userId) && !empty($this->password);
    }

    /**
     * Send OTP to mobile number
     */
    public function sendOtp(string $mobile, string $otp, int $validMinutes = 10): array
    {
        if (!$this->isConfigured()) {
            Log::info("OTP Mock Sent to {$mobile}: {$otp}");
            return ['success' => true, 'message' => 'OTP sent (Development Mode)', 'otp' => $otp];
        }
        $message = "NULAC Login OTP is {$otp}. Valid for {$validMinutes} minutes.";
        try {
            $response = Http::get('http://nimbusit.biz/api/SmsApi/SendSingleApi', [
                'UserID'     => $this->userId,
                'Password'   => $this->password,
                'SenderID'   => $this->senderId,
                'Phno'       => $mobile,
                'Msg'        => $message,
                'EntityID'   => $this->entityId,
                'TemplateID' => $this->templateId,
            ]);
            $body = $response->json();
            $success = isset($body['Status']) && $body['Status'] === 'OK';
            return [
                'success' => $success,
                'message' => $success ? 'OTP sent successfully' : ($body['Response']['Message'] ?? 'Failed to send OTP'),
            ];
        } catch (\Exception $e) {
            Log::error('SMS Send OTP Error: ' . $e->getMessage(), ['mobile' => $mobile]);
            return ['success' => false, 'message' => 'Failed to send OTP. Please try again.'];
        }
    }

    /**
     * Send Low Balance Alert SMS to member
     */
    public function sendLowBalanceAlert(string $mobile): array
    {
        $message = "Your Nulac Recharge Wallet balance is low. Please add funds to continue using Nulac services. https://nulac.in";
        
        if (!$this->isConfigured()) {
            Log::info("Low Balance SMS Mock Sent to {$mobile}: {$message}");
            return ['success' => true, 'message' => 'Low balance SMS sent (Development Mode)', 'mock' => true];
        }

        try {
            $response = Http::get('http://nimbusit.biz/api/SmsApi/SendSingleApi', [
                'UserID'     => $this->userId,
                'Password'   => $this->password,
                'SenderID'   => $this->senderId,
                'Phno'       => $mobile,
                'Msg'        => $message,
                'EntityID'   => $this->entityId,
                'TemplateID' => $this->lowBalanceTemplateId,
            ]);
            
            $body = $response->json();
            $success = isset($body['Status']) && $body['Status'] === 'OK';
            
            return [
                'success'  => $success,
                'message'  => $success ? 'Low balance SMS sent successfully' : ($body['Response']['Message'] ?? 'Failed to send SMS'),
                'response' => $body,
            ];
        } catch (\Exception $e) {
            Log::error('Low Balance SMS Error: ' . $e->getMessage(), ['mobile' => $mobile]);
            return ['success' => false, 'message' => 'Failed to send SMS: ' . $e->getMessage()];
        }
    }
}
