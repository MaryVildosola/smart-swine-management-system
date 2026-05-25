@extends('layouts.worker')

@section('content')
<style>
/* --- Light theme: high-contrast overrides for reports page --- */
body.light-theme .worker-dash { background: #f1f5f9 !important; }

/* Text */
body.light-theme .worker-dash .text-white { color: #0f172a !important; }
body.light-theme .worker-dash .text-white\/30,
body.light-theme .worker-dash .text-white\/40 { color: #94a3b8 !important; }
body.light-theme .worker-dash .text-white\/50,
body.light-theme .worker-dash .text-white\/60 { color: #64748b !important; }
body.light-theme .worker-dash .text-white\/70,
body.light-theme .worker-dash .text-white\/80 { color: #475569 !important; }

/* Card backgrounds — solid white with visible borders & shadows */
body.light-theme .worker-dash .bg-white\/5,
body.light-theme .worker-dash .bg-white\/10,
body.light-theme .worker-dash .bg-white\/15,
body.light-theme .worker-dash .glass-panel,
body.light-theme .worker-dash .backdrop-blur-xl {
    background: #ffffff !important;
    border-color: #e2e8f0 !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
    backdrop-filter: none !important;
}

/* Specific Panel Fixes */
body.light-theme .worker-dash .glass-panel {
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.2) !important;
}

body.light-theme #reportModal,
body.light-theme #careAlertModal {
    background: rgba(15, 23, 42, 0.8) !important;
}

/* Borders */
body.light-theme .worker-dash .border-white\/10,
body.light-theme .worker-dash .border-white\/5,
body.light-theme .worker-dash .border-white\/20 {
    border-color: #e2e8f0 !important;
}

/* Hover states */
body.light-theme .worker-dash .hover\:bg-white\/5:hover,
body.light-theme .worker-dash .hover\:bg-white\/10:hover,
body.light-theme .worker-dash .hover\:bg-white\/15:hover,
body.light-theme .worker-dash .hover\:bg-white\/20:hover {
    background: #f8fafc !important;
}

/* Success/Warning Badges in Light Mode */
body.light-theme .worker-dash .bg-green-500\/5,
body.light-theme .worker-dash .bg-green-500\/10,
body.light-theme .worker-dash .bg-green-500\/15 { background: #f0fdf4 !important; }
body.light-theme .worker-dash .text-green-400,
body.light-theme .worker-dash .text-green-300 { color: #166534 !important; }
body.light-theme .worker-dash .border-green-500\/15,
body.light-theme .worker-dash .border-green-500\/30 { border-color: #bbf7d0 !important; }

body.light-theme .worker-dash .bg-yellow-500\/5,
body.light-theme .worker-dash .bg-yellow-500\/10,
body.light-theme .worker-dash .bg-yellow-500\/15 { background: #fffbeb !important; }
body.light-theme .worker-dash .text-yellow-400,
body.light-theme .worker-dash .text-yellow-300 { color: #92400e !important; }
body.light-theme .worker-dash .border-yellow-500\/25,
body.light-theme .worker-dash .border-yellow-500\/30 { border-color: #fde68a !important; }

body.light-theme .worker-dash .bg-red-500\/5,
body.light-theme .worker-dash .bg-red-500\/10,
body.light-theme .worker-dash .bg-red-500\/20 { background: #fef2f2 !important; }
body.light-theme .worker-dash .text-red-400,
body.light-theme .worker-dash .text-red-300 { color: #991b1b !important; }
body.light-theme .worker-dash .border-red-500\/20,
body.light-theme .worker-dash .border-red-500\/30 { border-color: #fecaca !important; }

body.light-theme .worker-dash .bg-blue-500\/10 { background: #eff6ff !important; }
body.light-theme .worker-dash .text-blue-400 { color: #1e40af !important; }

/* Input field overrides */
body.light-theme .worker-dash input,
body.light-theme .worker-dash textarea {
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #0f172a !important;
}

body.light-theme .worker-dash input::placeholder,
body.light-theme .worker-dash textarea::placeholder {
    color: #94a3b8 !important;
}

/* Status buttons in care modal */
body.light-theme .worker-dash .status-btn {
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
}

body.light-theme .worker-dash .status-btn i {
    opacity: 0.8;
}

/* Modal specific overrides */
body.light-theme #careAlertModal .bg-slate-900\/90 {
    background: rgba(15, 23, 42, 0.4) !important;
}
</style>
<div class="worker-dash min-h-screen">
<div class="p-5 md:p-10 max-w-full">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-white/40 mb-1 uppercase tracking-widest">Worker Report</p>
                <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">Weekly Report</h1>
                <p class="text-white/40 text-sm mt-2 font-medium">
                    {{ \Carbon\Carbon::parse($thisWeek)->format('M d') }} — {{ \Carbon\Carbon::parse($thisWeek)->endOfWeek()->format('M d, Y') }}
                </p>
            </div>
        </div>
        
        <!-- Status Alert Box (Moved here to avoid overlap) -->
        <div class="mt-6 inline-flex items-center gap-3 px-5 py-3 rounded-2xl border {{ $existingReport ? 'border-green-500/30 bg-green-500/10' : 'border-yellow-500/30 bg-yellow-500/10' }} shadow-xl shadow-black/5">
            <i class='bx {{ $existingReport ? "bxs-badge-check text-green-400" : "bx-time text-yellow-400" }} text-2xl'></i>
            <div>
                <p class="text-white font-black text-base">{{ $existingReport ? 'Submitted' : 'Pending Submission' }}</p>
                <p class="text-white/40 text-xs">{{ $existingReport ? 'HQ received this week\'s report' : 'Submit before end of week' }}</p>
            </div>
        </div>
    </div>
    
    @if($errors->any())
    <div class="flex items-center gap-4 p-5 rounded-2xl bg-red-500/15 border border-red-500/30 mb-8">
        <i class='bx bxs-error-circle text-3xl text-red-400 shrink-0'></i>
        <div>
            <p class="text-white font-bold text-base">Submission Error</p>
            <p class="text-white/50 text-sm">Please ensure all required fields are filled correctly (e.g. notes must be at least 5 characters).</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="flex items-center gap-4 p-5 rounded-2xl bg-green-500/15 border border-green-500/30 mb-8">
        <i class='bx bxs-check-shield text-3xl text-green-400 shrink-0'></i>
        <div>
            <p class="text-white font-bold text-base">Report Sent to HQ Successfully</p>
            <p class="text-white/50 text-sm">Your weekly report has been transmitted and recorded.</p>
        </div>
    </div>
    @endif

    <!-- Stats Strip -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8">
        <div class="glass-panel p-4 rounded-2xl">
            <p class="text-white/40 text-xs font-bold uppercase tracking-widest mb-1">Total Pigs</p>
            <p class="text-3xl font-black text-white">{{ $analytics['total_pigs'] }}</p>
        </div>
        <div class="glass-panel p-4 rounded-2xl border-l-4 border-red-500/40">
            <p class="text-white/40 text-xs font-bold uppercase tracking-widest mb-1">Health Alerts</p>
            <p class="text-3xl font-black text-red-400">{{ $analytics['sick_pigs'] }}</p>
        </div>
        <div class="glass-panel p-4 rounded-2xl">
            <p class="text-white/40 text-xs font-bold uppercase tracking-widest mb-1">Avg. Weight</p>
            <p class="text-3xl font-black text-blue-400">{{ $analytics['avg_weight'] }}<span class="text-base ml-1">kg</span></p>
        </div>
        <div class="glass-panel p-4 rounded-2xl">
            <p class="text-white/40 text-xs font-bold uppercase tracking-widest mb-1">Pens Active</p>
            <p class="text-3xl font-black text-emerald-400">{{ $pens->count() }}</p>
        </div>
    </div>

    <!-- ===== PENS & PIGS LIST ===== -->
    @php
        $statusConfig = [
            'green'   => ['badge' => 'bg-green-500/20 text-green-300 border-green-500/30',   'bar' => 'bg-green-500',   'penBorder' => 'border-green-500/20'],
            'yellow'  => ['badge' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30', 'bar' => 'bg-yellow-500',  'penBorder' => 'border-yellow-500/20'],
            'emerald' => ['badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30','bar' => 'bg-emerald-500','penBorder' => 'border-emerald-500/20'],
            'Good'    => ['badge' => 'bg-green-500/20 text-green-300 border-green-500/30',   'bar' => 'bg-green-500',   'penBorder' => 'border-green-500/20'],
            'Fair'    => ['badge' => 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30', 'bar' => 'bg-yellow-500',  'penBorder' => 'border-yellow-500/20'],
            'Excellent' => ['badge' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30','bar' => 'bg-emerald-500','penBorder' => 'border-emerald-500/20'],
        ];
    @endphp

    <h2 class="text-xl font-black text-white mb-5">All Pens & Pigs</h2>
    <div class="space-y-5 mb-10" id="pensList">

        @foreach($pens as $pen)
        @php
            $sc = $statusConfig[$pen->status] ?? $statusConfig['Good'];
            $sickCount = $pen->pigs->where('health_status', 'Sick')->count();
            $avgW = $pen->pigs->avg('weight') ?? 0;
        @endphp

        <div class="glass-panel rounded-2xl border {{ $sc['penBorder'] }} overflow-hidden">

            <!-- Pen Header (Tap to expand) -->
            <button onclick="togglePen({{ $pen->id }})"
                class="w-full flex items-center gap-4 p-5 text-left hover:bg-white/5 transition active:scale-[0.99]">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <h3 class="text-xl font-black text-white">{{ $pen->name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $sc['badge'] }}">{{ $pen->status }}</span>
                        @if($sickCount > 0)
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-red-500/20 text-red-300 border border-red-500/30">{{ $sickCount }} sick</span>
                        @endif
                    </div>
                    <p class="text-white/40 text-xs font-semibold">{{ $pen->section }} · {{ $pen->pigs->count() }} pigs · Avg weight {{ round($avgW) }}kg</p>
                </div>
                <div class="text-right shrink-0 mr-2">
                    <p class="text-white/30 text-xs font-semibold mb-1">Progress</p>
                    <p class="text-white font-black text-lg">{{ $pen->progress }}%</p>
                </div>
                <i class='bx bx-chevron-down text-white/40 text-2xl transition-transform duration-300' id="pen-chevron-{{ $pen->id }}"></i>
            </button>

            <!-- Progress Bar -->
            <div class="w-full h-1.5 bg-white/5">
                <div class="{{ $sc['bar'] }} h-full transition-all" style="width:{{ $pen['progress'] }}%"></div>
            </div>

            <div id="pen-body-{{ $pen->id }}" class="hidden">
                <div class="p-4 space-y-3">
                    @foreach($pen->pigs as $pig)
                    @php
                        $isSick   = $pig->health_status !== 'Healthy';
                        $condColor = $isSick ? 'text-red-300' : 'text-green-300';
                        $condBg    = $isSick ? 'bg-red-500/10 border-red-500/20' : 'bg-green-500/10 border-green-500/20';
                        $feedColor = $pig->feeding_status === 'Poor' ? 'text-red-400' : ($pig->feeding_status === 'Active' ? 'text-green-400' : 'text-blue-400');
                    @endphp

                    <!-- Individual Pig Row — tap to see full details -->
                    <div class="rounded-xl border {{ $condBg }} cursor-pointer overflow-hidden">

                        <div class="flex items-center gap-3 p-3 hover:bg-white/5 transition active:scale-[0.99]" onclick="togglePig('pig-{{ $pen->id }}-{{ $pig->tag }}')">
                            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <i class='bx bxs-circle text-lg {{ $condColor }}'></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <p class="text-white font-black text-sm">Ear Tag #{{ $pig->tag }}</p>
                    @php $pendingAlert = $pig->activities->where('is_critical_alert', true)->whereNull('acknowledged_at')->first(); @endphp
                    @if($pendingAlert)
                    <span class="px-2 py-0.5 bg-red-500/20 text-red-400 text-[9px] font-black uppercase tracking-tighter rounded border border-red-500/30 animate-pulse">Waiting for Update</span>
                    @endif
                                </div>
                                <p class="text-white/40 text-xs">{{ $pig->health_status }} · {{ $pig->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-white font-bold text-sm">{{ $pig->weight }}kg</p>
                                <p class="text-white/30 text-[10px]">BCS {{ $pig->bcs_score }}</p>
                            </div>
                            <i class='bx bx-chevron-down text-white/30 text-lg ml-1 transition-transform duration-300' id="pig-chevron-pig-{{ $pen->id }}-{{ $pig->tag }}"></i>
                        </div>

                        <!-- Expanded Pig Details -->
                        <div id="pig-pig-{{ $pen->id }}-{{ $pig->tag }}" class="hidden border-t border-white/10">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 p-4">
                                <div class="bg-white/5 rounded-xl p-3 text-center">
                                    <p class="text-white/30 text-[9px] uppercase font-black mb-1">Condition</p>
                                    <p class="font-black text-sm {{ $condColor }}">{{ $pig->health_status }}</p>
                                </div>
                                <div class="bg-white/5 rounded-xl p-3 text-center">
                                    <p class="text-white/30 text-[9px] uppercase font-black mb-1">Feeding</p>
                                    <p class="font-black text-sm {{ $feedColor }}">{{ $pig->feeding_status }}</p>
                                </div>
                                <div class="bg-white/5 rounded-xl p-3 text-center">
                                    <p class="text-white/30 text-[9px] uppercase font-black mb-1">Weight</p>
                                    <p class="text-white font-black text-sm">{{ $pig->weight }} kg</p>
                                </div>
                                <div class="bg-white/5 rounded-xl p-3 text-center">
                                    <p class="text-white/30 text-[9px] uppercase font-black mb-1">BCS Score</p>
                                    <p class="text-white font-black text-sm">{{ $pig->bcs_score }} / 5</p>
                                </div>
                            </div>
                            @if($isSick)
                            <div class="mx-4 mb-4 p-3 rounded-xl bg-red-500/10 border border-red-500/20">
                                <div class="flex items-center gap-2 mb-3">
                                    <i class='bx bx-error text-red-400 text-lg shrink-0 mt-0.5'></i>
                                    <p class="text-red-500 text-xs font-semibold">This pig has been flagged. Status: <strong>{{ $pig->health_status }}</strong>. Feeding: <strong>{{ $pig->feeding_status }}</strong>.</p>
                                </div>
                                @php 
                                    $recentAck = $pig->activities->where('is_critical_alert', true)
                                                                ->whereNotNull('acknowledged_at')
                                                                ->sortByDesc('acknowledged_at')
                                                                ->first();
                                @endphp

                                @if($recentAck && $recentAck->admin_response)
                                    <div class="mb-3 p-3 rounded-xl bg-green-950/20 border border-green-500/20">
                                        <div class="flex items-start gap-2 mb-1">
                                            <i class='bx bxs-info-circle text-green-400 text-xs mt-0.5'></i>
                                            <p class="text-[9px] font-black uppercase text-green-400">Admin Response:</p>
                                        </div>
                                        <p class="text-white/80 text-xs italic">"{{ $recentAck->admin_response }}"</p>
                                        <div class="mt-2 flex gap-2">
                                            <span class="text-[8px] px-1.5 py-0.5 bg-green-500/10 text-green-300 rounded border border-green-500/20 font-bold uppercase">{{ $recentAck->new_health_status ?? 'Updated' }}</span>
                                            <span class="text-[8px] px-1.5 py-0.5 bg-blue-500/10 text-blue-300 rounded border border-blue-500/20 font-bold uppercase">Feed: {{ $recentAck->new_feeding_status ?? 'Updated' }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if($pendingAlert)
                                    <div class="mb-3 p-3 rounded-xl bg-red-950/20 border border-red-500/20">
                                        <p class="text-[9px] font-black uppercase text-red-400 mb-1">Last Sent to Admin:</p>
                                        <p class="text-white/60 text-xs italic">"{{ $pendingAlert->details }}"</p>
                                        <p class="text-[9px] text-red-500/60 mt-2 font-bold uppercase">Reported {{ $pendingAlert->created_at->diffForHumans() }}</p>
                                    </div>
                                    <button type="button" disabled
                                        class="w-full flex items-center justify-center gap-2 bg-red-600/20 text-white/30 py-3 rounded-xl border border-red-900/30 font-black text-xs uppercase tracking-widest cursor-not-allowed">
                                        <i class='bx bxs-time text-base'></i> Alert Pending Update
                                    </button>
                                @else
                                    <button type="button" 
                                        onclick="event.stopPropagation(); togglePig('{{ $pig->id }}')"
                                        class="flex items-center gap-2 bg-white/5 hover:bg-white/10 px-4 py-2 rounded-xl transition-all border border-white/10">
                                        <i id="pig-chevron-{{ $pig->id }}" class='bx bx-history text-blue-400'></i>
                                        <span class="text-white font-bold text-xs uppercase">History</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Pig Detail / History (Collapsible) -->
                        <div id="pig-{{ $pig->id }}" class="hidden px-5 pb-5 animate-slide-down">
                            <div class="space-y-3 pt-3 border-t border-white/5">
                                <p class="text-[9px] font-black uppercase text-blue-400 tracking-widest pl-1 mb-2">Pig Lifecycle & History</p>
                                
                                @forelse($pig->activities->sortByDesc('created_at')->take(5) as $activity)
                                    <div class="p-3 rounded-2xl bg-white/5 border border-white/10">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex items-center gap-2">
                                                <i class='bx {{ $activity->type === "Medical" ? "bx-plus-medical text-red-400" : "bx-check-circle text-green-400" }} text-xs'></i>
                                                <span class="text-white font-bold text-[10px] uppercase">{{ $activity->action }}</span>
                                            </div>
                                            <span class="text-white/30 text-[8px]">{{ $activity->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-white/60 text-xs italic">"{{ $activity->details }}"</p>
                                        
                                        @if($activity->admin_response)
                                            <div class="mt-2 p-2 rounded-lg bg-green-500/10 border border-green-500/20">
                                                <p class="text-[8px] font-black text-green-400 uppercase mb-1">Admin Response</p>
                                                <p class="text-white/80 text-[10px]">{{ $activity->admin_response }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-white/20 text-[10px] italic py-2">No recent activities found.</p>
                                @endforelse
                                
                                <div class="p-4 rounded-2xl bg-white/5 border border-white/10 mt-4">
                                    <button type="button"
                                        onclick="event.stopPropagation(); openCareAlertModal('{{ $pig->id }}', '{{ $pig->tag }}')"
                                        class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 active:scale-[0.98] text-white py-3 rounded-xl border border-red-700 transition font-black text-xs uppercase tracking-widest shadow-[0_4px_15px_rgba(220,38,38,0.4)] hover:shadow-[0_6px_20px_rgba(220,38,38,0.6)]">
                                        <i class='bx bxs-bell-ring text-base'></i> 🚨 Alert Admin &amp; Log Care
                                    </button>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <!-- Additional logs from localStorage for this pen -->
                    <div id="pen-local-logs-{{ $pen->id }}" class="space-y-2 mt-2"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Weekly Task Activity & History -->
    <h2 class="text-xl font-black text-white mb-5 flex items-center gap-2">
        <i class='bx bx-history text-blue-400'></i>
        Weekly Task Activity
    </h2>
    <div class="glass-panel rounded-2xl border border-white/10 overflow-hidden mb-10">
        <div class="p-5 border-b border-white/10 bg-white/5 flex justify-between items-center">
            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest">Protocol Execution History</p>
            <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">{{ $weeklyTasks->count() }} Tasks</span>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($weeklyTasks as $wTask)
                <div class="p-5 hover:bg-white/5 transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                <i class='bx {{ $wTask->status === "completed" ? "bx-check-circle text-green-400" : "bx-time text-yellow-400" }} text-xl'></i>
                            </div>
                            <div>
                                <h4 class="text-white font-black text-sm">{{ $wTask->title }}</h4>
                                <p class="text-white/30 text-[10px] font-bold uppercase tracking-tighter">
                                    {{ $wTask->status }} · Progress: {{ $wTask->progress }}% · {{ $wTask->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="px-3 py-1 bg-white/5 rounded-full text-[10px] font-black text-white/40 border border-white/10 uppercase tracking-widest">
                                {{ $wTask->priority }}
                            </div>
                        </div>
                    </div>
                    
                    @if($wTask->findings && count($wTask->findings) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach($wTask->findings as $finding)
                                <div class="bg-white/5 rounded-xl p-3 border border-white/5">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class='bx bx-radio-circle-marked text-blue-400'></i>
                                        <p class="text-[9px] font-black text-white/40 uppercase tracking-tighter">{{ Str::limit($finding['text'], 20) }}</p>
                                    </div>
                                    <p class="text-white/70 text-[11px] font-medium italic">"{{ $finding['finding'] ?: 'No findings recorded.' }}"</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-white/20 text-xs italic">No protocol tasks recorded this week.</p>
                </div>
            @endforelse
        </div>

        <!-- NEW: General Activity Logs -->
        <div class="p-5 border-t border-b border-white/10 bg-white/5 flex justify-between items-center">
            <p class="text-white/40 text-[10px] font-black uppercase tracking-widest">Daily Care & Health Logs</p>
            <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest">{{ $weeklyActivities->count() }} Logs</span>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($weeklyActivities as $act)
                <div class="p-5 hover:bg-white/5 transition flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                        <i class='bx {{ $act->type === "Medical" ? "bx-plus-medical text-red-400" : "bx-check-double text-green-400" }} text-xl'></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div>
                                <h4 class="text-white font-black text-sm uppercase tracking-tight">
                                    @if($act->pig)
                                        Pig #{{ $act->pig->tag }} · 
                                    @endif
                                    {{ $act->action }}
                                </h4>
                                <p class="text-white/30 text-[9px] font-bold uppercase tracking-widest">{{ $act->created_at->format('M d, Y · h:i A') }} ({{ $act->created_at->diffForHumans() }})</p>
                            </div>
                            @if($act->is_critical_alert)
                                <span class="px-2 py-0.5 bg-red-500/20 text-red-400 text-[8px] font-black uppercase rounded border border-red-500/30">Critical Alert</span>
                            @endif
                        </div>
                        <p class="text-white/60 text-xs leading-relaxed italic">"{{ $act->details ?: 'No additional details provided.' }}"</p>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <i class='bx bx-history text-white/10 text-5xl mb-3'></i>
                    <p class="text-white/30 text-sm font-medium">No general care activities recorded yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Feed & Notes -->
    @if(!$existingReport)
    <div class="glass-panel rounded-2xl border border-white/10 p-6 mb-8">
        <h3 class="text-base font-black text-white mb-5">Report Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Total Feed Consumed This Week (Auto-calculated)</label>
                <div class="relative">
                    <input type="number" id="reportFeedKg" value="{{ $analytics['feed_consumed_this_week'] }}" readonly
                        class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 pl-5 pr-14 text-white/60 text-2xl font-black focus:outline-none cursor-not-allowed transition">
                    <span class="absolute right-5 top-1/2 -translate-y-1/2 text-white/30 font-bold">kg</span>
                </div>
            </div>
            <div>
                <label class="block text-xs font-black text-white/40 uppercase tracking-widest mb-2">Operational Notes</label>
                <textarea id="reportNotes" rows="3" placeholder="Any notable observations, mortalities, or infrastructure issues this week..."
                    class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-5 text-white text-sm font-medium focus:outline-none focus:border-green-500/50 transition resize-none leading-relaxed"></textarea>
            </div>
        </div>
    </div>

    <!-- Generate Report Button -->
    <button onclick="generateReport()"
        class="w-full flex items-center justify-center gap-4 py-6 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-500 text-white font-black text-xl hover:shadow-[0_15px_40px_rgba(34,197,94,0.35)] transition active:scale-[0.98] mb-10">
        <i class='bx bx-file-find text-3xl'></i>
        Generate Weekly Report
    </button>

    @else
    <div class="flex items-center gap-4 p-5 rounded-2xl bg-green-500/10 border border-green-500/30 mb-10">
        <i class='bx bxs-badge-check text-3xl text-green-400 shrink-0'></i>
        <div>
            <p class="text-white font-black text-base">Report already submitted for this week</p>
            <p class="text-white/40 text-sm">Revision is locked. A new report can be submitted next week.</p>
        </div>
    </div>
    @endif

</div>
</div>

<!-- ===== REPORT PREVIEW MODAL ===== -->
<div id="reportModal" class="fixed inset-0 z-[220] hidden bg-slate-900/80 backdrop-blur-md flex items-start justify-center p-4 overflow-y-auto">
    <div class="glass-panel w-full max-w-lg rounded-3xl shadow-2xl my-6 overflow-hidden border border-white/10">

        <!-- Modal Header -->
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-white/5">
            <div>
                <h2 class="text-2xl font-black text-white">Report Preview</h2>
                <p class="text-white/40 text-xs font-semibold mt-0.5">Review carefully before submitting to HQ</p>
            </div>
            <button onclick="closeReport()" class="w-12 h-12 rounded-2xl bg-white/5 text-white flex items-center justify-center hover:bg-white/10 transition text-2xl">
                <i class='bx bx-x'></i>
            </button>
        </div>

        <!-- Report Content -->
        <div class="p-6 space-y-5" id="reportContent">
            <!-- Populated by JS -->
        </div>

        <!-- Action Buttons -->
        <div class="p-6 pt-0 space-y-3">
            <button onclick="submitReport()"
                class="w-full flex items-center justify-center gap-3 py-5 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 text-white font-black text-lg hover:shadow-[0_10px_30px_rgba(34,197,94,0.3)] transition active:scale-[0.98]">
                <i class='bx bxs-cloud-upload text-2xl'></i>
                Submit to HQ
            </button>
            <button onclick="closeReport()"
                class="w-full py-4 rounded-2xl bg-white/5 text-white/50 font-bold text-sm hover:bg-white/10 transition">
                Go Back & Edit
            </button>
        </div>
        </div>
    </div>
</div>

<!-- Admin Alert & Care Modal -->
<div id="careAlertModal" class="fixed inset-0 z-[230] hidden bg-slate-900/80 backdrop-blur-md items-center justify-center p-4 overflow-y-auto">
    <div class="glass-panel w-full max-w-lg rounded-[2rem] shadow-2xl overflow-hidden my-auto border border-white/10" onclick="event.stopPropagation()">

        <!-- Header -->
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-black text-white flex items-center gap-2"><i class='bx bxs-shield-x text-red-400'></i> Care & Alert Log</h2>
                <p class="text-white/40 text-xs mt-0.5">Pig #<span id="carePigTag" class="text-red-300 font-bold"></span></p>
            </div>
            <button onclick="closeCareModal()" class="w-10 h-10 rounded-xl bg-white/5 text-white/50 hover:bg-white/10 transition flex items-center justify-center"><i class='bx bx-x text-xl'></i></button>
        </div>

        <!-- Section 1: Assigned Tasks for this pig -->
        <div class="p-5 border-b border-white/10">
            <p class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-3">Assigned Tasks for This Pig</p>
            <div id="pigTaskList" class="space-y-2 max-h-36 overflow-y-auto pr-1">
                <p id="pigTaskEmpty" class="text-white/30 text-xs italic">No pending tasks assigned to you for this pig.</p>
            </div>
        </div>

        <!-- Section 2: Status & Care Log -->
        <div class="p-5 border-b border-white/10">
            <input type="hidden" id="carePigId">
            <p class="text-[10px] font-black uppercase tracking-widest text-white/40 mb-3">Current Status</p>
            <div class="grid grid-cols-2 gap-3 mb-5">
                <button type="button" id="statusActionTaken" onclick="selectStatus('action_taken')"
                    class="status-btn flex flex-col items-center gap-2 py-4 px-3 rounded-2xl border-2 border-white/10 bg-white/5 hover:border-green-500 transition">
                    <i class='bx bxs-check-shield text-3xl text-green-400'></i>
                    <span class="font-black text-sm text-white">Action Taken</span>
                    <span class="text-white/40 text-[10px] text-center">Care already given, logging for records</span>
                </button>
                <button type="button" id="statusInDanger" onclick="selectStatus('in_danger')"
                    class="status-btn flex flex-col items-center gap-2 py-4 px-3 rounded-2xl border-2 border-white/10 bg-white/5 hover:border-red-500 transition">
                    <i class='bx bxs-error text-3xl text-red-400'></i>
                    <span class="font-black text-sm text-white">Health In Danger</span>
                    <span class="text-white/40 text-[10px] text-center">Critical — alert admin immediately</span>
                </button>
            </div>

            <label class="block text-[10px] font-black text-white/40 uppercase tracking-widest mb-2">Details / Care Provided</label>
            <textarea id="careDetails" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl py-3 px-4 text-white text-sm focus:outline-none focus:border-red-500 transition resize-none" placeholder="Describe symptoms, medication given, or observations..."></textarea>
        </div>

        <!-- Actions -->
        <div class="p-5 flex gap-3">
            <button type="button" onclick="closeCareModal()" class="flex-1 py-3 rounded-xl bg-white/5 text-white/50 font-bold hover:bg-white/10 transition">Cancel</button>
            <button type="button" onclick="submitCareAlert()" id="sendAlertBtn"
                class="flex-[2] py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-black transition shadow-[0_4px_15px_rgba(220,38,38,0.4)] disabled:opacity-50">
                Submit
            </button>
        </div>
    </div>
</div>

<!-- Hidden Submission Form -->
<form id="hiddenReportForm" action="{{ route('worker.reports.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="total_pigs"    value="{{ $analytics['total_pigs'] }}">
    <input type="hidden" name="sick_pigs"     value="{{ $analytics['sick_pigs'] }}">
    <input type="hidden" name="avg_weight"    value="{{ $analytics['avg_weight'] }}">
    <input type="hidden" name="feed_consumed" id="hiddenFeed">
    <input type="hidden" name="details"       id="hiddenNotes">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ---- Pig Task Data (from server) ----
    const pigTaskData = {};
    @foreach($pens as $pen)
        @foreach($pen->pigs as $pig)
        pigTaskData['{{ $pig->id }}'] = [
            @foreach($pig->tasks as $task)
            { id: {{ $task->id }}, title: {!! json_encode($task->title) !!}, due: {!! json_encode($task->due_date ? $task->due_date->format('M d') : 'No due date') !!}, status: {!! json_encode($task->status) !!} },
            @endforeach
        ];
        @endforeach
    @endforeach

    // ---- SWAL THEME HELPER ----
    function getSwalConfig() {
        const isLight = document.body.classList.contains('light-theme');
        return {
            background: isLight ? '#ffffff' : '#070e08',
            color: isLight ? '#0f172a' : '#fff',
            confirmButtonColor: '#22c55e'
        };
    }

    // ---- Care Alert Modal ----
    function openCareAlertModal(pigId, pigTag) {
        selectedStatus = null;
        document.getElementById('carePigId').value = pigId;
        document.getElementById('carePigTag').innerText = pigTag;
        document.getElementById('careDetails').value = '';
        // Reset status buttons
        document.querySelectorAll('.status-btn').forEach(b => {
            b.classList.remove('border-green-500','bg-green-500/10','border-red-500','bg-red-500/10');
            b.classList.add('border-white/10','bg-white/5');
        });
        // Populate tasks
        const list = document.getElementById('pigTaskList');
        const empty = document.getElementById('pigTaskEmpty');
        const tasks = pigTaskData[pigId] || [];
        list.querySelectorAll('.task-item').forEach(el => el.remove());
        if(tasks.length > 0) {
            empty.classList.add('hidden');
            tasks.forEach(t => {
                list.insertAdjacentHTML('beforeend', `
                <div class="task-item flex items-start gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                    <i class='bx bx-task text-blue-400 text-lg shrink-0 mt-0.5'></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-bold truncate">${t.title}</p>
                        <p class="text-white/40 text-[10px]">${t.status} · Due: ${t.due}</p>
                    </div>
                </div>`);
            });
        } else {
            empty.classList.remove('hidden');
        }
        const modal = document.getElementById('careAlertModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function selectStatus(status) {
        selectedStatus = status;
        const btnAction = document.getElementById('statusActionTaken');
        const btnDanger = document.getElementById('statusInDanger');
        // Reset both
        [btnAction, btnDanger].forEach(b => {
            b.classList.remove('border-green-500','bg-green-500/10','border-red-500','bg-red-500/10');
            b.classList.add('border-white/10','bg-white/5');
        });
        if(status === 'action_taken') {
            btnAction.classList.remove('border-white/10','bg-white/5');
            btnAction.classList.add('border-green-500','bg-green-500/10');
            document.getElementById('sendAlertBtn').textContent = 'Log Care Record';
            document.getElementById('sendAlertBtn').className = document.getElementById('sendAlertBtn').className
                .replace('bg-red-600 hover:bg-red-700','bg-green-600 hover:bg-green-700');
        } else {
            btnDanger.classList.remove('border-white/10','bg-white/5');
            btnDanger.classList.add('border-red-500','bg-red-500/10');
            document.getElementById('sendAlertBtn').textContent = '🚨 Send Critical Alert to Admin';
            document.getElementById('sendAlertBtn').className = document.getElementById('sendAlertBtn').className
                .replace('bg-green-600 hover:bg-green-700','bg-red-600 hover:bg-red-700');
        }
    }

    function closeCareModal() {
        const modal = document.getElementById('careAlertModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function submitCareAlert() {
        const pigId   = document.getElementById('carePigId').value;
        const details = document.getElementById('careDetails').value.trim();

        if(!selectedStatus) {
            Swal.fire({ ...getSwalConfig(), title: 'Select Status', text: 'Please select either "Action Taken" or "Health In Danger".', icon: 'warning' });
            return;
        }
        if(!details) {
            Swal.fire({ ...getSwalConfig(), title: 'Add Details', text: 'Please describe the care provided or the current situation.', icon: 'warning' });
            return;
        }

        const isAlert  = selectedStatus === 'in_danger';
        const action   = isAlert ? '🚨 CRITICAL ALERT — Health In Danger' : '✅ Action Taken — Immediate Care Administered';
        const logType  = 'Medical';
        const btn = document.getElementById('sendAlertBtn');

        try {
            btn.disabled = true;
            btn.textContent = 'Submitting...';

            const res = await fetch(`/worker/pigs/${pigId}/log-activity`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ type: logType, action, details, is_critical_alert: isAlert })
            });

            if(!res.ok) throw new Error();

            closeCareModal();
            Swal.fire({
                title: isAlert ? '🚨 Admin Alerted!' : '✅ Care Logged!',
                text: isAlert
                    ? 'Admin has received a critical alert. The care details are now permanently logged.'
                    : 'Care record has been saved to the pig\'s activity history.',
                icon: 'success',
                background: '#0a0f0b', color: '#fff', confirmButtonColor: '#22c55e'
            }).then(() => {
                location.reload(); // Refresh to show "Waiting for update" badge
            });
        } catch (e) {
            Swal.fire({ title: 'Error', text: 'Could not submit. Please try again.', icon: 'error', background: '#0a0f0b', color: '#fff', confirmButtonColor: '#ef4444' });
        } finally {
            btn.disabled = false;
        }
    }
    // ---- Pen Accordion ----
    function togglePen(id) {
        const body    = document.getElementById(`pen-body-${id}`);
        const chevron = document.getElementById(`pen-chevron-${id}`);
        const isOpen  = !body.classList.contains('hidden');
        body.classList.toggle('hidden', isOpen);
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // ---- Individual Pig Toggle ----
    function togglePig(id) {
        const detail  = document.getElementById(`pig-${id}`);
        const chevron = document.getElementById(`pig-chevron-${id}`);
        const isOpen  = !detail.classList.contains('hidden');
        detail.classList.toggle('hidden', isOpen);
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    // ----------------------------------------------------------------
    // DEDUPLICATION HELPER
    // Multiple evaluations of the same pig in the same week are normal.
    // For the REPORT we only want the LATEST status per pig.
    // For the PEN VIEW we show ALL checks as history (audit trail).
    // ----------------------------------------------------------------
    function getLatestPerPig(logs) {
        const map = {};
        // Logs are stored newest-first (unshift), so first seen = latest
        logs.forEach(log => {
            if (!map[log.pigId]) {
                map[log.pigId] = log; // keep only the first occurrence (= latest)
            }
        });
        return Object.values(map);
    }

    function assignToPen(pigId) {
        const n = parseInt(pigId);
        if (n >= 1  && n <= 20) return 1;
        if (n >= 21 && n <= 60) return 5;
        return 12;
    }

    // ---- Inject ALL logs into pen accordion (full history — not deduplicated) ----
    document.addEventListener('DOMContentLoaded', () => {
        const allLogs = JSON.parse(localStorage.getItem('recent_monitoring') || '[]');

        [1, 5, 12].forEach(penId => {
            const penLogs   = allLogs.filter(l => assignToPen(l.pigId) === penId);
            const container = document.getElementById(`pen-local-logs-${penId}`);
            if (!container || !penLogs.length) return;

            // Group by pigId so we can show "Pig #xxx — N checks this week"
            const grouped = {};
            penLogs.forEach(log => {
                if (!grouped[log.pigId]) grouped[log.pigId] = [];
                grouped[log.pigId].push(log);
            });

            Object.entries(grouped).forEach(([pigId, pigLogs]) => {
                const latest  = pigLogs[0]; // newest first
                const isSick  = latest.symptom !== 'Healthy' || latest.feed === 'Poor/None';
                const bdr     = isSick ? 'border-red-500/20 bg-red-500/5' : 'border-green-500/15 bg-green-500/5';
                const badge   = isSick
                    ? `<span class="px-2 py-0.5 bg-red-500/20 text-red-300 rounded text-[9px] font-black border border-red-500/30 uppercase">Alert</span>`
                    : `<span class="px-2 py-0.5 bg-green-500/15 text-green-300 rounded text-[9px] font-black border border-green-500/20 uppercase">Healthy</span>`;
                const checkNote = pigLogs.length > 1
                    ? `<p class="text-blue-400/70 text-[10px] mt-0.5">${pigLogs.length} evaluations this week — showing latest</p>`
                    : '';

                container.insertAdjacentHTML('beforeend', `
                    <div class="flex gap-3 items-start p-3 rounded-xl border ${bdr}">
                        <div class="flex-1">
                            <p class="text-white text-xs font-bold">Pig #${pigId} — ${latest.symptom}</p>
                            <p class="text-white/40 text-[10px] mt-0.5">BCS: ${latest.bcs||'—'} · Weight: ${latest.weight?latest.weight+'kg':'—'} · Feeding: ${latest.feed||'—'} · Checks: ${latest.physicalChecks}</p>
                            ${latest.notes ? `<p class="text-white/30 text-[10px] italic mt-0.5">"${latest.notes}"</p>` : ''}
                            ${checkNote}
                        </div>
                        ${badge}
                    </div>`);
            });
        });
    });

    // ---- Generate Report (uses DEDUPLICATED data per pig) ----
    function generateReport() {
        const feed  = document.getElementById('reportFeedKg').value.trim();
        const notes = document.getElementById('reportNotes').value.trim();

        if (!feed) {
            Swal.fire({ title: 'Missing Feed Data', text: 'Please enter the total feed consumed this week.', icon: 'warning', ...getSwalConfig() });
            return;
        }

        if (notes.length < 5) {
            Swal.fire({ title: 'Short Notes', text: 'Please provide at least 5 characters in Operational Notes.', icon: 'warning', ...getSwalConfig() });
            return;
        }

        const allLogs   = JSON.parse(localStorage.getItem('recent_monitoring') || '[]');
        const totalChecks = allLogs.length; // total evaluations done (for audit info)

        // DEDUPLICATION — latest evaluation per pig only
        const latestPerPig = getLatestPerPig(allLogs);
        const sickPigs     = latestPerPig.filter(l => l.symptom !== 'Healthy' || l.feed === 'Poor/None');
        const healthyPigs  = latestPerPig.filter(l => l.symptom === 'Healthy' && l.feed !== 'Poor/None');
        const uniquePigsChecked = latestPerPig.length;
        const weekStart = '{{ \Carbon\Carbon::parse($thisWeek)->format("M d") }}';
        const weekEnd   = '{{ \Carbon\Carbon::parse($thisWeek)->endOfWeek()->format("M d, Y") }}';

        const pensData = [
            @foreach($pens as $p)
            {
                name: '{{ $p->name }}',
                type: '{{ $p->section }}',
                count: {{ $p->pigs->count() }},
                avgKg: {{ round($p->pigs->avg('weight') ?? 0) }},
                sick: {{ $p->pigs->where('health_status', 'Sick')->count() }},
                progress: {{ $p->progress }}
            },
            @endforeach
        ];

        const tasksData = [
            @foreach($weeklyTasks as $t)
            {
                title: {!! json_encode($t->title) !!},
                status: '{{ $t->status }}',
                progress: {{ $t->progress }},
                findings: {!! json_encode($t->findings) !!}
            },
            @endforeach
        ];

        const reportContent = document.getElementById('reportContent');
        reportContent.innerHTML = `

            <!-- Period -->
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-white/5 border border-white/10">
                <i class='bx bx-calendar text-blue-400 text-2xl shrink-0'></i>
                <div>
                    <p class="text-white/40 text-xs font-black uppercase tracking-widest">Report Period</p>
                    <p class="text-white font-bold">${weekStart} — ${weekEnd}</p>
                </div>
            </div>

            <!-- Farm Stats -->
            <div>
                <p class="text-white/40 text-xs font-black uppercase tracking-widest mb-3">Farm Overview</p>
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/5 rounded-xl p-3 text-center border border-white/10">
                        <p class="text-white/30 text-[9px] uppercase font-black mb-1">Total Pigs</p>
                        <p class="text-white font-black text-2xl">{{ $analytics['total_pigs'] }}</p>
                    </div>
                    <div class="bg-red-500/10 rounded-xl p-3 text-center border border-red-500/20">
                        <p class="text-white/30 text-[9px] uppercase font-black mb-1">Flagged</p>
                        <p class="text-red-400 font-black text-2xl">{{ $analytics['sick_pigs'] }}</p>
                    </div>
                    <div class="bg-blue-500/10 rounded-xl p-3 text-center border border-blue-500/20">
                        <p class="text-white/30 text-[9px] uppercase font-black mb-1">Avg. Weight</p>
                        <p class="text-blue-400 font-black text-2xl">{{ $analytics['avg_weight'] }}<span class="text-xs">kg</span></p>
                    </div>
                </div>
            </div>

            <!-- Task Summary -->
            <div>
                <p class="text-white/40 text-xs font-black uppercase tracking-widest mb-3">Protocol Execution</p>
                <div class="space-y-2">
                    ${tasksData.map(t => `
                    <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-white font-bold text-xs">${t.title}</p>
                            <span class="text-[9px] font-black uppercase ${t.status === 'completed' ? 'text-green-400' : 'text-yellow-400'}">${t.status} (${t.progress}%)</span>
                        </div>
                        ${t.findings ? t.findings.map(f => `<p class="text-white/40 text-[9px] italic ml-2">· ${f.text}: ${f.finding || 'N/A'}</p>`).join('') : ''}
                    </div>`).join('')}
                </div>
            </div>

            <!-- Pen Summary -->
            <div>
                <p class="text-white/40 text-xs font-black uppercase tracking-widest mb-3">Pen Summary</p>
                <div class="space-y-2">
                    ${pensData.map(p => `
                    <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/10">
                        <div>
                            <p class="text-white font-bold text-sm">${p.name} — <span class="text-white/50 font-normal">${p.type}</span></p>
                            <p class="text-white/40 text-xs">${p.count} pigs · Avg ${p.avgKg}kg · ${p.progress}% to target</p>
                        </div>
                        <span class="px-2 py-1 rounded-lg text-[10px] font-black border ${p.sick > 0 ? 'bg-yellow-500/15 text-yellow-300 border-yellow-500/25' : 'bg-green-500/15 text-green-300 border-green-500/25'} uppercase">
                            ${p.sick > 0 ? p.sick+' sick' : 'Good'}
                        </span>
                    </div>`).join('')}
                </div>
            </div>

            <!-- Monitoring Summary (deduplicated) -->
            <div>
                <p class="text-white/40 text-xs font-black uppercase tracking-widest mb-1">Monitoring Summary</p>
                <p class="text-blue-400/60 text-[10px] font-semibold mb-3">Each pig counted once using their most recent evaluation.</p>
                <div class="space-y-2">
                    <div class="flex justify-between p-3 rounded-xl bg-white/5 border border-white/10 text-sm">
                        <span class="text-white/60">Total evaluations done</span>
                        <span class="text-white/60 font-black">${totalChecks} <span class="text-white/30 font-normal text-xs">(audit history)</span></span>
                    </div>
                    <div class="flex justify-between p-3 rounded-xl bg-white/5 border border-white/10 text-sm">
                        <span class="text-white/60">Unique pigs evaluated</span>
                        <span class="text-white font-black">${uniquePigsChecked}</span>
                    </div>
                    <div class="flex justify-between p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-sm">
                        <span class="text-white/60">Currently healthy</span>
                        <span class="text-green-400 font-black">${healthyPigs.length}</span>
                    </div>
                    <div class="flex justify-between p-3 rounded-xl bg-red-500/10 border border-red-500/20 text-sm">
                        <span class="text-white/60">Currently flagged / sick</span>
                        <span class="text-red-400 font-black">${sickPigs.length}</span>
                    </div>
                    <div class="flex justify-between p-3 rounded-xl bg-white/5 border border-white/10 text-sm">
                        <span class="text-white/60">Feed consumed this week</span>
                        <span class="text-white font-black">${feed} kg</span>
                    </div>
                </div>
            </div>

            ${sickPigs.length > 0 ? `
            <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/20">
                <p class="text-red-300 text-xs font-black uppercase tracking-widest mb-3">Flagged Pigs — Requires Follow-Up</p>
                <div class="space-y-2">
                    ${sickPigs.map(l => `
                    <div class="flex gap-2 items-start">
                        <i class='bx bx-error-circle text-red-400 text-sm shrink-0 mt-0.5'></i>
                        <p class="text-white/70 text-xs">Pig #${l.pigId} — <strong>${l.symptom}</strong>, Feeding: ${l.feed||'—'}${l.notes ? ' — "'+l.notes+'"' : ''}</p>
                    </div>`).join('')}
                </div>
            </div>` : `
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-green-500/10 border border-green-500/20">
                <i class='bx bxs-check-circle text-green-400 text-xl shrink-0'></i>
                <p class="text-green-300 text-sm font-semibold">All evaluated pigs are currently healthy.</p>
            </div>`}

            <!-- Notes -->
            <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                <p class="text-white/40 text-xs font-black uppercase tracking-widest mb-2">Operational Notes</p>
                <p class="text-white/70 text-sm leading-relaxed">${notes || 'No additional notes provided.'}</p>
            </div>

            <div class="flex items-center gap-3 p-3 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                <i class='bx bx-info-circle text-yellow-400 text-lg shrink-0'></i>
                <p class="text-yellow-300 text-xs font-semibold">Once submitted, this report cannot be edited until next week.</p>
            </div>
        `;

        document.getElementById('reportModal').classList.remove('hidden');
        document.getElementById('reportModal').classList.add('flex');
        document.getElementById('reportModal').scrollTop = 0;
    }

    function closeReport() {
        document.getElementById('reportModal').classList.add('hidden');
        document.getElementById('reportModal').classList.remove('flex');
    }

    function submitReport() {
        Swal.fire({
            ...getSwalConfig(),
            title: 'Submit Weekly Report?',
            text: "This will transmit your data to HQ for review.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit Now',
            cancelButtonText: 'Review More'
        }).then((result) => {
            if (result.isConfirmed) {
                const feed  = document.getElementById('reportFeedKg').value.trim();
                const notes = document.getElementById('reportNotes').value.trim();
                document.getElementById('hiddenFeed').value  = feed;
                document.getElementById('hiddenNotes').value = notes;
                closeReport();
                document.getElementById('hiddenReportForm').submit();
            }
        });
    }
</script>

<form id="hiddenReportForm" action="{{ route('worker.reports.store') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="total_pigs" value="{{ $analytics['total_pigs'] }}">
    <input type="hidden" name="sick_pigs" value="{{ $analytics['sick_pigs'] }}">
    <input type="hidden" name="avg_weight" value="{{ $analytics['avg_weight'] }}">
    <input type="hidden" id="hiddenFeed" name="feed_consumed" value="">
    <input type="hidden" id="hiddenNotes" name="details" value="">
</form>
@endsection
