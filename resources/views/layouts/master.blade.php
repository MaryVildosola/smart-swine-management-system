<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light"
    data-menu-styles="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> PorciTrack - Farm Admin </title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/pig-logo.png') }}" type="image/png">

    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2e7d32">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/simplebar/simplebar.min.css') }}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: #f8fafc !important;
            font-family: 'Inter', sans-serif;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 2. Fix Sidebar position and background gap */
        .app-sidebar {
            top: 0 !important;
            position: fixed !important;
            height: 100vh !important;
            background-color: #0b1120 !important;
            border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
            z-index: 999;
        }

        .main-sidebar,
        #sidebar-scroll {
            padding-top: 0 !important;
            background-color: #0b1120 !important;
        }

        /* 3. ULTIMATE FIX: Force Sidebar Readability */
        .app-sidebar {
            background-color: #0b1120 !important;
        }

        /* Target EVERYTHING inside the menu items to be white/light */
        .side-menu__item,
        .side-menu__item span,
        .side-menu__item i,
        .side-menu__item svg,
        .side-menu__item div {
            color: #f1f5f9 !important; /* Solid bright white-gray */
            fill: #f1f5f9 !important;
            opacity: 1 !important;
        }

        .side-menu__item {
            margin: 4px 16px;
            border-radius: 12px;
            transition: all 0.2s ease;
            font-weight: 600 !important;
        }

        /* Hover & Active States: Pure White on Vibrant Green */
        .side-menu__item:hover,
        .side-menu__item.active {
            background: #22c55e !important;
            box-shadow: 0 4px 15px rgba(34, 197, 94, 0.4) !important;
        }

        .side-menu__item:hover *,
        .side-menu__item.active * {
            color: #ffffff !important;
            fill: #ffffff !important;
            font-weight: 800 !important;
        }

        .side-menu__angle {
            color: #f1f5f9 !important;
        }

        /* 4. Global Input Fields High-Contrast Fix */
        input:not([type="checkbox"]):not([type="radio"]), 
        select, 
        textarea, 
        .form-input,
        .form-control {
            color: #0f172a !important; /* Dark Navy/Black for maximum visibility */
            font-weight: 500 !important;
            font-family: 'Inter', sans-serif !important;
        }
        
        input::placeholder, 
        textarea::placeholder {
            color: #94a3b8 !important; /* Readable but distinct from actual values */
            font-weight: 400 !important;
        }

        /* Ensure values inside dropdowns are also solid dark */
        select option {
            color: #0f172a !important;
        }
        /* TOP HEADER STYLES */
        .app-header {
            height: 70px;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 240px;
            right: 0;
            z-index: 900; /* Below modals (1000) but above sidebar (999) */
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 40px;
            transition: all 0.3s ease;
            pointer-events: none; /* Allow clicks to pass through to content below */
        }

        .notif-wrapper {
            position: relative;
            pointer-events: auto; /* Re-enable clicks for the notification bell only */
        }

        .notif-bell {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            z-index: 1060;
        }

        .notif-bell i {
            pointer-events: none; /* Ensure the div gets the click */
        }

        .notif-bell:hover {
            border-color: #22c55e;
            color: #22c55e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 10px;
            border: 3px solid #fff;
            animation: pulse-red 2s infinite;
        }

        .notif-dropdown {
            position: absolute;
            top: 60px;
            right: 0;
            width: 350px;
            background: white;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: none;
            overflow: hidden;
            z-index: 1000;
        }

        .notif-dropdown.show {
            display: block;
            animation: slide-up 0.3s ease-out;
        }

        .notif-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            background: #f8fafc;
        }

        .notif-item {
            padding: 16px 20px;
            display: flex;
            gap: 12px;
            border-bottom: 1px solid #f8fafc;
            cursor: pointer;
            transition: background 0.2s;
        }

        .notif-item:hover {
            background: #f0fdf4;
        }

        .notif-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #fee2e2;
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .content {
            margin-top: 70px !important;
            padding-top: 20px !important;
        }

        .alerts-corner-hud {
            position: fixed;
            top: 85px;
            right: 20px;
            width: 380px;
            z-index: 1050; /* Below SweetAlert (1060+) but above most other elements */
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
            pointer-events: auto !important; /* Ensure it captures clicks */
            border: 1px solid rgba(239, 68, 68, 0.1);
            border-left: 5px solid #ef4444;
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

<body>


    <!-- Loader -->
    <div id="loader">
        <img src="{{ asset('backend/assets/images/media/loader.svg') }}" alt="">
    </div>
    <!-- Loader -->

    <div class="page">

        <!-- Top Header -->
        <header class="app-header">
            <div class="notif-wrapper" style="display: flex; align-items: center; gap: 15px;">
                @php $unacked = \App\Models\PigActivity::unacknowledgedAlerts()->with('pig.pen')->latest()->get(); @endphp
                
                {{-- Notification Bell --}}
                <div style="position: relative;">
                    <div class="notif-bell" id="notifBell" onclick="toggleNotifDropdown()">
                        <i class='bx bx-bell'></i>
                        @if($unacked->count() > 0)
                            <span class="notif-badge">{{ $unacked->count() }}</span>
                        @endif
                    </div>

                    <div class="notif-dropdown" id="notifDropdown" style="right: 0;">
                        <div class="notif-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h4 style="margin: 0; font-size: 0.9rem; font-weight: 800; color: #1e293b;">CRITICAL ALERTS</h4>
                                <span style="font-size: 0.7rem; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 99px;">{{ $unacked->count() }} Pending</span>
                            </div>
                        </div>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @forelse($unacked as $alert)
                                @if($alert->pig && $alert->pig->pen)
                                    <div class="notif-item" onclick="handleNotifClick({{ $alert->pig->id }}, {{ $alert->pig->pen->id }})">
                                        <div class="notif-icon">
                                            <i class='bx bxs-error-alt'></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <p style="margin: 0; font-size: 0.8rem; font-weight: 800; color: #0f172a;">Pig #{{ $alert->pig->tag }} needs attention</p>
                                            <p style="margin: 2px 0 0; font-size: 0.7rem; color: #64748b; line-height: 1.4;">{{ Str::limit($alert->details, 60) }}</p>
                                            <span style="font-size: 0.6rem; color: #94a3b8; display: block; margin-top: 4px;">{{ $alert->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <div style="padding: 40px 20px; text-align: center;">
                                    <i class='bx bx-check-circle' style="font-size: 2.5rem; color: #22c55e; margin-bottom: 12px; display: block;"></i>
                                    <p style="margin: 0; font-size: 0.8rem; font-weight: 700; color: #64748b;">All clear! No pending alerts.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Profile Dropdown --}}
                <div style="position: relative; pointer-events: auto;">
                    <div onclick="toggleProfileDropdown()" style="display: flex; align-items: center; gap: 10px; cursor: pointer; background: #fff; padding: 6px 14px; border-radius: 14px; border: 1px solid #e2e8f0; transition: all 0.2s;" class="hover:border-primary">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=166534&color=fff&size=80&bold=true' }}" 
                             style="width: 32px; height: 32px; border-radius: 10px; object-fit: cover;">
                        <div style="text-align: left; line-height: 1.2;">
                            <div style="font-size: 0.75rem; font-weight: 800; color: #1e293b;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Admin Account</div>
                        </div>
                        <i class='bx bx-chevron-down' style="color: #94a3b8; font-size: 1rem;"></i>
                    </div>

                    <div id="profileDropdown" style="position: absolute; top: 50px; right: 0; width: 220px; background: #fff; border-radius: 18px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.08); display: none; overflow: hidden; z-index: 1061;">
                        <div style="padding: 16px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                            <div style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Manage Account</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #475569; font-size: 0.85rem; font-weight: 700; text-decoration: none; transition: all 0.2s;" class="hover:bg-primary-light hover:text-primary">
                            <i class='bx bx-user-circle' style="font-size: 1.2rem;"></i> My Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="width: 100%; display: flex; align-items: center; gap: 12px; padding: 12px 16px; color: #ef4444; font-size: 0.85rem; font-weight: 700; border: none; background: transparent; cursor: pointer; transition: all 0.2s;" class="hover:bg-red-50">
                                <i class='bx bx-log-out-circle' style="font-size: 1.2rem;"></i> Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Start::app-sidebar -->
        <aside class="app-sidebar sticky" id="sidebar">
            @include('layouts.sidebar')
        </aside>
        <!-- End::app-sidebar -->

        <div class="content">
            @yield('contents')
        </div>
        <!-- end::main-content -->



        <!-- Footer Start -->
        <footer
            class="footer mt-auto xl:ps-[15rem]  font-normal font-inter bg-white  leading-normal !text-[0.875rem] shadow-[0_0_0.4rem_rgba(0,0,0,0.1)] dark:bg-bodybg py-4 text-center">
            <div class="container">
                <span class="text-gray dark:text-defaulttextcolor/50"> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);"
                        class="text-defaulttextcolor font-semibold dark:text-defaulttextcolor">PorciTrack</a>.
                    All rights reserved.
                </span>
            </div>
        </footer>
        <!-- Footer End -->

    </div>

    <!-- Back To Top -->
    <div class="scrollToTop">
        <span class="arrow"><i class="ri-arrow-up-s-fill text-xl"></i></span>
    </div>

    <div id="responsive-overlay"></div>


    <!-- popperjs -->
    <script src="{{ asset('backend/assets/libs/@popperjs/core/umd/popper.min.js') }}"></script>

    <!-- Color Picker JS -->
    <script src="{{ asset('backend/assets/libs/@simonwep/pickr/pickr.es5.min.js') }}"></script>

    <!-- sidebar JS -->
    <script src="{{ asset('backend/assets/js/defaultmenu.js') }}"></script>

    <!-- Switch JS -->
    <script src="{{ asset('backend/assets/js/switch.js') }}"></script>

    <!-- sticky JS -->
    <script src="{{ asset('backend/assets/js/sticky.js') }}"></script>


    <!-- Simplebar JS -->
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}" defer></script>

    <!-- Preline JS -->
    <script src="{{ asset('backend/assets/libs/preline/preline.js') }}" defer></script>

    <!-- Apex Charts JS -->
    <script src="{{ asset('backend/assets/libs/apexcharts/apexcharts.min.js') }}" defer></script>

    <!-- JSVector Maps JS commented out to save load time
    <script src="{{ asset('backend/assets/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ asset('backend/assets/js/us-merc-en.js') }}"></script>
    -->

    <!-- CRM-Dashboard -->
    <script src="{{ asset('backend/assets/js/index.js') }}" defer></script>

    <!-- Custom-Switcher JS -->
    <script src="{{ asset('backend/assets/js/custom-switcher.js') }}" defer></script>

    <!-- Custom JS -->
    <script src="{{ asset('backend/assets/js/custom.js') }}" defer></script>

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

    <div id="alerts-hud-container">
    @if($unacked->count() > 0)
        <div class="alerts-corner-hud">
            @foreach($unacked->take(3) as $alert)
                @if($alert->pig && $alert->pig->pen)
                    <div class="alert-card">
                        <div class="alert-card-icon">
                            <i class='bx bxs-error-alt'></i>
                        </div>
                        <div style="flex: 1;">
                            <h5 style="margin: 0; font-size: 0.75rem; font-weight: 900; color: #991b1b; text-transform: uppercase;">Medical Critical</h5>
                            <p style="margin: 3px 0 8px; font-size: 0.7rem; color: #64748b; font-weight: 500; line-height: 1.3;">Pig <strong>#{{ $alert->pig->tag }}</strong> needs immediate attention.</p>
                            <button onclick="handleNotifClick({{ $alert->pig->id }}, {{ $alert->pig->pen->id }}, event)">View & Resolve</button>
                        </div>
                    </div>
                @endif
            @endforeach
            @if($unacked->count() > 3)
                <div style="text-align: right; pointer-events: auto;">
                    <span style="font-size: 0.65rem; font-weight: 800; color: #ef4444; background: white; padding: 4px 10px; border-radius: 99px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">+ {{ $unacked->count() - 3 }} more alerts</span>
                </div>
            @endif
        </div>
    @endif
    </div>

    @stack('scripts')
    
    <script>
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            }
        }

        function toggleNotifDropdown() {
            const dropdown = document.getElementById('notifDropdown');
            console.log('Toggling dropdown:', dropdown);
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
        }

        function handleFirstEmergency() {
            const firstItem = document.querySelector('.notif-item');
            console.log('Handling first emergency, found item:', firstItem);
            if (firstItem) {
                firstItem.click();
            } else {
                toggleNotifDropdown();
            }
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            const bell = document.getElementById('notifBell');
            const notifDropdown = document.getElementById('notifDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            const profileToggle = profileDropdown ? profileDropdown.previousElementSibling : null;

            if (bell && notifDropdown && !bell.contains(e.target) && !notifDropdown.contains(e.target)) {
                notifDropdown.classList.remove('show');
            }

            if (profileDropdown && profileToggle && !profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.style.display = 'none';
            }
        });

        function handleNotifClick(pigId, penId, event) {
            console.log("Alert clicked for Pig ID:", pigId, "in Pen ID:", penId);
            
            // Remove the card immediately if it exists
            if (event && event.target) {
                const card = event.target.closest('.alert-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'translateX(50px)';
                    setTimeout(() => card.remove(), 400);
                }
            }

            // Function to handle clicking a notification
            // 1. Navigate to Pens & Pigs if not there, or just open modal
            if (window.location.pathname.includes('/pens/index')) {
                // If already on pens page, find the pig and click it
                openPigFromDashboard(pigId, penId);
            } else {
                // Store in session storage and redirect
                sessionStorage.setItem('pending_pig_modal', pigId);
                sessionStorage.setItem('pending_pen_id', penId);
                window.location.href = "{{ route('pens.index') }}";
            }
        }

        // Global function to open pig modal (to be called from handleNotifClick or on page load)
        function openPigFromDashboard(pigId, penId) {
            // Check if pens page script exists and has the necessary functions
            if (typeof window.expandPenAndShowPig === 'function') {
                window.expandPenAndShowPig(penId, pigId);
            }
        }

        function switchMiniTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("mini-tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("mini-tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.background = "transparent";
                tablinks[i].style.color = "#64748b";
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.style.background = "white";
            evt.currentTarget.style.color = "#0f172a";
        }

        async function acknowledgeAlert(activityId, currentHealth, currentFeeding, pensList, currentPenId) {
            let penOptions = '<option value="">-- Keep in current pen --</option>';
            pensList.forEach(p => {
                if(p.id != currentPenId) {
                    penOptions += `<option value="${p.id}">Move to ${p.name}</option>`;
                }
            });

            Swal.fire({
                title: '<span style="font-weight: 900; color: #991b1b;">ACKNOWLEDGE CARE</span>',
                html: `
                    <div style="text-align: left; padding: 10px;">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">Medical Response / Action Taken</label>
                        <textarea id="swal-admin-response" class="swal2-textarea" style="width: 100%; margin: 0; border-radius: 12px; font-size: 14px;" placeholder="Describe what actions were taken (e.g., administered antibiotic, moved to isolation, etc.)"></textarea>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 16px;">
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">Update Health</label>
                                <select id="swal-health-status" class="swal2-select" style="width: 100%; margin: 0; border-radius: 12px; height: 45px; font-size: 14px;">
                                    <option value="Healthy" ${currentHealth === 'Healthy' ? 'selected' : ''}>Healthy</option>
                                    <option value="Sick" ${currentHealth === 'Sick' ? 'selected' : ''}>Sick</option>
                                    <option value="Recovering" ${currentHealth === 'Recovering' ? 'selected' : ''}>Recovering</option>
                                    <option value="Critical" ${currentHealth === 'Critical' ? 'selected' : ''}>Critical</option>
                                </select>
                            </div>
                            <div>
                                <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">Relocate to Pen</label>
                                <select id="swal-new-pen" class="swal2-select" style="width: 100%; margin: 0; border-radius: 12px; height: 45px; font-size: 14px;">
                                    ${penOptions}
                                </select>
                            </div>
                        </div>

                        <div style="margin-top: 16px;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">Update Feeding Status</label>
                            <select id="swal-feeding-status" class="swal2-select" style="width: 100%; margin: 0; border-radius: 12px; height: 45px; font-size: 14px;">
                                <option value="Normal" ${currentFeeding === 'Normal' ? 'selected' : ''}>Normal</option>
                                <option value="Poor" ${currentFeeding === 'Poor' ? 'selected' : ''}>Poor</option>
                                <option value="None" ${currentFeeding === 'None' ? 'selected' : ''}>None</option>
                            </select>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Submit Update & Acknowledge',
                confirmButtonColor: '#ef4444',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-3xl border-none shadow-2xl',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs'
                },
                preConfirm: () => {
                    const response = document.getElementById('swal-admin-response').value;
                    const health = document.getElementById('swal-health-status').value;
                    const feeding = document.getElementById('swal-feeding-status').value;
                    const newPen = document.getElementById('swal-new-pen').value;
                    
                    if (!response) {
                        Swal.showValidationMessage('Please describe the action taken.');
                        return false;
                    }
                    
                    return { 
                        admin_response: response, 
                        health_status: health, 
                        feeding_status: feeding,
                        new_pen_id: newPen 
                    };
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        Swal.fire({
                            title: 'Saving Care Plan...',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        const response = await fetch(`/admin/pigs/activities/${activityId}/acknowledge`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(result.value)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Server error occurred');
                        }

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Care Update Saved!',
                                text: data.message,
                                confirmButtonColor: '#22c55e'
                            }).then(() => {
                                location.reload(); 
                            });
                        }
                    } catch (error) {
                        Swal.fire('Error', error.message, 'error');
                    }
                }
            });
        }
    </script>
    
    <script>
        // Real-time alerts polling for Admin
        let lastAlertCount = {{ $unacked->count() }};
        
        async function pollAlerts() {
            try {
                const response = await fetch('{{ route("admin.api.alerts") }}');
                const alerts = await response.json();
                const container = document.getElementById('alerts-hud-container');
                
                if (alerts.length !== lastAlertCount) {
                    console.log("New alerts detected, updating HUD...");
                    
                    // Update last count
                    const isNew = alerts.length > lastAlertCount;
                    lastAlertCount = alerts.length;
                    
                    if (alerts.length === 0) {
                        container.innerHTML = '';
                        return;
                    }

                    // Play notification sound if new alert
                    if (isNew) {
                        const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
                        audio.play().catch(e => console.log("Audio play blocked"));
                    }

                    // Generate HTML for HUD
                    let html = `<div class="alerts-corner-hud">`;
                    alerts.slice(0, 3).forEach(alert => {
                        if (alert.pig && alert.pig.pen) {
                            html += `
                                <div class="alert-card" style="animation: slide-up 0.4s ease-out;">
                                    <div class="alert-card-icon">
                                        <i class='bx bxs-error-alt'></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <h5 style="margin: 0; font-size: 0.75rem; font-weight: 900; color: #991b1b; text-transform: uppercase;">Medical Critical</h5>
                                        <p style="margin: 3px 0 8px; font-size: 0.7rem; color: #64748b; font-weight: 500; line-height: 1.3;">Pig <strong>#${alert.pig.tag}</strong> needs immediate attention.</p>
                                        <button onclick="handleNotifClick(${alert.pig.id}, ${alert.pig.pen.id}, event)">View & Resolve</button>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    
                    if (alerts.length > 3) {
                        html += `
                            <div style="text-align: right; pointer-events: auto;">
                                <span style="font-size: 0.65rem; font-weight: 800; color: #ef4444; background: white; padding: 4px 10px; border-radius: 99px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">+ ${alerts.length - 3} more alerts</span>
                            </div>
                        `;
                    }
                    html += `</div>`;
                    container.innerHTML = html;
                }
            } catch (error) {
                console.error("Alert polling failed:", error);
            }
        }

        // Poll every 15 seconds
        setInterval(pollAlerts, 15000);
    </script>
</body>

</html>
