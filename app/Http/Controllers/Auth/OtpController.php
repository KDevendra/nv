<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Send OTP to the provided mobile number.
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'type'  => ['nullable', 'string', 'in:login,register'],
        ], [
            'phone.regex' => 'Please enter a valid 10-digit mobile number.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
        $type = $request->input('type', 'login');

        // Validation based on type
        if ($type === 'login') {
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number not registered. Please create an account first.',
                ], 422);
            }
        } elseif ($type === 'register') {
            $user = User::where('phone', $phone)->first();
            if ($user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number is already registered. Please sign in instead.',
                ], 422);
            }
        }

        // Generate 6-digit OTP
        $otp = (string) mt_rand(100000, 999999);

        // Store OTP in cache for 10 minutes
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(10));

        // Call OtpService to send SMS
        $result = $this->otpService->sendOtp($phone, $otp, 10);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to send OTP. Please try again.',
            ], 500);
        }

        $responseData = [
            'success' => true,
            'message' => "OTP sent successfully to +91 {$phone}.",
        ];

        // Attach dev_otp if in local environment or OTP service is not configured
        if (config('app.env') === 'local' || !$this->otpService->isConfigured() || isset($result['otp'])) {
            $responseData['dev_otp'] = $otp;
        }

        return response()->json($responseData);
    }

    /**
     * Verify OTP and Login.
     */
    public function loginWithOtp(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'otp'   => ['required', 'string', 'digits:6'],
        ], [
            'phone.regex' => 'Please enter a valid 10-digit mobile number.',
            'otp.digits'  => 'OTP must be a 6-digit number.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
        $otp = trim($request->input('otp'));

        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp || $cachedOtp !== $otp) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP. Please request a new OTP.',
                ], 422);
            }
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mobile number not found. Please register first.',
                ], 404);
            }
            return back()->withErrors(['phone' => 'Mobile number not registered.']);
        }

        // Clear used OTP
        Cache::forget('otp_' . $phone);

        // Login User
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $redirectUrl = $user->getDashboardUrl();

        if ($request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Logged in successfully.',
                'redirect_url' => $redirectUrl,
            ]);
        }

        return redirect()->intended($redirectUrl);
    }

    /**
     * Verify OTP and Register new user.
     */
    public function registerWithOtp(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:users,phone'],
            'email'    => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'otp'      => ['required', 'string', 'digits:6'],
            'role'     => ['required', 'string', 'in:user,owner,channel_partner'],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'phone.regex'  => 'Please enter a valid 10-digit mobile number.',
            'phone.unique' => 'Mobile number is already registered.',
            'otp.digits'   => 'OTP must be a 6-digit number.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
        $otp = trim($request->input('otp'));

        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp || $cachedOtp !== $otp) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired OTP. Please request a new OTP.',
                ], 422);
            }
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // Clear used OTP
        Cache::forget('otp_' . $phone);

        $email = $request->input('email') ?: ($phone . '@zendoindia.com');
        $password = $request->input('password') ? Hash::make($request->input('password')) : Hash::make(Str::random(16));

        $user = User::create([
            'name'     => $request->input('name'),
            'phone'    => $phone,
            'email'    => $email,
            'password' => $password,
            'role'     => $request->input('role', 'user'),
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        $redirectUrl = $user->getDashboardUrl();

        if ($request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Account created and verified successfully!',
                'redirect_url' => $user->getDashboardUrl(),
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Verify OTP and Reset Password.
     */
    public function resetPasswordWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone'    => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'otp'      => ['required', 'string', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'phone.regex'        => 'Please enter a valid 10-digit mobile number.',
            'otp.digits'         => 'OTP must be a 6-digit number.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min'       => 'Password must be at least 8 characters.',
        ]);

        $phone = preg_replace('/[^0-9]/', '', $request->input('phone'));
        $otp = trim($request->input('otp'));

        $cachedOtp = Cache::get('otp_' . $phone);

        if (!$cachedOtp || $cachedOtp !== $otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP. Please try again.',
            ], 422);
        }

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No account found associated with this mobile number.',
            ], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        Cache::forget('otp_' . $phone);
        Auth::login($user);

        return response()->json([
            'success'      => true,
            'message'      => 'Password reset successfully! You are now logged in.',
            'redirect_url' => $user->getDashboardUrl(),
        ]);
    }
}
