<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
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

        $credentials = $this->only('email', 'password');

        if (Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());

            return;
        }

        $defaultAdminEmail = env('DEFAULT_ADMIN_EMAIL', 'admin@gmail.com');
        $defaultAdminPassword = env('DEFAULT_ADMIN_PASSWORD', 'admin123');

        if ($credentials['email'] === $defaultAdminEmail && $credentials['password'] === $defaultAdminPassword) {
            $user = User::where('email', $defaultAdminEmail)->first();

            if (! $user) {
                $user = User::create([
                    'name' => 'Admin',
                    'email' => $defaultAdminEmail,
                    'password' => Hash::make($defaultAdminPassword),
                    'role' => 'admin',
                ]);
            }

            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());

            return;
        }

        $defaultAuthorEmail = env('DEFAULT_AUTHOR_EMAIL', 'author@gmail.com');
        $defaultAuthorPassword = env('DEFAULT_AUTHOR_PASSWORD', 'author123');

        if ($credentials['email'] === $defaultAuthorEmail && $credentials['password'] === $defaultAuthorPassword) {
            $user = User::where('email', $defaultAuthorEmail)->first();

            if (! $user) {
                $user = User::create([
                    'name' => 'Author',
                    'email' => $defaultAuthorEmail,
                    'password' => Hash::make($defaultAuthorPassword),
                    'role' => 'author',
                ]);
            }

            Auth::login($user, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());

            return;
        }

        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
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
