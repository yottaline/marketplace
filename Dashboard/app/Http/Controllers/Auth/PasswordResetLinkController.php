<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);


        $user = DB::table('users')->where('user_email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );


        try {
            Mail::send('mail.forgetPassword', ['token' => $token], function($message) use($request) {
                $message->to($request->email);
                $message->subject('Reset Password');
            });

            return back()->with('status', __('A password reset link has been sent to your email address.'));
        } catch (\Exception $e) {
            Log::error('Error sending password reset email: ' . $e->getMessage());

            return back()->withInput($request->only('email'))
                         ->withErrors(['email' => __('There was an error sending the password reset email. Please try again.')]);
        }
    }
}