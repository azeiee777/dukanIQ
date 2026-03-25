<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        try {
            $this->otpService->sendSignupOtp($user->email);

            return redirect()->route('otp.verify', [
                'email' => $user->email,
                'purpose' => Otp::PURPOSE_SIGNUP,
            ])->with('status', 'Account created successfully! Check your inbox for the verification code to continue.');
        } catch (\Throwable $exception) {
            return redirect()->route('otp.verify', [
                'email' => $user->email,
                'purpose' => Otp::PURPOSE_SIGNUP,
            ])->with('error', 'Account created, but we could not send the OTP right now. Please use resend to continue.');
        }
    }
}
