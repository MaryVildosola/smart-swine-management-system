<x-guest-layout>
    <div class="auth-container">
        <div class="logo-card">
            <img src="{{ asset('assets/images/pig-logo.png') }}" alt="Logo">
        </div>

        <div class="form-card">
            <h2>Verify Email</h2>
            <p class="register-subtitle">One last step to get started</p>

            <div class="mb-4 text-sm" style="color: var(--text-muted); line-height: 1.6; margin-bottom: 24px;">
                {{ __('Thanks for signing up! Please verify your email address by clicking on the link we just emailed to you.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600" style="margin-bottom: 24px;">
                    {{ __('A new verification link has been sent to your email address.') }}
                </div>
            @endif

            <div class="mt-4 flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-login">
                        {{ __('Resend Email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
                    @csrf
                    <button type="submit" class="signin-link" style="background: none; border: none; cursor: pointer; font-family: inherit;">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
