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

    /* Dark Mode styles (default ΓÇö no class needed) */
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

<div class="worker-dash min-h-screen transition-colors duration-200">
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
            <div class="dash-card flex items-center gap-4 p-5 rounded-[2rem] backdrop-blur-2xl bg-white/80 border border-red-100 shadow-sm hover:shadow-md transition cursor-pointer" onclick="window.location='{{ route('worker.pigs.show', $alert->id) }}'">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center shrink-0 animate-pulse">
                    <i class='bx bxs-error-circle text-2xl'></i>
                </div>
                <div class="flex-1">
                    <p class="text-slate-900 font-black text-sm">CRITICAL ΓÇö Pig #{{ $alert->tag }}, {{ $alert->pen->name ?? 'Unknown Pen' }}</p>
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
                <div onclick="openFeedingModal('{{ $pen['id'] }}')" class="dash-card group backdrop-blur-2xl bg-white/80 rounded-[2.5rem] p-8 border border-slate-100 hover:border-green-500/50 hover:shadow-[0_15px_30px_rgba(0,0,0,0.1)] transition-all cursor-pointer relative overflow-hidden shadow-[0_5px_15px_rgba(0,0,0,0.05)]">
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
                            <p class="text-slate-900 text-[9px] font-black">{{ $pen['progress'] }}%</p>
                        </div>
                        <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden p-0.5">
                            <div class="h-full bg-{{ $pen['color'] }}-500 rounded-full shadow-[0_0_10px_rgba(0,0,0,0.1)] transition-all duration-1000" style="width: {{ $pen['progress'] }}%"></div>
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
            <div class="dash-card backdrop-blur-2xl bg-white/80 rounded-[2rem] p-6 flex gap-5 items-center border border-slate-100 shadow-[0_5px_15px_rgba(0,0,0,0.03)] hover:shadow-md transition cursor-pointer" onclick="window.location='{{ route('worker.pigs.show', $activity->pig_id) }}'">
                <div class="w-14 h-14 rounded-2xl bg-{{ $activity->type === 'Medical' ? 'red' : ($activity->type === 'Growth' ? 'blue' : 'green') }}-50 flex items-center justify-center shrink-0 border border-slate-100 text-{{ $activity->type === 'Medical' ? 'red' : ($activity->type === 'Growth' ? 'blue' : 'green') }}-600">
                    <i class='bx {{ $activity->type === 'Medical' ? 'bx-plus-medical' : ($activity->type === 'Growth' ? 'bx-trending-up' : 'bx-check-double') }} text-2xl'></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-0.5">
                        <p class="text-slate-900 font-black text-base tracking-tight">{{ $activity->pig->tag }} ΓÇö {{ $activity->action }}</p>
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
        </div>
    </div>

    <!-- Routine Feeding Form Modal -->
    <div id="feedingModal" class="fixed inset-0 z-[210] hidden bg-slate-900/95 backdrop-blur-3xl flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl border border-slate-200 animate-fade-in my-auto">
            <div class="p-8 pb-6 border-b border-slate-100 relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold border border-green-200 uppercase tracking-widest">
                        Pen ID: <span id="targetPenId">--</span><span id="targetPenName"></span>
                    </div>
                    <button onclick="closeFeedingModal()" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-slate-100 transition">
                        <i class='bx bx-x text-3xl'></i>
                    </button>
                </div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Routine Feeding</h2>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed font-medium">Batch log for pigs in current growth stage.</p>
            </div>
            <div class="p-8 pt-6 space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Quantity (kg)</label>
                    <input type="number" id="feedQty" placeholder="0.0" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-5 px-6 text-2xl font-black text-slate-900 focus:outline-none focus:border-green-500 transition">
                </div>
                <button onclick="submitFeedingLog()" class="w-full bg-green-600 text-white py-5 rounded-[2rem] font-black text-lg hover:shadow-[0_10px_30px_rgba(34,197,94,0.3)] transition active:scale-[0.98]">
                    Confirm Log
                </button>
            </div>
        </div>
    </div>

    <!-- Health Monitoring Modal -->
    <div id="healthModal" class="fixed inset-0 z-[210] hidden bg-slate-900/95 backdrop-blur-3xl flex items-start justify-center p-4 overflow-y-auto">
        <div class="bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl border border-slate-200 animate-fade-in my-6">
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-slate-400 text-sm font-semibold uppercase tracking-widest mb-1">Ear Tag No.</p>
                        <h2 class="text-4xl font-black text-slate-900 tracking-tight" id="targetPigId">--</h2>
                    </div>
                    <button onclick="closeHealthModal()" class="w-14 h-14 rounded-2xl bg-white text-slate-400 flex items-center justify-center hover:bg-slate-100 transition border border-slate-200">
                        <i class='bx bx-x text-4xl'></i>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-8">
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-xs text-slate-400 font-bold uppercase mb-1">Pen</p>
                        <p class="text-lg text-slate-900 font-black" id="pigInfoPen">Loading...</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-xs text-slate-400 font-bold uppercase mb-1">Stage</p>
                        <p class="text-lg text-slate-900 font-black" id="pigInfoStage">Loading...</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                        <p class="text-xs text-slate-400 font-bold uppercase mb-1">Last Check</p>
                        <p class="text-base text-slate-900 font-black" id="pigInfoLastCheck">Loading...</p>
                    </div>
                </div>

                <div>
                    <p class="text-base font-black text-slate-900 mb-1">Body Condition Score</p>
                    <p class="text-sm text-slate-400 mb-3">1 = Thin | 3 = Ideal | 5 = Obese</p>
                    <div class="flex gap-2">
                        @foreach([1, 2, 3, 4, 5] as $score)
                        <button type="button" onclick="setBCS(this, {{ $score }})" class="bcs-btn flex-1 py-4 rounded-xl border border-slate-200 bg-white text-slate-900 font-black text-base transition active:scale-95">
                            {{ $score }}
                        </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="selectedBCS" value="3">
                </div>

                <div>
                    <p class="text-base font-black text-slate-900 mb-3">Feeding Behavior</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="setFeedBehavior(this, 'Active')" class="feed-btn py-4 rounded-xl border border-slate-200 bg-white text-slate-900 font-bold text-xs transition active:scale-95">Active</button>
                        <button type="button" onclick="setFeedBehavior(this, 'Normal')" class="feed-btn py-4 rounded-xl border border-green-500 bg-green-50 text-green-700 font-bold text-xs transition active:scale-95">Normal</button>
                        <button type="button" onclick="setFeedBehavior(this, 'Poor/None')" class="feed-btn py-4 rounded-xl border border-slate-200 bg-white text-slate-900 font-bold text-xs transition active:scale-95">Poor/None</button>
                    </div>
                    <input type="hidden" id="selectedFeedBehavior" value="Normal">
                </div>

                <div>
                    <p class="text-base font-black text-slate-900 mb-4">Physical Inspection ΓÇö Tap to confirm</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="physicalChecklist"></div>
                </div>

                <div>
                    <p class="text-base font-black text-slate-900 mb-3">Estimated Weight (kg)</p>
                    <div class="relative">
                        <input type="number" id="pigWeight" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-5 pl-6 pr-16 text-slate-900 text-3xl font-black focus:outline-none focus:border-green-500 transition">
                        <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xl">kg</span>
                    </div>
                </div>

                <div>
                    <p class="text-base font-black text-slate-900 mb-3">Observed Symptom</p>
                    <select id="symptom" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-5 px-5 text-slate-900 text-base focus:outline-none focus:border-green-500 transition font-bold">
                        <option value="Healthy">Healthy ΓÇö No Issues</option>
                        <option value="Coughing">Coughing / Respiratory Problem</option>
                        <option value="Lethargic">Lethargic / Not Eating</option>
                        <option value="Diarrhea">Diarrhea / Loose Stool</option>
                        <option value="Lameness">Lameness / Limping</option>
                        <option value="Skin">Skin Wound / Rash</option>
                        <option value="Fever">Suspected Fever</option>
                        <option value="Other">Other ΓÇö See Notes Below</option>
                    </select>
                </div>

                <div>
                    <p class="text-base font-black text-slate-900 mb-3">Additional Notes</p>
                    <textarea id="pigNotes" rows="3" placeholder="Write any other observations here..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-5 text-slate-900 text-base font-medium focus:outline-none focus:border-green-500 transition resize-none leading-relaxed"></textarea>
                </div>

                <div class="pt-4">
                    <button onclick="submitHealthLog()" class="w-full bg-green-600 text-white py-6 rounded-2xl font-black text-xl hover:shadow-[0_10px_30px_rgba(34,197,94,0.3)] transition active:scale-[0.98]">
                        Save Report
                    </button>
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
                Swal.fire({ title: 'Camera Error', text: 'Unable to access your camera.', icon: 'error' });
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

        function openFeedingModal(penId, penName = '') {
            document.getElementById('targetPenId').innerText = penId;
            const nameEl = document.getElementById('targetPenName');
            if (nameEl) nameEl.innerText = penName ? ` (${penName})` : '';
            document.getElementById('feedingModal').classList.remove('hidden');
            document.getElementById('feedingModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeFeedingModal() {
            document.getElementById('feedingModal').classList.add('hidden');
            document.getElementById('feedingModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        async function submitFeedingLog() {
            const qty = document.getElementById('feedQty').value;
            const penId = document.getElementById('targetPenId').innerText;
            if (!qty) { Swal.fire({ title: 'Error', text: 'Enter quantity', icon: 'error' }); return; }
            Swal.fire({ title: 'Feeding Logged', text: `${qty}kg for Pen ${penId}`, icon: 'success' });
            closeFeedingModal();
        }

        const physicalCheckItems = [
            'Snout ΓÇö No discharge','Eyes ΓÇö Clear, bright','Ears ΓÇö No redness',
            'Legs ΓÇö Walking normally','Skin ΓÇö No wounds/rashes','Breathing ΓÇö Normal','Water ΓÇö Drinking adequately',
        ];

        function openHealthModal(pigId) {
            document.getElementById('targetPigId').innerText = pigId;
            document.getElementById('healthModal').classList.remove('hidden');
            document.getElementById('healthModal').classList.add('flex');
            document.body.style.overflow = 'hidden';

            document.getElementById('pigInfoPen').innerText = 'Loading...';
            document.getElementById('pigInfoStage').innerText = 'Loading...';
            document.getElementById('pigInfoLastCheck').innerText = 'Loading...';
            document.getElementById('selectedBCS').value = '3';
            document.getElementById('selectedFeedBehavior').value = 'Normal';
            document.getElementById('pigWeight').value = '';
            document.getElementById('pigNotes').value = '';
            document.getElementById('symptom').value = 'Healthy';

            document.querySelectorAll('.bcs-btn').forEach(b => {
                b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
                if (b.innerText.trim() === '3') b.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
            });
            document.querySelectorAll('.feed-btn').forEach(b => {
                b.classList.remove('border-green-500', 'bg-green-50', 'text-green-700');
                if (b.innerText.trim() === 'Normal') b.classList.add('border-green-500', 'bg-green-50', 'text-green-700');
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
                        document.getElementById('pigInfoPen').innerText = data.pig.pen_name;
                        document.getElementById('pigInfoStage').innerText = data.pig.growth_stage;
                        document.getElementById('pigInfoLastCheck').innerText = data.pig.last_check;
                        document.getElementById('targetPigId').dataset.pigDatabaseId = data.pig.id;
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

            const data = {
                pig_id: pigId,
                symptom: document.getElementById('symptom').value,
                weight: document.getElementById('pigWeight').value,
                body_condition_score: document.getElementById('selectedBCS').value,
                feeding_behavior: document.getElementById('selectedFeedBehavior').value,
                notes: document.getElementById('pigNotes').value,
                physical_checks: physicalChecks
            };

            Swal.fire({ title: 'Saving Report...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            try {
                const response = await fetch('/api/health/report', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(data)
                });
                if (response.ok) {
                    Swal.fire({ title: 'Report Saved', text: 'Farm database updated successfully.', icon: 'success' });
                    closeHealthModal();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    const err = await response.json();
                    Swal.fire({ title: 'Error', text: err.message || 'Failed to save report', icon: 'error' });
                }
            } catch (e) {
                Swal.fire({ title: 'Error', text: 'Network error', icon: 'error' });
            }
        }
    </script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards; }
    </style>
@endsection
