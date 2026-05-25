@php
    $targetActivityId = request('activity');

    $health  = $pig->health_status ?? 'Healthy';
    $hColor  = match($health) { 'Sick' => '#ef4444', 'Warning' => '#f59e0b', default => '#16a34a' };
    $hLight  = match($health) { 'Sick' => '#fef2f2', 'Warning' => '#fffbeb', default => '#f0fdf4' };
    $hText   = match($health) { 'Sick' => '#dc2626', 'Warning' => '#d97706', default => '#15803d' };
    $hBorder = match($health) { 'Sick' => '#fecaca', 'Warning' => '#fde68a', default => '#bbf7d0' };
    
    $ageMonths  = $pig->birth_date ? round(\Carbon\Carbon::parse($pig->birth_date)->diffInMonths()) : 0;
    $ageLabel   = $ageMonths >= 12 ? round($ageMonths/12, 1).' yrs' : ($ageMonths > 0 ? $ageMonths.' mo.' : '&mdash;');
    
    $feedSt     = $pig->feeding_status ?? 'Normal';
    $todayCount = $pig->activities->filter(fn($a) => $a->created_at >= now()->startOfDay())->count();
    $feedColor  = match($feedSt) { 'Active' => '#16a34a', 'Poor' => '#ef4444', default => '#2563eb' };
@endphp

<style>
    #pig-record-card .text-slate-900 { color: #0f172a !important; }
    #pig-record-card .text-slate-500 { color: #64748b !important; }
    .activity-highlight {
        animation: highlight-pulse 2.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        border: 2px solid #22c55e !important;
        background: #f0fdf4 !important;
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(34, 197, 94, 0.15) !important;
    }
    @keyframes highlight-pulse {
        0%, 100% { border-color: #22c55e; }
        50% { border-color: #86efac; }
    }
</style>

<div id="pig-record-card"
     class="relative bg-white w-full flex flex-col overflow-hidden"
     style="border-radius:2rem; box-shadow:0 8px 40px rgba(0,0,0,0.10); max-height:88vh;">

    <button onclick="hidePigModal()" class="absolute top-6 right-6 z-50 w-10 h-10 rounded-xl bg-slate-50 text-slate-300 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center border border-slate-100/50">
        <i class='bx bx-x text-2xl'></i>
    </button>

    {{-- ACCENT LINE --}}
    <div class="h-1.5 w-full shrink-0" style="background:linear-gradient(to right,{{ $hColor }},{{ $hColor }}80);"></div>

    <div class="overflow-y-auto" style="scrollbar-width:thin; scrollbar-color:#e2e8f0 transparent;">

        {{-- HERO --}}
        <div class="px-8 pt-7 pb-0">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.35em] mb-2" style="color:{{ $hColor }}">Animal Record</p>
                    <h1 class="font-black leading-none text-slate-900 tracking-tighter" style="font-size:3.2rem">#{{ $pig->tag }}</h1>
                    <p class="text-slate-400 font-bold text-sm uppercase tracking-widest mt-1">{{ $pig->breed ?? 'Yorkshire' }}</p>
                </div>
                <div class="shrink-0 text-right">
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest"
                          style="background:{{ $hLight }};color:{{ $hText }};border:1.5px solid {{ $hBorder }};">
                        @if($health==='Sick')<i class='bx bxs-x-circle text-sm'></i>
                        @elseif($health==='Warning')<i class='bx bxs-error text-sm'></i>
                        @else<i class='bx bxs-check-circle text-sm'></i>@endif
                        {{ $health }}
                    </span>
                    @if($todayCount > 0)
                    <div class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-50 text-slate-400 border border-slate-200">
                        <i class='bx bx-check text-xs'></i> {{ $todayCount }}x today
                    </div>
                    @endif
                </div>
            </div>

            {{-- QUICK STATS --}}
            <div class="grid grid-cols-4 gap-2.5 mt-5">
                @php $qstats = [
                    ['bx-home-alt-2',  $pig->pen->name ?? 'N/A',       'Pen'],
                    ['bx-time-five',   $ageLabel,                       'Age'],
                    ['bx-dumbbell',    ($pig->weight ?? '—').' kg',     'Weight'],
                    ['bx-trending-up', $pig->growth_stage,              'Stage'],
                ]; @endphp
                @foreach($qstats as [$ico,$val,$lbl])
                <div class="rounded-2xl p-4 text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
                    <i class='bx {{ $ico }} text-xl mb-1.5 block' style="color:{{ $hColor }}"></i>
                    <p class="text-slate-900 font-black text-sm leading-tight">{{ $val }}</p>
                    <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest mt-0.5">{{ $lbl }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- VITALS BAND --}}
        <div class="mx-8 mt-4 rounded-2xl overflow-hidden" style="border:1px solid #e2e8f0;">
            <div class="grid grid-cols-3 divide-x divide-slate-100">
                <div class="p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Feeding Status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $feedColor }}"></span>
                        <span class="font-black text-sm text-slate-900">{{ $feedSt }}</span>
                    </div>
                </div>
                <div class="p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Body Condition</p>
                    <span class="font-black text-sm text-slate-900">{{ $pig->bcs_score ?? 3 }}/5</span>
                </div>
                <div class="p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Growth Target</p>
                    <span class="font-black text-sm text-slate-900">{{ round(($pig->weight / ($pig->target_weight ?: 110)) * 100) }}%</span>
                </div>
            </div>
        </div>

        {{-- FEED FORMULA BAND --}}
        <div class="mx-8 mt-3 rounded-2xl p-4 flex items-center justify-between" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <div class="flex items-center gap-3">
                <i class='bx bx-bowl-hot text-2xl' style="color:{{ $hColor }}"></i>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Assigned Feed Formula</p>
                    <p class="font-black text-sm text-slate-900">{{ $pig->feed_formula_name ?? 'Standard Mix' }}</p>
                </div>
            </div>
            <a href="{{ route('worker.feed-formulas') }}" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition" style="background:{{ $hLight }}; color:{{ $hText }};">View Mix</a>
        </div>

        {{-- ACTIVITY HISTORY --}}
        <div class="px-8 mt-8 mb-8">
            <div class="flex items-center justify-between mb-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Activity History</p>
                <span class="text-[9px] font-bold text-slate-300 uppercase">Latest 15 Events</span>
            </div>

            <div class="space-y-3">
                @forelse($pig->activities as $act)
                    <div id="act-{{ $act->id }}" class="p-4 rounded-2xl border border-slate-100 flex gap-4 transition-all @if($targetActivityId == $act->id) activity-highlight @endif" style="background:#fcfdfe;">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                            <i class='bx {{ $act->type === "Medical" ? "bx-plus-medical text-red-500" : ($act->type === "Growth" ? "bx-trending-up text-blue-500" : "bx-check text-green-500") }} text-lg'></i>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <p class="text-slate-900 font-black text-sm tracking-tight">{{ $act->action }}</p>
                                <span class="text-slate-300 text-[9px] font-bold uppercase">{{ $act->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-500 text-xs leading-relaxed font-medium">{{ $act->details }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 rounded-2xl border-2 border-dashed border-slate-100 bg-slate-50">
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No activities recorded</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ACTION FOOTER --}}
    <div class="px-8 py-5 border-t border-slate-100 flex flex-col sm:flex-row gap-3 bg-slate-50 mt-auto shrink-0">
        <button onclick="openDailyCheckIn({{ $pig->id }})" style="color: white !important;" class="flex-1 py-4 rounded-xl bg-slate-900 hover:bg-slate-800 font-black text-xs sm:text-sm uppercase tracking-widest transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class='bx bx-edit-alt text-lg'></i> Care & Log Activity
        </button>
        <button onclick="flagMedicalEmergency({{ $pig->id }}, '{{ $pig->tag }}')" class="px-6 py-4 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 font-black text-xs sm:text-sm uppercase tracking-widest transition-all shrink-0 flex items-center justify-center gap-2">
            <i class='bx bx-error-circle text-lg'></i> Flag Emergency
        </button>
    </div>

</div>
