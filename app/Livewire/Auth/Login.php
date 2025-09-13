<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    /**
     * Validation rules for the component.
     */
    protected function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login()
    {
        Log::debug("=== LOGIN ATTEMPT START ===");
        Log::debug("Attempting login with: {$this->email}");
        Log::debug("Password length: " . strlen($this->password));
        Log::debug("Remember: " . ($this->remember ? 'true' : 'false'));

        try {
            $this->validate();
            Log::debug("Validation passed");
        } catch (\Exception $e) {
            Log::debug("Validation failed: " . $e->getMessage());
            return;
        }

        // Check if user exists
        $user = \App\Models\User::where('email', $this->email)->first();
        if (!$user) {
            Log::debug("User not found: {$this->email}");
            $this->addError('email', 'Invalid credentials');
            return;
        }

        Log::debug("User found: {$user->name} (ID: {$user->id})");
        Log::debug("Stored password hash: {$user->password}");

        // Test password manually
        $passwordCheck = Hash::check($this->password, $user->password);
        Log::debug("Password check result: " . ($passwordCheck ? 'TRUE' : 'FALSE'));

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            Log::debug("=== LOGIN SUCCESS ===");
            Log::debug("Login SUCCESS for: {$this->email}");
            session()->regenerate();

            // // Add this temporarily for debugging
            // dd('Login success for ' . $this->email);

            // Redirect SPA users correctly and fall back to dashboard
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
            return null;
        }

        Log::debug("=== LOGIN FAILED ===");
        Log::debug("Login FAILED for: {$this->email}");
        Log::debug("Auth::attempt returned false");
        $this->addError('email', 'Invalid credentials');
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}
