@extends('layouts.master')
@section('contents')
<style>
.ing-wrap { padding: 32px; }
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
.table th { text-align: left; padding: 11px 12px; color: #6b7280; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; border-bottom: 2px solid #f3f4f6; }
.table td { padding: 12px 12px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 0.9rem; vertical-align: middle; }
.table tr:last-child td { border-bottom: none; }
.alert-box { border-radius: 14px; padding: 14px 20px; margin-bottom: 20px; }
.alert-box.success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; }
.alert-box.error   { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
.empty-state { text-align: center; color: #9ca3af; padding: 32px; }

/* Add ingredient form */
.add-form { background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 24px; margin-bottom: 24px; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; }
.form-row label { display: block; font-size: 0.82rem; color: #374151; margin-bottom: 6px; font-weight: 500; }
.form-row input { width: 100%; border: 1px solid #d1d5db; border-radius: 10px; padding: 9px 12px; font-size: 0.9rem; box-sizing: border-box; }
.form-row input:focus { outline: none; border-color: #22c55e; box-shadow: 0 0 0 3px rgba(34,197,94,0.12); }
.form-row.full { grid-column: 1 / -1; }

/* Edit modal */
.modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 1000; align-items: center; justify-content: center; }
.modal-overlay.show { display: flex; }
.modal-box { background: #fff; border-radius: 20px; padding: 32px; width: 90%; max-width: 680px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); }
.modal-title { font-size: 1.1rem; font-weight: 700; color: #111827; margin-bottom: 20px; }
.modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
.btn-cancel { padding: 10px 18px; border-radius: 10px; background: #f1f5f9; border: 1px solid #e2e8f0; cursor: pointer; font-weight: 600; font-size: 0.88rem; }
</style>

<div class="ing-wrap">
    <div class="top-bar">
        <div>
            <h1 class="page-title">⚗️ Ingredient Library</h1>
            <p class="page-subtitle">Manage the nutrient profiles for each raw feed ingredient used in formulas. All nutrient values are per kg of ingredient.</p>
        </div>
        <a href="{{ route('admin.feed-mix.index') }}" class="btn-secondary">← Back to Feed Mixing</a>
    </div>

    @if(session('success'))
        <div class="alert-box success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-box error">⚠️ {{ $errors->first() }}</div>
    @endif

    {{-- Add Ingredient Form --}}
    <div class="add-form">
        <div style="font-weight:700;color:#111827;margin-bottom:14px;font-size:1rem;">➕ Add New Ingredient</div>
        <form method="POST" action="{{ route('admin.feed-ingredients.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-row full">
                    <label for="name">Ingredient Name *</label>
                    <input id="name" name="name" type="text" placeholder="e.g. Yellow Corn" value="{{ old('name') }}" required>
                </div>
                <div class="form-row">
                    <label for="crude_protein">Crude Protein (%)</label>
                    <input id="crude_protein" name="crude_protein" type="number" step="0.01" min="0" max="100" value="{{ old('crude_protein', 0) }}" required>
                </div>
                <div class="form-row">
                    <label for="metabolizable_energy">ME (kcal/kg)</label>
                    <input id="metabolizable_energy" name="metabolizable_energy" type="number" step="1" min="0" value="{{ old('metabolizable_energy', 0) }}" required>
                </div>
                <div class="form-row">
                    <label for="crude_fat">Crude Fat (%)</label>
                    <input id="crude_fat" name="crude_fat" type="number" step="0.01" min="0" max="100" value="{{ old('crude_fat', 0) }}" required>
                </div>
                <div class="form-row">
                    <label for="crude_fiber">Crude Fiber (%)</label>
                    <input id="crude_fiber" name="crude_fiber" type="number" step="0.01" min="0" max="100" value="{{ old('crude_fiber', 0) }}" required>
                </div>
                <div class="form-row">
                    <label for="calcium">Calcium (%)</label>
                    <input id="calcium" name="calcium" type="number" step="0.001" min="0" max="100" value="{{ old('calcium', 0) }}" required>
                </div>
                <div class="form-row">
                    <label for="phosphorus">Phosphorus (%)</label>
                    <input id="phosphorus" name="phosphorus" type="number" step="0.001" min="0" max="100" value="{{ old('phosphorus', 0) }}" required>
                </div>
                <div class="form-row">
                    <label for="cost_per_sack">Cost / Sack (₱)</label>
                    <input id="cost_per_sack" name="cost_per_sack" type="number" step="0.01" min="0" value="{{ old('cost_per_sack') }}" placeholder="Optional">
                </div>
            </div>
            <button type="submit" class="btn-primary" style="margin-top:16px;">Save Ingredient</button>
        </form>
    </div>

    {{-- Ingredient Table --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">All Ingredients</span>
            <span style="color:#6b7280;font-size:0.88rem;">{{ $ingredients->count() }} ingredient(s)</span>
        </div>
        @if($ingredients->isEmpty())
            <div class="empty-state">No ingredients yet. Add one above.</div>
        @else
        <div style="overflow-x:auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>CP (%)</th>
                        <th>ME (kcal/kg)</th>
                        <th>Fat (%)</th>
                        <th>Fiber (%)</th>
                        <th>Ca (%)</th>
                        <th>P (%)</th>
                        <th>Cost/Sack</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ingredients as $ing)
                    <tr>
                        <td><strong>{{ $ing->name }}</strong></td>
                        <td>{{ $ing->crude_protein }}</td>
                        <td>{{ number_format($ing->metabolizable_energy) }}</td>
                        <td>{{ $ing->crude_fat }}</td>
                        <td>{{ $ing->crude_fiber }}</td>
                        <td>{{ $ing->calcium }}</td>
                        <td>{{ $ing->phosphorus }}</td>
                        <td>{{ $ing->cost_per_sack ? '₱' . number_format($ing->cost_per_sack, 2) : '—' }}</td>
                        <td style="display:flex;gap:6px;align-items:center;">
                            <button onclick="openEdit({{ $ing->id }}, {{ json_encode($ing) }})" class="btn-secondary" style="padding:5px 10px;font-size:0.8rem;">Edit</button>
                            <form method="POST" action="{{ route('admin.feed-ingredients.destroy', $ing) }}" onsubmit="return confirm('Delete {{ $ing->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:#fee2e2;color:#991b1b;border:none;padding:5px 10px;border-radius:8px;cursor:pointer;font-size:0.8rem;font-weight:600;">Delete</button>
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

{{-- Edit Modal --}}
<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <div class="modal-title">✏️ Edit Ingredient</div>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-row full">
                    <label>Name *</label>
                    <input name="name" id="edit_name" type="text" required>
                </div>
                <div class="form-row">
                    <label>Crude Protein (%)</label>
                    <input name="crude_protein" id="edit_cp" type="number" step="0.01" min="0" max="100" required>
                </div>
                <div class="form-row">
                    <label>ME (kcal/kg)</label>
                    <input name="metabolizable_energy" id="edit_me" type="number" step="1" min="0" required>
                </div>
                <div class="form-row">
                    <label>Crude Fat (%)</label>
                    <input name="crude_fat" id="edit_fat" type="number" step="0.01" min="0" max="100" required>
                </div>
                <div class="form-row">
                    <label>Crude Fiber (%)</label>
                    <input name="crude_fiber" id="edit_fiber" type="number" step="0.01" min="0" max="100" required>
                </div>
                <div class="form-row">
                    <label>Calcium (%)</label>
                    <input name="calcium" id="edit_ca" type="number" step="0.001" min="0" max="100" required>
                </div>
                <div class="form-row">
                    <label>Phosphorus (%)</label>
                    <input name="phosphorus" id="edit_p" type="number" step="0.001" min="0" max="100" required>
                </div>
                <div class="form-row">
                    <label>Cost / Sack (₱)</label>
                    <input name="cost_per_sack" id="edit_cost" type="number" step="0.01" min="0" placeholder="Optional">
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEdit()">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEdit(id, ing) {
    document.getElementById('editForm').action = '/admin/feed-ingredients/' + id;
    document.getElementById('edit_name').value  = ing.name;
    document.getElementById('edit_cp').value    = ing.crude_protein;
    document.getElementById('edit_me').value    = ing.metabolizable_energy;
    document.getElementById('edit_fat').value   = ing.crude_fat;
    document.getElementById('edit_fiber').value = ing.crude_fiber;
    document.getElementById('edit_ca').value    = ing.calcium;
    document.getElementById('edit_p').value     = ing.phosphorus;
    document.getElementById('edit_cost').value  = ing.cost_per_sack ?? '';
    document.getElementById('editModal').classList.add('show');
}
function closeEdit() {
    document.getElementById('editModal').classList.remove('show');
}
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) closeEdit();
});
</script>
@endsection
