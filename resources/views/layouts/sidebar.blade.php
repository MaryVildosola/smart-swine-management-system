{{-- New SwineForge Dark Sidebar with Dropdowns --}}

<!-- Start::main-sidebar -->
<div class="main-sidebar" id="sidebar-scroll" style="padding-top: 0 !important; margin-top: 0 !important;">

    <!-- Farm Admin Profile -->
    <div class="sidebar-profile" style="padding-top: 24px !important;">
        <div class="sidebar-avatar">
    <img 
        src="{{ Auth::user()->photo 
            ? asset('storage/' . Auth::user()->photo) 
            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=166534&color=fff&size=80&bold=true' 
        }}" 
        alt="{{ Auth::user()->name }}">
</div>
        <div class="sidebar-profile-info">
            <p class="sidebar-profile-name">{{ Auth::user()->name }}</p>
            <p class="sidebar-profile-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
        </div>
    </div>

    <!-- Start::nav -->
    <nav class="main-menu-container nav nav-pills flex-column sub-open">
        <ul class="main-menu">
            <!-- Home -->
            <li class="slide {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="side-menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" /></svg>
                    <span class="side-menu__label">Dashboard</span>
                    @php $alertCount = \App\Models\PigActivity::unacknowledgedAlerts()->count(); @endphp
                    @if($alertCount > 0)
                        <span class="badge bg-red-500 text-white rounded-full px-2 py-1 text-[10px] ml-auto animate-pulse">{{ $alertCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Operations Dropdown -->
            <li class="slide has-sub {{ request()->routeIs('pens.*') || request()->routeIs('admin.tasks.*') || request()->routeIs('admin.qr.index') ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="side-menu__item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" fill="currentColor"/></svg>
                    <span class="side-menu__label">Operations</span>
                    @if($alertCount > 0)
                        <span class="w-2 h-2 bg-red-500 rounded-full ml-1 animate-ping"></span>
                    @endif
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide {{ request()->routeIs('pens.*') ? 'active' : '' }}">
                        <a href="{{ route('pens.index') }}" class="side-menu__item">
                            Pens & Pigs
                            @if($alertCount > 0)
                                <span class="badge bg-red-500/20 text-red-300 text-[9px] px-1.5 py-0.5 rounded ml-auto">{{ $alertCount }} alert{{ $alertCount > 1 ? 's' : '' }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="slide {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.tasks.index') }}" class="side-menu__item">Farmer Tasks</a>
                    </li>
                    <li class="slide {{ request()->routeIs('admin.qr.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.qr.index') }}" class="side-menu__item">QR Labels</a>
                    </li>
                </ul>
            </li>

            <!-- Stock & Inventory Dropdown -->
            <li class="slide has-sub {{ request()->routeIs('admin.feed-stock.*') || request()->routeIs('admin.feed-mix.*') ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="side-menu__item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M20 6h-2.18c.07-.44.18-.88.18-1.34 0-2.58-2.09-4.66-4.67-4.66-1.41 0-2.67.61-3.55 1.57L8 3.96 6.22 1.57C5.34.61 4.08 0 2.67 0 .09 0-2 2.08-2 4.66c0 .46.11.9.18 1.34H-2v2h24V6z" fill="currentColor"/></svg>
                    <span class="side-menu__label">Inventory</span>
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide {{ request()->routeIs('admin.feed-stock.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.feed-stock.index') }}" class="side-menu__item">Master Stocks</a>
                    </li>
                    <li class="slide {{ request()->routeIs('admin.feed-mix.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.feed-mix.index') }}" class="side-menu__item">Feed Mixing</a>
                    </li>
                </ul>
            </li>

            <!-- Analysis Dropdown -->
            <li class="slide has-sub {{ request()->routeIs('admin.analytics') || request()->routeIs('admin.reports*') ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="side-menu__item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" fill="currentColor"/></svg>
                    <span class="side-menu__label">Reports</span>
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                        <a href="{{ route('admin.analytics') }}" class="side-menu__item">Live Analytics</a>
                    </li>
                    <li class="slide {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                        <a href="{{ route('admin.reports') }}" class="side-menu__item">Weekly Reports</a>
                    </li>
                </ul>
            </li>

            <!-- Systems Dropdown -->
            <li class="slide has-sub {{ request()->routeIs('users.*') ? 'open active' : '' }}">
                <a href="javascript:void(0);" class="side-menu__item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" fill="currentColor"/></svg>
                    <span class="side-menu__label">Settings</span>
                    <i class="fe fe-chevron-right side-menu__angle"></i>
                </a>
                <ul class="slide-menu child1">
                    <li class="slide {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}" class="side-menu__item">User Management</a>
                    </li>
                    <li class="slide {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}" class="side-menu__item">System Settings</a>
                    </li>
                    <li class="slide {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <a href="{{ route('profile.edit') }}" class="side-menu__item">My Profile</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- End::nav -->

    <div class="sidebar-logout">
        <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="sidebar-logout-btn">
        <i class="bx bx-log-out"></i> Logout
    </button>
</form>
    </div>

    <script>
    function confirmAdminLogout() {
        Swal.fire({
            title: '<span style="font-weight:900;color:#1e293b;">Log Out?</span>',
            html: '<p style="color:#64748b;font-size:0.88rem;margin:0;">Are you sure you want to log out of SwineForge?</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, log out',
            cancelButtonText: 'Stay',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            customClass: {
                popup: 'rounded-3xl border-none shadow-2xl',
                confirmButton: 'px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs',
                cancelButton: 'px-6 py-3 rounded-xl font-bold uppercase tracking-widest text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('adminLogoutForm').submit();
            }
        });
    }
    </script>

</div>
<!-- End::main-sidebar -->

<style>
    /* Sidebar Profile */
    .sidebar-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 8px;
    }

    .sidebar-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        background: #1a3a1a;
        padding: 2px;
        border: 2px solid #bbd1bc;
    }

    .sidebar-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .sidebar-profile-info {
        flex: 1;
        min-width: 0;
    }

    .sidebar-profile-name {
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-profile-role {
        color: rgba(255, 255, 255, 0.6); /* Increased contrast */
        font-size: 0.72rem;
        margin: 0;
    }

    /* Sub-menu styling for dark theme */
    .slide-menu.child1 {
        background: rgba(0, 0, 0, 0.2) !important;
        padding-left: 12px !important;
    }
    
    .slide-menu.child1 .side-menu__item {
        font-size: 0.8rem !important;
        opacity: 0.85; /* Increased opacity */
        color: #cbd5e1 !important;
    }
    
    .slide-menu.child1 .side-menu__item:hover {
        opacity: 1;
        color: #fff !important;
    }

    /* Sidebar logout */
    .sidebar-logout {
        margin-top: auto;
        padding: 16px 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .sidebar-logout-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.55);
        font-size: 0.85rem;
        cursor: pointer;
        padding: 6px 0;
        transition: color 0.2s;
        width: 100%;
        font-family: inherit;
    }

    .sidebar-logout-btn:hover {
        color: #ff6b6b;
    }

    .sidebar-logout-btn svg {
        width: 18px;
        height: 18px;
        fill: currentColor;
    }
</style>
