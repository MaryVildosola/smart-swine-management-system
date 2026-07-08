@extends('layouts.master')
@section('contents')
<style>
.fm-wrap { padding: 32px; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
.page-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 24px; }
.top-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
.btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 12px; background: #22c55e; color: #fff; font-weight: 600; border: none; cursor: pointer; text-decoration: none; font-size: 0.9rem; transition: background 0.2s; }
.btn-primary:hover { background: #16a34a; color: #fff; }
.btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; background: #f1f5f9; color: #374151; font-weight: 600; border: 1px solid #e2e8f0; cursor: pointer; text-decoration: none; font-size: 0.88rem; transition: background 0.2s; }
.btn-secondary:hover { background: #e2e8f0; color: #111; }
.panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; padding: 28px; margin-bottom: 28px; }
.panel-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 18px; }
.panel-title { font-size: 1.1rem; font-weight: 700; color: #111827; }
.table { width: 100%; border-collapse: collapse; }
.table th { text-align: left; padding: 12px 14px; color: #6b7280; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 2px solid #f3f4f6; }
.table td { padding: 14px 14px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 0.93rem; vertical-align: middle; }
.table tr:last-child td { border-bottom: none; }
.badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 600; }
.badge-green  { background: #dcfce7; color: #166534; }
.badge-red    { background: #fee2e2; color: #991b1b; }
.badge-blue   { background: #dbeafe; color: #1e40af; }
.badge-amber  { background: #fef3c7; color: #92400e; }
.badge-purple { background: #f3e8ff; color: #6b21a8; }
.empty-state { text-align: center; color: #9ca3af; padding: 32px; }
.alert-box { border-radius: 14px; padding: 14px 20px; margin-bottom: 20px; }
.alert-box.success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; }
.req-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
.req-table th, .req-table td { padding: 8px 12px; border: 1px solid #e5e7eb; text-align: center; }
.req-table th { background: #f8fafc; color: #374151; font-weight: 600; }
.details-toggle { background: none; border: none; cursor: pointer; color: #22c55e; font-size: 0.88rem; font-weight: 600; text-decoration: underline; padding: 0; }
</style>

<div class="fm-wrap">
    <div class="top-bar">
        <div>
            <h1 class="page-title">🌿 Feed Mixing</h1>
            <p class="page-subtitle">Create and manage custom feed formulas. The system checks each formula against standard nutrient requirements.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="{{ route('admin.feed-ingredients.index') }}" class="btn-secondary">⚗️ Ingredient Library</a>
            <a href="{{ route('admin.feed-mix.create') }}" class="btn-primary">+ New Formula</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-box success">✅ {{ session('success') }}</div>
    @endif

    {{-- Nutrient Requirements Reference --}}
    <div class="panel" style="margin-bottom:28px;">
        <div class="panel-header">
            <span class="panel-title">📋 Nutrient Requirements Reference (NRC / Philippine Standards)</span>
            <button class="details-toggle" onclick="document.getElementById('req-ref').style.display = document.getElementById('req-ref').style.display==='none'?'block':'none'">Show / Hide</button>
        </div>
        <div id="req-ref" style="display:none; overflow-x:auto;">
            <table class="req-table">
                <thead>
                    <tr>
                        <th>Life Stage</th>
                        <th>Crude Protein (%)</th>
                        <th>ME (kcal/kg)</th>
                        <th>Crude Fat (%)</th>
                        <th>Crude Fiber (%)</th>
                        <th>Calcium (%)</th>
                        <th>Phosphorus (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>Starter</strong> (10–25 kg)</td><td>≥ 20%</td><td>≥ 3,265</td><td>3–5%</td><td>≤ 4%</td><td>≥ 0.80%</td><td>≥ 0.65%</td></tr>
                    <tr><td><strong>Grower</strong> (25–60 kg)</td><td>≥ 16%</td><td>≥ 3,265</td><td>3–5%</td><td>≤ 5%</td><td>≥ 0.60%</td><td>≥ 0.55%</td></tr>
                    <tr><td><strong>Finisher</strong> (60–100 kg)</td><td>≥ 14%</td><td>≥ 3,265</td><td>3–5%</td><td>≤ 6%</td><td>≥ 0.50%</td><td>≥ 0.45%</td></tr>
                    <tr><td><strong>Breeder / Sow</strong></td><td>≥ 15%</td><td>≥ 3,000</td><td>3–5%</td><td>≤ 8%</td><td>≥ 0.85%</td><td>≥ 0.70%</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Formula List --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Saved Formulas</span>
            <span style="color:#6b7280;font-size:0.88rem;">{{ $formulas->count() }} formula(s) saved</span>
        </div>

        @if($formulas->isEmpty())
            <div class="empty-state">No feed formulas yet. <a href="{{ route('admin.feed-mix.create') }}" style="color:#22c55e;">Create your first formula →</a></div>
        @else
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Formula Name</th>
                        <th>Life Stage</th>
                        <th>Total Batch</th>
                        <th>Nutrient Status</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formulas as $formula)
                    @php
                        $stageLabels = ['starter'=>'Starter','grower'=>'Grower','finisher'=>'Finisher','breeder'=>'Breeder'];
                        $stageBadge  = ['starter'=>'badge-blue','grower'=>'badge-green','finisher'=>'badge-amber','breeder'=>'badge-purple'];
                        $allMet = $formula->meets_requirements ? !in_array(false, $formula->meets_requirements) : null;
                    @endphp
                    <tr>
                        <td><strong>{{ $formula->name }}</strong></td>
                        <td><span class="badge {{ $stageBadge[$formula->life_stage] ?? 'badge-blue' }}">{{ $stageLabels[$formula->life_stage] ?? $formula->life_stage }}</span></td>
                        <td>{{ $formula->total_batch_sacks }} sack(s)</td>
                        <td>
                            @if($allMet === true)
                                <span class="badge badge-green">✅ Meets Requirements</span>
                            @elseif($allMet === false)
                                <span class="badge badge-red">⚠️ Deficient</span>
                            @else
                                <span class="badge" style="background:#f3f4f6;color:#6b7280;">—</span>
                            @endif
                        </td>
                        <td>{{ $formula->creator->name ?? '—' }}</td>
                        <td>{{ $formula->created_at->format('M j, Y') }}</td>
                        <td style="display:flex;gap:8px;align-items:center;">
                            <a href="{{ route('admin.feed-mix.show', $formula) }}" class="btn-secondary" style="padding:6px 12px;font-size:0.82rem;">View</a>
                            <form method="POST" action="{{ route('admin.feed-mix.destroy', $formula) }}" onsubmit="return confirm('Delete this formula?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#fee2e2;color:#991b1b;border:none;padding:6px 12px;border-radius:8px;cursor:pointer;font-size:0.82rem;font-weight:600;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
