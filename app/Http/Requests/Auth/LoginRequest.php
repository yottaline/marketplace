<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $user = User::where('user_email',request('user_email'))->first();
        if(!$user)
        {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'mobile' => 'You do not have an account']);
        }
        else{
            $password = Hash::check(request('password'), $user->user_password);

            if(!$password){
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'password' => 'The password is incorrect',]);
                    dd(11);
            }else{
                RateLimiter::clear($this->throttleKey());

                if ($user->hasRole('admin')) {

                    Auth::login($user);

                } elseif ($user->hasRole('retailer')) {

                   if($user->retailer_approved == 'null'){
                        RateLimiter::hit($this->throttleKey());
                        throw ValidationException::withMessages([
                        'mobile' => 'Your account not approved']);
                   }
                   else{
                    if($user->retailer_status == 0)   Auth::login($user);
                    else {
                        RateLimiter::hit($this->throttleKey());
                        throw ValidationException::withMessages([
                        'mobile' => 'Your account is backed']);
                    }
                   }

                }

            }
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}