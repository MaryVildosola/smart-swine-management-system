<x-guest-layout>
    <div class="auth-container">
        <div class="logo-card">
            <img src="{{ asset('assets/images/pig-logo.png') }}" alt="Logo">
        </div>

        <div class="form-card">
            <h2>Confirm Password</h2>
            <p class="register-subtitle">Secure area - Please confirm access</p>

            <div class="mb-4 text-sm" style="color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="input-group">
                    <label class="field-label">Password</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <button type="submit" class="btn-login">
                    {{ __('Confirm') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
