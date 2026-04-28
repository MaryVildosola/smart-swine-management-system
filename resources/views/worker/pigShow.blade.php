@extends('layouts.worker')

@section('content')
@php
    $health  = $pig->health_status ?? 'Healthy';
    $hColor  = match($health) { 'Sick' => '#ef4444', 'Warning' => '#f59e0b', default => '#16a34a' };
    $hLight  = match($health) { 'Sick' => '#fef2f2', 'Warning' => '#fffbeb', default => '#f0fdf4' };
    $hText   = match($health) { 'Sick' => '#dc2626', 'Warning' => '#d97706', default => '#15803d' };
    $hBorder = match($health) { 'Sick' => '#fecaca', 'Warning' => '#fde68a', default => '#bbf7d0' };
    $ageMonths = round(\Carbon\Carbon::parse($pig->birth_date)->diffInMonths());
    $ageLabel  = $ageMonths >= 12 ? round($ageMonths/12, 1).' yrs' : $ageMonths.' mo.';
    $bcs       = $pig->bcs_score ?? '—';
    $feedSt    = $pig->feeding_status ?? 'Normal';
    $lastActiv = $pig->activities->first();
    $todayChecked = $pig->activities->where('created_at', '>=', now()->startOfDay())->count();

    $feedColor = match($feedSt) { 'Active' => ['#16a34a','#f0fdf4','#bbf7d0'], 'Poor' => ['#ef4444','#fef2f2','#fecaca'], default => ['#2563eb','#eff6ff','#bfdbfe'] };
@endphp

<div id="pig-record-card"
     class="bg-white w-full max-h-[88vh] flex flex-col overflow-hidden"
     style="border-radius: 2rem; box-shadow: 0 32px 80px rgba(0,0,0,0.22);">

    {{-- ▌ TOP ACCENT LINE --}}
    <div class="h-1.5 w-full shrink-0" style="background: linear-gradient(to right, {{ $hColor }}, {{ $hColor }}88);"></div>

    {{-- ▌ SCROLLABLE BODY --}}
    <div class="flex-1 overflow-y-auto" style="scrollbar-width:thin; scrollbar-color:#e2e8f0 transparent;">

        {{-- ════════════════════════════════
             HERO SECTION
        ════════════════════════════════ --}}
        <div class="px-8 pt-7 pb-0">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.35em] mb-2" style="color: {{ $hColor }}">Animal Record · Porcitrack</p>
                    <h1 class="font-black leading-none text-slate-900 tracking-tighter" style="font-size: 3.2rem">#{{ $pig->tag }}</h1>
                    <p class="text-slate-400 font-bold text-sm uppercase tracking-widest mt-1">{{ $pig->breed ?? 'Yorkshire' }}</p>
                </div>
                <div class="shrink-0 text-right">
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest"
                          style="background: {{ $hLight }}; color: {{ $hText }}; border: 1.5px solid {{ $hBorder }};">
                        @if($health === 'Sick') <i class='bx bxs-x-circle text-sm'></i>
                        @elseif($health === 'Warning') <i class='bx bxs-error text-sm'></i>
                        @else <i class='bx bxs-check-circle text-sm'></i> @endif
                        {{ $health }}
                    </span>
                    @if($todayChecked > 0)
                    <div class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-50 text-slate-400 border border-slate-200">
                        <i class='bx bx-check text-xs'></i> Checked today
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── QUICK STATS ── --}}
            <div class="grid grid-cols-4 gap-2.5 mt-5">
                @php $qstats = [
                    ['bx-home-alt-2',   $pig->pen->name,   'Pen'],
                    ['bx-time-five',    $ageLabel,         'Age'],
                    ['bx-dumbbell',     ($pig->weight ?? '—').' kg','Weight'],
                    ['bx-trending-up',  $pig->growth_stage,'Stage'],
                ]; @endphp
                @foreach($qstats as [$ico,$val,$lbl])
                <div class="rounded-2xl p-4 text-center" style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <i class='bx {{ $ico }} text-xl mb-1.5 block' style="color:{{ $hColor }}"></i>
                    <p class="text-slate-900 font-black text-sm leading-tight">{{ $val }}</p>
                    <p class="text-slate-400 text-[9px] font-black uppercase tracking-widest mt-0.5">{{ $lbl }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ════════════════════════════════
             VITALS BAND
        ════════════════════════════════ --}}
        <div class="mx-8 mt-5 rounded-2xl overflow-hidden" style="border: 1px solid #e2e8f0;">
            <div class="grid grid-cols-3 divide-x divide-slate-100">
                {{-- Feeding Status --}}
                <div class="p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Feeding Status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $feedColor[0] }}"></span>
                        <span class="font-black text-sm text-slate-900">{{ $feedSt }}</span>
                    </div>
                </div>
                {{-- BCS Score --}}
                <div class="p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Body Cond. Score</p>
                    <div class="flex items-center gap-2">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="w-4 h-4 rounded-sm {{ $i <= ($pig->bcs_score ?? 0) ? '' : 'opacity-10' }}"
                                 style="background:{{ $hColor }}"></div>
                        @endfor
                        <span class="font-black text-xs text-slate-500">{{ $pig->bcs_score ?? '—' }}/5</span>
                    </div>
                </div>
                {{-- Last Check --}}
                <div class="p-4">
                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Last Check-In</p>
                    <p class="font-black text-sm text-slate-900">
                        {{ $lastActiv ? $lastActiv->created_at->diffForHumans() : 'Never' }}
                    </p>
                </div>
            </div>

            {{-- Remarks banner (if any) --}}
            @if($pig->remarks)
            <div class="px-4 py-3 border-t border-slate-100 flex items-start gap-2.5" style="background:#fffbeb;">
                <i class='bx bxs-info-circle text-amber-500 text-base shrink-0 mt-0.5'></i>
                <p class="text-xs font-bold text-amber-800 leading-snug">{{ $pig->remarks }}</p>
            </div>
            @endif
        </div>

        {{-- ════════════════════════════════
             ANIMAL DETAILS
        ════════════════════════════════ --}}
        <div class="mx-8 mt-4">
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-3">Animal Particulars</p>
            <div class="grid grid-cols-2 gap-2">
                @php $details = [
                    ['Birth Date',    \Carbon\Carbon::parse($pig->birth_date)->format('M d, Y')],
                    ['Target Weight', ($pig->target_weight ?? '—').' kg'],
                    ['Pen Location',  $pig->pen->name],
                    ['Diet Formula',  $pig->feed_formula_name ?? 'Standard Mix'],
                    ['Pen Section',   $pig->pen->section ?? '—'],
                    ['Status',        $pig->status ?? 'Active'],
                ]; @endphp
                @foreach($details as [$lbl,$val])
                <div class="flex items-center justify-between px-4 py-3 rounded-xl"
                     style="background:#f8fafc; border:1px solid #e2e8f0;">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">{{ $lbl }}</span>
                    <span class="text-xs font-black text-slate-900">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ════════════════════════════════
             SYMPTOMS
        ════════════════════════════════ --}}
        @if($pig->symptoms)
        <div class="mx-8 mt-4 p-4 rounded-2xl flex gap-3" style="background:#fef2f2; border:1px solid #fecaca;">
            <i class='bx bxs-error-circle text-red-500 text-xl shrink-0 mt-0.5'></i>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-red-500 mb-1">Reported Symptoms</p>
                <p class="text-sm font-bold text-red-800">{{ $pig->symptoms }}</p>
            </div>
        </div>
        @endif

        {{-- DIVIDER --}}
        <div class="mx-8 mt-5 border-t border-slate-100"></div>

        {{-- ════════════════════════════════
             TABS
        ════════════════════════════════ --}}
        <div class="flex gap-2 px-8 py-4">
            <button onclick="switchPigTab('activity')" id="tab-btn-activity"
                class="flex-1 py-3.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all"
                style="background:#f0fdf4; color:#15803d; border:1.5px solid #bbf7d0;">
                <i class='bx bx-calendar-check mr-1.5'></i>Daily Operations
            </button>
            <button onclick="switchPigTab('medical')" id="tab-btn-medical"
                class="flex-1 py-3.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all"
                style="background:#f1f5f9; color:#94a3b8; border:1.5px solid #e2e8f0;">
                <i class='bx bx-plus-medical mr-1.5'></i>Medical History
            </button>
        </div>

        {{-- ════════════════════════════════
             DAILY OPERATIONS TAB
        ════════════════════════════════ --}}
        <div id="pig-tab-activity" class="px-8 pb-6 space-y-5">

            {{-- CHECK-IN CTA --}}
            <button onclick="openDailyCheckIn({{ json_encode($pig) }})"
                class="w-full text-left rounded-2xl p-6 flex items-center gap-5 group transition-all active:scale-[0.99]"
                style="background: {{ $hColor }}; box-shadow: 0 14px 40px {{ $hColor }}35;">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                     style="background:rgba(255,255,255,0.2);">
                    <i class='bx bx-edit-alt text-white text-2xl'></i>
                </div>
                <div class="flex-1">
                    <p class="text-white/70 text-[10px] font-black uppercase tracking-[0.3em] mb-0.5">Mandatory · Every Shift</p>
                    <p class="text-white font-black text-lg uppercase tracking-tight">Start Daily Check-In</p>
                    <p class="text-white/60 text-[11px] font-bold mt-0.5">Feed · Water · Skin · Behavior · Weight</p>
                </div>
                <i class='bx bx-chevron-right text-white/40 text-2xl group-hover:text-white/80 transition-colors'></i>
            </button>

            {{-- ACTIVITY FEED --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Activity & Admin Tasks</p>
                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-wider">{{ $pig->activities->count() }} entries</span>
                </div>

                @php
                    $combined = collect();
                    foreach($pig->activities as $a) {
                        $combined->push(['type'=>'log','title'=>$a->action,'desc'=>$a->details,'date'=>$a->created_at,'subtype'=>$a->type ?? 'Care']);
                    }
                    foreach($pig->tasks as $t) {
                        $combined->push(['type'=>'task','title'=>$t->task_name,'desc'=>$t->description,'date'=>$t->created_at,'priority'=>$t->priority]);
                    }
                    $combined = $combined->sortByDesc('date')->take(15);
                @endphp

                <div class="space-y-2">
                @forelse($combined as $item)
                    @php
                        $isTask   = $item['type'] === 'task';
                        $isMed    = !$isTask && ($item['subtype'] ?? '') === 'Medical';
                        $bgCard   = $isTask ? '#fffbeb' : ($isMed ? '#fef2f2' : '#f8fafc');
                        $bdCard   = $isTask ? '#fde68a' : ($isMed ? '#fecaca' : '#e2e8f0');
                        $iconBg   = $isTask ? '#fef3c7' : ($isMed ? '#fee2e2' : '#f0fdf4');
                        $iconColor = $isTask ? '#d97706' : ($isMed ? '#dc2626' : '#16a34a');
                        $bxIcon   = $isTask ? 'bx-task' : ($isMed ? 'bx-plus-medical' : 'bx-check-double');
                    @endphp
                    <div class="flex gap-4 p-4 rounded-2xl" style="background:{{ $bgCard }}; border:1px solid {{ $bdCard }};">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                             style="background:{{ $iconBg }};">
                            <i class='bx {{ $bxIcon }} text-sm' style="color:{{ $iconColor }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 mb-0.5">
                                <p class="text-slate-900 font-black text-xs uppercase tracking-tight leading-tight">{{ $item['title'] }}</p>
                                <span class="text-slate-300 text-[9px] font-bold uppercase shrink-0">{{ $item['date']->format('M d · h:i A') }}</span>
                            </div>
                            @if(!empty($item['desc']))
                                <p class="text-slate-500 text-xs leading-snug">{{ Str::limit($item['desc'], 100) }}</p>
                            @endif
                            <div class="flex gap-1.5 mt-1.5 flex-wrap">
                                @if($isTask)
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-amber-100 text-amber-700">Admin Task</span>
                                    @if(!empty($item['priority']))
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-slate-100 text-slate-500">{{ $item['priority'] }}</span>
                                    @endif
                                @elseif($isMed)
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-red-100 text-red-600">Medical</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-green-50 text-green-700">{{ $item['subtype'] ?? 'Care' }}</span>
                                @endif
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-50 text-slate-400">{{ $item['date']->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-14 rounded-2xl" style="background:#f8fafc; border:1px dashed #e2e8f0;">
                        <i class='bx bx-history text-4xl text-slate-200 block mb-2'></i>
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No activity yet</p>
                    </div>
                @endforelse
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════
             MEDICAL HISTORY TAB
        ════════════════════════════════ --}}
        <div id="pig-tab-medical" class="hidden px-8 pb-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Full Medical Log</p>
                    <p class="text-[9px] font-bold text-slate-300 mt-0.5">Vaccinations, medications & interventions</p>
                </div>
                <button onclick="openMedicalLogger({{ $pig->id }})"
                    class="px-4 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest text-white"
                    style="background:#ef4444; box-shadow:0 6px 20px rgba(239,68,68,0.3);">
                    + Log Entry
                </button>
            </div>

            {{-- Health Reports --}}
            @foreach($pig->healthReports->take(3) as $hr)
            <div class="p-4 rounded-2xl" style="background:#f0fdf4; border:1px solid #bbf7d0;">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] font-black uppercase tracking-widest text-green-600">Health Report</span>
                    <span class="text-[9px] font-bold text-slate-300 uppercase">{{ $hr->created_at->format('M d, Y') }}</span>
                </div>
                <p class="text-xs font-bold text-slate-700">{{ Str::limit($hr->findings ?? 'No additional notes.', 120) }}</p>
            </div>
            @endforeach

            {{-- Medical Activities --}}
            @forelse($pig->activities->where('type','Medical') as $med)
                <div class="flex gap-4 p-4 rounded-2xl" style="background:#fef2f2; border:1px solid #fecaca;">
                    <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                        <i class='bx bx-plus-medical text-red-500 text-sm'></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-2 mb-0.5">
                            <p class="text-red-700 font-black text-sm uppercase tracking-tight">{{ $med->action }}</p>
                            <span class="text-slate-300 text-[9px] font-bold uppercase">{{ $med->created_at->format('M d, Y') }}</span>
                        </div>
                        <p class="text-slate-600 text-xs leading-snug">{{ $med->details }}</p>
                        <p class="text-slate-300 text-[9px] font-bold mt-1.5 uppercase tracking-wider">By: {{ $med->user->name ?? 'Farm Worker' }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 rounded-2xl" style="background:#f8fafc; border:2px dashed #e2e8f0;">
                    <i class='bx bxs-heart text-4xl text-slate-200 block mb-2'></i>
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No medical entries recorded</p>
                    <p class="text-[9px] font-bold text-slate-200 mt-1">This animal has a clean medical history</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ▌ FOOTER --}}
    <div class="px-8 py-5 shrink-0" style="border-top:1px solid #f1f5f9;">
        <button onclick="hidePigModal()"
            class="w-full py-4 rounded-2xl font-black text-xs uppercase tracking-[0.3em] text-slate-500 transition-all hover:bg-slate-100"
            style="background:#f8fafc; border:1px solid #e2e8f0;">
            Close
        </button>
    </div>
</div>
@endsection
