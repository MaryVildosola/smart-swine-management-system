<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Farm Worker App</title>

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0b1120">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        app: {
                            green: '#22c55e',
                            dark: '#0b1120',
                            darker: '#060b16',
                            accent: '#ff5c5c', // for alerts
                        }
                    }
                }
            }
        }
    </script>

<script>
    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark');
    }
</script>

    <style>
        body {
            background-color: #060b16; /* Default Dark Base */
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
            margin: 0;
            padding: 0;
            color: #ffffff; /* Default White Text */
        }

        html,
        body {
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        /* Hide scrollbars for an app-like feel */
        ::-webkit-scrollbar {
            display: none;
        }

        .glass-panel {
    background: rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

body.light-theme .glass-panel {
    background: rgba(255,255,255,0.85);
    border-color: rgba(0,0,0,0.1);
}

        .glass-button {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .glass-button:active {
            transform: scale(0.97);
            background: rgba(255, 255, 255, 1);
        }

        /* --- GLOBAL LIGHT THEME OVERRIDES --- */
        body.light-theme,
        body.light-theme main,
        body.light-theme .worker-dash {
            background-color: #f8fafc !important;
            color: #0f172a !important;
        }
        
        /* Layout Backgrounds (Sidebar, Header, Floating Icons) */
        body.light-theme .bg-\[\#0b1120\],
        body.light-theme .bg-\[\#060b16\] { background-color: #ffffff !important; }
        body.light-theme .bg-\[\#141e36\] { background-color: #f1f5f9 !important; }

        .worker-dash {
    background: linear-gradient(
        135deg,
        #0a180e 0%,
        #0d2214 40%,
        #0a180e 100%
    );
}
        
        /* Text Colors - Relaxed to avoid breaking high-contrast banners like success messages */
        body.light-theme .text-white:not(.bg-green-600 *):not(.bg-red-600 *):not(.bg-amber-600 *) { 
            color: #0f172a !important; 
        }
        body.light-theme .text-white\/30,
        body.light-theme .text-white\/40,
        body.light-theme .text-white\/50,
        body.light-theme .text-white\/60,
        body.light-theme .text-white\/70,
        body.light-theme .text-white\/80 { color: #64748b !important; }
        
        /* High-contrast Input fix for worker portal */
        body.light-theme input:not([type="checkbox"]):not([type="radio"]), 
        body.light-theme select, 
        body.light-theme textarea {
            color: #0f172a !important;
            font-weight: 600 !important;
        }

        /* Borders */
        body.light-theme .border-white\/5,
        body.light-theme .border-white\/10,
        body.light-theme .border-white\/20 { border-color: #e2e8f0 !important; }
        
        /* Button and Hover Backgrounds */
        body.light-theme .bg-white\/5 { background-color: #f8fafc !important; }
        body.light-theme .bg-white\/10 { background-color: #f1f5f9 !important; }
        body.light-theme .hover\:bg-white\/10:hover,
        body.light-theme .hover\:bg-\[\#141e36\]:hover { background-color: #e2e8f0 !important; }
        /* Sidebar background */
        #workerSidebar {
            background-color: #05120a !important; /* Dark Greenish */
        }

        /* --- GLOBAL WORKER DASHBOARD STYLES (Glassmorphism & Theme) --- */
        
        /* Dark Mode is the DEFAULT */
        .worker-dash { 
            background: transparent !important; 
        }

        .dash-card, .glass-panel, .backdrop-blur-xl {
            background: rgba(255,255,255,0.08) !important;
            border-color: rgba(255,255,255,0.12) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important;
        }
        
        .dash-inner {
            background: rgba(0,0,0,0.25) !important;
            border-color: rgba(255,255,255,0.04) !important;
            box-shadow: inset 0 4px 15px rgba(0,0,0,0.2) !important;
        }

        .worker-dash .text-slate-900, .worker-dash .text-slate-800, .worker-dash .text-slate-700 { color: #ffffff !important; }
        .worker-dash .text-slate-500, .worker-dash .text-slate-400, .worker-dash .text-slate-300 { color: rgba(255,255,255,0.5) !important; }

        /* Ensure SweetAlert modals in Light Mode have dark text */
        body.light-theme .swal2-container, 
        body.light-theme .swal2-popup { color: #0f172a !important; }
        body.light-theme .swal2-container .text-slate-900, 
        body.light-theme .swal2-container .text-slate-800 { color: #0f172a !important; }
        body.light-theme .swal2-container .text-slate-500, 
        body.light-theme .swal2-container .text-slate-400 { color: #64748b !important; }

        /* Ensure SweetAlert is always on top of the record modal (z-1100) */
        .swal2-container { z-index: 2000 !important; }
        
        /* Light Mode Overrides */
        body.light-theme .worker-dash { background: #f1f5f9 !important; }
        body.light-theme .dash-card, body.light-theme .glass-panel, body.light-theme .backdrop-blur-xl {
            background: rgba(255,255,255,0.95) !important;
            border-color: rgba(0,0,0,0.08) !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.06) !important;
            backdrop-filter: none;
        }
        body.light-theme .text-slate-900, body.light-theme .text-slate-800 { color: #0f172a !important; }
        body.light-theme .text-slate-500, body.light-theme .text-slate-400 { color: #64748b !important; }
        body.light-theme .dash-inner {
            background: #f8fafc !important;
            border-color: rgba(0,0,0,0.05) !important;
        }

        /* Main Content Background (Theme Aware) */
        .main-content-bg {
            background: linear-gradient(to bottom right, #0a180e, #0d2214, #0a180e) !important;
            min-height: 100vh;
        }
        body.light-theme .main-content-bg {
            background: #f1f5f9 !important;
        }

        /* Sidebar Specific Contrast Fixes */
        #workerSidebar a, 
        #workerSidebar a *,
        #workerSidebar button#farmOpsToggle,
        #workerSidebar button#farmOpsToggle *,
        #workerSidebar h2 {
            color: #f1f5f9 !important; /* Force bright white-gray */
            fill: #f1f5f9 !important;
            opacity: 1 !important;
            transition: all 0.2s ease !important;
        }
        
        #workerSidebar a {
            font-weight: 600 !important;
            margin-bottom: 2px;
        }

        #workerSidebar a:hover, #workerSidebar button#farmOpsToggle:hover {
            background: #22c55e !important; /* Solid vibrant green */
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.25) !important;
        }
        
        #workerSidebar a:hover *, #workerSidebar button#farmOpsToggle:hover * {
            color: #ffffff !important;
            fill: #ffffff !important;
        }

        #workerSidebar a.active {
            background: #166534 !important; /* Deep forest green */
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }

        #workerSidebar a.active * {
            color: #ffffff !important;
            font-weight: 800 !important;
        }

        /* Medical Alerts HUD */
        .alerts-corner-hud {
            position: fixed;
            top: 85px;
            right: 20px;
            width: 380px;
            z-index: 90; /* Lower than modals (100+) */
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }

        .alert-card {
            background: rgba(255, 255, 255, 0.95);
            border-left: 5px solid #ef4444;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.2), 0 8px 10px -6px rgba(220, 38, 38, 0.1);
            backdrop-filter: blur(10px);
            display: flex;
            gap: 12px;
            animation: slide-in-right 0.5s ease-out;
            pointer-events: auto !important;
            border: 1px solid rgba(239, 68, 68, 0.1);
            border-left: 5px solid #ef4444;
            color: #0f172a; /* Ensure dark text in light background card */
        }

        .alert-card-icon {
            width: 40px;
            height: 40px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
            animation: pulse-red-icon 2s infinite;
        }

        @keyframes pulse-red-icon {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); background: #fecaca; }
            100% { transform: scale(1); }
        }

        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .alert-card button {
            background: #ef4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.65rem;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            transition: all 0.2s;
        }

        .alert-card button:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="text-white bg-[#060b16] antialiased min-h-screen selection:bg-green-200 overflow-x-hidden">
    <div class="flex h-screen w-screen overflow-hidden">

        <!-- Backdrop Overlay (Mobile only) -->
        <div id="sidebarBackdrop"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[90] hidden md:hidden transition-opacity duration-300 opacity-0">
        </div>

        <!-- Mobile Header (Green as requested) -->
        <div
            class="md:hidden fixed top-0 left-0 right-0 h-16 bg-[#0b1120] border-b border-white/5 flex items-center justify-between px-4 z-[80] shadow-md">
            <!-- Left: Logo -->
            <div class="flex items-center gap-2">
                <div
                    class="w-8 h-8 rounded-md bg-white/10 flex items-center justify-center shadow-lg border border-white/10">
                    <i class='bx bx-layer text-white text-sm'></i>
                </div>
                <h2 class="font-bold text-white text-sm tracking-tight hidden sm:block">Porcitrack</h2>
            </div>

            <!-- Right: Actions & Menu -->
            <div class="flex items-center gap-3">
                <button onclick="toggleWorkerTheme()" class="text-white/80 hover:text-white p-1">
                    <i class='bx bx-sun text-xl global-theme-icon'></i>
                </button>
                <div id="mobileSyncStatus"
                    class="flex items-center gap-1.5 bg-white/10 px-2.5 py-1.5 rounded-lg border border-white/10 cursor-pointer transition-all"
                    onclick="syncQueue()">
                    <div id="mobileSyncDot" class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                    <span id="mobileSyncLabel" class="text-white text-[9px] font-bold uppercase tracking-widest leading-none">Online</span>
                </div>
                <button
                    onclick="if(typeof openNotificationsPanel === 'function'){ openNotificationsPanel(); } else { window.location.href='/worker/dashboard'; }"
                    class="relative text-white/80 hover:text-white p-1">
                    <i class='bx bx-bell text-xl'></i>
                    <span id="mobileAlertBadge"
                        class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full border border-[rgba(0,0,0,0.5)] animate-pulse"></span>
                </button>
                <button onclick="if(typeof showSearch === 'function'){ showSearch(); }"
                    class="text-white/80 hover:text-white p-1">
                    <i class='bx bx-search text-xl'></i>
                </button>
                <button id="mobileMenuToggle" class="text-white text-2xl ml-1 active:scale-90 transition-transform">
                    <i class='bx bx-menu'></i>
                </button>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <aside id="workerSidebar"
            class="fixed inset-y-0 left-0 z-[100] w-72 backdrop-blur-2xl border-r border-white/5 flex flex-col shrink-0 transform -translate-x-full transition-all duration-300 ease-in-out md:relative md:translate-x-0 shadow-2xl" style="background-color: #05120a;">
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-lg border border-white/10 shrink-0">
                        @if(Auth::user()->photo)
                            <img src="{{ asset('storage/'. Auth::user()->photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=166534&color=fff&size=80&bold=true" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h2 class="font-extrabold text-white text-base tracking-tight leading-tight">{{ Auth::user()->name }}</h2>
                        <p class="text-[11px] text-white/50 truncate font-bold uppercase tracking-widest">Worker</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-8 space-y-1 overflow-y-auto custom-scrollbar">
                <a href="{{ route('worker.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('worker.dashboard') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-white hover:bg-white/10' }} font-bold transition">
                    <i class='bx bx-home text-lg text-green-400'></i>
                    <span>Dashboard</span>
                </a>

                <!-- Farm Operations Dropdown -->
                <div>
                    <button id="farmOpsToggle" onclick="toggleDropdown('farmOpsDropdown', 'farmOpsIcon')"
                        class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-white hover:bg-white/10 font-bold transition group">
                        <div class="flex items-center gap-3">
                            <i class='bx bx-landscape text-lg text-green-400'></i>
                            <span>Farm Operations</span>
                        </div>
                        <i id="farmOpsIcon" class='bx bx-chevron-down text-white/50 transition-transform duration-300'></i>
                    </button>

                    <div id="farmOpsDropdown"
                        class="hidden pl-4 mt-1 space-y-1 overflow-hidden transition-all duration-300">
                        <a href="{{ route('worker.tasks') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('worker.tasks') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-white/80 hover:bg-white/10' }} font-bold transition text-sm">
                            <i class='bx bx-task text-lg text-green-400/80'></i>
                            <span>Tasks</span>
                        </a>
                        <a href="{{ route('worker.reports') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('worker.reports') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-white/80 hover:bg-white/10' }} font-bold transition text-sm">
                            <i class='bx bx-book-content text-lg text-green-400/80'></i>
                            <span>Report</span>
                        </a>
                        <a href="{{ route('worker.swineDetails') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('worker.swineDetails') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-white/80 hover:bg-white/10' }} font-bold transition text-sm">
                            <i class='bx bx-detail text-lg text-green-400/80'></i>
                            <span>Swine Details</span>
                        </a>
                        <a href="{{ route('worker.feed-formulas') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('worker.feed-formulas') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-white/60 hover:bg-white/10 hover:text-white' }} font-medium transition text-sm">
                            <i class='bx bx-bowl-hot text-lg'></i>
                            <span>Feed Formulas</span>
                        </a>
                    </div>
                    <script>
function toggleDropdown(dropdownId, iconId) {
    const dropdown = document.getElementById(dropdownId);
    const icon = document.getElementById(iconId);

    // Toggle the 'hidden' class
    if (dropdown.classList.contains('hidden')) {
        dropdown.classList.remove('hidden');
        // Add a slight delay for the transition effect if desired
        icon.style.transform = 'rotate(180deg)';
    } else {
        dropdown.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
                </div>

                <a href="{{ route('worker.settings') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('worker.settings') ? 'bg-green-500/20 text-green-300 border border-green-500/30' : 'text-white hover:bg-white/10' }} font-bold transition">
                    <i class='bx bx-cog text-lg text-green-400'></i>
                    <span>Settings</span>
                </a>

            </nav>

            <div class="p-4 border-t border-white/10 space-y-3">
                <form method="POST" action="{{ route('logout') }}" id="workerLogoutForm">
                    @csrf
                    <button type="button" onclick="confirmWorkerLogout()"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 hover:text-red-300 transition-all duration-200 font-black text-xs uppercase tracking-widest">
                        <i class='bx bx-log-out text-lg'></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>

            <!-- Mobile Close Button -->
            <button id="mobileMenuClose"
                class="md:hidden absolute top-4 right-4 text-white/60 hover:text-white text-2xl p-2 transition">
                <i class='bx bx-x'></i>
            </button>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto overflow-x-hidden pt-16 md:pt-0 relative main-content-bg">

            <!-- Global Floating Icons (Right Side - Green as requested) -->
            <div
                class="hidden md:flex absolute top-4 right-4 md:top-12 md:right-8 z-50 items-center gap-2 md:gap-3 pointer-events-auto">
                <!-- Sync Status -->
                <div id="globalSyncStatus"
                    class="flex items-center gap-2 md:gap-3 bg-[#0b1120] px-3 py-1.5 md:px-4 md:py-2 rounded-2xl border border-white/10 cursor-pointer hover:bg-[#141e36] transition shadow-lg"
                    onclick="syncQueue()">
                    <div id="globalSyncDot"
                        class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-green-400 shadow-[0_0_10px_rgba(34,197,94,0.6)] animate-pulse">
                    </div>
                    <span id="globalSyncLabel"
                        class="text-white text-[9px] md:text-[10px] font-bold uppercase tracking-widest leading-none">Online</span>
                </div>

                <!-- Notifications Bell -->
                <button
                    onclick="if(typeof openNotificationsPanel === 'function'){ openNotificationsPanel(); } else { window.location.href='/worker/dashboard'; }"
                    class="relative w-10 h-10 md:w-12 md:h-12 rounded-2xl flex items-center justify-center bg-[#0b1120] border border-white/10 hover:bg-[#141e36] transition shadow-lg text-white">
                    <i class='bx bx-bell text-lg md:text-xl'></i>
                    <span id="globalAlertBadge"
                        class="absolute top-2 right-2 md:top-2.5 md:right-2.5 w-2.5 h-2.5 md:w-3 md:h-3 bg-red-500 rounded-full border border-[rgba(0,0,0,0.5)] animate-pulse"></span>
                </button>

                <!-- Search -->
                <button onclick="if(typeof showSearch === 'function'){ showSearch(); }"
                    class="w-10 h-10 md:w-12 md:h-12 rounded-2xl flex items-center justify-center bg-[#0b1120] border border-white/10 hover:bg-[#141e36] transition shadow-lg text-white">
                    <i class='bx bx-search text-lg md:text-xl'></i>
                </button>
            </div>

            @yield('content')
        </main>
    </div>

    <!-- Notifications / Alerts Slide Panel (Global) -->
    <div id="notificationsBackdrop" class="fixed inset-0 z-[190] hidden bg-black/50 backdrop-blur-sm"
        onclick="closeNotificationsPanel()"></div>
    <div id="notificationsPanel"
        class="fixed top-0 right-0 bottom-0 z-[200] w-full max-w-sm bg-white border-l border-slate-200 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center shrink-0">
            <div>
                <h2 class="text-2xl font-black text-slate-900">Alerts</h2>
                <p class="text-slate-400 text-xs font-semibold mt-0.5">All farm notifications</p>
            </div>
            <button onclick="closeNotificationsPanel()"
                class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-2 px-6 py-4 border-b border-slate-100 shrink-0">
            <button onclick="filterPanel('all', this)"
                class="panel-filter-btn flex-1 py-2 rounded-xl bg-green-100 text-green-700 border border-green-200 text-xs font-black uppercase">All</button>
            <button onclick="filterPanel('critical', this)"
                class="panel-filter-btn flex-1 py-2 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-xs font-black uppercase">Critical</button>
            <button onclick="filterPanel('health', this)"
                class="panel-filter-btn flex-1 py-2 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-xs font-black uppercase">Health</button>
            <button onclick="filterPanel('general', this)"
                class="panel-filter-btn flex-1 py-2 rounded-xl bg-slate-100 text-slate-500 border border-slate-200 text-xs font-black uppercase">General</button>
        </div>

        <!-- Alert Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3" id="alertPanelList">
            <!-- Critical -->
            <div class="alert-item" data-type="critical">
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200">
                    <div class="flex gap-3 items-start">
                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                            <i class='bx bx-heart-broken text-red-600 text-lg'></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-red-700 font-black text-sm">Pig #42 &mdash; Health Crisis</p>
                                <span class="text-slate-400 text-[10px]">2 min ago</span>
                            </div>
                            <p class="text-slate-600 text-xs mt-1 leading-snug">Rapid breathing and lethargy in Pen 3. Vet required.</p>
                            <div class="flex gap-2 mt-3">
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-md text-[9px] font-black border border-red-200 uppercase">Critical</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Health -->
            <div class="alert-item" data-type="health">
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200">
                    <div class="flex gap-3 items-start">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                            <i class='bx bx-error-alt text-amber-600 text-lg'></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <p class="text-amber-700 font-black text-sm">Pig #17 &mdash; Check Needed</p>
                                <span class="text-slate-400 text-[10px]">1 hr ago</span>
                            </div>
                            <p class="text-slate-600 text-xs mt-1 leading-snug">Due for routine health monitoring. Last check was 4 days ago.</p>
                            <div class="flex gap-2 mt-3">
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded-md text-[9px] font-black border border-amber-200 uppercase">Health</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Responsive Menu Toggle Logic
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuClose = document.getElementById('mobileMenuClose');
        const workerSidebar = document.getElementById('workerSidebar');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            workerSidebar.classList.remove('-translate-x-full');
            sidebarBackdrop.classList.remove('hidden');
            setTimeout(() => sidebarBackdrop.classList.add('opacity-100'), 10);
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            workerSidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.remove('opacity-100');
            setTimeout(() => sidebarBackdrop.classList.add('hidden'), 300);
            document.body.style.overflow = '';
        }

        if (mobileMenuToggle) mobileMenuToggle.addEventListener('click', openSidebar);
        if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeSidebar);
        if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

        function triggerManualEntry(e) {
            if (e) e.preventDefault();
            closeSidebar();

            const isLight = document.body.classList.contains('light-theme');

            Swal.fire({
                title: 'Manual ID Entry',
                html: `
                    <p class="text-xs text-slate-500 mb-5 px-2 leading-relaxed">Type the <strong>Pig Ear Tag</strong> (e.g. <code>A2-001</code>) or <strong>Pen Number</strong> (e.g. <code>1</code>) to load the full assessment form.</p>
                    <input id="swal-manual-id-input" type="text"
                        class="swal2-input uppercase tracking-widest font-bold text-center text-lg"
                        placeholder="e.g. A2-001 or 1"
                        autocomplete="off"
                        style="width:100%;border-radius:12px;font-size:1.1rem;">
                `,
                background: isLight ? '#ffffff' : '#0b1120',
                color: isLight ? '#1e293b' : '#ffffff',
                confirmButtonText: 'Open Form',
                confirmButtonColor: '#22c55e',
                showCancelButton: true,
                cancelButtonColor: '#94a3b8',
                focusConfirm: false,
                didOpen: () => {
                    // Focus the input and allow Enter to confirm
                    const inp = document.getElementById('swal-manual-id-input');
                    if (inp) {
                        inp.focus();
                        inp.addEventListener('keydown', (ev) => {
                            if (ev.key === 'Enter') { ev.preventDefault(); Swal.clickConfirm(); }
                        });
                    }
                },
                preConfirm: () => {
                    const val = document.getElementById('swal-manual-id-input')?.value?.trim();
                    if (!val) {
                        Swal.showValidationMessage('Please enter an ID first.');
                        return false;
                    }
                    return val.toUpperCase();
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const tagId = result.value;

                    // If on Dashboard — open form inline
                    if (typeof onScanSuccess === 'function') {
                        onScanSuccess(tagId);
                    } else {
                        // On another page — redirect to dashboard and auto-open there
                        window.location.href = "{{ route('worker.dashboard') }}?manual_scan=" + encodeURIComponent(tagId);
                    }
                }
            });
        }


        // Auto-open dropdown if child is active
        document.addEventListener('DOMContentLoaded', () => {
            const farmOpsDropdown = document.getElementById('farmOpsDropdown');
            const farmOpsIcon = document.getElementById('farmOpsIcon');
            if (farmOpsDropdown) {
                const activeChild = farmOpsDropdown.querySelector('[class*="bg-white/15"]');
                if (activeChild) {
                    farmOpsDropdown.classList.remove('hidden');
                    if (farmOpsIcon) farmOpsIcon.classList.add('rotate-180');
                }
            }
        });

        // Global Notifications Panel Logic
        function openNotificationsPanel() {
            document.getElementById('notificationsPanel').classList.remove('translate-x-full');
            document.getElementById('notificationsBackdrop').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (window.innerWidth < 768) {
                closeSidebar(); // close mobile sidebar if open
            }
        }

        function closeNotificationsPanel() {
            document.getElementById('notificationsPanel').classList.add('translate-x-full');
            document.getElementById('notificationsBackdrop').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function filterPanel(type, btn) {
            document.querySelectorAll('.panel-filter-btn').forEach(b => {
                b.classList.remove('bg-green-500/20', 'text-green-300', 'border-green-500/30');
                b.classList.add('bg-white/5', 'text-white/50', 'border-white/10');
            });
            btn.classList.add('bg-green-500/20', 'text-green-300', 'border-green-500/30');
            btn.classList.remove('bg-white/5', 'text-white/50', 'border-white/10');

            document.querySelectorAll('.alert-item').forEach(item => {
                if (type === 'all' || item.dataset.type === type) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        // --- Global Theme Engine ---
        function toggleWorkerTheme() {
            const currentTheme = localStorage.getItem('porcitrack-worker-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            console.log("Switching theme to:", newTheme);
            localStorage.setItem('porcitrack-worker-theme', newTheme);
            applyPageTheme(newTheme);
        }
        
        function applyPageTheme(theme) {
            // 1. Update Body Class
            if (theme === 'light') {
                document.body.classList.add('light-theme');
            } else {
                document.body.classList.remove('light-theme');
            }

            // 2. Update Icons (Sun for Light, Moon for Dark)
            document.querySelectorAll('.global-theme-icon').forEach(icon => {
                icon.className = theme === 'dark' ? 'bx bx-moon text-xl md:text-xl global-theme-icon' : 'bx bx-sun text-xl md:text-xl global-theme-icon';
            });

            // 3. Sync Settings Toggle Switch (if on settings page)
            const themeToggle = document.getElementById('themeToggleSwitch');
            if(themeToggle) {
                themeToggle.checked = (theme === 'dark');
            }

            // 4. Sync Hidden Input for Form Submission (if on settings page)
            const themeInput = document.getElementById('themeInput');
            if (themeInput) {
                themeInput.value = theme;
            }
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            const current = localStorage.getItem('porcitrack-worker-theme') || 'dark';
            applyPageTheme(current);
        });

        // --- FIXED BROKEN BUTTONS (Search & Sync) ---
        window.showSearch = function() {
            Swal.fire({
                title: 'Search',
                input: 'text',
                inputPlaceholder: 'Search pigs, pens, or tasks...',
                background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                color: document.body.classList.contains('light-theme') ? '#000000' : '#ffffff',
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'Search'
            });
        }

        // ═══════════════════════════════════════════════
        // GLOBAL OFFLINE QUEUE SYSTEM
        // ═══════════════════════════════════════════════
        const QUEUE_KEY = 'porcitrack_offline_queue';
        function getQueue() { try { return JSON.parse(localStorage.getItem(QUEUE_KEY) || '[]'); } catch { return []; } }
        function saveQueue(q) { localStorage.setItem(QUEUE_KEY, JSON.stringify(q)); updateGlobalSyncUI(); }
        function enqueue(url, payload, label) {
            const q = getQueue();
            q.push({ url, payload, label, savedAt: new Date().toISOString(), id: Date.now() });
            saveQueue(q);
        }

        function updateGlobalSyncUI() {
            const q = getQueue();
            const hasPending = q.length > 0;
            const online = navigator.onLine;

            const updateBadge = (dotId, labelId, statusId) => {
                const dot = document.getElementById(dotId);
                const label = document.getElementById(labelId);
                const status = document.getElementById(statusId);
                if(!dot || !label) return;

                if (!online) {
                    dot.className = "w-1.5 h-1.5 rounded-full bg-slate-500";
                    label.textContent = "Offline";
                } else if (hasPending) {
                    dot.className = "w-1.5 h-1.5 rounded-full bg-amber-500 animate-bounce";
                    label.textContent = `Pending (${q.length})`;
                } else {
                    dot.className = "w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse";
                    label.textContent = "Online";
                }
            };

            updateBadge('mobileSyncDot', 'mobileSyncLabel', 'mobileSyncStatus');
            updateBadge('globalSyncDot', 'globalSyncLabel', 'globalSyncStatus');
            
            // If the specific page has its own UI (like the banner in swineDetails), call it
            if (typeof updateOfflineUI === 'function') updateOfflineUI();
        }

        async function syncQueue() {
            if (!navigator.onLine) {
                Swal.fire({ icon: 'warning', title: 'Offline', text: 'You are currently offline. Connect to the internet to sync your data.', background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120', color: document.body.classList.contains('light-theme') ? '#000000' : '#ffffff' });
                return;
            }
            
            const q = getQueue();
            if (q.length === 0) {
                Swal.fire({ icon: 'success', title: 'Up to Date', text: 'No pending data to sync.', timer: 1500, showConfirmButton: false, background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120', color: document.body.classList.contains('light-theme') ? '#000000' : '#ffffff' });
                return;
            }

            Swal.fire({ title: 'Syncing...', text: `Uploading ${q.length} item(s)...`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            const remaining = [];
            let count = 0;
            for (const item of q) {
                try {
                    const res = await fetch(item.url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify(item.payload)
                    });
                    if (!res.ok) throw new Error('Server error');
                    count++;
                } catch {
                    remaining.push(item);
                }
            }
            
            saveQueue(remaining);
            updateGlobalSyncUI();
            
            if (remaining.length === 0) {
                Swal.fire({ icon: 'success', title: 'Synced!', text: `Successfully uploaded ${count} item(s) to the admin server.`, background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120', color: document.body.classList.contains('light-theme') ? '#000000' : '#ffffff' });
            } else {
                Swal.fire({ icon: 'warning', title: 'Partial Sync', text: `Uploaded ${count} item(s). ${remaining.length} items failed and will be retried later.`, background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120', color: document.body.classList.contains('light-theme') ? '#000000' : '#ffffff' });
            }
        }

        async function offlineSafeFetch(url, payload, label) {
            console.log(`[Fetch] Sending to ${url}:`, payload);
            if (!navigator.onLine) {
                console.warn("[Fetch] Offline mode: Queueing request.");
                enqueue(url, payload, label);
                return { success: true, offline: true };
            }
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify(payload)
                });
                
                let data;
                const contentType = res.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    data = await res.json();
                } else {
                    const text = await res.text();
                    console.error("[Fetch] Non-JSON response:", text);
                    throw new Error("Server returned an invalid response. Please check your connection.");
                }

                if (!res.ok) {
                    console.error("[Fetch] Error response:", data);
                    throw new Error(data.message || data.error || `Server Error (${res.status})`);
                }
                
                console.log("[Fetch] Success:", data);
                return data;
            } catch (err) {
                console.error("[Fetch] Exception:", err);
                // If it's a network error (failed to fetch), we queue it
                if (err.message.includes('Failed to fetch') || err.message.includes('NetworkError')) {
                    enqueue(url, payload, label);
                    return { success: true, offline: true };
                }
                throw err; // Re-throw validation or server errors
            }
        }

        window.addEventListener('online', updateGlobalSyncUI);
        window.addEventListener('offline', updateGlobalSyncUI);
        document.addEventListener('DOMContentLoaded', updateGlobalSyncUI);
        setInterval(updateGlobalSyncUI, 30000); // Check UI every 30s 
        setInterval(() => { if(navigator.onLine && getQueue().length > 0) syncQueue(); }, 60000); // Auto-sync every 60s 


        function confirmWorkerLogout() {
            const isLight = document.body.classList.contains('light-theme');
            Swal.fire({
                title: 'Log Out?',
                text: 'Are you sure you want to log out of PorciTrack?',
                icon: 'question',
                background: isLight ? '#ffffff' : '#0b1120',
                color: isLight ? '#1e293b' : '#ffffff',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Stay',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-xl font-bold text-sm',
                    cancelButton: 'rounded-xl font-bold text-sm',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('workerLogoutForm').submit();
                }
            });
        }
        function handleNotifClick(pigId, penId, penName = '', event = null, activityId = null) {
            console.log("Worker Alert clicked for Pig ID:", pigId, "Activity ID:", activityId);

            // Remove the card immediately if it exists (for floating toast alerts)
            const target = event?.target || window.event?.target;
            if (target) {
                const card = target.closest('.alert-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(50px)';
                    setTimeout(() => card.remove(), 400);
                }
            }

            if (window.location.pathname.includes('/worker/swine-details')) {
                if (typeof window.gotoPig === 'function') {
                    window.gotoPig(pigId, activityId);
                } else if (typeof showFloatingCard === 'function') {
                    showFloatingCard(pigId, penName, activityId);
                }
            } else {
                sessionStorage.setItem('pending_pig_modal', pigId);
                sessionStorage.setItem('pending_pen_name', penName);
                if (activityId) sessionStorage.setItem('pending_activity_id', activityId);
                window.location.href = "{{ route('worker.swineDetails') }}";
            }
        }
    </script>

    <!-- PWA Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.log('Service Worker registration failed', err));
            });
        }
    </script>

    <script>
function toggleDarkMode() {
    const html = document.documentElement;

    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
}
</script>

    <!-- GLOBAL PIG RECORD MODAL -->
    <div id="pigModalOverlay" class="fixed inset-0 z-[1100] hidden items-start justify-center p-4 md:p-8 overflow-y-auto">
        <div onclick="hidePigModal()" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md"></div>

        <div id="modalLoader" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-[110] hidden flex flex-col items-center justify-center gap-6 animate-fade-in pointer-events-none">
            <div class="w-16 h-16 border-4 border-green-500 border-t-transparent rounded-full animate-spin shadow-[0_0_50px_rgba(34,197,94,0.3)]"></div>
            <p class="text-[10px] font-black text-white uppercase tracking-[0.5em] drop-shadow-[0_2px_4px_rgba(0,0,0,0.5)]">Loading...</p>
        </div>

        <div id="pigModalContent" class="relative z-10 w-full max-w-5xl my-8 animate-fade-in shadow-[0_50px_100px_rgba(0,0,0,0.5)] flex flex-col overflow-hidden">
            {{-- Injected via JS --}}
        </div>
    </div>

    <script>
        function hidePigModal() {
            document.getElementById('pigModalOverlay').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
</body>

</html>
