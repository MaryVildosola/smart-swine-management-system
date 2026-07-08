@extends('layouts.master')
@section('contents')
<style>
.show-wrap { padding: 32px; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
.page-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 24px; }
.top-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
.btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border-radius: 12px; background: #22c55e; color: #fff; font-weight: 600; border: none; cursor: pointer; text-decoration: none; font-size: 0.9rem; transition: background 0.2s; }
.btn-primary:hover { background: #16a34a; color: #fff; }
.btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; background: #f1f5f9; color: #374151; font-weight: 600; border: 1px solid #e2e8f0; cursor: pointer; text-decoration: none; font-size: 0.88rem; }
.btn-secondary:hover { background: #e2e8f0; color: #111; }
.panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; padding: 28px; margin-bottom: 24px; }
.panel-title { font-size: 1.05rem; font-weight: 700; color: #111827; margin-bottom: 16px; }
.meta-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; margin-bottom: 20px; }
.meta-card { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 14px; padding: 16px; }
.meta-label { font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 6px; }
.meta-value { font-size: 1.2rem; font-weight: 700; color: #111827; }
.badge { display: inline-block; padding: 4px 12px; border-radius: 999px; font-size: 0.82rem; font-weight: 600; }
.badge-green  { background: #dcfce7; color: #166534; }
.badge-red    { background: #fee2e2; color: #991b1b; }
.badge-blue   { background: #dbeafe; color: #1e40af; }
.badge-amber  { background: #fef3c7; color: #92400e; }
.badge-purple { background: #f3e8ff; color: #6b21a8; }
.table { width: 100%; border-collapse: collapse; }
.table th { text-align: left; padding: 11px 12px; color: #6b7280; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 2px solid #f3f4f6; }
.table td { padding: 12px 12px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 0.9rem; vertical-align: middle; }
.table tr:last-child td { border-bottom: none; }
.nutrient-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; }
.nutrient-card { border: 2px solid #e5e7eb; border-radius: 14px; padding: 16px; text-align: center; }
.nutrient-card.pass { border-color: #86efac; background: #f0fdf4; }
.nutrient-card.fail { border-color: #fca5a5; background: #fff5f5; }
.nutrient-card .n-label { font-size: 0.78rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 8px; }
.nutrient-card .n-value { font-size: 1.4rem; font-weight: 700; }
.nutrient-card.pass .n-value { color: #16a34a; }
.nutrient-card.fail .n-value { color: #dc2626; }
.nutrient-card .n-req { font-size: 0.75rem; color: #9ca3af; margin-top: 4px; }
.nutrient-card .n-status { font-size: 0.8rem; font-weight: 600; margin-top: 6px; }
.nutrient-card.pass .n-status { color: #16a34a; }
.nutrient-card.fail .n-status { color: #dc2626; }
.overall-banner { border-radius: 14px; padding: 16px 22px; font-weight: 700; font-size: 1rem; text-align: center; margin-bottom: 20px; }
.overall-banner.pass { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
.overall-banner.fail { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.notes-box { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 14px; padding: 16px; color: #374151; font-size: 0.92rem; }
@media print { .top-bar .btn-secondary, .top-bar form { display: none; } }
</style>

<div class="show-wrap">
    <div class="top-bar">
        <div>
            <h1 class="page-title">🧪 {{ $formula->name }}</h1>
            <p class="page-subtitle">Feed formula result — nutrient analysis vs. requirements for <strong>{{ ucfirst($formula->life_stage) }}</strong> pigs.</p>
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button onclick="window.print()" class="btn-secondary">🖨️ Print</button>
            <form method="POST" action="{{ route('admin.feed-mix.destroy', $formula->id) }}" onsubmit="return confirm('Delete this formula?')" style="display:inline;">
                @csrf @method('DELETE')
                <button type="submit" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:10px 18px;border-radius:12px;cursor:pointer;font-size:0.88rem;font-weight:600;">Delete</button>
            </form>
            <a href="{{ route('admin.feed-mix.index') }}" class="btn-secondary">← Back to Formulas</a>
        </div>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #86efac;border-radius:12px;padding:12px 18px;color:#166534;margin-bottom:18px;">✅ {{ session('success') }}</div>
    @endif

    {{-- Overall Status Banner --}}
    @php
        $allPass = !empty($checks) && !in_array(false, $checks);
        $stageLabels = ['starter'=>'Starter','grower'=>'Grower','finisher'=>'Finisher','breeder'=>'Breeder'];
        $stageBadgeMap = ['starter'=>'badge-blue','grower'=>'badge-green','finisher'=>'badge-amber','breeder'=>'badge-purple'];
    @endphp
    <div class="overall-banner {{ $allPass ? 'pass' : 'fail' }}">
        @if($allPass)
            ✅ This formula meets ALL nutrient requirements for {{ $stageLabels[$formula->life_stage] ?? $formula->life_stage }} pigs!
        @else
            ⚠️ This formula is DEFICIENT in some nutrients. Review the analysis below.
        @endif
    </div>

    {{-- About This Formula --}}
    <div class="panel" style="background:#f0f9ff;border-color:#bae6fd;">
        <div style="display:flex;align-items:flex-start;gap:14px;">
            <span style="font-size:1.6rem;line-height:1;">📋</span>
            <div>
                <div style="font-size:0.85rem;font-weight:700;color:#0369a1;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">About This Feed Formula</div>
                <p style="font-size:0.92rem;color:#374151;margin:0 0 8px;line-height:1.65;">
                    A <strong>feed mixing formula</strong> defines the exact combination of raw feed ingredients (e.g., corn, soybean meal, fishmeal) blended per batch to meet the specific nutritional requirements of pigs at a given life stage. Each ingredient is measured in <strong>50 kg sacks</strong>, and the system automatically computes the weighted average nutrient profile of the resulting mix.
                </p>
                <p style="font-size:0.87rem;color:#6b7280;margin:0;line-height:1.6;">
                    📚 <strong>Nutrient standards are based on</strong> Philippine swine industry guidelines aligned with <em>NRC (National Research Council)</em> and <em>PCAARRD</em> recommendations — covering Crude Protein (CP), Metabolizable Energy (ME), Crude Fat, Crude Fiber, Calcium (Ca), and Phosphorus (P) for Starter, Grower, Finisher, and Breeder/Sow stages.
                </p>
            </div>
        </div>
    </div>

    {{-- Formula Meta --}}
    <div class="panel">
        <div class="panel-title">Formula Overview</div>
        <div class="meta-grid">
            <div class="meta-card">
                <div class="meta-label">Life Stage</div>
                <div class="meta-value"><span class="badge {{ $stageBadgeMap[$formula->life_stage] ?? 'badge-blue' }}">{{ $stageLabels[$formula->life_stage] ?? ucfirst($formula->life_stage) }}</span></div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Total Batch</div>
                <div class="meta-value">{{ $formula->total_batch_sacks }} sack(s)</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Total Weight</div>
                <div class="meta-value">{{ number_format($formula->total_batch_sacks * 50) }} kg</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Ingredients</div>
                <div class="meta-value">{{ $formula->formulaIngredients->count() }}</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Created By</div>
                <div class="meta-value" style="font-size:0.95rem;">{{ $formula->creator->name ?? '—' }}</div>
            </div>
            <div class="meta-card">
                <div class="meta-label">Date Created</div>
                <div class="meta-value" style="font-size:0.95rem;">{{ $formula->created_at->format('M j, Y') }}</div>
            </div>
        </div>

        @if($formula->notes)
        <div>
            <div class="meta-label" style="margin-bottom:8px;">Notes</div>
            <div class="notes-box">{{ $formula->notes }}</div>
        </div>
        @endif
    </div>

    {{-- Ingredient Breakdown --}}
    <div class="panel">
        <div class="panel-title">Ingredient Breakdown</div>
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ingredient</th>
                        <th>Qty (sacks)</th>
                        <th>Qty (kg)</th>
                        <th>% of Batch</th>
                        <th>CP (%)</th>
                        <th>ME (kcal/kg)</th>
                        <th>Fat (%)</th>
                        <th>Fiber (%)</th>
                        <th>Ca (%)</th>
                        <th>P (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($formula->formulaIngredients as $item)
                    @php
                        $kg  = $item->quantity_sacks * 50;
                        $pct = $formula->total_batch_sacks > 0 ? round($item->quantity_sacks / $formula->total_batch_sacks * 100, 1) : 0;
                        $ing = $item->ingredient;
                    @endphp
                    <tr>
                        <td><strong>{{ $ing->name }}</strong></td>
                        <td>{{ $item->quantity_sacks }}</td>
                        <td>{{ number_format($kg) }} kg</td>
                        <td>{{ $pct }}%</td>
                        <td>{{ $ing->crude_protein }}</td>
                        <td>{{ number_format($ing->metabolizable_energy) }}</td>
                        <td>{{ $ing->crude_fat }}</td>
                        <td>{{ $ing->crude_fiber }}</td>
                        <td>{{ $ing->calcium }}</td>
                        <td>{{ $ing->phosphorus }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Nutrient Analysis --}}
    <div class="panel">
        <div class="panel-title">Nutrient Analysis vs. Requirements</div>
        <div class="nutrient-grid">
            @php
                $nutrientDefs = [
                    'cp'    => ['label' => 'Crude Protein', 'unit' => '%', 'req' => '≥ '.$requirements['cp'].'%'],
                    'me'    => ['label' => 'Metab. Energy',  'unit' => ' kcal/kg', 'req' => '≥ '.number_format($requirements['me']).' kcal/kg'],
                    'fat'   => ['label' => 'Crude Fat',      'unit' => '%', 'req' => $requirements['fat_min'].'–'.$requirements['fat_max'].'%'],
                    'fiber' => ['label' => 'Crude Fiber',    'unit' => '%', 'req' => '≤ '.$requirements['fiber_max'].'%'],
                    'ca'    => ['label' => 'Calcium',        'unit' => '%', 'req' => '≥ '.$requirements['ca'].'%'],
                    'p'     => ['label' => 'Phosphorus',     'unit' => '%', 'req' => '≥ '.$requirements['p'].'%'],
                ];
            @endphp

            @foreach($nutrientDefs as $key => $def)
            @php $pass = $checks[$key] ?? false; @endphp
            <div class="nutrient-card {{ $pass ? 'pass' : 'fail' }}">
                <div class="n-label">{{ $def['label'] }}</div>
                <div class="n-value">
                    @if($key === 'me')
                        {{ number_format($nutrients[$key]) }}
                    @elseif(in_array($key, ['ca','p']))
                        {{ number_format($nutrients[$key], 3) }}{{ $def['unit'] }}
                    @else
                        {{ number_format($nutrients[$key], 2) }}{{ $def['unit'] }}
                    @endif
                </div>
                <div class="n-req">Required: {{ $def['req'] }}</div>
                <div class="n-status">{{ $pass ? '✅ Meets Requirement' : '❌ Deficient' }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Nutrient Requirements Reference for this stage --}}
    <div class="panel" style="background:#f8fafc;">
        <div class="panel-title" style="color:#6b7280;font-size:0.9rem;text-transform:uppercase;letter-spacing:0.04em;">All Stage Requirements (Reference)</div>
        <div style="overflow-x:auto;">
            <table class="table" style="font-size:0.85rem;">
                <thead>
                    <tr>
                        <th>Life Stage</th>
                        <th>CP (%)</th>
                        <th>ME (kcal/kg)</th>
                        <th>Fat (%)</th>
                        <th>Fiber (%)</th>
                        <th>Ca (%)</th>
                        <th>P (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allRequirements as $stage => $req)
                    <tr style="{{ $stage === $formula->life_stage ? 'background:#f0fdf4;font-weight:600;' : '' }}">
                        <td>{{ ucfirst($stage) }} {{ $stage === $formula->life_stage ? '← this formula' : '' }}</td>
                        <td>≥{{ $req['cp'] }}</td>
                        <td>≥{{ number_format($req['me']) }}</td>
                        <td>{{ $req['fat_min'] }}–{{ $req['fat_max'] }}</td>
                        <td>≤{{ $req['fiber_max'] }}</td>
                        <td>≥{{ $req['ca'] }}</td>
                        <td>≥{{ $req['p'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
