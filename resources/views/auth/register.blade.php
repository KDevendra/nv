@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="container mx-auto px-4 py-12" x-data="{ 
        role: '{{ old('role', 'user') }}',
        name: '{{ old('name') }}',
        phone: '{{ old('phone') }}',
        otpDigits: ['', '', '', '', '', ''],
        otpSent: false,
        sendingOtp: false,
        registering: false,
        termsAccepted: true,
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
                if (clean.length === 6 && this.$refs['reg_otp_5']) {
                    this.$refs['reg_otp_5'].focus();
                } else {
                    const next = Math.min(clean.length, 5);
                    if (this.$refs['reg_otp_' + next]) {
                        this.$refs['reg_otp_' + next].focus();
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
            if (val && index < 5 && this.$refs['reg_otp_' + (index + 1)]) {
                this.$refs['reg_otp_' + (index + 1)].focus();
            }
        },

        handleKeyDown(e, index) {
            if (e.key === 'Backspace') {
                if (!this.otpDigits[index] && index > 0 && this.$refs['reg_otp_' + (index - 1)]) {
                    this.otpDigits[index - 1] = '';
                    this.$refs['reg_otp_' + (index - 1)].focus();
                } else {
                    this.otpDigits[index] = '';
                }
            } else if (e.key === 'ArrowLeft' && index > 0 && this.$refs['reg_otp_' + (index - 1)]) {
                this.$refs['reg_otp_' + (index - 1)].focus();
            } else if (e.key === 'ArrowRight' && index < 5 && this.$refs['reg_otp_' + (index + 1)]) {
                this.$refs['reg_otp_' + (index + 1)].focus();
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

        async sendRegOtp() {
            this.otpError = '';
            this.otpMsg = '';
            if (!this.name || this.name.trim() === '') {
                this.otpError = 'Please enter your full name.';
                return;
            }
            this.phone = this.phone.replace(/[^0-9]/g, '');
            if (!this.phone || this.phone.length < 10) {
                this.otpError = 'Please enter a valid 10-digit mobile number.';
                return;
            }
            if (!this.termsAccepted) {
                this.otpError = 'Please agree to the Terms of Service and Privacy Policy.';
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
                    body: JSON.stringify({ phone: this.phone, type: 'register' })
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
                        if (this.$refs['reg_otp_0']) this.$refs['reg_otp_0'].focus();
                    });
                } else {
                    this.otpError = data.message || 'Failed to send OTP.';
                }
            } catch (err) {
                this.sendingOtp = false;
                this.otpError = 'Network error. Please try again.';
            }
        },

        async registerWithOtp() {
            this.otpError = '';
            this.otpMsg = '';
            if (!this.name || this.name.trim() === '') {
                this.otpError = 'Please enter your full name.';
                return;
            }
            if (!this.phone || this.phone.length < 10) {
                this.otpError = 'Please enter a valid 10-digit mobile number.';
                return;
            }
            const currentOtp = this.otp;
            if (!currentOtp || currentOtp.length !== 6) {
                this.otpError = 'Please enter the 6-digit OTP.';
                return;
            }
            this.registering = true;
            try {
                const res = await fetch('{{ route('otp.register') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: this.name,
                        phone: this.phone,
                        otp: currentOtp,
                        role: this.role
                    })
                });
                const data = await res.json();
                this.registering = false;
                if (res.ok && data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    this.otpError = data.message || 'Registration failed.';
                }
            } catch (err) {
                this.registering = false;
                this.otpError = 'Network error. Please try again.';
            }
        }
    }">
        <div class="max-w-5xl mx-auto mt-12">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col lg:flex-row">

                <!-- Left-Side Content Panel -->
                <div class="hidden lg:flex lg:w-5/12 bg-zendo-navy bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-opacity-20 p-10 flex-col justify-between relative h-full">
                    <div class="absolute inset-0 bg-zendo-navy/90"></div>
                    
                    <div class="relative z-10 flex-grow flex flex-col justify-center">
                        <h2 class="text-4xl font-heading mb-8 text-white">Join ZendoIndia</h2>
                        
                        <!-- Individual Content -->
                        <div x-show="role === 'user'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                            <p class="font-body text-gray-300 leading-relaxed text-lg mb-8">
                                Find your perfect property — browse 25K+ listings for buy, lease, or rent. Get expert
                                guidance and 24/7 support.
                            </p>
                            
                            <ul class="space-y-4 text-gray-200 font-body">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Save your favorite properties
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Get alerts for new matches
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Direct contact with sellers/agents
                                </li>
                            </ul>
                        </div>

                        <!-- Property Owner Content -->
                        <div x-show="role === 'owner'" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" x-cloak style="display: none;">
                            <p class="font-body text-gray-300 leading-relaxed text-lg mb-8">
                                List your property with ease — reach thousands of verified buyers/tenants. Manage listings,
                                track inquiries, get maximum visibility.
                            </p>
                            
                            <ul class="space-y-4 text-gray-200 font-body">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Reach a wider audience instantly
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Easy dashboard to manage listings
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 text-zendo-gold mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Connect with verified leads
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="relative z-10 mt-12 pt-8 border-t border-white/10">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 bg-white/10 p-2 rounded-full">
                                <svg class="w-6 h-6 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">100% Secure & Confidential</p>
                                <p class="text-xs text-gray-400 font-body">Your data is safe with us</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Form Panel -->
                <div class="w-full lg:w-7/12 p-6 lg:p-8 bg-white">
                    <h2 class="text-2xl font-bold font-heading text-zendo-navy mb-6">Create Your Account</h2>

                    <!-- Mobile OTP Register Form -->
                    <div class="space-y-4">
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

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">I am a</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center p-3 border rounded-xl cursor-pointer transition-all"
                                    :class="role === 'user' ? 'border-zendo-gold bg-zendo-light-bg/60 ring-2 ring-zendo-gold/20' : 'border-gray-300 bg-white'">
                                    <input type="radio" name="otp_role" value="user" x-model="role" class="w-4 h-4 text-zendo-gold focus:ring-zendo-gold">
                                    <div class="ml-2.5">
                                        <span class="block text-sm font-semibold text-gray-900">Individual</span>
                                        <span class="block text-xs text-gray-500">Buy, lease, or rent</span>
                                    </div>
                                </label>
                                <label class="relative flex items-center p-3 border rounded-xl cursor-pointer transition-all"
                                    :class="role === 'owner' ? 'border-zendo-gold bg-zendo-light-bg/60 ring-2 ring-zendo-gold/20' : 'border-gray-300 bg-white'">
                                    <input type="radio" name="otp_role" value="owner" x-model="role" class="w-4 h-4 text-zendo-gold focus:ring-zendo-gold">
                                    <div class="ml-2.5">
                                        <span class="block text-sm font-semibold text-gray-900">Property Owner</span>
                                        <span class="block text-xs text-gray-500">List property space</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Full Name -->
                        <div>
                            <label for="otp_name" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input id="otp_name" type="text" x-model="name" :readonly="otpSent"
                                class="form-input block w-full px-3 py-2.5 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold text-sm"
                                placeholder="Enter your full name">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="otp_phone" class="block text-sm font-semibold text-gray-700 font-highlight mb-1">
                                Mobile Number <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex">
                                <span class="inline-flex items-center px-3.5 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-600 text-sm font-bold">
                                    +91
                                </span>
                                <input id="otp_phone" type="tel" x-model="phone" maxlength="10" :readonly="otpSent"
                                    @input="phone = phone.replace(/[^0-9]/g, '')"
                                    class="form-input block w-full rounded-r-lg border border-gray-300 py-2.5 px-3 shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold text-sm"
                                    placeholder="Enter 10-digit mobile number">
                            </div>
                        </div>

                        <!-- OTP Verification Step -->
                        <div x-show="otpSent" x-cloak class="space-y-3 pt-2 border-t border-gray-100">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 font-highlight mb-2 text-center">
                                    Enter 6-Digit Verification Code
                                </label>
                                <input type="text" autocomplete="one-time-code" class="sr-only" @input="fillOtp($event.target.value)">
                                <div class="flex items-center justify-center gap-2 md:gap-3" @paste="handlePaste($event)">
                                    <template x-for="(digit, idx) in otpDigits" :key="idx">
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                                            :x-ref="'reg_otp_' + idx"
                                            x-model="otpDigits[idx]"
                                            @input="handleDigitInput($event, idx)"
                                            @keydown="handleKeyDown($event, idx)"
                                            @focus="$event.target.select()"
                                            class="w-10 h-11 md:w-11 md:h-12 text-center text-lg md:text-xl font-extrabold text-gray-900 bg-gray-50 border-2 rounded-xl shadow-sm transition-all focus:outline-none font-mono"
                                            :class="otpDigits[idx] ? 'border-zendo-gold bg-white ring-2 ring-zendo-gold/30' : 'border-gray-300 focus:border-zendo-navy focus:ring-2 focus:ring-zendo-navy/20'"
                                            placeholder="•">
                                    </template>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-600">
                                <span>Didn't receive code?</span>
                                <button type="button" @click="sendRegOtp()" :disabled="countdown > 0 || sendingOtp"
                                    :class="countdown > 0 ? 'text-gray-400 cursor-not-allowed' : 'text-zendo-navy hover:text-zendo-gold font-bold underline'">
                                    <span x-show="countdown > 0">Resend in <span x-text="countdown"></span>s</span>
                                    <span x-show="countdown === 0">Resend OTP</span>
                                </button>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="flex items-start pt-1">
                            <div class="flex items-center h-5">
                                <input id="terms" type="checkbox" x-model="termsAccepted" required
                                    class="h-4 w-4 text-zendo-gold focus:ring-zendo-gold border-gray-300 rounded transition-colors">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="text-gray-700 font-body">
                                    I agree to the
                                    <a href="{{ route('terms-and-conditions') }}" target="_blank"
                                        class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">Terms of Service</a>
                                    and
                                    <a href="{{ route('privacy-policy') }}" target="_blank"
                                        class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-2">
                            <template x-if="!otpSent">
                                <button type="button" @click="sendRegOtp()" :disabled="sendingOtp"
                                    class="bg-zendo-navy hover:bg-zendo-gold w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-300">
                                    <svg x-show="sendingOtp" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Send OTP Verification Code</span>
                                </button>
                            </template>

                            <template x-if="otpSent">
                                <button type="button" @click="registerWithOtp()" :disabled="registering"
                                    class="bg-emerald-600 hover:bg-emerald-700 w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300">
                                    <svg x-show="registering" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Verify & Create Account</span>
                                </button>
                            </template>
                        </div>

                        <!-- Already have account -->
                        <div class="text-center mt-6 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-600 font-body">
                                Already have an account?
                                <a href="{{ route('login') }}"
                                    class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                                    Sign in here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection