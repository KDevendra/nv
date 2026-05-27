@section('title', 'Forgot Password')

<div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-zendo-light-bg mb-4">
            <svg class="h-6 w-6 text-zendo-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-3xl font-heading text-zendo-navy mb-2">Forgot Password?</h2>
        <p class="text-gray-600 font-body text-center max-w-sm mx-auto">
            No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 font-highlight mb-2">
                Email Address
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required 
                       autofocus
                       class="form-input block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-zendo-gold focus:border-zendo-gold font-body transition-all duration-200"
                       placeholder="Enter your email address">
            </div>
            @if($errors->get('email'))
                <div class="mt-2 text-sm text-red-600 font-body">
                    @foreach($errors->get('email') as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="btn-anim btn-primary w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zendo-gold transition-all duration-200 transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email Password Reset Link
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <p class="text-sm text-gray-600 font-body">
                Remember your password? 
                <a href="{{ route('login') }}" class="font-semibold text-zendo-navy hover:text-zendo-gold transition-colors">
                    Back to Sign In
                </a>
            </p>
        </div>
    </form>
</div>

<!-- Additional Info -->
<div class="mt-8 text-center">
    <div class="bg-zendo-light-bg rounded-lg p-4 border border-zendo-gold/20">
        <h3 class="text-sm font-semibold text-zendo-navy font-highlight mb-2">Need Immediate Help?</h3>
        <p class="text-xs text-gray-600 font-body mb-2">
            If you're having trouble accessing your account, our support team is here to help.
        </p>
        <div class="flex justify-center space-x-4 text-xs">
            <a href="74-94-01-01-01" class="text-zendo-navy hover:text-zendo-gold transition-colors font-semibold">
                📞 74-94-01-01-01
            </a>
            <a href="mailto:info@zendoindia.com" class="text-zendo-navy hover:text-zendo-gold transition-colors font-semibold">
                ✉️ info@zendoindia.com
            </a>
        </div>
    </div>
</div>
