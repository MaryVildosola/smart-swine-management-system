<x-guest-layout>
    <div class="auth-container">
        <div class="logo-card">
            <img src="{{ asset('assets/images/pig-logo.png') }}" alt="Logo">
        </div>

        <div class="form-card">
            <h2>Reset Password</h2>
            <p class="register-subtitle">Enter your new credentials</p>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="input-group">
                    <label class="field-label">Email Address</label>
                    <input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus placeholder="you@example.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="input-group">
                    <label class="field-label">New Password</label>
                    <input id="password" type="password" name="password" required placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="input-group">
                    <label class="field-label">Confirm New Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit" class="btn-login">
                    {{ __('Reset Password') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
