@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container mx-auto px-4 py-16" x-data="{
    resetMode: 'otp',
    phone: '',
    email: '{{ old('email') }}',
    password: '',
    password_confirmation: '',
    otpDigits: ['', '', '', '', '', ''],
    otpSent: false,
    sendingOtp: false,
    resetting: false,
    otpMsg: '',
    otpError: '',
    devOtp: '',
    countdown: 0,
    timer: null,

    get otp() {
        return this.otpDigits.join('');
    },

    set otp(val) {
        this.fillOtp(val);
    },

    fillOtp(val) {
        if (!val) {
            this.otpDigits = ['', '', '', '', '', ''];
            return;
        }
        const clean = String(val).replace(/[^0-9]/g, '').slice(0, 6);
        const arr = clean.split('');
        for (let i = 0; i < 6; i++) {
            this.otpDigits[i] = arr[i] || '';
        }
        this.$nextTick(() => {
            if (clean.length === 6 && this.$refs['forgot_otp_5']) {
                this.$refs['forgot_otp_5'].focus();
            } else {
                const next = Math.min(clean.length, 5);
                if (this.$refs['forgot_otp_' + next]) {
                    this.$refs['forgot_otp_' + next].focus();
                }
            }
        });
    },

    handleDigitInput(e, index) {
        const val = e.target.value.replace(/[^0-9]/g, '');
        if (val.length > 1) {
            this.fillOtp(val);
            return;
        }
        this.otpDigits[index] = val;
        if (val && index < 5 && this.$refs['forgot_otp_' + (index + 1)]) {
            this.$refs['forgot_otp_' + (index + 1)].focus();
        }
    },

    handleKeyDown(e, index) {
        if (e.key === 'Backspace') {
            if (!this.otpDigits[index] && index > 0 && this.$refs['forgot_otp_' + (index - 1)]) {
                this.otpDigits[index - 1] = '';
                this.$refs['forgot_otp_' + (index - 1)].focus();
            } else {
                this.otpDigits[index] = '';
            }
        } else if (e.key === 'ArrowLeft' && index > 0 && this.$refs['forgot_otp_' + (index - 1)]) {
            this.$refs['forgot_otp_' + (index - 1)].focus();
        } else if (e.key === 'ArrowRight' && index < 5 && this.$refs['forgot_otp_' + (index + 1)]) {
            this.$refs['forgot_otp_' + (index + 1)].focus();
        }
    },

    handlePaste(e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (pasted) {
            this.fillOtp(pasted);
        }
    },

    listenWebOTP() {
        if ('OTPCredential' in window) {
            const ac = new AbortController();
            navigator.credentials.get({
                otp: { transport: ['sms'] },
                signal: ac.signal
            }).then(otp => {
                if (otp && otp.code) {
                    this.fillOtp(otp.code);
                }
            }).catch(() => {});
        }
    },

    startTimer() {
        this.countdown = 60;
        clearInterval(this.timer);
        this.timer = setInterval(() => {
            if (this.countdown > 0) {
                this.countdown--;
            } else {
                clearInterval(this.timer);
            }
        }, 1000);
    },

    async sendForgotOtp() {
        this.otpError = '';
        this.otpMsg = '';
        this.phone = this.phone.replace(/[^0-9]/g, '');
        if (!this.phone || this.phone.length < 10) {
            this.otpError = 'Please enter a valid 10-digit mobile number.';
            return;
        }
        this.sendingOtp = true;
        try {
            const res = await fetch('{{ route('otp.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ phone: this.phone, type: 'reset' })
            });
            const data = await res.json();
            this.sendingOtp = false;
            if (res.ok && data.success) {
                this.otpSent = true;
                this.otpMsg = data.message;
                if (data.dev_otp) {
                    this.devOtp = data.dev_otp;
                    this.fillOtp(data.dev_otp);
                }
                this.startTimer();
                this.listenWebOTP();
                this.$nextTick(() => {
                    if (this.$refs['forgot_otp_0']) this.$refs['forgot_otp_0'].focus();
                });
            } else {
                this.otpError = data.message || 'Failed to send OTP.';
            }
        } catch (err) {
            this.sendingOtp = false;
            this.otpError = 'Network error. Please try again.';
        }
    },

    async submitForgotOtpReset() {
        this.otpError = '';
        this.otpMsg = '';
        const currentOtp = this.otp;
        if (!currentOtp || currentOtp.length !== 6) {
            this.otpError = 'Please enter the complete 6-digit OTP.';
            return;
        }
        if (!this.password || this.password.length < 8) {
            this.otpError = 'Password must be at least 8 characters long.';
            return;
        }
        if (this.password !== this.password_confirmation) {
            this.otpError = 'Password confirmation does not match.';
            return;
        }
        this.resetting = true;
        try {
            const res = await fetch('{{ route('otp.reset-password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    phone: this.phone,
                    otp: currentOtp,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
            });
            const data = await res.json();
            this.resetting = false;
            if (res.ok && data.success) {
                window.location.href = data.redirect_url;
            } else {
                this.otpError = data.message || 'Password reset failed.';
            }
        } catch (err) {
            this.resetting = false;
            this.otpError = 'Network error. Please try again.';
        }
    }
}">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-zendo-light-bg mb-4">
                    <svg class="h-6 w-6 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-heading text-zendo-navy mb-2">Forgot Password?</h2>
                <p class="text-gray-600 font-body text-sm max-w-sm mx-auto">
                    Enter your registered mobile number below. We will send a 6-digit OTP to reset your password.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
                    <svg class="h-5 w-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                </div>
            @endif

            <!-- Mobile OTP Reset Form -->
            <div class="space-y-5">
                <div x-show="otpMsg" x-cloak class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium">
                    <div x-text="otpMsg"></div>
                    <template x-if="devOtp">
                        <div class="mt-2.5 p-2.5 bg-emerald-100/80 border border-emerald-300 rounded-lg flex items-center justify-between font-mono text-xs text-emerald-950 shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="font-bold uppercase tracking-wider text-[11px] bg-emerald-700 text-white px-1.5 py-0.5 rounded">Development Mode</span>
                                <span>OTP: <strong x-text="devOtp" class="text-sm bg-white px-2 py-0.5 rounded text-emerald-900 border border-emerald-200 font-extrabold tracking-widest"></strong></span>
                            </div>
                            <button type="button" @click="fillOtp(devOtp)" class="bg-emerald-700 hover:bg-emerald-800 text-white px-2.5 py-1 rounded text-xs font-sans font-bold transition-all shadow-sm">
                                Auto Fill
                            </button>
                        </div>
                    </template>
                </div>
                <div x-show="otpError" x-cloak class="p-3.5 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium" x-text="otpError"></div>

                <!-- Mobile Phone Field -->
                <div>
                    <label for="forgot_phone" class="block text-sm font-semibold text-gray-700 font-highlight mb-1.5">
                        Registered Mobile Number
                    </label>
                    <div class="relative flex">
                        <span class="inline-flex items-center px-3.5 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-600 text-sm font-bold">
                            +91
                        </span>
                        <input id="forgot_phone" type="tel" x-model="phone" maxlength="10" :readonly="otpSent"
                            @input="phone = phone.replace(/[^0-9]/g, '')"
                            class="form-input block w-full rounded-r-lg border border-gray-300 py-2.5 px-3 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold font-body text-sm"
                            placeholder="Enter 10-digit mobile number">
                    </div>
                </div>

                <!-- OTP Step (Visible after OTP sent) -->
                <div x-show="otpSent" x-cloak class="space-y-4 pt-2 border-t border-gray-100">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 font-highlight mb-2 text-center">
                            Enter 6-Digit OTP Code
                        </label>
                        <!-- Hidden WebOTP helper input -->
                        <input type="text" autocomplete="one-time-code" class="sr-only" @input="fillOtp($event.target.value)">

                        <!-- 6 Digit Boxes -->
                        <div class="flex items-center justify-center gap-2" @paste="handlePaste($event)">
                            <template x-for="(digit, idx) in otpDigits" :key="idx">
                                <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                    :x-ref="'forgot_otp_' + idx"
                                    x-model="otpDigits[idx]"
                                    @input="handleDigitInput($event, idx)"
                                    @keydown="handleKeyDown($event, idx)"
                                    @focus="$event.target.select()"
                                    class="w-10 h-11 text-center text-xl font-extrabold text-gray-900 bg-gray-50 border-2 rounded-xl shadow-sm transition-all focus:outline-none font-mono"
                                    :class="otpDigits[idx] ? 'border-zendo-gold bg-white ring-2 ring-zendo-gold/30' : 'border-gray-300 focus:border-zendo-navy focus:ring-2 focus:ring-zendo-navy/20'"
                                    placeholder="•">
                            </template>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                            New Password
                        </label>
                        <input id="new_password" type="password" x-model="password"
                            class="form-input block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-zendo-gold text-sm"
                            placeholder="Enter new password (min 8 chars)">
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                            Confirm New Password
                        </label>
                        <input id="new_password_confirmation" type="password" x-model="password_confirmation"
                            class="form-input block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-zendo-gold text-sm"
                            placeholder="Re-enter new password">
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-600">
                        <span>Didn't receive OTP?</span>
                        <button type="button" @click="sendForgotOtp()" :disabled="countdown > 0 || sendingOtp"
                            :class="countdown > 0 ? 'text-gray-400 cursor-not-allowed' : 'text-zendo-navy hover:text-zendo-gold font-bold underline'">
                            <span x-show="countdown > 0">Resend in <span x-text="countdown"></span>s</span>
                            <span x-show="countdown === 0">Resend OTP</span>
                        </button>
                    </div>
                </div>

                <!-- Action Button -->
                <div>
                    <template x-if="!otpSent">
                        <button type="button" @click="sendForgotOtp()" :disabled="sendingOtp"
                            class="bg-zendo-navy hover:bg-zendo-gold w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-300">
                            <svg x-show="sendingOtp" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Send OTP to Mobile</span>
                        </button>
                    </template>

                    <template x-if="otpSent">
                        <button type="button" @click="submitForgotOtpReset()" :disabled="resetting"
                            class="bg-emerald-600 hover:bg-emerald-700 w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300">
                            <svg x-show="resetting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Reset Password & Sign In</span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Back to Login -->
            <div class="text-center mt-6 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-600 font-body">
                    Remember your password? 
                    <a href="{{ route('login') }}" class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                        Back to Sign In
                    </a>
                </p>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <div class="bg-zendo-light-bg rounded-lg p-4 border border-zendo-gold/20">
                <h3 class="text-sm font-semibold text-zendo-navy font-highlight mb-2">Need Immediate Help?</h3>
                <p class="text-xs text-gray-600 font-body mb-2">
                    If you're having trouble accessing your account, our support team is here to help.
                </p>
                <div class="flex justify-center space-x-4 text-xs">
                    <a href="tel:+917494010101" class="text-zendo-navy hover:text-zendo-gold transition-colors font-semibold">
                        +91 74-94-01-01-01
                    </a>
                    <a href="mailto:info@zendoindia.com" class="text-zendo-navy hover:text-zendo-gold transition-colors font-semibold">
                        info@zendoindia.com
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
