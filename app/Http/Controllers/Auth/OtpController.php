<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {
    }

    /**
     * Show the OTP verification form
     */
    public function show(Request $request): View|RedirectResponse
    {
        $email = $request->query('email');
        $purpose = $request->query('purpose', Otp::PURPOSE_LOGIN);

        if (!$email || !$this->isSupportedPurpose($purpose)) {
            return redirect()->route('login')->with('error', 'Email address is required.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'We could not find an account with that email address.',
            ]);
        }

        if ($purpose === Otp::PURPOSE_SIGNUP && $user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Your email is already verified. Log in with your password or request a login OTP.');
        }

        if ($purpose === Otp::PURPOSE_LOGIN && !$user->email_verified_at) {
            return redirect()->route('otp.verify', [
                'email' => $email,
                'purpose' => Otp::PURPOSE_SIGNUP,
            ])->with('status', 'Complete signup verification first. The verification OTP below will activate your account.');
        }

        return view('auth.verify-otp', compact('email', 'purpose', 'user'));
    }

    /**
     * Send OTP to the user's email
     */
    public function send(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'purpose' => 'nullable|string',
        ]);

        $email = $request->input('email');
        $requestedPurpose = $request->input('purpose', Otp::PURPOSE_LOGIN);

        if (!$this->isSupportedPurpose($requestedPurpose)) {
            return $this->sendFailureResponse($request, 'Unsupported OTP request.', 422);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return $this->sendFailureResponse($request, 'We could not find an account with that email address.', 422);
        }

        $purpose = $requestedPurpose;
        $message = $purpose === Otp::PURPOSE_LOGIN
            ? 'Check your email for the login code and enter it to sign in.'
            : 'Check your email for the verification code.';

        if ($requestedPurpose === Otp::PURPOSE_LOGIN && !$user->email_verified_at) {
            $purpose = Otp::PURPOSE_SIGNUP;
            $message = 'Your signup is not complete yet. Check your email for the verification code to activate your account.';
        }

        try {
            $this->otpService->send($email, $purpose);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'success' => true,
                    'purpose' => $purpose,
                    'redirect' => route('otp.verify', ['email' => $email, 'purpose' => $purpose]),
                ]);
            }

            return redirect()->route('otp.verify', [
                'email' => $email,
                'purpose' => $purpose,
            ])->with('status', $message);
        } catch (\Throwable $exception) {
            return $this->sendFailureResponse($request, 'Failed to send OTP. Please try again.', 500);
        }
    }

    /**
     * Verify the OTP code
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'purpose' => 'required|string',
        ]);

        $email = $request->input('email');
        $otpCode = $request->input('otp');
        $purpose = $request->input('purpose');

        if (!$this->isSupportedPurpose($purpose)) {
            throw ValidationException::withMessages([
                'otp' => ['Unsupported OTP request.'],
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['We could not find an account with that email address.'],
            ]);
        }

        if ($purpose === Otp::PURPOSE_LOGIN && !$user->email_verified_at) {
            throw ValidationException::withMessages([
                'otp' => ['Please verify your email first.'],
            ]);
        }

        $otp = Otp::findValidOtp($email, $otpCode, $purpose);

        if (!$otp) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired OTP code.'],
            ]);
        }

        $otp->markAsUsed();

        if ($purpose === Otp::PURPOSE_SIGNUP && !$user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        auth()->login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => $purpose === Otp::PURPOSE_SIGNUP
                ? 'Email verified successfully! Welcome to Dukaniq.'
                : 'Login successful! Welcome back.',
            'success' => true,
            'redirect' => route('dashboard'),
        ]);
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'purpose' => 'nullable|string',
        ]);

        return $this->send($request);
    }

    private function isSupportedPurpose(string $purpose): bool
    {
        return in_array($purpose, Otp::supportedPurposes(), true);
    }

    private function sendFailureResponse(Request $request, string $message, int $status): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'success' => false,
            ], $status);
        }

        return back()->withErrors([
            'email' => $message,
        ])->withInput();
    }
}
