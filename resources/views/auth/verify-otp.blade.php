<x-guest-layout>
    @php
        $isSignup = $purpose === \App\Models\Otp::PURPOSE_SIGNUP;
    @endphp

    <div class="text-center mb-8">
        <a href="/"
            class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-500/30 mb-5 hover:scale-105 transition-transform duration-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </a>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
            {{ $isSignup ? 'Verify Your Email' : 'Login with OTP' }}
        </h2>
        <p class="text-slate-500 dark:text-slate-400 text-sm mt-2">
            {{ $isSignup ? 'Complete your registration by verifying your email' : 'Enter the code sent to your email to sign in' }}
        </p>
    </div>

    @if (session('status'))
        <div
            class="mb-6 font-medium text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/50 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 font-medium text-sm text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 p-4 rounded-xl border border-rose-100 dark:border-rose-800/50 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div x-data="otpVerification({
        email: @js($email),
        purpose: @js($purpose),
        verifyUrl: @js(route('otp.verify.post')),
        resendUrl: @js(route('otp.resend'))
    })" x-init="init()" class="space-y-6">

        <template x-if="errorMessage">
            <div
                class="font-medium text-sm text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20 p-4 rounded-xl border border-rose-100 dark:border-rose-800/50 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="errorMessage"></span>
            </div>
        </template>

        <template x-if="noticeMessage">
            <div
                class="font-medium text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/50 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span x-text="noticeMessage"></span>
            </div>
        </template>

        <div>
            <label for="otp-digit-0"
                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide mb-1.5 ml-1">Verification Code</label>
            <div class="flex items-center justify-center gap-2 sm:gap-2.5">
                <template x-for="(digit, index) in otpDigits" :key="index">
                    <input :id="digitInputId(index)" type="text" inputmode="numeric" maxlength="1"
                        :value="digit"
                        :autocomplete="index === 0 ? 'one-time-code' : 'off'"
                        @input="handleDigitInput($event, index)"
                        @keydown.backspace="handleBackspace($event, index)"
                        @keydown.delete="handleDelete($event, index)"
                        @keydown.left.prevent="focusDigit(index - 1)"
                        @keydown.right.prevent="focusDigit(index + 1)"
                        @focus="selectDigit(index)"
                        @paste="handlePaste($event, index)"
                        class="h-12 w-10 flex-none rounded-2xl border border-slate-200/90 bg-white/95 text-center text-lg font-black tracking-[0.08em] text-slate-900 outline-none ring-1 ring-white/90 transition-all duration-200 shadow-[0_12px_24px_-18px_rgba(99,102,241,0.5),inset_0_1px_0_rgba(255,255,255,0.9)] focus:-translate-y-0.5 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 focus:shadow-[0_18px_30px_-18px_rgba(79,70,229,0.55)] dark:border-slate-700 dark:bg-slate-950/90 dark:text-white dark:ring-slate-800/80 sm:h-14 sm:w-11 sm:text-xl"
                        :class="(digit ? 'border-indigo-300/90 text-indigo-600 shadow-[0_16px_28px_-18px_rgba(79,70,229,0.55),inset_0_1px_0_rgba(255,255,255,0.9)] dark:border-indigo-500/50 dark:text-indigo-300' : 'text-slate-400 dark:text-slate-500') + (index === 2 ? ' mr-2.5 sm:mr-3.5' : '')">
                </template>
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">
                Enter the 6-digit code sent to <strong>{{ $email }}</strong>
            </p>
        </div>

        <div class="space-y-3">
            <button @click="verifyOtp()" :disabled="otp.length !== 6 || loading"
                :class="otp.length === 6 && !loading ? 'bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-500/30' : 'bg-slate-300 dark:bg-slate-600 cursor-not-allowed'"
                class="w-full py-4 rounded-xl font-bold text-white transition-all duration-200 transform active:scale-[0.98] flex items-center justify-center gap-2">
                <span x-show="!loading">{{ $isSignup ? 'Verify Email' : 'Login with OTP' }}</span>
                <span x-show="loading" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Verifying...
                </span>
            </button>

            <div class="text-center">
                <button @click="resendOtp()" :disabled="countdown > 0 || resendLoading"
                    :class="countdown > 0 || resendLoading ? 'text-slate-400 cursor-not-allowed' : 'text-indigo-600 hover:text-indigo-700'"
                    class="text-sm font-medium transition-colors duration-200">
                    <span x-show="countdown > 0 && !resendLoading" x-text="`Resend code in ${countdown}s`"></span>
                    <span x-show="countdown <= 0 && !resendLoading">Didn't receive code? Resend</span>
                    <span x-show="resendLoading" class="flex items-center gap-1 justify-center">
                        <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sending...
                    </span>
                </button>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}"
                class="text-sm text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors duration-200">
                {{ $isSignup ? '← Back to login' : '← Use password instead' }}
            </a>
        </div>
    </div>

    <script>
        function otpVerification(config) {
            return {
                email: config.email,
                purpose: config.purpose,
                verifyUrl: config.verifyUrl,
                resendUrl: config.resendUrl,
                otp: '',
                otpDigits: ['', '', '', '', '', ''],
                loading: false,
                resendLoading: false,
                countdown: 0,
                errorMessage: '',
                noticeMessage: '',
                init() {
                    this.startCountdown();
                    this.$nextTick(() => this.focusDigit(0));
                },
                startCountdown() {
                    this.countdown = 60;
                    const timer = setInterval(() => {
                        this.countdown--;
                        if (this.countdown <= 0) {
                            clearInterval(timer);
                        }
                    }, 1000);
                },
                digitInputId(index) {
                    return `otp-digit-${index}`;
                },
                syncOtp() {
                    this.otp = this.otpDigits.join('');
                },
                focusDigit(index) {
                    if (index < 0 || index > 5) {
                        return;
                    }

                    const input = document.getElementById(this.digitInputId(index));

                    if (input) {
                        input.focus();
                        input.select();
                    }
                },
                selectDigit(index) {
                    const input = document.getElementById(this.digitInputId(index));

                    if (input) {
                        input.select();
                    }
                },
                fillDigits(value, startIndex = 0) {
                    const numbers = value.replace(/[^0-9]/g, '').slice(0, 6 - startIndex).split('');

                    if (!numbers.length) {
                        return;
                    }

                    numbers.forEach((number, offset) => {
                        this.otpDigits[startIndex + offset] = number;
                    });

                    this.syncOtp();

                    const nextIndex = startIndex + numbers.length;

                    if (this.otp.length === 6) {
                        this.verifyOtp();
                    } else {
                        this.focusDigit(nextIndex);
                    }
                },
                handleDigitInput(event, index) {
                    const value = event.target.value.replace(/[^0-9]/g, '');

                    this.errorMessage = '';

                    if (!value) {
                        this.otpDigits[index] = '';
                        this.syncOtp();
                        return;
                    }

                    this.fillDigits(value, index);
                },
                handleBackspace(event, index) {
                    this.errorMessage = '';

                    if (this.otpDigits[index]) {
                        this.otpDigits[index] = '';
                        this.syncOtp();
                        event.preventDefault();
                        return;
                    }

                    if (index > 0) {
                        this.otpDigits[index - 1] = '';
                        this.syncOtp();
                        this.focusDigit(index - 1);
                        event.preventDefault();
                    }
                },
                handleDelete(event, index) {
                    if (!this.otpDigits[index]) {
                        return;
                    }

                    this.otpDigits[index] = '';
                    this.syncOtp();
                    event.preventDefault();
                },
                handlePaste(event, index) {
                    event.preventDefault();
                    this.fillDigits(event.clipboardData.getData('text'), index);
                },
                async parseJsonResponse(response) {
                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        await response.text();
                        throw new Error('Server returned an unexpected response. Please refresh and try again.');
                    }

                    return response.json();
                },
                async verifyOtp() {
                    if (this.otp.length !== 6) {
                        this.focusDigit(this.otpDigits.findIndex((digit) => digit === ''));
                        return;
                    }

                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch(this.verifyUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                email: this.email,
                                otp: this.otp,
                                purpose: this.purpose
                            })
                        });

                        const data = await this.parseJsonResponse(response);

                        if (!response.ok || !data.success) {
                            throw new Error(data.errors?.otp?.[0] || data.errors?.email?.[0] || data.message || 'Verification failed');
                        }

                        window.location.href = data.redirect;
                    } catch (error) {
                        this.errorMessage = error.message || 'An error occurred. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                },
                async resendOtp() {
                    if (this.countdown > 0) return;

                    this.resendLoading = true;
                    this.errorMessage = '';
                    this.noticeMessage = '';

                    try {
                        const response = await fetch(this.resendUrl, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                email: this.email,
                                purpose: this.purpose
                            })
                        });

                        const data = await this.parseJsonResponse(response);

                        if (!response.ok || !data.success) {
                            throw new Error(data.errors?.email?.[0] || data.message || 'Failed to resend OTP');
                        }

                        this.noticeMessage = data.message || 'OTP sent successfully.';
                        this.startCountdown();
                    } catch (error) {
                        this.errorMessage = error.message || 'An error occurred. Please try again.';
                    } finally {
                        this.resendLoading = false;
                    }
                }
            };
        }
    </script>
</x-guest-layout>
