<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\InternActivationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InternActivationController extends Controller
{
    /**
     * Display the initial activation request page.
     */
    public function showRequestForm(): View
    {
        return view('auth.activate-request');
    }

    /**
     * Send the activation link to the pre-registered intern.
     */
    public function sendActivationLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:t_users,email'],
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if (!$user->isIntern()) {
            throw ValidationException::withMessages([
                'email' => 'Only pre-registered intern accounts can be activated through this portal.',
            ]);
        }

        if ($user->email_verified_at !== null) {
            throw ValidationException::withMessages([
                'email' => 'This account has already been activated. Please login or reset your password.',
            ]);
        }

        // Generate temporary signed URL (valid for 24 hours)
        $activationUrl = URL::temporarySignedRoute(
            'activate.reset',
            now()->addHours(24),
            ['email' => $user->email]
        );

        // Send notification email
        $user->notify(new InternActivationNotification($activationUrl));

        return redirect()->route('login')
            ->with('status', 'An activation link has been sent to your registered email address.');
    }

    /**
     * Show the password setup form (validates the signed link).
     */
    public function showPasswordForm(Request $request, string $email): View
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This activation link has expired or is invalid.');
        }

        $user = User::where('email', $email)->firstOrFail();

        if ($user->email_verified_at !== null) {
            return redirect()->route('login')->with('status', 'Account already activated.');
        }

        return view('auth.activate-set-password', compact('email'));
    }

    /**
     * Complete the activation by setting the password.
     */
    public function completeActivation(Request $request, string $email): RedirectResponse
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'This activation link has expired or is invalid.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->firstOrFail();

        // Update password and verify email
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->save();

        // Auto login the user
        Auth::login($user);

        return redirect()->route('intern.dashboard')
            ->with('status', 'Welcome! Your intern account has been activated successfully.');
    }
}
