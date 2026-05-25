@extends('layouts.worker')

@section('content')
<style>
    /* Dark mode is the DEFAULT (body has no class).
       Light mode overrides activate when body has .light-theme (set by worker.blade.php) */
    body.light-theme .worker-dash { background: #f1f5f9 !important; }

    body.light-theme .dash-card,
    body.light-theme .bg-white\/80 {
        background: rgba(255,255,255,0.95) !important;
        border-color: rgba(0,0,0,0.08) !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.06) !important;
    }
    body.light-theme .dash-inner,
    body.light-theme .bg-slate-50,
    body.light-theme .bg-slate-100,
    body.light-theme .bg-slate-200 {
        background: #f8fafc !important;
        border-color: rgba(0,0,0,0.05) !important;
        box-shadow: none !important;
    }
    body.light-theme .border-slate-100,
    body.light-theme .border-slate-200 { border-color: #e2e8f0 !important; }
    body.light-theme .text-slate-900,
    body.light-theme .text-slate-800,
    body.light-theme h1,
    body.light-theme h2,
    body.light-theme h3 { color: #0f172a !important; }
    body.light-theme .text-slate-500,
    body.light-theme .text-slate-400,
    body.light-theme .text-slate-300 { color: #64748b !important; }

    /* Dark Mode styles (default &mdash; no class needed) */
    .worker-dash { background: linear-gradient(to bottom right, #0a180e, #0d2214, #0a180e); }

    .dash-card, .bg-white\/80 {
        background: rgba(255,255,255,0.08) !important;
        border-color: rgba(255,255,255,0.12) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.4) !important;
    }
    .dash-inner, .bg-slate-50, .bg-slate-100, .bg-slate-200 {
        background: rgba(0,0,0,0.25) !important;
        border-color: rgba(255,255,255,0.04) !important;
        box-shadow: inset 0 4px 15px rgba(0,0,0,0.2) !important;
    }
    .border-slate-100, .border-slate-200, .border-dashed { border-color: rgba(255,255,255,0.08) !important; }
    .text-slate-900, .text-slate-800 { color: #ffffff !important; }
    .text-slate-500, .text-slate-400, .text-slate-300 { color: rgba(255,255,255,0.5) !important; }
    .text-slate-50, .text-slate-100 { color: rgba(255,255,255,0.03) !important; }

    /* Light mode for inputs/modals when toggled */
    body.light-theme input, body.light-theme select, body.light-theme textarea {
        background-color: rgba(0,0,0,0.04) !important;
        color: #0f172a !important;
        border-color: rgba(0,0,0,0.1) !important;
    }
    body.light-theme .bcs-btn, body.light-theme .feed-btn {
        background: rgba(0,0,0,0.04) !important;
        border-color: rgba(0,0,0,0.1) !important;
        color: #64748b !important;
    }
    body.light-theme .bcs-btn.border-green-500,
    body.light-theme .feed-btn.border-green-500 {
        background: rgba(34,197,94,0.1) !important;
        border-color: rgba(34,197,94,0.4) !important;
        color: #15803d !important;
    }
</style>

<div class="worker-dash">
    <div class="px-6 md:px-12 py-10 max-w-full">

        <!-- Header Section -->
        <div class="mb-10 md:mb-14 flex justify-between items-center w-full">
            <div>
                <p class="text-xs md:text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Operational Dashboard</p>
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter">Hello, <span class="text-green-600">{{ explode(' ', Auth::user()->name)[0] }}</span></h1>
            </div>
        </div>

        <!-- Critical Alerts Banner -->
        @if($criticalAlerts->count() > 0)
        @foreach($criticalAlerts as $alert)
        <div id="criticalAlertsBanner" class="mb-10 animate-fade-in">
            <div class="dash-card flex items-center gap-4 p-5 rounded-[2rem] backdrop-blur-2xl bg-white/80 border border-red-100 shadow-sm hover:shadow-md transition cursor-pointer" 
                 onclick="handleNotifClick({{ $alert->id }}, {{ $alert->pen_id ?? 'null' }}, '{{ addslashes($alert->pen->name ?? '') }}', null, null)">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shrink-0 animate-pulse">
                    <i class='bx bxs-error-circle text-2xl'></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-900 font-black text-sm">CRITICAL &mdash; Pig #{{ $alert->tag }}, {{ $alert->pen->name ?? 'Unknown Pen' }}</p>
                    <p class="text-slate-500 text-xs font-medium">{{ $alert->remarks ?? 'Unusual health metrics detected. Immediate check-up required.' }}</p>
                </div>
                <i class='bx bx-chevron-right text-slate-300 text-2xl'></i>
            </div>
        </div>
        @endforeach
        @endif

        <!-- Top Actions Wrapper -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            <button onclick="startQRScanner()" class="group relative p-8 rounded-[2.5rem] bg-green-600 hover:bg-green-700 transition-all shadow-xl shadow-green-500/20 flex items-center gap-6 overflow-hidden">
                <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="w-16 h-16 rounded-3xl bg-white/20 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                    <i class='bx bx-qr-scan text-3xl'></i>
                </div>
                <div class="text-left relative z-10">
                    <p class="text-white font-black text-2xl tracking-tight">Scan QR Code</p>
                    <p class="text-white/70 text-[10px] uppercase font-black tracking-widest mt-1">Manual ID Verification</p>
                </div>
            </button>

            <div class="dash-card backdrop-blur-2xl bg-white/80 p-8 rounded-[2.5rem] flex items-center justify-between border border-slate-100 shadow-[0_5px_15px_rgba(0,0,0,0.05)]">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                        <i class='bx bx-cloud-upload text-2xl'></i>
                    </div>
                    <div>
                        <p class="text-slate-900 font-extrabold text-lg">Auto-Sync Active</p>
                        <p class="text-slate-400 text-[10px] uppercase font-black tracking-widest">Network: Operational</p>
                    </div>
                </div>
                <div class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.4)]"></div>
            </div>
        </div>


        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            @foreach($stats as $s)
                <div class="dash-card backdrop-blur-2xl bg-white/80 p-7 rounded-[2.2rem] border border-slate-100 shadow-[0_5px_15px_rgba(0,0,0,0.05)] relative overflow-hidden group hover:shadow-[0_10px_25px_rgba(0,0,0,0.1)] transition-all">
                    <div class="relative z-10">
                        <span class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] block mb-2">{{ $s['label'] }}</span>
                        <span class="text-4xl font-black text-{{ $s['color'] }}-600 tracking-tighter">{{ $s['val'] }}</span>
                    </div>
                    <i class='bx {{ $s['icon'] }} absolute bottom-[-15px] right-[-15px] text-7xl text-slate-50 group-hover:scale-110 group-hover:text-slate-100 transition-all duration-500'></i>
                </div>
            @endforeach
        </div>

        <!-- AI Biosecurity Intelligence Section -->
        <div class="mb-16">
            <div class="flex items-center justify-between mb-8 px-1">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">AI Local Threat Alerts</h2>
                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1">Real-time biosecurity intelligence</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    </span>
                    <span class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em]">Live Monitoring</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($regionalDiseases as $disease)
                    <div class="dash-card backdrop-blur-2xl bg-white/80 p-6 rounded-[2rem] border border-slate-100 shadow-[0_5px_15px_rgba(0,0,0,0.05)] flex flex-col gap-4 hover:border-red-500/50 transition-all group">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background: {{ $disease->level == 'High' ? 'rgba(239, 68, 68, 0.1)' : ($disease->level == 'Medium' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(34, 197, 94, 0.1)') }}; color: {{ $disease->level == 'High' ? '#ef4444' : ($disease->level == 'Medium' ? '#f59e0b' : '#22c55e') }};">
                                    <i class='bx {{ $disease->level == 'High' ? 'bxs-virus' : 'bx-virus' }} text-2xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-900 tracking-tight leading-tight">{{ $disease->name }}</h3>
                                    <div class="text-[10px] text-slate-500 font-bold flex items-center gap-1 mt-1">
                                        <i class='bx bx-map-pin text-indigo-500'></i> {{ Str::limit($disease->distance, 40) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dash-inner bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col gap-3">
                            <div>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Transmission Vector</span>
                                <span class="text-xs font-bold text-slate-900 block">{{ $disease->vector ?? 'Not specified' }}</span>
                            </div>
                            <div>
                                <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-1">Primary Symptoms</span>
                                <span class="text-xs font-bold text-slate-900 block">{{ $disease->symptoms ?? 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="mt-auto pt-2">
                            <div class="w-full bg-indigo-50 border border-indigo-100 rounded-xl p-3 flex items-start gap-3 shadow-sm">
                                <i class='bx bx-shield-quarter text-xl mt-0.5' style="color: #4f46e5 !important;"></i>
                                <div>
                                    <span class="text-[8px] font-black uppercase tracking-widest block mb-0.5" style="color: #4f46e5 !important;">Required Action</span>
                                    <span class="text-xs font-bold block" style="color: #312e81 !important;">{{ $disease->action_required ?? 'Monitor closely' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-10 text-center bg-white/50 dark:bg-white/5 rounded-[2rem] border border-dashed border-slate-200 dark:border-white/10">
                        <i class='bx bx-check-shield text-4xl text-green-500 mb-3'></i>
                        <p class="text-slate-500 dark:text-white/50 text-sm font-bold">No active local threats detected.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Section Header -->
        <div class="flex items-center justify-between mb-8 px-1">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Pens Overview</h2>
                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1">Real-time inventory</p>
            </div>
  <button class="w-10 h-10 rounded-xl bg-white dark:bg-white/10 border border-slate-200 dark:border-white/10 flex items-center justify-center text-slate-400 hover:text-green-600 dark:text-white/60 transition shadow-sm">
    <i class='bx bx-filter-alt'></i>
</button>
        </div>

        <!-- Pens Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            @foreach($pens as $pen)
                <div onclick="openPenPreview('{{ $pen['id'] }}','{{ addslashes($pen['name']) }}','{{ $pen['type'] }}',{{ $pen['count'] ?? 0 }},{{ $pen['sick'] ?? 0 }},{{ $pen['weight'] ?? 0 }},{{ $pen['progress'] ?? 0 }},'{{ $pen['color'] }}','{{ addslashes($pen['tag']) }}')" class="dash-card group backdrop-blur-2xl bg-white/80 rounded-[2.5rem] p-8 border border-slate-100 hover:border-green-500/50 hover:shadow-[0_15px_30px_rgba(0,0,0,0.1)] transition-all cursor-pointer relative overflow-hidden shadow-[0_5px_15px_rgba(0,0,0,0.05)]">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tighter">{{ $pen['name'] }}</h3>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1">{{ $pen['type'] }}</p>
                        </div>
                        <span class="px-3 py-1 bg-{{ $pen['color'] }}-50 text-{{ $pen['color'] }}-600 rounded-full text-[9px] font-black border border-{{ $pen['color'] }}-100 uppercase tracking-widest">{{ $pen['tag'] }}</span>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-8">
                        <div class="dash-inner bg-slate-50 shadow-sm border border-slate-100 rounded-2xl p-4 text-center group-hover:bg-green-50 transition-colors">
                            <p class="text-slate-400 text-[8px] uppercase font-black mb-1">Pigs</p>
                            <p class="text-slate-900 font-black text-xl tracking-tight">{{ $pen['count'] }}</p>
                        </div>
                        <div class="dash-inner bg-slate-50 shadow-sm border border-slate-100 rounded-2xl p-4 text-center">
                            <p class="text-slate-400 text-[8px] uppercase font-black mb-1">Sick</p>
                            <p class="{{ $pen['sick'] > 0 ? 'text-red-500' : 'text-slate-900' }} font-black text-xl tracking-tight">{{ $pen['sick'] }}</p>
                        </div>
                        <div class="dash-inner bg-slate-50 shadow-sm border border-slate-100 rounded-2xl p-4 text-center">
                            <p class="text-slate-400 text-[8px] uppercase font-black mb-1">Avg Kg</p>
                            <p class="text-slate-900 font-black text-xl tracking-tight">{{ $pen['weight'] }}</p>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest">Weight Target</p>
                            <p class="text-slate-900 text-[9px] font-black">{{ $pen['progress'] ?? 0 }}%</p>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5">
                            <div class="h-full bg-{{ $pen['color'] }}-500 rounded-full shadow-[0_0_10px_rgba(0,0,0,0.1)] transition-all duration-1000" style="width: {{ $pen['progress'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Recent Logs Header -->
        <div class="flex items-center justify-between mb-8 px-1">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Recent Activity</h2>
                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mt-1">Latest synchronization</p>
            </div>
            <a href="{{ route('worker.activity-log') }}" class="text-[10px] font-black text-green-600 uppercase tracking-[0.2em] hover:text-green-700 transition">View History</a>
        </div>

        <div id="recentMonitoringList" class="space-y-4 pb-20">
            @forelse($recentActivities as $activity)
            <div class="dash-card backdrop-blur-2xl bg-white/80 rounded-[2rem] p-6 flex gap-5 items-center border border-slate-100 shadow-[0_5px_15px_rgba(0,0,0,0.03)] hover:shadow-md transition cursor-pointer" 
                 onclick="handleNotifClick({{ $activity->pig_id }}, {{ $activity->pig->pen_id ?? 'null' }}, '{{ addslashes($activity->pig->pen->name ?? '') }}', event, {{ $activity->id }})">
                <div class="w-14 h-14 rounded-2xl bg-{{ $activity->type === 'Medical' ? 'red' : ($activity->type === 'Growth' ? 'blue' : 'green') }}-50 flex items-center justify-center shrink-0 border border-slate-100 text-{{ $activity->type === 'Medical' ? 'red' : ($activity->type === 'Growth' ? 'blue' : 'green') }}-600">
                    <i class='bx {{ $activity->type === 'Medical' ? 'bx-plus-medical' : ($activity->type === 'Growth' ? 'bx-trending-up' : 'bx-check-double') }} text-2xl'></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-0.5">
                        <p class="text-slate-900 font-black text-base tracking-tight">{{ $activity->pig->tag }} &mdash; {{ $activity->action }}</p>
                        <span class="text-slate-300 text-[10px] font-black">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-slate-500 text-xs font-medium">{{ $activity->details ?? 'Activity recorded by ' . $activity->user->name }}</p>
                </div>
            </div>
            @empty
            <div class="p-10 text-center bg-white dark:bg-white/5 rounded-[2rem] border border-dashed border-slate-200 dark:border-white/10">
    <p class="text-slate-400 dark:text-white/50 text-sm font-medium">
        No recent activity found.
    </p>
</div>
            @endforelse
        </div>

    </div>
</div>

    <!-- QR Scanner Modal -->
    <div id="qrModal" class="fixed inset-0 z-[200] hidden bg-black/95 backdrop-blur-2xl flex flex-col items-center justify-center p-6">
        <div class="w-full max-w-sm">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight">Scanner</h2>
                    <p class="text-green-400 text-[10px] uppercase font-bold tracking-[0.2em] mt-1">Operational ID Check</p>
                </div>
                <button onclick="stopQRScanner()" class="w-14 h-14 rounded-2xl bg-white/10 text-white flex items-center justify-center hover:bg-white/20 transition-all active:scale-90">
                    <i class='bx bx-x text-3xl'></i>
                </button>
            </div>
            <div class="relative group">
                <div id="qr-reader" class="relative rounded-[2.5rem] overflow-hidden border-2 border-white/10 bg-black aspect-square"></div>
                <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 h-0.5 bg-green-500/50 shadow-[0_0_15px_rgba(34,197,94,0.8)] animate-pulse"></div>
            </div>
            <div class="mt-8 text-center space-y-2">
                <p class="text-white font-bold">Waiting for ID...</p>
                <p class="text-white/40 text-xs px-6 leading-relaxed">Position a Pen QR or Ear Tag within the frame.</p>
            </div>

            <!-- Manual fallback inside scanner -->
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-white/40 text-[10px] uppercase font-black tracking-widest text-center mb-3">Or enter ID manually</p>
                <div class="flex gap-3">
                    <input type="text" id="qrManualInput" placeholder="e.g. PEN-1 or A2-001" 
                        class="flex-1 bg-white/10 border border-white/20 rounded-2xl py-4 px-5 text-white font-bold text-sm placeholder-white/30 focus:outline-none focus:border-green-500 transition"
                        onkeydown="if(event.key==='Enter') submitManualId()">
                    <button onclick="submitManualId()" class="px-6 py-4 bg-green-600 text-white font-black rounded-2xl hover:bg-green-700 transition active:scale-95 text-sm uppercase tracking-wide flex items-center justify-center">
                        <span class="text-white">Go</span>
                    </button>
                </div>
            </div>
        </div>
    </div>




    <!-- Pen Preview Modal -->
    <div id="penPreviewModal" class="fixed inset-0 z-[205] hidden" aria-modal="true">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closePenPreview()"></div>
        <!-- Sheet -->
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[2.5rem] shadow-2xl p-8 pb-10 animate-fade-in max-w-2xl mx-auto">
            <!-- Handle -->
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-7"></div>

            <!-- Header -->
            <div class="flex justify-between items-start mb-7">
                <div>
                    <span id="ppTag" class="inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border mb-2"></span>
                    <h2 id="ppName" class="text-3xl font-black text-slate-900 tracking-tighter"></h2>
                    <p id="ppType" class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1"></p>
                </div>
                <button onclick="closePenPreview()" class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center hover:bg-slate-200 transition active:scale-90">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4 mb-7">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-center">
                    <p class="text-slate-400 text-[9px] uppercase font-black mb-1">Total Pigs</p>
                    <p id="ppCount" class="text-slate-900 font-black text-3xl tracking-tight"></p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-center">
                    <p class="text-slate-400 text-[9px] uppercase font-black mb-1">Sick</p>
                    <p id="ppSick" class="font-black text-3xl tracking-tight"></p>
                </div>
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-center">
                    <p class="text-slate-400 text-[9px] uppercase font-black mb-1">Avg Kg</p>
                    <p id="ppWeight" class="text-slate-900 font-black text-3xl tracking-tight"></p>
                </div>
            </div>

            <!-- Weight Progress -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-2">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Weight Target</p>
                    <p id="ppProgress" class="text-slate-900 text-[10px] font-black"></p>
                </div>
                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                    <div id="ppProgressBar" class="h-full rounded-full transition-all duration-700"></div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-2 gap-4">
                <button onclick="closePenPreview()" class="py-5 rounded-2xl border border-slate-200 text-slate-600 font-black text-sm uppercase tracking-wider hover:bg-slate-50 transition active:scale-95">
                    <i class='bx bx-x mr-1'></i> Close
                </button>
                <button id="ppLogBtn" onclick="" class="py-5 rounded-2xl bg-green-600 text-white font-black text-sm uppercase tracking-wider hover:bg-green-700 transition active:scale-95 shadow-lg shadow-green-500/20">
                    <i class='bx bx-clipboard mr-1'></i> Log Feeding &amp; Tasks
                </button>
            </div>
        </div>
    </div>

    <!-- Expanded Pen Feeding & Task Modal -->
    <div id="feedingModal" class="fixed inset-0 z-[210] hidden bg-slate-900/95 backdrop-blur-3xl flex items-start justify-center p-4 overflow-y-auto">
        <div class="bg-white w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-2xl border border-slate-200 animate-fade-in my-6">
            <div class="p-8 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <div class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-[10px] font-black border border-blue-200 uppercase tracking-widest mb-2 inline-block">
                        Pen Identifier: <span id="targetPenId">--</span>
                    </div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tighter" id="targetPenTitle">Routine Feeding</h2>
                </div>
                <button onclick="closeFeedingModal()" class="w-16 h-16 rounded-3xl bg-white text-slate-400 flex items-center justify-center hover:bg-slate-100 transition border border-slate-200 shadow-sm active:scale-90">
                    <i class='bx bx-x text-4xl'></i>
                </button>
            </div>

            <div class="p-8 space-y-8">
                <!-- PEN STATS SUMMARY -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-5 bg-green-50 border border-green-100 rounded-3xl text-center">
                        <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Healthy Pigs</p>
                        <p class="text-3xl font-black text-slate-900" id="penStatHealthy">--</p>
                    </div>
                    <div class="p-5 bg-red-50 border border-red-100 rounded-3xl text-center">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1">Sick Pigs</p>
                        <p class="text-3xl font-black text-slate-900" id="penStatSick">--</p>
                    </div>
                </div>

                <!-- PEN TASKS SECTION -->
                <div id="penTasksSection" class="hidden">
                    <div class="p-6 bg-amber-50 border border-amber-200 rounded-[2.5rem] shadow-sm">
                        <p class="text-[10px] font-black text-amber-700 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <i class='bx bx-task text-base'></i>
                            Pen Maintenance Tasks &mdash; Check if Done
                        </p>
                        <div id="penTasksList" class="space-y-3"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- WATER SUPPLY -->
                    <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Water Supply Status</label>
                        <select id="penWaterStatus" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-sm font-bold focus:border-blue-500 transition">
                            <option value="Operational">Operational</option>
                            <option value="Leaking">Leaking</option>
                            <option value="Low Pressure">Low Pressure</option>
                            <option value="Broken">Broken / No Flow</option>
                            <option value="Dirty">Needs Cleaning</option>
                        </select>
                    </div>

                    <!-- HYGIENE -->
                    <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Pen Hygiene/Bedding</label>
                        <select id="penHygieneStatus" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-sm font-bold focus:border-blue-500 transition">
                            <option value="Clean">Clean / Adequate</option>
                            <option value="Fair">Fair / Needs Cleaning</option>
                            <option value="Dirty">Dirty / High Ammonia</option>
                            <option value="Wet">Wet / Needs Bedding</option>
                        </select>
                    </div>
                </div>

                <!-- FEEDING INPUT -->
                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-200">
                    <label class="block text-sm font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class='bx bx-bowl-hot text-green-600'></i> Quantity to Feed (kg)
                    </label>
                    <div class="relative">
                        <input type="number" id="feedQty" placeholder="0.0" class="w-full bg-white border border-slate-200 rounded-2xl py-6 px-8 text-4xl font-black text-slate-900 focus:outline-none focus:border-green-500 transition shadow-sm">
                        <span class="absolute right-8 top-1/2 -translate-y-1/2 text-slate-300 font-black text-2xl">KG</span>
                    </div>
                </div>

                <!-- NOTES -->
                <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Batch Observations / Notes</label>
                    <textarea id="penNotes" rows="2" placeholder="Any issues with the batch?" class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-sm font-medium focus:border-blue-500 transition resize-none"></textarea>
                </div>

                <div class="pt-2">
                    <button onclick="submitFeedingLog()" class="w-full bg-green-600 text-white py-7 rounded-[2rem] font-black text-2xl shadow-[0_20px_40px_rgba(34,197,94,0.3)] hover:shadow-xl transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        <i class='bx bx-check-double text-3xl'></i> Confirm Batch Feed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Health Monitoring Modal (Comprehensive Assessment) -->
    <div id="healthModal" class="fixed inset-0 z-[210] hidden bg-slate-900/95 backdrop-blur-3xl flex items-start justify-center p-4 overflow-y-auto">
        <div class="bg-white w-full max-w-2xl rounded-[3rem] overflow-hidden shadow-2xl border border-slate-200 animate-fade-in my-6">
            <!-- Header -->
            <div class="p-8 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black border border-green-200 uppercase tracking-widest">Pig Registry</span>
                        <span id="pigInfoPenBadge" class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full text-[10px] font-black border border-slate-300 uppercase tracking-widest">Pen: --</span>
                    </div>
                    <h2 class="text-5xl font-black text-slate-900 tracking-tighter" id="targetPigId">--</h2>
                </div>
                <button onclick="closeHealthModal()" class="w-16 h-16 rounded-3xl bg-white text-slate-400 flex items-center justify-center hover:bg-slate-100 transition border border-slate-200 shadow-sm active:scale-90">
                    <i class='bx bx-x text-4xl'></i>
                </button>
            </div>

            <div class="p-8 space-y-10">
                <!-- SECTION 1: PENDING TASKS (Actionable) -->
                <div id="pigTasksSection" class="hidden">
                    <div class="p-6 bg-amber-50 border border-amber-200 rounded-[2.5rem] shadow-sm">
                        <p class="text-[10px] font-black text-amber-700 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <i class='bx bx-task text-base'></i>
                            Pending Tasks &mdash; Check if Completed
                        </p>
                        <div id="pigTasksList" class="space-y-3"></div>
                    </div>
                </div>

                <!-- SECTION 2: PHYSICAL ASSESSMENT -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Feeding -->
                    <div class="space-y-4">
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i class='bx bx-bowl-hot text-green-600'></i> Feeding Status
                        </p>
                        <div class="grid grid-cols-1 gap-2">
                            <button type="button" onclick="setFeedBehavior(this, 'Active')" class="feed-btn w-full py-4 rounded-2xl border border-slate-200 bg-white text-slate-600 font-bold text-sm transition text-left px-5 flex justify-between items-center group">
                                Active / Finished All <i class='bx bx-check-circle opacity-0 group-[.border-green-500]:opacity-100 text-green-600'></i>
                            </button>
                            <button type="button" onclick="setFeedBehavior(this, 'Normal')" class="feed-btn w-full py-4 rounded-2xl border border-green-500 bg-green-50 text-green-700 font-bold text-sm transition text-left px-5 flex justify-between items-center group">
                                Normal Intake <i class='bx bx-check-circle opacity-100 text-green-600'></i>
                            </button>
                            <button type="button" onclick="setFeedBehavior(this, 'Poor/None')" class="feed-btn w-full py-4 rounded-2xl border border-slate-200 bg-white text-slate-600 font-bold text-sm transition text-left px-5 flex justify-between items-center group">
                                Poor / Leftovers <i class='bx bx-error opacity-0 group-[.border-green-500]:opacity-100 text-red-500'></i>
                            </button>
                        </div>
                        <input type="hidden" id="selectedFeedBehavior" value="Normal">
                    </div>

                    <!-- Water -->
                    <div class="space-y-4">
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i class='bx bx-droplet text-blue-500'></i> Water Supply
                        </p>
                        <select id="val-water" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-5 px-5 text-slate-900 text-sm focus:outline-none focus:border-blue-500 transition font-bold appearance-none">
                            <option value="Operational">Operational &mdash; Working</option>
                            <option value="Leaking">Leaking / Wasteful</option>
                            <option value="Dirty">Dirty / Needs Cleaning</option>
                            <option value="No Supply">No Supply / Broken</option>
                        </select>
                    </div>
                </div>

                <!-- SECTION 3: VITAL SIGNS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- BCS -->
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest mb-1">Body Condition (BCS)</p>
                        <p class="text-[10px] text-slate-400 font-bold mb-4">1: Thin | 3: Ideal | 5: Obese</p>
                        <div class="flex gap-2">
                            @foreach([1, 2, 3, 4, 5] as $score)
                            <button type="button" onclick="setBCS(this, {{ $score }})" class="bcs-btn flex-1 py-4 rounded-xl border border-slate-200 bg-white text-slate-900 font-black text-base transition active:scale-95">
                                {{ $score }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" id="selectedBCS" value="3">
                    </div>

                    <!-- Weight -->
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest mb-5">Estimated Weight</p>
                        <div class="relative">
                            <input type="number" id="pigWeight" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-5 pl-6 pr-16 text-slate-900 text-3xl font-black focus:outline-none focus:border-green-500 transition">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xl">kg</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: PHYSICAL INSPECTION CHECKLIST -->
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase tracking-widest mb-4">Physical Inspection &mdash; Tap to confirm</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="physicalChecklist"></div>
                </div>

                <!-- SECTION 5: RELOCATION (NEW) -->
                <div class="bg-blue-50/50 p-8 rounded-[2.5rem] border border-blue-100 space-y-4">
                    <p class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <i class='bx bx-transfer text-blue-600'></i> Relocate Animal (Optional)
                    </p>
                    <div class="relative">
                        <select id="movePenId" class="w-full bg-white border border-slate-200 rounded-2xl py-5 px-5 text-slate-900 text-sm focus:outline-none focus:border-blue-500 transition font-bold appearance-none">
                            <option value="">&mdash; Keep in current pen &mdash;</option>
                            @foreach($pens as $pen)
                            <option value="{{ $pen['id'] }}">{{ $pen['name'] }} ({{ $pen['type'] }})</option>
                            @endforeach
                        </select>
                        <i class='bx bx-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none'></i>
                    </div>
                    <p class="text-[10px] text-blue-400 font-bold uppercase tracking-wider text-center">Only select if moving to a different pen today.</p>
                </div>

                <!-- SECTION 6: HEALTH STATUS & SYMPTOMS -->
                <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-200 space-y-6">
                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest mb-3">Overall Health Status</p>
                        <select id="symptom" class="w-full bg-white border border-slate-200 rounded-2xl py-5 px-5 text-slate-900 text-base focus:outline-none focus:border-green-500 transition font-black">
                            <option value="Healthy">Γ£à Healthy &mdash; No Issues</option>
                            <option value="Coughing">Respiratory / Coughing</option>
                            <option value="Lethargic">Lethargic / Not Eating</option>
                            <option value="Diarrhea">Diarrhea / Loose Stool</option>
                            <option value="Lameness">Lameness / Limping</option>
                            <option value="Skin">Skin Wound / Rash</option>
                            <option value="Fever">Suspected Fever</option>
                            <option value="Other">Other &mdash; See Notes</option>
                        </select>
                    </div>

                    <div>
                        <p class="text-sm font-black text-slate-900 uppercase tracking-widest mb-3 text-slate-400">Additional Shift Notes</p>
                        <textarea id="pigNotes" rows="3" placeholder="Write any other observations here..." class="w-full bg-white border border-slate-200 rounded-2xl py-4 px-5 text-slate-900 text-base font-medium focus:outline-none focus:border-green-500 transition resize-none leading-relaxed shadow-sm"></textarea>
                    </div>
                </div>

                <div class="pt-4">
                    <button onclick="submitHealthLog()" class="w-full bg-green-600 text-white py-7 rounded-[2rem] font-black text-2xl shadow-[0_20px_40px_rgba(34,197,94,0.3)] hover:shadow-xl transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        <i class='bx bx-save text-3xl'></i> Save & Complete Assessment
                    </button>
                    <p class="text-center text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-6">Records will sync automatically once online.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let html5QrcodeScanner = null;

        function startQRScanner() {
            document.getElementById('qrModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            html5QrcodeScanner = new Html5Qrcode("qr-reader");
            html5QrcodeScanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 },
                onScanSuccess
            ).catch(err => {
                Swal.fire({ 
                    title: 'Camera Error', 
                    text: 'Unable to access your camera.', 
                    icon: 'error',
                    background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                    color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                });
                stopQRScanner();
            });
        }

        function stopQRScanner() {
            document.getElementById('qrModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            if (html5QrcodeScanner && html5QrcodeScanner.getState() !== 1) {
                html5QrcodeScanner.stop().catch(() => {});
            }
        }

        function onScanSuccess(decodedText) {
            try { stopQRScanner(); } catch (e) {}
            const rawId = decodedText.trim();
            if (rawId.startsWith('{')) {
                try {
                    const qrData = JSON.parse(rawId);
                    const type = (qrData.type || '').toUpperCase();
                    if (type === 'PIG') { openHealthModal(qrData.tag); return; }
                    if (type === 'PEN') { openFeedingModal(qrData.pen_id, qrData.pen_name); return; }
                } catch (e) {}
            }
            let id = rawId.toUpperCase();
            if (id.startsWith('PEN-') || id.startsWith('PEN')) {
                openFeedingModal(id.replace('PEN-', '').replace('PEN', '').trim());
            } else {
                openHealthModal(id);
            }
        }

        function submitManualId(source) {
            const inputEl = source === 'dash' 
                ? document.getElementById('dashManualInput')
                : document.getElementById('qrManualInput');
            const val = inputEl.value.trim();
            if (!val) {
                inputEl.focus();
                inputEl.classList.add('border-red-400');
                setTimeout(() => inputEl.classList.remove('border-red-400'), 1500);
                return;
            }
            inputEl.value = '';
            // Route via the same scan success handler
            onScanSuccess(val);
        }

        // ── Pen Preview (summary sheet before full form) ──
        let _currentPreviewPenId = null;

        function openPenPreview(id, name, type, count, sick, weight, progress, color, tag) {
            _currentPreviewPenId = id;

            // Populate fields
            document.getElementById('ppName').textContent = name;
            document.getElementById('ppType').textContent = type;
            document.getElementById('ppCount').textContent = count;
            document.getElementById('ppWeight').textContent = weight + ' kg';
            document.getElementById('ppProgress').textContent = progress + '%';

            const sickEl = document.getElementById('ppSick');
            sickEl.textContent = sick;
            sickEl.className = 'font-black text-3xl tracking-tight ' + (sick > 0 ? 'text-red-500' : 'text-slate-900');

            const tagEl = document.getElementById('ppTag');
            tagEl.textContent = tag;
            tagEl.className = `inline-block px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border mb-2 bg-${color}-50 text-${color}-600 border-${color}-100`;

            const bar = document.getElementById('ppProgressBar');
            bar.style.width = progress + '%';
            bar.className = `h-full rounded-full transition-all duration-700 bg-${color}-500`;

            // Wire the CTA button
            document.getElementById('ppLogBtn').onclick = () => {
                closePenPreview();
                setTimeout(() => openFeedingModal(id), 150);
            };

            const modal = document.getElementById('penPreviewModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePenPreview() {
            document.getElementById('penPreviewModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openFeedingModal(penId) {
            document.getElementById('targetPenId').innerText = penId;
            document.getElementById('feedingModal').classList.remove('hidden');
            document.getElementById('feedingModal').classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Reset state
            document.getElementById('targetPenTitle').innerText = 'Loading...';
            document.getElementById('penStatHealthy').innerText = '--';
            document.getElementById('penStatSick').innerText = '--';
            document.getElementById('feedQty').value = '';
            document.getElementById('penTasksSection').classList.add('hidden');
            document.getElementById('penTasksList').innerHTML = '';

            fetch(`/api/health/pen/${penId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('targetPenTitle').innerText = data.pen.name;
                        document.getElementById('penStatHealthy').innerText = data.pen.healthy_pigs;
                        document.getElementById('penStatSick').innerText = data.pen.sick_pigs;

                        // Tasks
                        const tasksSection = document.getElementById('penTasksSection');
                        const tasksList = document.getElementById('penTasksList');
                        
                        if (data.pen.tasks && data.pen.tasks.length > 0) {
                            tasksSection.classList.remove('hidden');
                            data.pen.tasks.forEach(task => {
                                const isMine = task.is_mine;
                                tasksList.insertAdjacentHTML('beforeend', `
                                    <div class="flex items-center gap-3 p-4 ${isMine ? 'bg-amber-100/50 border-amber-300' : 'bg-white border-amber-100'} border rounded-2xl hover:border-amber-400 transition cursor-pointer group shadow-sm" onclick="const cb = this.querySelector('input'); cb.checked = !cb.checked;">
                                        <input type="checkbox" class="pen-task-checkbox w-6 h-6 rounded-lg border-amber-200 text-amber-600 focus:ring-amber-500" value="${task.id}">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <p class="text-slate-900 font-black text-sm tracking-tight truncate">${task.title}</p>
                                                ${isMine ? '<span class="px-2 py-0.5 bg-amber-200 text-amber-800 text-[8px] font-black rounded-full uppercase tracking-widest">Given to you</span>' : ''}
                                            </div>
                                            <p class="text-slate-500 text-[10px] font-bold truncate">${task.description || 'Pen maintenance required'}</p>
                                        </div>
                                    </div>
                                `);
                            });
                        }
                    }
                });
        }

        function closeFeedingModal() {
            document.getElementById('feedingModal').classList.add('hidden');
            document.getElementById('feedingModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        async function submitFeedingLog() {
            const qty = document.getElementById('feedQty').value;
            const penId = document.getElementById('targetPenId').innerText;

            const completedTasks = [];
            document.querySelectorAll('.pen-task-checkbox:checked').forEach(cb => {
                completedTasks.push(cb.value);
            });

            const waterStatus = document.getElementById('penWaterStatus')?.value || 'Operational';
            const hygieneStatus = document.getElementById('penHygieneStatus')?.value || 'Clean';
            const notes = document.getElementById('penNotes')?.value || '';

            if (!qty || parseFloat(qty) <= 0) {
                Swal.fire({
                    title: 'Missing Quantity',
                    text: 'Please enter how many kg were fed.',
                    icon: 'warning',
                    background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                    color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                });
                return;
            }

            Swal.fire({
                title: 'Saving...',
                text: 'Recording feeding log...',
                allowOutsideClick: false,
                background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff',
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch('/api/health/pen/log', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pen_id: penId,
                        quantity: parseFloat(qty),
                        water_status: waterStatus,
                        hygiene_status: hygieneStatus,
                        notes: notes,
                        completed_tasks: completedTasks
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    Swal.fire({
                        title: 'Feeding Logged ✓',
                        text: `${qty}kg recorded for Pen ${penId}. Water: ${waterStatus}. Hygiene: ${hygieneStatus}.`,
                        icon: 'success',
                        background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                        color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                    });
                    closeFeedingModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    throw new Error(result.message || 'Server error');
                }
            } catch (e) {
                Swal.fire({
                    title: 'Error',
                    text: e.message || 'Failed to save. Please try again.',
                    icon: 'error',
                    background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                    color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                });
            }
        }

        const physicalCheckItems = [
            'Snout &mdash; No discharge','Eyes &mdash; Clear, bright','Ears &mdash; No redness',
            'Legs &mdash; Walking normally','Skin &mdash; No wounds/rashes','Breathing &mdash; Normal','Water &mdash; Drinking adequately',
        ];

        function openHealthModal(pigId) {
            document.getElementById('targetPigId').innerText = pigId;
            document.getElementById('healthModal').classList.remove('hidden');
            document.getElementById('healthModal').classList.add('flex');
            document.body.style.overflow = 'hidden';

            document.getElementById('pigInfoPenBadge').innerText = 'Pen: --';
            document.getElementById('pigTasksSection').classList.add('hidden');
            document.getElementById('pigTasksList').innerHTML = '';
            document.getElementById('selectedBCS').value = '3';
            document.getElementById('selectedFeedBehavior').value = 'Normal';
            document.getElementById('val-water').value = 'Operational';
            document.getElementById('pigWeight').value = '';
            document.getElementById('pigNotes').value = '';
            document.getElementById('symptom').value = 'Healthy';

            document.querySelectorAll('.bcs-btn').forEach(b => {
                b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
                if (b.innerText.trim() === '3') b.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            });
            document.querySelectorAll('.feed-btn').forEach(b => {
                b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
                if (b.innerText.trim().includes('Normal')) b.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            });

            const container = document.getElementById('physicalChecklist');
            container.innerHTML = '';
            physicalCheckItems.forEach((item, i) => {
                container.insertAdjacentHTML('beforeend', `
                    <div onclick="toggleCheck(this)" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100 transition cursor-pointer group">
                        <input type="checkbox" class="w-5 h-5 rounded border-slate-300 text-green-600 focus:ring-green-500 pointer-events-none" id="pcheck-${i}">
                        <span class="text-slate-600 text-sm font-bold group-hover:text-slate-900">${item}</span>
                    </div>
                `);
            });

            fetch(`/api/health/pig/${pigId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('pigInfoPenBadge').innerText = `Pen: ${data.pig.pen_name}`;
                        document.getElementById('targetPigId').dataset.pigDatabaseId = data.pig.id;

                        // Populate Tasks
                        const tasksSection = document.getElementById('pigTasksSection');
                        const tasksList = document.getElementById('pigTasksList');
                        
                        if (data.pig.tasks && data.pig.tasks.length > 0) {
                            tasksSection.classList.remove('hidden');
                            data.pig.tasks.forEach(task => {
                                const isMine = task.is_mine;
                                tasksList.insertAdjacentHTML('beforeend', `
                                    <div class="flex items-center gap-3 p-4 ${isMine ? 'bg-amber-100/50 border-amber-300' : 'bg-white border-amber-100'} border rounded-2xl hover:border-amber-400 transition cursor-pointer group shadow-sm" onclick="const cb = this.querySelector('input'); cb.checked = !cb.checked;">
                                        <input type="checkbox" class="task-checkbox w-6 h-6 rounded-lg border-amber-200 text-amber-600 focus:ring-amber-500" value="${task.id}">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-0.5">
                                                <p class="text-slate-900 font-black text-sm tracking-tight truncate">${task.title}</p>
                                                ${isMine ? '<span class="px-2 py-0.5 bg-amber-200 text-amber-800 text-[8px] font-black rounded-full uppercase tracking-widest">Given to you</span>' : ''}
                                            </div>
                                            <p class="text-slate-500 text-[10px] font-bold truncate">${task.description || 'Special care required'}</p>
                                        </div>
                                    </div>
                                `);
                            });
                        }
                    }
                });
        }

        function toggleCheck(el) {
            const cb = el.querySelector('input');
            cb.checked = !cb.checked;
            el.classList.toggle('border-green-200', cb.checked);
            el.classList.toggle('bg-green-50\/50', cb.checked);
        }

        function setBCS(btn, score) {
            document.querySelectorAll('.bcs-btn').forEach(b => b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700'));
            btn.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            document.getElementById('selectedBCS').value = score;
        }

        function setFeedBehavior(btn, val) {
            document.querySelectorAll('.feed-btn').forEach(b => b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700'));
            btn.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            document.getElementById('selectedFeedBehavior').value = val;
        }

        function closeHealthModal() {
            document.getElementById('healthModal').classList.add('hidden');
            document.getElementById('healthModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        async function submitHealthLog() {
            const pigId = document.getElementById('targetPigId').dataset.pigDatabaseId || document.getElementById('targetPigId').innerText;
            const physicalChecks = [];
            document.querySelectorAll('#physicalChecklist input[type="checkbox"]:checked').forEach(cb => {
                physicalChecks.push(cb.nextElementSibling.innerText);
            });

            const completedTasks = [];
            document.querySelectorAll('.task-checkbox:checked').forEach(cb => {
                completedTasks.push(cb.value);
            });

            const data = {
                pig_id: pigId,
                symptom: document.getElementById('symptom').value,
                weight: document.getElementById('pigWeight').value,
                body_condition_score: document.getElementById('selectedBCS').value,
                feeding_behavior: document.getElementById('selectedFeedBehavior').value,
                water_intake: document.getElementById('val-water').value,
                notes: document.getElementById('pigNotes').value,
                move_pen_id: document.getElementById('movePenId').value,
                physical_checks: physicalChecks,
                completed_tasks: completedTasks
            };

            Swal.fire({ 
                title: 'Saving Report...', 
                allowOutsideClick: false, 
                background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff',
                didOpen: () => { Swal.showLoading(); } 
            });

            try {
                const response = await fetch('/api/health/report', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    Swal.fire({ 
                        title: 'Report Saved', 
                        text: 'Farm database updated successfully.', 
                        icon: 'success',
                        background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                        color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                    });
                    closeHealthModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    const err = await response.json();
                    Swal.fire({ 
                        title: 'Error', 
                        text: err.message || 'Failed to save report', 
                        icon: 'error',
                        background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                        color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                    });
                }
            } catch (e) {
                Swal.fire({ 
                    title: 'Error', 
                    text: 'Network error', 
                    icon: 'error',
                    background: document.body.classList.contains('light-theme') ? '#ffffff' : '#0b1120',
                    color: document.body.classList.contains('light-theme') ? '#0f172a' : '#ffffff'
                });
            }
        }

        // Handle manual scan redirect from other pages
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const manualScan = urlParams.get('manual_scan');
            if (manualScan) {
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
                setTimeout(() => {
                    if (typeof onScanSuccess === 'function') {
                        onScanSuccess(manualScan);
                    }
                }, 400);
            }
        });
    </script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards; }
    </style>
@endsection
