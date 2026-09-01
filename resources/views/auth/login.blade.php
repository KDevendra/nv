@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto mt-20">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col lg:flex-row">

                <!-- Left-Side Content Panel (Hidden on mobile) -->
                <div
                    class="hidden lg:flex lg:w-5/12 bg-zendo-navy bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-opacity-20 p-10 flex-col justify-between relative h-full">
                    <!-- Decorative overlay to ensure text readability against pattern -->
                    <div class="absolute inset-0 bg-zendo-navy/90"></div>

                    <div class="relative z-10 flex-grow flex flex-col justify-center">
                        <h2 class="text-4xl font-heading mb-8 text-white">Welcome Back</h2>

                        <p class="font-body text-gray-300 leading-relaxed text-lg mb-8">
                            Sign in to access your dashboard, manage your properties, and connect with buyers or tenants.
                        </p>

                        <ul class="space-y-4 text-gray-200 font-body">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Manage your favorites and alerts
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Access exclusive listings
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Connect directly with agents
                            </li>
                        </ul>
                    </div>

                    <!-- Bottom Info / Trust Badge -->
                    <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 bg-white/10 p-2 rounded-full">
                                <svg class="w-6 h-6 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">100% Secure & Confidential</p>
                                <p class="text-xs text-gray-400 font-body">Your data is safe with us</p>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div
                        class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-zendo-gold opacity-10 rounded-full blur-3xl pointer-events-none">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl pointer-events-none">
                    </div>
                </div>

                <!-- Right Form Panel -->
                <div class="w-full lg:w-7/12 p-8 lg:p-12 bg-white flex flex-col justify-between" x-data="{ 
                    phone: '', 
                    otpDigits: ['', '', '', '', '', ''],
                    otpSent: false, 
                    sendingOtp: false, 
                    verifyingOtp: false, 
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
                            if (clean.length === 6 && this.$refs['otp_5']) {
                                this.$refs['otp_5'].focus();
                            } else {
                                const next = Math.min(clean.length, 5);
                                if (this.$refs['otp_' + next]) {
                                    this.$refs['otp_' + next].focus();
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
                        if (val && index < 5 && this.$refs['otp_' + (index + 1)]) {
                            this.$refs['otp_' + (index + 1)].focus();
                        }
                    },

                    handleKeyDown(e, index) {
                        if (e.key === 'Backspace') {
                            if (!this.otpDigits[index] && index > 0 && this.$refs['otp_' + (index - 1)]) {
                                this.otpDigits[index - 1] = '';
                                this.$refs['otp_' + (index - 1)].focus();
                            } else {
                                this.otpDigits[index] = '';
                            }
                        } else if (e.key === 'ArrowLeft' && index > 0 && this.$refs['otp_' + (index - 1)]) {
                            this.$refs['otp_' + (index - 1)].focus();
                        } else if (e.key === 'ArrowRight' && index < 5 && this.$refs['otp_' + (index + 1)]) {
                            this.$refs['otp_' + (index + 1)].focus();
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

                    async sendOtp() {
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
                                body: JSON.stringify({ phone: this.phone, type: 'login' })
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
                                    if (this.$refs['otp_0']) this.$refs['otp_0'].focus();
                                });
                            } else {
                                this.otpError = data.message || 'Failed to send OTP.';
                            }
                        } catch (err) {
                            this.sendingOtp = false;
                            this.otpError = 'Network error. Please try again.';
                        }
                    },

                    async verifyOtp() {
                        this.otpError = '';
                        this.otpMsg = '';
                        const currentOtp = this.otp;
                        if (!currentOtp || currentOtp.length !== 6) {
                            this.otpError = 'Please enter a complete 6-digit OTP.';
                            return;
                        }
                        this.verifyingOtp = true;
                        try {
                            const res = await fetch('{{ route('otp.login') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ phone: this.phone, otp: currentOtp })
                            });
                            const data = await res.json();
                            this.verifyingOtp = false;
                            if (res.ok && data.success) {
                                window.location.href = data.redirect_url;
                            } else {
                                this.otpError = data.message || 'Invalid OTP.';
                            }
                        } catch (err) {
                            this.verifyingOtp = false;
                            this.otpError = 'Network error. Please try again.';
                        }
                    }
                }">
                    <div>
                        <!-- Header Section -->
                        <div class="mb-6">
                            <h2 class="text-3xl font-bold font-heading text-zendo-navy mb-2">Sign In with OTP</h2>
                            <p class="text-gray-600 font-body text-sm leading-relaxed">
                                Enter your 10-digit mobile number below. We'll send a 6-digit verification code directly to your phone.
                            </p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800"
                            :status="session('status')" />

                        <!-- Mobile OTP Login View -->
                        <div class="space-y-6">
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

                            <!-- Phone Step -->
                            <div>
                                <label for="mobile_phone" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                                    Registered Mobile Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative flex">
                                    <span class="inline-flex items-center px-4 rounded-l-xl border border-r-0 border-gray-300 bg-gray-50 text-gray-700 text-sm font-bold">
                                        +91
                                    </span>
                                    <input id="mobile_phone" type="tel" x-model="phone" maxlength="10"
                                        @input="phone = phone.replace(/[^0-9]/g, '')"
                                        :readonly="otpSent"
                                        class="form-input block w-full rounded-r-xl border border-gray-300 py-3.5 px-4 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body text-base font-medium"
                                        placeholder="Enter 10-digit mobile number">
                                </div>
                            </div>

                            <!-- OTP Input Step (Visible after OTP Sent) -->
                            <div x-show="otpSent" x-cloak class="space-y-4 pt-2 border-t border-gray-100">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 font-highlight mb-3 text-center">
                                        Enter 6-Digit Verification Code
                                    </label>

                                    <!-- Hidden WebOTP helper input for mobile browser SMS auto-fill -->
                                    <input type="text" autocomplete="one-time-code" class="sr-only" 
                                        @input="fillOtp($event.target.value)">

                                    <!-- 6 Individual Digit Boxes -->
                                    <div class="flex items-center justify-center gap-2 md:gap-3" @paste="handlePaste($event)">
                                        <template x-for="(digit, idx) in otpDigits" :key="idx">
                                            <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                                :x-ref="'otp_' + idx"
                                                x-model="otpDigits[idx]"
                                                @input="handleDigitInput($event, idx)"
                                                @keydown="handleKeyDown($event, idx)"
                                                @focus="$event.target.select()"
                                                class="w-11 h-12 md:w-12 md:h-14 text-center text-xl md:text-2xl font-extrabold text-gray-900 bg-gray-50 border-2 rounded-xl shadow-sm transition-all focus:outline-none font-mono"
                                                :class="otpDigits[idx] ? 'border-zendo-gold bg-white ring-2 ring-zendo-gold/30' : 'border-gray-300 focus:border-zendo-navy focus:ring-2 focus:ring-zendo-navy/20'"
                                                placeholder="•">
                                        </template>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-gray-600 pt-1">
                                    <span>Didn't receive OTP?</span>
                                    <button type="button" @click="sendOtp()" :disabled="countdown > 0 || sendingOtp"
                                        :class="countdown > 0 ? 'text-gray-400 cursor-not-allowed' : 'text-zendo-navy hover:text-zendo-gold font-bold underline'">
                                        <span x-show="countdown > 0">Resend in <span x-text="countdown"></span>s</span>
                                        <span x-show="countdown === 0">Resend OTP</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div>
                                <template x-if="!otpSent">
                                    <button type="button" @click="sendOtp()" :disabled="sendingOtp"
                                        class="bg-zendo-navy hover:bg-zendo-gold w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-base font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-300 transform hover:-translate-y-0.5">
                                        <svg x-show="sendingOtp" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Get Verification OTP</span>
                                    </button>
                                </template>

                                <template x-if="otpSent">
                                    <button type="button" @click="verifyOtp()" :disabled="verifyingOtp"
                                        class="bg-emerald-600 hover:bg-emerald-700 w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-base font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 transform hover:-translate-y-0.5">
                                        <svg x-show="verifyingOtp" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Verify OTP & Sign In</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Footer: Need an account? -->
                    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                        <p class="text-sm text-gray-600 font-body">
                            Don't have an account?
                            <a href="{{ route('register') }}"
                                class="font-bold text-zendo-navy hover:text-zendo-gold transition-colors underline decoration-2 underline-offset-4 ml-1">
                                Create Account
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection