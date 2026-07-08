@extends('layouts.master')
@section('contents')
<style>
.create-wrap { padding: 32px; }
.page-title { font-size: 1.6rem; font-weight: 700; color: #111827; margin-bottom: 6px; }
.page-subtitle { color: #6b7280; font-size: 0.95rem; margin-bottom: 24px; }
.top-bar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
.btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 12px; background: #22c55e; color: #fff; font-weight: 600; border: none; cursor: pointer; font-size: 0.9rem; transition: background 0.2s; }
.btn-primary:hover { background: #16a34a; }
.btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 12px; background: #f1f5f9; color: #374151; font-weight: 600; border: 1px solid #e2e8f0; cursor: pointer; text-decoration: none; font-size: 0.88rem; }
.btn-secondary:hover { background: #e2e8f0; color: #111; }
.btn-add-row { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; background: #f0fdf4; color: #16a34a; border: 1px dashed #86efac; cursor: pointer; font-size: 0.88rem; font-weight: 600; margin-top: 14px; }
.btn-add-row:hover { background: #dcfce7; }
.btn-remove { background: #fee2e2; color: #991b1b; border: none; padding: 6px 10px; border-radius: 8px; cursor: pointer; font-size: 0.82rem; font-weight: 600; }
.grid-layout { display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 24px; align-items: start; }
@media(max-width:900px) { .grid-layout { grid-template-columns: 1fr; } }
.panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; padding: 28px; }
.panel-title { font-size: 1.05rem; font-weight: 700; color: #111827; margin-bottom: 18px; }
.form-row { margin-bottom: 16px; }
.form-row label { display: block; font-size: 0.88rem; color: #374151; font-weight: 500; margin-bottom: 7px; }
.form-row input, .form-row select, .form-row textarea { width: 100%; border: 1px solid #d1d5db; border-radius: 10px; padding: 10px 14px; font-size: 0.92rem; box-sizing: border-box; }
.form-row input:focus, .form-row select:focus, .form-row textarea:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.12); }
.ingredient-row { display: grid; grid-template-columns: 1fr 130px 42px; gap: 10px; align-items: center; margin-bottom: 10px; }
.ingredient-row select, .ingredient-row input { padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 0.88rem; width: 100%; box-sizing: border-box; }
.ingredient-row select:focus, .ingredient-row input:focus { outline: none; border-color: #22c55e; }
.preview-card { background: #f8fafc; border: 1px solid #e5e7eb; border-radius: 14px; padding: 18px; }
.preview-title { font-size: 0.88rem; font-weight: 700; color: #374151; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.04em; }
.nutrient-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f3f4f6; font-size: 0.88rem; }
.nutrient-row:last-child { border-bottom: none; }
.nutrient-label { color: #6b7280; }
.nutrient-value { font-weight: 700; color: #111827; }
.nutrient-value.pass { color: #16a34a; }
.nutrient-value.fail { color: #dc2626; }
.nutrient-req { font-size: 0.75rem; color: #9ca3af; margin-left: 4px; }
.stage-select-group { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 6px; }
.stage-btn { padding: 8px 16px; border-radius: 999px; border: 2px solid #e5e7eb; background: #f8fafc; cursor: pointer; font-size: 0.85rem; font-weight: 600; color: #374151; transition: all 0.2s; }
.stage-btn.active { border-color: #22c55e; background: #f0fdf4; color: #15803d; }
.batch-summary { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 12px 16px; margin-bottom: 14px; font-size: 0.88rem; color: #166534; font-weight: 600; }
.req-hint { font-size: 0.75rem; color: #9ca3af; margin-top: 4px; }
</style>

<div class="create-wrap">
    <div class="top-bar">
        <div>
            <h1 class="page-title">🧪 Create Feed Formula</h1>
            <p class="page-subtitle">Mix ingredients by sack and let the system calculate the resulting nutrient profile.</p>
        </div>
        <a href="{{ route('admin.feed-mix.index') }}" class="btn-secondary">← Back</a>
    </div>

    <form method="POST" action="{{ route('admin.feed-mix.store') }}" id="formulaForm">
        @csrf
        <div class="grid-layout">

            {{-- LEFT: Formula Builder --}}
            <div class="panel">
                <div class="panel-title">Formula Details</div>

                <div class="form-row">
                    <label for="name">Formula Name *</label>
                    <input id="name" name="name" type="text" placeholder="e.g. Grower Mix Batch A" value="{{ old('name') }}" required>
                </div>

                <div class="form-row">
                    <label>Life Stage *</label>
                    <input type="hidden" name="life_stage" id="life_stage_input" value="{{ old('life_stage', 'grower') }}" required>
                    <div class="stage-select-group">
                        <button type="button" class="stage-btn {{ old('life_stage','grower')==='starter' ? 'active' : '' }}" onclick="setStage('starter')">🐷 Starter (10–25 kg)</button>
                        <button type="button" class="stage-btn {{ old('life_stage','grower')==='grower' ? 'active' : '' }}" onclick="setStage('grower')">🐖 Grower (25–60 kg)</button>
                        <button type="button" class="stage-btn {{ old('life_stage','grower')==='finisher' ? 'active' : '' }}" onclick="setStage('finisher')">🥩 Finisher (60–100 kg)</button>
                        <button type="button" class="stage-btn {{ old('life_stage','grower')==='breeder' ? 'active' : '' }}" onclick="setStage('breeder')">🐽 Breeder / Sow</button>
                    </div>
                </div>

                <div class="form-row">
                    <label for="notes">Notes (optional)</label>
                    <textarea id="notes" name="notes" rows="2" placeholder="Any remarks about this formula...">{{ old('notes') }}</textarea>
                </div>

                <hr style="border:none;border-top:1px solid #f3f4f6;margin:18px 0;">

                <div class="panel-title">Ingredients</div>
                <div style="display:grid;grid-template-columns:1fr 130px 42px;gap:10px;margin-bottom:8px;padding:0 2px;">
                    <span style="font-size:0.78rem;color:#6b7280;font-weight:600;text-transform:uppercase;">Ingredient</span>
                    <span style="font-size:0.78rem;color:#6b7280;font-weight:600;text-transform:uppercase;">Qty (sacks)</span>
                    <span></span>
                </div>

                <div id="ingredient-rows">
                    {{-- Initial row --}}
                    <div class="ingredient-row" data-row="0">
                        <select name="ingredients[0][id]" class="ing-select" required>
                            <option value="">— Select Ingredient —</option>
                            @foreach($ingredients as $ing)
                                <option value="{{ $ing->id }}" data-cp="{{ $ing->crude_protein }}" data-me="{{ $ing->metabolizable_energy }}" data-fat="{{ $ing->crude_fat }}" data-fiber="{{ $ing->crude_fiber }}" data-ca="{{ $ing->calcium }}" data-p="{{ $ing->phosphorus }}">{{ $ing->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="ingredients[0][sacks]" class="ing-sacks" step="0.5" min="0.5" placeholder="0" required>
                        <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
                    </div>
                </div>

                <button type="button" class="btn-add-row" onclick="addRow()">+ Add Ingredient</button>

                <div style="margin-top:22px;">
                    <div id="batchSummary" class="batch-summary" style="display:none;"></div>
                    <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">💾 Save Formula</button>
                </div>
            </div>

            {{-- RIGHT: Live Nutrient Preview --}}
            <div class="panel" style="position:sticky;top:24px;">
                <div class="panel-title">📊 Live Nutrient Preview</div>
                <p style="font-size:0.85rem;color:#9ca3af;margin-bottom:16px;">Updates as you add ingredients. Green = meets requirement. Red = deficient.</p>

                <div id="preview-empty" style="text-align:center;color:#cbd5e1;padding:24px 0;font-size:0.9rem;">
                    Add at least one ingredient to see the nutrient preview.
                </div>

                <div id="preview-content" style="display:none;">
                    <div class="preview-card">
                        <div class="preview-title">Computed Nutrient Profile (weighted avg/kg)</div>
                        <div class="nutrient-row">
                            <span class="nutrient-label">Crude Protein</span>
                            <span><span class="nutrient-value" id="pv-cp">—</span> <span class="nutrient-req" id="req-cp"></span></span>
                        </div>
                        <div class="nutrient-row">
                            <span class="nutrient-label">Metab. Energy</span>
                            <span><span class="nutrient-value" id="pv-me">—</span> <span class="nutrient-req" id="req-me"></span></span>
                        </div>
                        <div class="nutrient-row">
                            <span class="nutrient-label">Crude Fat</span>
                            <span><span class="nutrient-value" id="pv-fat">—</span> <span class="nutrient-req" id="req-fat"></span></span>
                        </div>
                        <div class="nutrient-row">
                            <span class="nutrient-label">Crude Fiber</span>
                            <span><span class="nutrient-value" id="pv-fiber">—</span> <span class="nutrient-req" id="req-fiber"></span></span>
                        </div>
                        <div class="nutrient-row">
                            <span class="nutrient-label">Calcium</span>
                            <span><span class="nutrient-value" id="pv-ca">—</span> <span class="nutrient-req" id="req-ca"></span></span>
                        </div>
                        <div class="nutrient-row">
                            <span class="nutrient-label">Phosphorus</span>
                            <span><span class="nutrient-value" id="pv-p">—</span> <span class="nutrient-req" id="req-p"></span></span>
                        </div>
                    </div>

                    <div id="overall-status" style="margin-top:14px;border-radius:12px;padding:12px 16px;text-align:center;font-weight:700;font-size:0.95rem;"></div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
const REQUIREMENTS = @json($requirements);
const KG_PER_SACK  = 50;
let rowIndex = 1;

function setStage(stage) {
    document.getElementById('life_stage_input').value = stage;
    document.querySelectorAll('.stage-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    recompute();
}

function addRow() {
    const container = document.getElementById('ingredient-rows');
    const template  = container.querySelector('.ingredient-row');
    const clone     = template.cloneNode(true);
    clone.dataset.row = rowIndex;

    // Update input names
    clone.querySelector('.ing-select').name  = `ingredients[${rowIndex}][id]`;
    clone.querySelector('.ing-select').value = '';
    clone.querySelector('.ing-sacks').name   = `ingredients[${rowIndex}][sacks]`;
    clone.querySelector('.ing-sacks').value  = '';

    // Re-attach listeners
    clone.querySelector('.ing-select').addEventListener('change', recompute);
    clone.querySelector('.ing-sacks').addEventListener('input', recompute);

    container.appendChild(clone);
    rowIndex++;
    recompute();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.ingredient-row');
    if (rows.length <= 1) return;
    btn.closest('.ingredient-row').remove();
    recompute();
}

function recompute() {
    const stage = document.getElementById('life_stage_input').value;
    const req   = REQUIREMENTS[stage] || {};
    const rows  = document.querySelectorAll('.ingredient-row');

    let totalKg = 0, sumCP = 0, sumME = 0, sumFat = 0, sumFiber = 0, sumCa = 0, sumP = 0;
    let hasData = false;

    rows.forEach(row => {
        const sel   = row.querySelector('.ing-select');
        const sacks = parseFloat(row.querySelector('.ing-sacks').value) || 0;
        if (!sel.value || !sacks) return;

        const opt = sel.options[sel.selectedIndex];
        const kg  = sacks * KG_PER_SACK;
        totalKg   += kg;
        sumCP     += parseFloat(opt.dataset.cp)    * kg;
        sumME     += parseFloat(opt.dataset.me)    * kg;
        sumFat    += parseFloat(opt.dataset.fat)   * kg;
        sumFiber  += parseFloat(opt.dataset.fiber) * kg;
        sumCa     += parseFloat(opt.dataset.ca)    * kg;
        sumP      += parseFloat(opt.dataset.p)     * kg;
        hasData    = true;
    });

    if (!hasData || totalKg === 0) {
        document.getElementById('preview-empty').style.display = 'block';
        document.getElementById('preview-content').style.display = 'none';
        document.getElementById('batchSummary').style.display = 'none';
        return;
    }

    document.getElementById('preview-empty').style.display = 'none';
    document.getElementById('preview-content').style.display = 'block';

    const totalSacks = totalKg / KG_PER_SACK;
    document.getElementById('batchSummary').style.display = 'block';
    document.getElementById('batchSummary').textContent = `Total batch: ${totalSacks} sack(s) = ${totalKg.toLocaleString()} kg`;

    const cp    = sumCP    / totalKg;
    const me    = sumME    / totalKg;
    const fat   = sumFat   / totalKg;
    const fiber = sumFiber / totalKg;
    const ca    = sumCa    / totalKg;
    const p     = sumP     / totalKg;

    function fmt(v, dec) { return v.toFixed(dec); }

    // CP
    const cpPass = req.cp && cp >= req.cp;
    document.getElementById('pv-cp').textContent    = fmt(cp, 2) + '%';
    document.getElementById('pv-cp').className      = 'nutrient-value ' + (req.cp ? (cpPass ? 'pass' : 'fail') : '');
    document.getElementById('req-cp').textContent   = req.cp ? `(need ≥${req.cp}%)` : '';

    // ME
    const mePass = req.me && me >= req.me;
    document.getElementById('pv-me').textContent    = fmt(me, 0) + ' kcal/kg';
    document.getElementById('pv-me').className      = 'nutrient-value ' + (req.me ? (mePass ? 'pass' : 'fail') : '');
    document.getElementById('req-me').textContent   = req.me ? `(need ≥${req.me})` : '';

    // Fat
    const fatPass = req.fat_min && fat >= req.fat_min && fat <= req.fat_max;
    document.getElementById('pv-fat').textContent   = fmt(fat, 2) + '%';
    document.getElementById('pv-fat').className     = 'nutrient-value ' + (req.fat_min ? (fatPass ? 'pass' : 'fail') : '');
    document.getElementById('req-fat').textContent  = req.fat_min ? `(need ${req.fat_min}–${req.fat_max}%)` : '';

    // Fiber
    const fiberPass = req.fiber_max && fiber <= req.fiber_max;
    document.getElementById('pv-fiber').textContent  = fmt(fiber, 2) + '%';
    document.getElementById('pv-fiber').className    = 'nutrient-value ' + (req.fiber_max ? (fiberPass ? 'pass' : 'fail') : '');
    document.getElementById('req-fiber').textContent = req.fiber_max ? `(need ≤${req.fiber_max}%)` : '';

    // Ca
    const caPass = req.ca && ca >= req.ca;
    document.getElementById('pv-ca').textContent    = fmt(ca, 3) + '%';
    document.getElementById('pv-ca').className      = 'nutrient-value ' + (req.ca ? (caPass ? 'pass' : 'fail') : '');
    document.getElementById('req-ca').textContent   = req.ca ? `(need ≥${req.ca}%)` : '';

    // P
    const pPass = req.p && p >= req.p;
    document.getElementById('pv-p').textContent     = fmt(p, 3) + '%';
    document.getElementById('pv-p').className       = 'nutrient-value ' + (req.p ? (pPass ? 'pass' : 'fail') : '');
    document.getElementById('req-p').textContent    = req.p ? `(need ≥${req.p}%)` : '';

    // Overall status
    const allPass = cpPass && mePass && fatPass && fiberPass && caPass && pPass;
    const statusEl = document.getElementById('overall-status');
    if (allPass) {
        statusEl.style.background = '#dcfce7';
        statusEl.style.color      = '#166534';
        statusEl.textContent      = '✅ This formula meets all nutrient requirements!';
    } else {
        statusEl.style.background = '#fee2e2';
        statusEl.style.color      = '#991b1b';
        statusEl.textContent      = '⚠️ Some nutrients are deficient. Adjust the mix.';
    }
}

// Attach listeners to initial row
document.querySelector('.ing-select').addEventListener('change', recompute);
document.querySelector('.ing-sacks').addEventListener('input', recompute);

// Initial compute
recompute();
</script>
@endsection
