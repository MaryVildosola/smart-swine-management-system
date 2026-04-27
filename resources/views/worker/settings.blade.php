@extends('layouts.worker')

@section('content')
<style>
    body.light-theme .worker-dash          { background-color: #f8fafc !important; }
    body.light-theme .glass-panel {
        background: rgba(255,255,255,0.95) !important;
        border-color: rgba(0,0,0,0.08) !important;
    }
    body.light-theme .text-white           { color: #1e293b !important; }
    body.light-theme .text-white\/30,
    body.light-theme .text-white\/40,
    body.light-theme .text-white\/50,
    body.light-theme .text-white\/60,
    body.light-theme .text-white\/70       { color: #64748b !important; }
    body.light-theme .bg-white\/5,
    body.light-theme .bg-white\/10         { background-color: rgba(0,0,0,0.04) !important; }
    body.light-theme .border-white\/10,
    body.light-theme .border-white\/5      { border-color: rgba(0,0,0,0.08) !important; }
    body.light-theme input,
    body.light-theme select,
    body.light-theme textarea {
        background-color: rgba(0,0,0,0.03) !important;
        color: #0f172a !important;
        border-color: rgba(0,0,0,0.1) !important;
    }
    body.light-theme input::placeholder,
    body.light-theme textarea::placeholder { color: #94a3b8 !important; }
</style>

<div class="worker-dash min-h-screen">
    <div class="p-6 md:p-12 max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Profile Settings</h1>
            <p class="text-white/60 mt-2">Manage your personal information and account security</p>
        </div>

        <form action="{{ route('worker.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Success/Alert Messages --}}
            @if(session('success'))
    <div id="successAlert"
         class="mb-8 p-4 bg-green-500/20 border border-green-500/30 rounded-2xl text-green-300 text-sm flex items-center gap-3">
        <i class='bx bx-check-circle text-xl'></i>
        {{ session('success') }}
    </div>
@endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Profile Photo -->
                <div class="lg:col-span-1 text-center">
                    <div class="glass-panel p-8 rounded-3xl shadow-xl flex flex-col items-center">
                        <div class="relative group cursor-pointer">
                            <div class="w-40 h-40 md:w-48 md:h-48 rounded-full overflow-hidden border-4 border-white/20 shadow-2xl relative bg-white/5">
                                <img id="profilePreview"
                                     src="{{ auth()->user()->photo ?asset('storage/' . auth()->user()->photo) . '?v=' . time() : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=65a767&color=fff&size=200' }}"
                                     alt="Profile Photo"
                                     class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                                    <i class='bx bx-camera text-4xl text-white'></i>
                                </div>
                            </div>
                            <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*">
                            <button type="button" onclick="document.getElementById('photoInput').click()"
                                    class="absolute bottom-2 right-2 w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-green-600 transition active:scale-90 border-4 border-[#0f2818]">
                                <i class='bx bx-edit-alt text-xl'></i>
                            </button>
                        </div>
                        <h3 class="mt-6 text-xl font-bold text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-white/40 text-xs uppercase tracking-widest mt-1">Farm Worker</p>

                        <div class="mt-8 w-full pt-8 border-t border-white/10 text-left space-y-4">
                            <div class="flex items-center gap-3 text-white/60">
                                <i class='bx bx-calendar text-lg'></i>
                                <span class="text-sm">Joined {{ auth()->user()->created_at->format('M Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-white/60">
                                <i class='bx bx-shield-check text-lg'></i>
                                <span class="text-sm text-green-400">Account Verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Form Details -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Info -->
                    <div class="glass-panel p-6 md:p-8 rounded-3xl shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                            <i class='bx bx-user text-green-400'></i> Personal Details
                        </h2>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-green-500/50 transition">
                                @error('name') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-2">Email Address</label>
                                <div class="flex gap-2">
                                    <input type="email" value="{{ auth()->user()->email }}" readonly
                                           class="flex-1 bg-white/5 border border-white/5 rounded-xl px-4 py-3 text-white/40 cursor-not-allowed italic">
                                    <button type="button" onclick="requestAdmin('email')"
                                            class="px-4 bg-white/10 text-white/60 border border-white/10 rounded-xl hover:bg-white/20 transition text-xs font-medium">
                                        Request Change
                                    </button>
                                </div>
                                <p class="text-[10px] text-white/30 mt-1 italic">* Requires administrator approval</p>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-2">Phone Number</label>
                                <div class="flex gap-2">
                                    <input type="text" value="{{ auth()->user()->phone ?? 'Not set' }}" readonly
                                           class="flex-1 bg-white/5 border border-white/5 rounded-xl px-4 py-3 text-white/40 cursor-not-allowed italic">
                                    <button type="button" onclick="requestAdmin('phone')"
                                            class="px-4 bg-white/10 text-white/60 border border-white/10 rounded-xl hover:bg-white/20 transition text-xs font-medium">
                                        Request Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="glass-panel p-6 md:p-8 rounded-3xl shadow-xl border-l-4 border-l-amber-500/30">
                        <h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                            <i class='bx bx-lock-alt text-amber-400'></i> Update Password
                        </h2>
                        <p class="text-white/40 text-xs mb-6">Leave blank to keep your current password</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-2">New Password</label>
                                <input type="password" name="password" placeholder="••••••••"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-500/50 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-amber-500/50 transition">
                            </div>
                        </div>
                        @error('password') <p class="text-red-400 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Theme Toggle -->
                    <div class="glass-panel p-6 md:p-8 rounded-3xl shadow-xl border-l-4 border-l-blue-500/30">
                        <input type="hidden" name="theme" id="themeInput" value="{{ auth()->user()->theme ?? 'dark' }}">
                        <h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                            <i class='bx bx-slider-alt text-blue-400'></i> General Settings
                        </h2>
                        <p class="text-white/40 text-xs mb-6">Customize your app experience</p>

                        <div class="flex items-center justify-between bg-white/5 border border-white/10 p-4 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <i class='bx bx-moon text-blue-400 text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold text-sm">Dark Mode</h3>
                                    <p class="text-white/40 text-xs">Switch between light and dark themes</p>
                                </div>
                            </div>
                            <!-- toggleWorkerTheme() is defined in worker.blade.php -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="themeToggleSwitch" class="sr-only peer" onchange="toggleWorkerTheme()">
                                <div class="w-11 h-6 bg-gray-600 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('worker.dashboard') }}" class="text-white/60 hover:text-white transition font-medium text-sm">Cancel</a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-bold hover:shadow-[0_0_20px_rgba(34,197,94,0.4)] transition active:scale-95">
                            Save Changes
                        </button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Sync the toggle switch to match current theme on page load
document.addEventListener('DOMContentLoaded', () => {
    const theme = localStorage.getItem('porcitrack-worker-theme') || 'dark';
    const toggle = document.getElementById('themeToggleSwitch');

    if (toggle) toggle.checked = (theme === 'dark');

    // ✅ sync hidden input
    const input = document.getElementById('themeInput');
    if (input) input.value = theme;
});

document.getElementById('photoInput').addEventListener('change', function (event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('profilePreview').src = e.target.result;
        };

        reader.readAsDataURL(file);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const alert = document.getElementById('successAlert');

    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';

            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 2500); // success alert disappears after 2.5s
    }
});


    function requestAdmin(field) {
        Swal.fire({
            title: 'Request ' + field.charAt(0).toUpperCase() + field.slice(1) + ' Change',
            text: 'Please enter the new ' + field + ' you would like to use.',
            input: 'text',
            inputPlaceholder: 'Enter new ' + field,
            showCancelButton: true,
            confirmButtonText: 'Submit Request',
            confirmButtonColor: '#22c55e',
            background: '#1a3a1a',
            color: '#ffffff',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                Swal.fire({
                    title: 'Request Sent!',
                    text: 'Your request to change ' + field + ' has been submitted.',
                    icon: 'success',
                    background: '#1a3a1a',
                    color: '#ffffff',
                    confirmButtonColor: '#22c55e'
                });
            }
        });
    }

    function toggleWorkerTheme() {
    const toggle = document.getElementById('themeToggleSwitch');
    const theme = toggle.checked ? 'dark' : 'light';

    // Save locally (UI)
    localStorage.setItem('porcitrack-worker-theme', theme);

    // ✅ SEND TO BACKEND
    document.getElementById('themeInput').value = theme;

    // Apply instantly
    document.body.classList.toggle('light-theme', theme === 'light');
}
</script>

<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
@endsection