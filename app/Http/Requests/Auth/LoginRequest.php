<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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

        $currentCompanyId = config('app.current_company');
        $tenantType = config('app.tenant_type', 'company');

        // Build credentials with company context
        $credentials = $this->only('email', 'password');
        
        // For Super Admin on main domain, don't filter by company
        if ($tenantType === 'platform') {
            // Super Admin can login from main domain
            $user = \App\Models\User::where('email', $credentials['email'])
                ->whereHas('role', function($q) {
                    $q->where('slug', 'super_admin');
                })
                ->first();
        } else {
            // Company users must match the current company
            if (!$currentCompanyId) {
                RateLimiter::hit($this->throttleKey());
                throw ValidationException::withMessages([
                    'email' => 'Company not found. Please check your subdomain or contact support.',
                ]);
            }
            
            $user = \App\Models\User::where('email', $credentials['email'])
                ->where('company_id', $currentCompanyId)
                ->first();
            
            // If user not found, check if they exist but belong to different company
            if (!$user) {
                $userExists = \App\Models\User::where('email', $credentials['email'])->first();
                if ($userExists) {
                    RateLimiter::hit($this->throttleKey());
                    throw ValidationException::withMessages([
                        'email' => 'This account belongs to a different company. Please login from the correct subdomain.',
                    ]);
                }
            }
        }

        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Additional check: Super Admin should only login from main domain
        if ($user->role && $user->role->slug === 'super_admin' && $tenantType !== 'platform') {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Super Admin can only login from the main platform domain.',
            ]);
        }

        // Additional check: Company users should only login from their company subdomain
        if ($user->role && $user->role->slug !== 'super_admin' && $tenantType !== 'company') {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Please login from your company subdomain.',
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
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
