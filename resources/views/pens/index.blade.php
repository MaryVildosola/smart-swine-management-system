@extends('layouts.master')
@section('title', 'Pens & Pigs Report')

@section('contents')
    <style>
        /* Premium Soft UI Design Tokens */
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.4);
            --accent-green: #22c55e;
            --deep-slate: #0f172a;
            --soft-gray: #f8fafc;
        }

        .report-container {
            padding: 24px 40px;
        }

        .report-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 32px;
            align-items: start;
        }

        .pen-list-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .pen-accordion {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .pen-accordion.active-row {
            border-color: var(--accent-green);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.05);
        }

        .pen-header-row {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            background: #fff;
            transition: background 0.2s;
        }

        .pen-header-row:hover {
            background: #f8fafc;
        }

        .pen-identity {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pen-icon-circle {
            width: 44px;
            height: 44px;
            background: #f1f5f9;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #64748b;
            transition: all 0.3s;
        }

        .pen-accordion.active-row .pen-icon-circle {
            background: #f0fdf4;
            color: var(--accent-green);
        }

        .pen-name-text {
            font-size: 1rem;
            font-weight: 800;
            color: var(--deep-slate);
        }

        .pen-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
        }

        .mini-pigs-summary {
            display: flex;
            gap: 24px;
            margin-right: 20px;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-label {
            font-size: 0.55rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-val {
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
        }

        .summary-val.danger {
            color: #ef4444;
        }

        .pig-accordion-content {
            display: none;
            background: #fcfdfe;
            border-top: 1px solid #f1f5f9;
            padding: 20px 24px;
        }

        .pen-accordion.expanded .pig-accordion-content {
            display: block;
        }

        .pig-list-vertical {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .pig-row-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pig-row-item:hover {
            border-color: var(--accent-green);
            background: #f0fdf4;
            transform: translateX(4px);
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-indicator.healthy {
            background: #22c55e;
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.4);
        }

        .status-indicator.sick {
            background: #ef4444;
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.4);
        }

        .status-indicator.warning {
            background: #f59e0b;
            box-shadow: 0 0 8px rgba(245, 158, 11, 0.4);
        }

        .details-panel {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 32px;
            padding: 32px;
            position: sticky;
            top: 100px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
        }

        .panel-section {
            margin-top: 28px;
        }

        .section-hdr {
            font-size: 0.7rem;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .health-status-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .h-card {
            padding: 20px;
            border-radius: 20px;
            border: 1px solid transparent;
        }

        .h-card.h-green {
            background: #f0fdf4;
            border-color: #dcfce7;
        }

        .h-card.h-red {
            background: #fef2f2;
            border-color: #fee2e2;
        }

        .h-val {
            font-size: 1.5rem;
            font-weight: 900;
            margin-top: 4px;
        }

        .h-label {
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .btn-action-edit:hover {
            color: var(--accent-green);
            border-color: var(--accent-green);
            background: #f0fdf4;
        }

        .btn-action-delete:hover {
            color: #ef4444;
            border-color: #fee2e2;
            background: #fef2f2;
        }

        .progress-bar-bg {
            background: #f1f5f9;
            height: 10px;
            border-radius: 5px;
            margin: 12px 0;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: var(--accent-green);
            height: 100%;
            border-radius: 5px;
            transition: width 0.6s ease;
        }

        .finance-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .finance-item {
            background: #f8fafc;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .fin-label {
            font-size: 0.6rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 4px;
            display: block;
        }

        .fin-val {
            font-size: 0.9rem;
            font-weight: 800;
            color: #0f172a;
        }

        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .custom-modal {
            background: #fff;
            width: 90%;
            max-width: 550px;
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 1.5rem;
            cursor: pointer;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: var(--deep-slate);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.9rem;
            background: #fff;
            color: #1e293b;
            transition: all 0.2s;
        }

        .form-input:focus {
            border-color: var(--accent-green);
            outline: none;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }

        /* High-visibility Close Button for all SweetAlert modals on this page */
        .swal2-close {
            color: #ffffff !important;
            background: #1e293b !important;
            border-radius: 50% !important;
            width: 40px !important;
            height: 40px !important;
            margin-top: 15px !important;
            margin-right: 15px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 24px !important;
            box-shadow: 0 8px 16px rgba(0,0,0,0.4) !important;
            transition: all 0.2s !important;
            opacity: 1 !important;
            z-index: 9999 !important;
            border: 2px solid white !important;
        }
        .swal2-close:hover {
            background: #ef4444 !important;
            transform: scale(1.1) !important;
            color: #ffffff !important;
        }

        .modal-scroll-area {
            max-height: 65vh;
            overflow-y: auto;
            padding-right: 12px;
            margin-right: -12px;
        }

        .modal-scroll-area::-webkit-scrollbar {
            width: 6px;
        }

        .modal-scroll-area::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .modal-scroll-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .modal-scroll-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="report-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
            <div>
                <h1 style="font-size: 2rem; font-weight: 900; color: var(--deep-slate); margin: 0; letter-spacing: -0.04em;">
                    Pens & Pigs Report</h1>
                <p style="color: #64748b; font-weight: 500; margin-top: 4px;">Detailed batch summary and animal health
                    oversight</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <button onclick="openModal('addPenModal')"
                    style="background: var(--deep-slate); color: white; border: none; padding: 14px 28px; border-radius: 16px; font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-plus-circle' style="font-size: 1.2rem;"></i> New Pen
                </button>
            </div>
        </div>

        <div class="report-grid">
            <div class="pen-list-wrapper" id="pens-list-container">
                @forelse($pens as $pen)
                    <div class="pen-accordion {{ $loop->first ? 'active-row' : '' }}" data-id="{{ $pen->id }}">
                        <div class="pen-header-row" data-pen="{{ json_encode($pen) }}" onclick='handlePenClick(this, JSON.parse(this.dataset.pen))'>
                            <div class="pen-identity">
                                <div class="pen-icon-circle"><i class='bx bx-grid-alt'></i></div>
                                <div style="display: flex; flex-direction: column;">
                                    <span class="pen-name-text">{{ $pen->name }}</span>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span class="pen-section-label">{{ $pen->section ?: 'Batch Unassigned' }}</span>
                                        @if($pen->assignedPersonnel)
                                            <span style="font-size: 0.6rem; color: #22c55e; font-weight: 700; background: #f0fdf4; padding: 1px 6px; border-radius: 4px;">
                                                <i class='bx bx-user' style="font-size: 0.65rem;"></i> {{ $pen->assignedPersonnel->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div class="mini-pigs-summary">
                                    <div class="summary-item"><span class="summary-label">Total</span><span
                                            class="summary-val">{{ $pen->pigs->count() }}</span></div>
                                    <div class="summary-item"><span class="summary-label">Sick</span><span
                                            class="summary-val {{ $pen->pigs->where('health_status', 'Sick')->count() > 0 ? 'danger' : '' }}">{{ $pen->pigs->where('health_status', 'Sick')->count() }}</span>
                                    </div>
                                </div>
                                <button onclick="toggleAccordion(event, {{ $pen->id }})"
                                    style="background: #f8fafc; border: none; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <i class='bx bx-chevron-down' style="font-size: 1.25rem; color: #94a3b8;"></i>
                                </button>
                            </div>
                        </div>
                        <div class="pig-accordion-content" id="pig-list-{{ $pen->id }}">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                <span
                                    style="font-size: 0.7rem; font-weight: 900; color: #475569; text-transform: uppercase; letter-spacing: 0.1em;">Individual
                                    Pigs</span>
                                <button onclick="openAddPigModal({{ $pen->id }})"
                                    style="background: #f0fdf4; color: #16a34a; border: none; padding: 6px 14px; border-radius: 10px; font-size: 0.7rem; font-weight: 800; cursor: pointer;">+
                                    Register Animal</button>
                            </div>
                            <div class="pig-list-vertical">
                                @foreach ($pen->pigs as $pig)
                                    <div class="pig-row-item" onclick="viewPig({{ $pig->id }})">
                                        <div style="display: flex; align-items: center; gap: 14px;">
                                            <div
                                                class="status-indicator {{ strtolower($pig->health_status === 'Sick' ? 'sick' : ($pig->health_status === 'Warning' ? 'warning' : 'healthy')) }}">
                                            </div>
                                            <span
                                                style="font-weight: 800; font-size: 0.9rem; color: #1e293b;">#{{ $pig->tag }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 20px;">
                                            <div style="text-align: right;">
                                                <div
                                                    style="font-size: 0.6rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">
                                                    Weight</div>
                                                <div style="font-size: 0.8rem; font-weight: 700; color: #475569;">
                                                    {{ $pig->weight ?: 0 }} kg</div>
                                            </div>
                                            <i class='bx bx-chevron-right' style="color: #cbd5e1;"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: #94a3b8;">No pens found.</div>
                @endforelse
            </div>

            <div class="summary-column">
                <div class="details-panel" id="pen-summary-panel">
                    @if ($pens->isNotEmpty())
                        @php $firstPen = $pens->first(); @endphp
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px;">
                            <div>
                                <h2 style="font-size: 1.5rem; font-weight: 900; color: var(--deep-slate); margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <span id="side-pen-name">{{ $firstPen->name }}</span>
                                    <span id="side-pen-id" style="font-size: 0.65rem; background: #f1f5f9; color: #64748b; padding: 2px 8px; border-radius: 6px; font-weight: 800;">#{{ $firstPen->id }}</span>
                                </h2>
                                <p style="color: #64748b; font-weight: 500; margin: 4px 0 0;" id="side-pen-section">
                                    {{ $firstPen->section ?: 'Unassigned' }}</p>
                                <div id="side-pen-assignment" style="margin-top: 6px;">
                                    @if($firstPen->assignedPersonnel)
                                        <span style="font-size: 0.7rem; color: #22c55e; font-weight: 800; background: #f0fdf4; padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class='bx bx-user-check'></i> {{ $firstPen->assignedPersonnel->name }}
                                        </span>
                                    @else
                                        <span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700;">No Personnel Assigned</span>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button id="side-edit-btn" onclick="editPen({{ $firstPen->id }})" class="btn-action-edit"
                                    style="width: 38px; height: 38px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; transition: all 0.2s;"><i
                                        class='bx bx-edit-alt'></i></button>
                                <button id="side-del-btn" onclick="deletePen({{ $firstPen->id }})"
                                    class="btn-action-delete"
                                    style="width: 38px; height: 38px; border-radius: 10px; border: 1px solid #fee2e2; background: #fef2f2; cursor: pointer; transition: all 0.2s;"><i
                                        class='bx bx-trash'></i></button>
                            </div>
                        </div>
                        <div class="panel-section">
                            <div class="section-hdr"><i class='bx bx-plus-medical'></i> Population Health</div>
                            <div class="health-status-cards">
                                <div class="h-card h-green">
                                    <div class="h-label" style="color: #16a34a;">Healthy</div>
                                    <div class="h-val" style="color: #15803d;" id="side-healthy-count">
                                        {{ $firstPen->pigs->where('health_status', 'Healthy')->count() }}</div>
                                </div>
                                <div class="h-card h-red">
                                    <div class="h-label" style="color: #ef4444;">Sick / Alert</div>
                                    <div class="h-val" style="color: #b91c1c;" id="side-sick-count">
                                        {{ $firstPen->pigs->where('health_status', 'Sick')->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-section">
                            <div class="section-hdr"><i class='bx bx-dollar-circle'></i> Financial Performance</div>
                            <div class="finance-grid">
                                <div class="finance-item"><span class="fin-label">Revenue</span><span class="fin-val"
                                        id="side-revenue">₱{{ number_format($firstPen->revenue, 2) }}</span></div>
                                <div class="finance-item"><span class="fin-label">Est. Income</span><span class="fin-val"
                                        id="side-income"
                                        style="color: {{ $firstPen->income >= 0 ? '#16a34a' : '#ef4444' }};">₱{{ number_format($firstPen->income, 2) }}</span>
                                </div>
                            </div>
                            <div
                                style="margin-top: 16px; background: #fcfdfe; border: 1px solid #f1f5f9; padding: 12px; border-radius: 12px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 4px;"><span
                                        style="font-size: 0.65rem; font-weight: 700; color: #94a3b8;">Batch
                                        Investment</span><span
                                        style="font-size: 0.75rem; font-weight: 800; color: #475569;"
                                        id="side-batch-cost">{{ $firstPen->batch_cost ?: '₱0' }}</span></div>
                                <div style="display: flex; justify-content: space-between;"><span
                                        style="font-size: 0.65rem; font-weight: 700; color: #94a3b8;">Daily Feed Cost
                                        (Avg)</span><span style="font-size: 0.75rem; font-weight: 800; color: #475569;"
                                        id="side-feed-cons">{{ $firstPen->feed_cons ?: '0 kg' }}</span></div>
                            </div>
                        </div>
                        <div class="panel-section">
                            <div class="section-hdr"><i class='bx bx-line-chart'></i> Growth Progress</div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Avg: <span
                                        style="color: #0f172a;"
                                        id="side-avg-weight">{{ $firstPen->avg_weight ?: '0 kg' }}</span></span>
                                <span style="font-size: 0.75rem; font-weight: 700; color: #64748b;">Target: <span
                                        style="color: #0f172a;"
                                        id="side-target-weight">{{ $firstPen->target_weight ?: '0 kg' }}</span></span>
                            </div>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" id="side-progress-fill"
                                    style="width: {{ $firstPen->progress ?: 0 }}%;"></div>
                            </div>
                            <p style="text-align: center; font-size: 0.65rem; font-weight: 800; color: #94a3b8; margin: 0;"
                                id="side-progress-text">{{ $firstPen->progress ?: 0 }}% of market target</p>
                        </div>
                        <button type="button" onclick="previewReport()" class="btn-full-report"
                            style="width: 100%; background: var(--deep-slate); color: white; border: none; padding: 18px; border-radius: 20px; font-weight: 800; font-size: 0.9rem; margin-top: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 12px; box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);"><i
                                class='bx bx-file-find' style="font-size: 1.2rem;"></i> Generate & View Report</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL: ADD PEN -->
    <div id="addPenModal" class="custom-modal-overlay">
        <div class="custom-modal">
            <i class='bx bx-x modal-close' onclick="closeModal('addPenModal')"></i>
            <h2 style="font-weight: 900; margin-bottom: 4px;">Create New Pen</h2>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 28px;">Ear tags are auto-generated from the pen
                name and pig count.</p>
            <div class="modal-scroll-area">
                <form id="add-pen-form">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Pen Identifier / Name</label>
                            <input id="pen-name-input" name="name" class="form-input" placeholder="e.g. Pen Alpha-1" required
                                autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Auto-Generated Code</label>
                            <div style="position: relative;">
                                <input id="pen-code-input" class="form-input" readonly
                                    style="background: #f8fafc; font-weight: 900; color: #16a34a; letter-spacing: 0.1em; cursor: not-allowed; text-align: center;">
                                <i class='bx bx-lock-alt' style="position:absolute; right:15px; top:50%; transform:translateY(-50%); color: #cbd5e1; font-size: 0.9rem;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Section / Classification</label>
                        <input name="section" class="form-input" placeholder="e.g. Nursery, Fattening">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assigned Personnel</label>
                        <select name="assigned_to" class="form-input">
                            <option value="">-- No Personnel Assigned --</option>
                            @foreach ($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Initial Pig Count</label>
                            <input id="pen-pig-count-input" name="pig_count" type="number" min="0" max="200"
                                class="form-input" placeholder="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Batch Investment (₱)</label>
                            <input name="batch_cost" class="form-input" placeholder="0.00">
                        </div>
                    </div>
                    <!-- Live Ear Tag Preview -->
                    <div id="pen-tag-preview"
                        style="display:none; background: #f0fdf4; border: 1.5px solid #dcfce7; border-radius: 16px; padding: 16px; margin-bottom: 20px;">
                        <div
                            style="font-size: 0.65rem; font-weight: 900; color: #16a34a; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px;">
                            <i class='bx bx-purchase-tag-alt'></i> Auto-Generated Ear Tags Preview
                        </div>
                        <div id="pen-tag-badges" style="display: flex; flex-wrap: wrap; gap: 6px;"></div>
                        <p id="pen-tag-overflow" style="font-size: 0.7rem; color: #64748b; margin: 8px 0 0; display:none;">
                        </p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Avg Start Weight (kg)</label>
                            <input name="avg_weight" class="form-input" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Target Weight (kg)</label>
                            <input name="target_weight" class="form-input" placeholder="0.00">
                        </div>
                    </div>
                    <button type="submit" id="pen-submit-btn"
                        style="width: 100%; background: var(--accent-green); color: white; border: none; padding: 16px; border-radius: 16px; font-weight: 800; margin-top: 10px; cursor: pointer;">Register
                        Pen</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: EDIT PEN -->
    <div id="editPenModal" class="custom-modal-overlay">
        <div class="custom-modal">
            <i class='bx bx-x modal-close' onclick="closeModal('editPenModal')"></i>
            <h2 style="font-weight: 900; margin-bottom: 4px;">Edit Pen</h2>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 28px;">Update pen details and financial parameters.</p>
            <div class="modal-scroll-area">
                <form id="edit-pen-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit-pen-id" name="id">
                    <div class="form-group">
                        <label class="form-label">Pen Identifier / Name</label>
                        <input id="edit-pen-name" name="name" class="form-input" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Section / Classification</label>
                        <input id="edit-pen-section" name="section" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Assigned Personnel</label>
                        <select id="edit-pen-assigned-to" name="assigned_to" class="form-input">
                            <option value="">-- No Personnel Assigned --</option>
                            @foreach ($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Batch Investment (₱)</label>
                            <input id="edit-pen-batch-cost" name="batch_cost" class="form-input" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Daily Feed Cost (Avg)</label>
                            <input id="edit-pen-feed-cons" name="feed_cons" class="form-input" placeholder="0.00">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Avg Start Weight (kg)</label>
                            <input id="edit-pen-avg-weight" name="avg_weight" class="form-input" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Target Weight (kg)</label>
                            <input id="edit-pen-target-weight" name="target_weight" class="form-input" placeholder="0.00">
                        </div>
                    </div>
                    <button type="submit" id="edit-pen-submit-btn" style="width: 100%; background: var(--accent-green); color: white; border: none; padding: 16px; border-radius: 16px; font-weight: 800; margin-top: 10px; cursor: pointer;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL: ADD PIG -->
    <div id="addPigModal" class="custom-modal-overlay">
        <div class="custom-modal">
            <i class='bx bx-x modal-close' onclick="closeModal('addPigModal')"></i>
            <h2 style="font-weight: 900; margin-bottom: 4px;">Register Individual Animal</h2>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 20px;">Target Pen: <span id="add-pig-pen-name"
                    style="font-weight: 800; color: var(--deep-slate);"></span></p>

            <!-- Registration Mode Toggle -->
            <div style="display: flex; gap: 8px; margin-bottom: 24px; padding: 6px; background: #f1f5f9; border-radius: 14px;">
                <button type="button" onclick="window.setRegistrationMode('new')" id="reg-mode-new" 
                    style="flex: 1; padding: 10px; border-radius: 10px; border: none; background: #ffffff; color: #1e293b; font-weight: 800; font-size: 0.75rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                    <i class='bx bx-plus-circle'></i> New Registration
                </button>
                <button type="button" onclick="window.setRegistrationMode('transfer')" id="reg-mode-transfer" 
                    style="flex: 1; padding: 10px; border-radius: 10px; border: none; background: transparent; color: #64748b; font-weight: 700; font-size: 0.75rem; cursor: pointer; transition: all 0.2s;">
                    <i class='bx bx-transfer'></i> Transfer Existing
                </button>
            </div>

            <form id="add-pig-form">
                @csrf
                <input type="hidden" name="pen_id" id="add-pig-pen-id">
                <input type="hidden" name="status" value="Active">
                <input type="hidden" id="reg-mode-input" value="new">

                <!-- NEW REGISTRATION FIELDS -->
                <div id="new-pig-fields">
                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; justify-content:space-between;">
                            <span>Ear Tag / Identifier</span>
                            <span id="pig-tag-auto-badge"
                                style="font-size: 0.6rem; background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; padding: 2px 8px; border-radius: 20px; font-weight: 800;">Auto</span>
                        </label>
                        <div style="position: relative;">
                            <input id="pig-tag-input" name="tag" class="form-input" placeholder="Loading auto-tag..."
                                readonly style="padding-left: 44px; background: #f8fafc; cursor: not-allowed;">
                            <i class='bx bx-purchase-tag-alt'
                                style="position:absolute; left:15px; top:50%; transform:translateY(-50%); color: #94a3b8; font-size: 1.1rem;"></i>
                        </div>
                        <p style="font-size: 0.7rem; color: #16a34a; font-weight: 700; margin-top: 6px;">
                            <i class='bx bx-check-shield'></i> System-protected identifier (auto-generated)
                        </p>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">Initial Weight (kg)</label>
                            <input name="weight" type="number" step="0.01" class="form-input" placeholder="0.00">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Health Status</label>
                            <select name="health_status" class="form-input">
                                <option value="Healthy">Healthy</option>
                                <option value="Warning">Observation</option>
                                <option value="Sick">Sick</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Breed / Variant</label>
                        <input name="breed" class="form-input" placeholder="e.g. Large White">
                    </div>
                </div>

                <!-- TRANSFER FIELDS -->
                <div id="transfer-pig-fields" style="display: none;">
                    <div class="form-group">
                        <label class="form-label">Select Pig to Transfer</label>
                        <select id="transfer-pig-id" name="transfer_pig_id" class="form-input">
                            <option value="">-- Choose Animal --</option>
                            @foreach($allPigs as $p)
                                <option value="{{ $p->id }}" data-pen-id="{{ $p->pen_id }}">#{{ $p->tag }} (Current: {{ $p->pen->name ?? 'Unassigned' }})</option>
                            @endforeach
                        </select>
                        <p style="font-size: 0.7rem; color: #64748b; margin-top: 8px;">Moving an animal will update its location and record the movement in its history.</p>
                    </div>
                </div>

                <button type="submit" id="add-pig-submit-btn"
                    style="width: 100%; background: var(--accent-green); color: white; border: none; padding: 16px; border-radius: 16px; font-weight: 800; margin-top: 10px; cursor: pointer;">
                    Confirm & Add Animal
                </button>
            </form>
        </div>
    </div>
    <!-- MODAL: ASSIGN TASK -->
    <div id="assignTaskModal" class="custom-modal-overlay">
        <div class="custom-modal">
            <i class='bx bx-x modal-close' onclick="closeModal('assignTaskModal')"></i>
            <h2 style="font-weight: 900; margin-bottom: 4px;">Assign Monitoring Task</h2>
            <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 28px;">Setting up a health check for <strong id="task-pig-tag-display" style="color: var(--deep-slate);"></strong></p>
            <form id="assign-task-form">
                @csrf
                <input type="hidden" id="task-pig-id" name="pig_id">
                <input type="hidden" id="task-pen-id" name="pen_id">
                <input type="hidden" name="status" value="pending">
                
                <div class="form-group">
                    <label class="form-label">Task Description</label>
                    <textarea id="task-desc" name="description" class="form-input" style="height: 100px; resize: none;" placeholder="What should the worker check? (e.g. Monitor appetite, Check leg wound, etc.)" required></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Assign To</label>
                        <select id="task-worker" name="assigned_to" class="form-input">
                            @foreach ($workers as $worker)
                                <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" class="form-input" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Priority / Importance</label>
                    <select name="priority" class="form-input">
                        <option value="Low">Low Priority</option>
                        <option value="Medium" selected>Medium / Normal</option>
                        <option value="High">High Priority</option>
                        <option value="Critical">Critical Alert</option>
                    </select>
                </div>

                <button type="submit" id="task-submit-btn" style="width: 100%; background: var(--accent-green); color: white; border: none; padding: 16px; border-radius: 16px; font-weight: 800; margin-top: 10px; cursor: pointer;">Assign Task</button>
            </form>
        </div>
    </div>

    <div id="reportPreviewModal" class="custom-modal-overlay">
        <div class="custom-modal" style="max-width: 800px;"><i class='bx bx-x modal-close'
                onclick="closeModal('reportPreviewModal')"></i>
            <h2 style="font-weight: 900; margin-bottom: 24px;">Report Preview</h2>
            <div id="report-content"
                style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 20px; padding: 32px; max-height: 500px; overflow-y: auto;">
            </div>
            <div style="display: flex; gap: 16px; margin-top: 24px;">
                <button onclick="closeModal('reportPreviewModal')"
                    style="flex: 1; padding: 14px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #fff; font-weight: 700; cursor: pointer;">Cancel</button>
                <button onclick="downloadReportPDF()"
                    style="flex: 2; padding: 14px; border-radius: 14px; background: #22c55e; color: #fff; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class='bx bxs-file-pdf'></i> Download PDF
                </button>
                <button onclick="printReportContent()"
                    style="flex: 1; padding: 14px; border-radius: 14px; background: var(--deep-slate); color: #fff; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class='bx bx-printer'></i> Print
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Use a single global object to avoid pollution and ease debugging
        window.PT_APP = {
            workers: @json($workers),
            currentPen: @json($pens->first()),

            handlePenClick: function(element, data) {
                document.querySelectorAll('.pen-accordion').forEach(function(r) {
                    r.classList.remove('active-row');
                });
                element.closest('.pen-accordion').classList.add('active-row');
                this.currentPen = data;

                document.getElementById('side-pen-name').innerText = data.name;
                document.getElementById('side-pen-id').innerText = '#' + data.id;
                document.getElementById('side-pen-section').innerText = data.section || 'Unassigned';

                var assignmentEl = document.getElementById('side-pen-assignment');
                if (data.assigned_personnel) {
                    assignmentEl.innerHTML = `
                        <span style="font-size: 0.7rem; color: #22c55e; font-weight: 800; background: #f0fdf4; padding: 4px 10px; border-radius: 8px; display: inline-flex; align-items: center; gap: 4px;">
                            <i class='bx bx-user-check'></i> ${data.assigned_personnel.name}
                        </span>`;
                } else {
                    assignmentEl.innerHTML = `<span style="font-size: 0.7rem; color: #94a3b8; font-weight: 700;">No Personnel Assigned</span>`;
                }

                var rev = parseFloat(data.revenue || 0);
                var inc = parseFloat(data.income || 0);
                document.getElementById('side-revenue').innerText = '\u20B1' + rev.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });
                document.getElementById('side-income').innerText = '\u20B1' + inc.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });
                document.getElementById('side-income').style.color = inc >= 0 ? '#16a34a' : '#ef4444';

                var healthy = data.pigs ? data.pigs.filter(function(p) {
                    return p.health_status === 'Healthy';
                }).length : 0;
                var sick = data.pigs ? data.pigs.filter(function(p) {
                    return p.health_status === 'Sick';
                }).length : 0;
                document.getElementById('side-healthy-count').innerText = healthy;
                document.getElementById('side-sick-count').innerText = sick;
                document.getElementById('side-avg-weight').innerText = (data.avg_weight || 0) + ' kg';
                document.getElementById('side-target-weight').innerText = (data.target_weight || 0) + ' kg';
                document.getElementById('side-progress-fill').style.width = (data.progress || 0) + '%';
                document.getElementById('side-progress-text').innerText = (data.progress || 0) +
                    '% of market target';
                document.getElementById('side-batch-cost').innerText = '\u20B1' + (data.batch_cost || 0);
                document.getElementById('side-feed-cons').innerText = (data.feed_cons || 0) + ' kg';

                document.getElementById('side-edit-btn').setAttribute('onclick', 'window.PT_APP.editPen(' + data
                    .id + ')');
                document.getElementById('side-del-btn').setAttribute('onclick', 'window.PT_APP.deletePen(' + data
                    .id + ')');
            },

            toggleAccordion: function(event, id) {
                if (event) event.stopPropagation();
                var row = document.querySelector('.pen-accordion[data-id="' + id + '"]');
                if (!row) return;
                var isExpanded = row.classList.contains('expanded');
                document.querySelectorAll('.pen-accordion').forEach(function(r) {
                    if (r !== row) {
                        r.classList.remove('expanded');
                        var btn = r.querySelector('button i');
                        if (btn) btn.classList.replace('bx-chevron-up', 'bx-chevron-down');
                    }
                });
                if (!isExpanded) {
                    row.classList.add('expanded');
                    var icon = row.querySelector('button i');
                    if (icon) icon.classList.replace('bx-chevron-down', 'bx-chevron-up');
                } else {
                    row.classList.remove('expanded');
                    var icon = row.querySelector('button i');
                    if (icon) icon.classList.replace('bx-chevron-up', 'bx-chevron-down');
                }
            },

            viewPig: function(pigId) {
                Swal.fire({
                    title: 'Gathering Record...',
                    width: 700,
                    padding: '0',
                    background: '#f1f5f9',
                    showConfirmButton: false,
                    showCloseButton: true,
                    customClass: {
                        popup: 'rounded-3xl overflow-hidden'
                    },
                    didOpen: function() {
                        Swal.showLoading();
                        fetch('/admin/pigs/' + pigId).then(function(res) {
                            return res.text();
                        }).then(function(html) {
                            Swal.fire({
                                html: html,
                                width: 700,
                                padding: '0',
                                background: '#f8fafc',
                                showConfirmButton: false,
                                showCloseButton: true,
                                customClass: {
                                    popup: 'rounded-3xl overflow-hidden'
                                }
                            });
                        });
                    }
                });
            },

            quickAssignTask: function(event, pigId, pigTag, penId) {
                if (event) event.stopPropagation();

                document.getElementById('task-pig-tag-display').innerText = 'Pig #' + pigTag;
                document.getElementById('task-pig-id').value = pigId;
                document.getElementById('task-pen-id').value = penId;
                document.getElementById('task-desc').value = '';
                
                // Reset due date to today
                var today = new Date().toISOString().split('T')[0];
                var dateInput = document.querySelector('#assignTaskModal [name="due_date"]');
                if (dateInput) dateInput.value = today;

                window.openModal('assignTaskModal');
            },

            editPen: function(id) {
                var pen = this.currentPen;
                document.getElementById('edit-pen-id').value = pen.id;
                document.getElementById('edit-pen-name').value = pen.name;
                document.getElementById('edit-pen-section').value = pen.section || '';
                document.getElementById('edit-pen-batch-cost').value = pen.batch_cost || '';
                document.getElementById('edit-pen-feed-cons').value = pen.feed_cons || '';
                document.getElementById('edit-pen-avg-weight').value = pen.avg_weight || '';
                document.getElementById('edit-pen-target-weight').value = pen.target_weight || '';
                document.getElementById('edit-pen-assigned-to').value = pen.assigned_to || '';
                
                window.openModal('editPenModal');
            },

            deletePen: function(id) {
                Swal.fire({
                    title: 'Delete?',
                    showCancelButton: true
                }).then(function(r) {
                    if (r.isConfirmed) fetch('/pens/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(function() {
                        location.reload();
                    });
                });
            },

            previewReport: function() {
                const data = this.currentPen;
                if (!data) {
                    Swal.fire('No Pen Selected', 'Please click on a pen from the list first.', 'info');
                    return;
                }

                const healthy = data.pigs ? data.pigs.filter(p => p.health_status === 'Healthy').length : 0;
                const sick = data.pigs ? data.pigs.filter(p => p.health_status === 'Sick').length : 0;
                const revenue = parseFloat(data.revenue || 0).toLocaleString(undefined, { minimumFractionDigits: 2 });
                const incVal = parseFloat(data.income || 0);
                const income = incVal.toLocaleString(undefined, { minimumFractionDigits: 2 });
                const incomeColor = incVal >= 0 ? '#16a34a' : '#ef4444';

                const html = `
                    <div id="report-printable-area" style="font-family: 'Inter', sans-serif; color: #0f172a; padding: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px;">
                            <div>
                                <h1 style="font-size: 1.75rem; font-weight: 900; margin: 0; color: #0f172a; letter-spacing: -0.05em;">PEN BATCH REPORT</h1>
                                <p style="color: #64748b; font-size: 0.8rem; font-weight: 700; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.05em;">SwineForge Farm Intelligence System</p>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin: 0;">Report Generated</p>
                                <p style="font-size: 0.95rem; font-weight: 700; margin: 2px 0 0;">${new Date().toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; margin-bottom: 35px;">
                            <div>
                                <h3 style="font-size: 0.65rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.1em;">Location Identifier</h3>
                                <p style="font-size: 1.4rem; font-weight: 900; margin: 0; color: #0f172a;">${data.name}</p>
                                <div style="display: flex; gap: 15px; margin-top: 8px;">
                                    <p style="color: #64748b; font-size: 0.85rem; margin: 0;"><strong>Section:</strong> ${data.section || 'Unassigned'}</p>
                                    <p style="color: #64748b; font-size: 0.85rem; margin: 0;"><strong>Assigned:</strong> ${data.assigned_personnel ? data.assigned_personnel.name : 'Unassigned'}</p>
                                </div>
                            </div>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px;">
                                <h3 style="font-size: 0.65rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.05em;">Health Summary</h3>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                                    <span style="color: #64748b;">Total Population</span> <span style="font-weight: 800;">${data.pigs ? data.pigs.length : 0}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.9rem;">
                                    <span style="color: #64748b;">Healthy Animals</span> <span style="font-weight: 800; color: #16a34a;">${healthy}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                    <span style="color: #64748b;">Sick / Alert</span> <span style="font-weight: 800; color: #ef4444;">${sick}</span>
                                </div>
                            </div>
                        </div>

                        <h3 style="font-size: 0.65rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.1em;">Economic Metrics</h3>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 35px;">
                            <div style="background: #fff; padding: 15px; border-radius: 16px; border: 1.5px solid #f1f5f9; text-align: center;">
                                <p style="font-size: 0.55rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 5px;">Revenue</p>
                                <p style="font-size: 1rem; font-weight: 900; margin: 0; color: #0f172a;">₱${revenue}</p>
                            </div>
                            <div style="background: #fff; padding: 15px; border-radius: 16px; border: 1.5px solid #f1f5f9; text-align: center;">
                                <p style="font-size: 0.55rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 5px;">Income</p>
                                <p style="font-size: 1rem; font-weight: 900; margin: 0; color: ${incomeColor};">₱${income}</p>
                            </div>
                            <div style="background: #fff; padding: 15px; border-radius: 16px; border: 1.5px solid #f1f5f9; text-align: center;">
                                <p style="font-size: 0.55rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 5px;">Avg Weight</p>
                                <p style="font-size: 1rem; font-weight: 900; margin: 0; color: #0f172a;">${data.avg_weight || 0} kg</p>
                            </div>
                            <div style="background: #fff; padding: 15px; border-radius: 16px; border: 1.5px solid #f1f5f9; text-align: center;">
                                <p style="font-size: 0.55rem; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 5px;">Progress</p>
                                <p style="font-size: 1rem; font-weight: 900; margin: 0; color: #22c55e;">${data.progress || 0}%</p>
                            </div>
                        </div>

                        <h3 style="font-size: 0.65rem; font-weight: 900; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.1em;">Animal Audit Log</h3>
                        <div style="overflow: hidden; border-radius: 16px; border: 1.5px solid #f1f5f9;">
                            <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
                                <thead>
                                    <tr style="background: #f8fafc; text-align: left; border-bottom: 1.5px solid #f1f5f9;">
                                        <th style="padding: 12px 15px; font-weight: 800; color: #64748b; text-transform: uppercase; font-size: 0.6rem;">Animal Tag</th>
                                        <th style="padding: 12px 15px; font-weight: 800; color: #64748b; text-transform: uppercase; font-size: 0.6rem;">Status</th>
                                        <th style="padding: 12px 15px; font-weight: 800; color: #64748b; text-transform: uppercase; font-size: 0.6rem;">Breed</th>
                                        <th style="padding: 12px 15px; font-weight: 800; color: #64748b; text-transform: uppercase; font-size: 0.6rem; text-align: right;">Current Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${(data.pigs || []).length > 0 ? data.pigs.map(p => `
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 12px 15px; font-weight: 800; color: #0f172a;">#${p.tag}</td>
                                            <td style="padding: 12px 15px;">
                                                <span style="display: inline-block; padding: 2px 8px; border-radius: 6px; background: ${p.health_status === 'Sick' ? '#fef2f2' : '#f0fdf4'}; color: ${p.health_status === 'Sick' ? '#ef4444' : '#16a34a'}; font-weight: 800; font-size: 0.65rem;">
                                                    ${p.health_status}
                                                </span>
                                            </td>
                                            <td style="padding: 12px 15px; color: #64748b;">${p.breed || 'Standard'}</td>
                                            <td style="padding: 12px 15px; text-align: right; font-weight: 700; color: #475569;">${p.weight || 0} kg</td>
                                        </tr>
                                    `).join('') : '<tr><td colspan="4" style="padding: 20px; text-align: center; color: #94a3b8; font-style: italic;">No animals registered in this pen.</td></tr>'}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;

                document.getElementById('report-content').innerHTML = html;
                window.openModal('reportPreviewModal');
            },

            printReportContent: function() {
                const content = document.getElementById('report-content').innerHTML;
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html` + `>
                        <head` + `>
                            <title>Report - ${this.currentPen.name}</title>
                            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800;900&display=swap" rel="stylesheet">
                            <style>
                                body { font-family: 'Inter', sans-serif; padding: 40px; }
                                @media print {
                                    body { padding: 0; }
                                    #report-printable-area { padding: 0; }
                                }
                            </style>
                        </head` + `>
                        <body` + `>
                            ${content}
                            <script` + `>
                                window.onload = function() {
                                    window.print();
                                    window.onafterprint = function() { window.close(); };
                                }
                            </script` + `>
                        </body` + `>
                    </html` + `>
                `);
                printWindow.document.close();
            },

            downloadReportPDF: function() {
                const element = document.getElementById('report-printable-area');
                const penName = this.currentPen.name;
                const date = new Date().toISOString().split('T')[0];
                
                const opt = {
                    margin: 0.5,
                    filename: `Report_${penName}_${date}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
                };

                if (typeof html2pdf === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js';
                    script.onload = () => html2pdf().set(opt).from(element).save();
                    document.head.appendChild(script);
                } else {
                    html2pdf().set(opt).from(element).save();
                }
            }
        };

        // Map global functions for compatibility with existing onclick attributes
        window.handlePenClick = window.PT_APP.handlePenClick.bind(window.PT_APP);
        window.toggleAccordion = window.PT_APP.toggleAccordion.bind(window.PT_APP);
        window.viewPig = window.PT_APP.viewPig.bind(window.PT_APP);
        window.quickAssignTask = window.PT_APP.quickAssignTask.bind(window.PT_APP);
        window.editPen = window.PT_APP.editPen.bind(window.PT_APP);
        window.deletePen = window.PT_APP.deletePen.bind(window.PT_APP);
        window.previewReport = window.PT_APP.previewReport.bind(window.PT_APP);
        window.printReportContent = window.PT_APP.printReportContent.bind(window.PT_APP);
        window.downloadReportPDF = window.PT_APP.downloadReportPDF.bind(window.PT_APP);

        window.openModal = function(id) {
            document.getElementById(id).style.display = 'flex';
        };
        window.closeModal = function(id) {
            document.getElementById(id).style.display = 'none';
        };

        window.openAddPigModal = function(penId) {
            var row = document.querySelector('.pen-accordion[data-id="' + penId + '"]');
            var penName = row.querySelector('.pen-name-text').innerText;
            var pigCount = row.querySelectorAll('.pig-row-item').length;
            document.getElementById('add-pig-pen-name').innerText = penName;
            document.getElementById('add-pig-pen-id').value = penId;
            // Reset the tag input while loading
            var tagInput = document.getElementById('pig-tag-input');
            var badge = document.getElementById('pig-tag-auto-badge');
            tagInput.value = '';
            tagInput.placeholder = 'Loading...';
            badge.innerText = 'Auto';
            badge.style.background = '#f0fdf4';
            badge.style.color = '#16a34a';
            window.openModal('addPigModal');
            // Fetch the next available tag from server
            fetch('/api/pens/next-tag?pen_name=' + encodeURIComponent(penName) + '&existing_count=' + pigCount)
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    tagInput.placeholder = data.tag;
                    tagInput.value = data.tag;
                    badge.innerText = 'Auto-Generated';
                })
                .catch(function() {
                    tagInput.placeholder = 'Enter manually';
                });

        };

        document.addEventListener('DOMContentLoaded', function() {
            // --- NEW PEN FORM: live ear-tag preview ---
            var penNameInput = document.getElementById('pen-name-input');
            var penCountInput = document.getElementById('pen-pig-count-input');
            var tagPreviewBox = document.getElementById('pen-tag-preview');
            var tagBadgesEl = document.getElementById('pen-tag-badges');
            var tagOverflowEl = document.getElementById('pen-tag-overflow');
            var BADGE_MAX = 12; // Show max 12 badges, indicate overflow after

            function buildPrefix(name) {
                return (name.replace(/[^A-Za-z0-9]/g, '').substring(0, 6) || 'PIG').toUpperCase();
            }

            function updateTagPreview() {
                var name = penNameInput ? penNameInput.value : '';
                var count = parseInt((penCountInput ? penCountInput.value : '') || 0);
                
                // Auto-generate Pen Code
                var prefix = buildPrefix(name);
                var penCodeInput = document.getElementById('pen-code-input');
                if (penCodeInput) {
                    penCodeInput.value = name ? prefix : '';
                }

                if (!name || count <= 0) {
                    tagPreviewBox.style.display = 'none';
                    return;
                }
                tagPreviewBox.style.display = 'block';
                var show = Math.min(count, BADGE_MAX);
                var html = '';
                for (var i = 1; i <= show; i++) {
                    html +=
                        '<span style="background:#dcfce7;color:#15803d;font-size:0.7rem;font-weight:800;padding:4px 10px;border-radius:20px;">' +
                        prefix + '-' + String(i).padStart(3, '0') + '</span>';
                }
                tagBadgesEl.innerHTML = html;
                if (count > BADGE_MAX) {
                    tagOverflowEl.style.display = 'block';
                    tagOverflowEl.innerText = '+ ' + (count - BADGE_MAX) + ' more tags (e.g. ' + prefix + '-' +
                        String(count).padStart(3, '0') + ')';
                } else {
                    tagOverflowEl.style.display = 'none';
                }
            }

            if (penNameInput) penNameInput.addEventListener('input', updateTagPreview);
            if (penCountInput) penCountInput.addEventListener('input', updateTagPreview);

            // --- PEN FORM SUBMIT ---
            document.getElementById('add-pen-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                var btn = document.getElementById('pen-submit-btn');
                btn.disabled = true;
                btn.innerText = 'Registering...';
                var res = await fetch('{{ route('pens.store') }}', {
                    method: 'POST',
                    body: JSON.stringify(Object.fromEntries(new FormData(e.target))),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var data = await res.json();
                if (data.success) {
                    closeModal('addPenModal');
                    Swal.fire({
                        title: 'Pen Registered!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#22c55e'
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    btn.disabled = false;
                    btn.innerText = 'Register Pen';
                    Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                }
            });

            // --- PIG FORM SUBMIT ---
            document.getElementById('add-pig-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                var mode = document.getElementById('reg-mode-input').value;
                var btn = document.getElementById('add-pig-submit-btn');
                var penId = document.getElementById('add-pig-pen-id').value;
                
                btn.disabled = true;
                btn.innerText = mode === 'transfer' ? 'Transferring...' : 'Registering...';

                if (mode === 'transfer') {
                    var pigId = document.getElementById('transfer-pig-id').value;
                    if (!pigId) {
                        Swal.fire('Notice', 'Please select an animal to transfer.', 'info');
                        btn.disabled = false;
                        btn.innerText = 'Confirm & Add Animal';
                        return;
                    }

                    var res = await fetch(`/admin/pigs/${pigId}/move-pen`, {
                        method: 'POST',
                        body: JSON.stringify({ pen_id: penId }),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    var data = await res.json();
                } else {
                    var res = await fetch('{{ route('admin.pigs.store') }}', {
                        method: 'POST',
                        body: JSON.stringify(Object.fromEntries(new FormData(e.target))),
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    var data = await res.json();
                }

                if (data.success) {
                    closeModal('addPigModal');
                    Swal.fire({
                        title: mode === 'transfer' ? 'Animal Transferred!' : 'Pig Registered!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#22c55e'
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    btn.disabled = false;
                    btn.innerText = 'Confirm & Add Animal';
                    Swal.fire('Error', data.message || 'Could not save record.', 'error');
                }
            });

            window.setRegistrationMode = function(mode) {
                document.getElementById('reg-mode-input').value = mode;
                var newFields = document.getElementById('new-pig-fields');
                var transFields = document.getElementById('transfer-pig-fields');
                var newBtn = document.getElementById('reg-mode-new');
                var transBtn = document.getElementById('reg-mode-transfer');

                if (mode === 'new') {
                    newFields.style.display = 'block';
                    transFields.style.display = 'none';
                    newBtn.style.background = '#ffffff';
                    newBtn.style.color = '#1e293b';
                    newBtn.style.boxShadow = '0 4px 6px rgba(0,0,0,0.05)';
                    transBtn.style.background = 'transparent';
                    transBtn.style.color = '#64748b';
                    transBtn.style.boxShadow = 'none';
                } else {
                    newFields.style.display = 'none';
                    transFields.style.display = 'block';
                    transBtn.style.background = '#ffffff';
                    transBtn.style.color = '#1e293b';
                    transBtn.style.boxShadow = '0 4px 6px rgba(0,0,0,0.05)';
                    newBtn.style.background = 'transparent';
                    newBtn.style.color = '#64748b';
                    newBtn.style.boxShadow = 'none';
                    
                    // Filter out pigs already in THIS pen from the dropdown
                    var currentPenId = document.getElementById('add-pig-pen-id').value;
                    var select = document.getElementById('transfer-pig-id');
                    Array.from(select.options).forEach(opt => {
                        if (opt.value && opt.getAttribute('data-pen-id') === currentPenId) {
                            opt.style.display = 'none';
                        } else {
                            opt.style.display = 'block';
                        }
                    });
                }
            };

            // --- ADMIN PIG EDITING LOGIC (GLOBAL) ---
            window.togglePigEdit = function() {
                console.log("Toggle Edit Triggered");
                const editElements = document.querySelectorAll('.edit-mode');
                const viewElements = document.querySelectorAll('.view-mode');
                
                if (editElements.length === 0) return;
                const isEditing = editElements[0].style.display !== 'none';

                editElements.forEach(el => {
                    if (el.id === 'edit-actions-hud') {
                        el.style.display = isEditing ? 'none' : 'flex';
                    } else if (el.tagName === 'DIV') {
                        el.style.display = isEditing ? 'none' : 'block';
                    } else {
                        el.style.display = isEditing ? 'none' : 'inline-block';
                    }
                });
                viewElements.forEach(el => {
                    if (el.tagName === 'H2' || el.tagName === 'DIV' || el.tagName === 'SECTION') {
                        el.style.display = isEditing ? 'block' : 'none';
                    } else {
                        el.style.display = isEditing ? '' : 'none';
                    }
                });
                
                if (isEditing) {
                    const activeTab = document.querySelector('.mini-tab-btn.active');
                    if (activeTab) {
                        const onclickAttr = activeTab.getAttribute('onclick');
                        const match = onclickAttr ? onclickAttr.match(/'([^']+)'/) : null;
                        if (match) {
                            document.querySelectorAll('.mini-tab-content').forEach(c => c.style.display = 'none');
                            const targetTab = document.getElementById(match[1]);
                            if (targetTab) targetTab.style.display = 'block';
                        }
                    }
                } else {
                    const hud = document.getElementById('edit-actions-hud');
                    if (hud) hud.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }

                const btn = document.getElementById('edit-pig-toggle-btn');
                if (btn) {
                    btn.innerHTML = isEditing ? '<i class="bx bx-edit-alt"></i> Edit Details' : '<i class="bx bx-x"></i> Cancel Edit';
                    btn.style.background = isEditing ? 'rgba(255,255,255,0.15)' : '#ef4444';
                }
            };

            window.saveAdminPigChanges = async function(id) {
                const form = document.getElementById('admin-edit-pig-form');
                if (!form) return;
                
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                Swal.fire({
                    title: 'Saving changes...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                try {
                    const response = await fetch(`/admin/pigs/${id}/update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();
                    if (result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: result.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            if (typeof window.viewPig === 'function') {
                                window.viewPig(id);
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Error', result.message || 'Something went wrong', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Failed to save changes.', 'error');
                }
            };
            // --- TASK FORM SUBMIT ---
            document.getElementById('assign-task-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                var btn = document.getElementById('task-submit-btn');
                var originalText = btn.innerText;
                
                btn.disabled = true;
                btn.innerText = 'Assigning...';

                var formData = Object.fromEntries(new FormData(e.target));
                // Enhanced title logic: If description is short, prepend to title for better visibility
                formData.title = 'Monitor ' + document.getElementById('task-pig-tag-display').innerText;

                try {
                    var res = await fetch('{{ route('admin.tasks.store') }}', {
                        method: 'POST',
                        body: JSON.stringify(formData),
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    var data = await res.json();
                    if (res.ok) {
                        window.closeModal('assignTaskModal');
                        Swal.fire({
                            title: 'Task Assigned!',
                            text: 'The monitoring task has been successfully recorded and will now appear on the Task Assignment board and Worker views.',
                            icon: 'success',
                            confirmButtonColor: '#22c55e'
                        }).then(() => {
                            location.reload(); 
                        });
                    } else {
                        throw new Error(data.message || 'Server returned an error.');
                    }
                } catch (err) {
                    console.error('Task Assignment Error:', err);
                    Swal.fire({
                        title: 'Assignment Failed',
                        text: err.message || 'Could not connect to the server. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                } finally {
                    btn.disabled = false;
                    btn.innerText = originalText;
                }
            });

            // --- EDIT PEN FORM SUBMIT ---
            document.getElementById('edit-pen-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                var id = document.getElementById('edit-pen-id').value;
                var btn = document.getElementById('edit-pen-submit-btn');
                btn.disabled = true;
                btn.innerText = 'Saving...';

                var res = await fetch('/pens/' + id, {
                    method: 'PUT',
                    body: JSON.stringify(Object.fromEntries(new FormData(e.target))),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });
                var data = await res.json();
                if (data.success) {
                    closeModal('editPenModal');
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#22c55e'
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    btn.disabled = false;
                    btn.innerText = 'Save Changes';
                    Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                }
            });
        });

        // Global function to expand a pen and show a specific pig modal (for notifications)
        window.expandPenAndShowPig = function(penId, pigId) {
            console.log("Auto-opening Pen:", penId, "and Pig:", pigId);
            const penRow = document.querySelector(`.pen-accordion[data-id="${penId}"]`);
            if (penRow) {
                // 1. Select the pen (populates sidebar)
                const header = penRow.querySelector('.pen-header-row');
                if (header) header.click();
                
                // 2. Expand accordion if needed
                if (!penRow.classList.contains('expanded')) {
                    window.PT_APP.toggleAccordion(null, penId);
                }

                // 3. Open the pig details
                setTimeout(() => {
                    window.PT_APP.viewPig(pigId);
                }, 400);
            }
        };

        // Auto-open modal if redirected from an alert (Admin side)
        document.addEventListener('DOMContentLoaded', function() {
            const pendingPigId = sessionStorage.getItem('pending_pig_modal');
            const pendingPenId = sessionStorage.getItem('pending_pen_id');

            if (pendingPigId) {
                // Clear the storage immediately
                sessionStorage.removeItem('pending_pig_modal');
                sessionStorage.removeItem('pending_pen_id');
                
                // Use the global function we just defined
                window.expandPenAndShowPig(parseInt(pendingPenId), parseInt(pendingPigId));
            }
        });
    </script>
    @endpush
@endsection
