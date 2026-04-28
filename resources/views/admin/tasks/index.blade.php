@extends('layouts.master')
@section('contents')
<style>
    :root {
        --deep-slate: #0f172a;
        --accent-green: #22c55e;
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    .task-wrap { 
        padding: 24px 32px; 
        max-width: 1550px; 
        margin: 0 auto; 
    }

    .premium-panel { 
        background: #ffffff; 
        border: 1px solid var(--border-color); 
        border-radius: 28px; 
        padding: 24px; 
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
    }

    .task-grid { 
        display: grid; 
        grid-template-columns: 1fr 380px; 
        gap: 24px; 
        align-items: start;
    }

    .compact-table th { 
        font-size: 0.65rem; 
        font-weight: 900; 
        color: #94a3b8; 
        text-transform: uppercase; 
        letter-spacing: 0.1em;
        padding: 12px 16px; 
        border-bottom: 2px solid #f1f5f9; 
    }

    .compact-table td { 
        font-size: 0.8rem; 
        padding: 14px 16px; 
        border-bottom: 1px solid #f8fafc; 
        vertical-align: middle;
    }

    .badge { 
        padding: 6px 14px; 
        border-radius: 10px; 
        font-size: 0.65rem; 
        font-weight: 900; 
        text-transform: uppercase; 
        letter-spacing: 0.05em;
    }

    .badge-pending { 
        background: #fff7ed; 
        color: #c2410c; 
        border: 1.5px solid #fed7aa; 
    }

    .badge-completed { 
        background: #f0fdf4; 
        color: #15803d; 
        border: 1.5px solid #bbf7d0; 
    }

    .form-input { 
        width: 100%; 
        padding: 14px 18px; 
        border: 1.5px solid #e2e8f0; 
        border-radius: 16px; 
        font-size: 0.9rem; 
        color: var(--deep-slate); 
        font-weight: 600;
        background-color: #f8fafc;
        transition: all 0.2s;
        outline: none;
    }

    .form-input:focus { 
        border-color: var(--accent-green); 
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);
    }

    .form-label { 
        display: block; 
        font-size: 0.7rem; 
        font-weight: 900; 
        color: #64748b; 
        margin-bottom: 10px; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }

    .btn-save { 
        width: 100%; 
        padding: 18px; 
        background: var(--deep-slate); 
        color: #fff; 
        border: none; 
        border-radius: 20px; 
        font-weight: 800; 
        font-size: 0.95rem;
        cursor: pointer; 
        transition: all 0.2s;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.1);
        margin-top: 20px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15);
        background: #1e293b;
    }

    .suggestion-box {
        position: absolute;
        width: 100%;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        z-index: 100;
        display: none;
        margin-top: 8px;
        max-height: 250px;
        overflow-y: auto;
        padding: 8px;
    }

    .suggestion-item {
        padding: 12px 16px;
        font-size: 0.85rem;
        cursor: pointer;
        border-radius: 12px;
        margin-bottom: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }

    .suggestion-item:hover { 
        background: #f0fdf4; 
        color: #166534; 
    }

    .suggestion-tag { 
        font-size: 0.6rem; 
        font-weight: 900; 
        background: #f1f5f9; 
        color: #64748b; 
        padding: 4px 10px; 
        border-radius: 8px; 
    }

    .target-badge {
        font-size: 0.7rem;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .target-pig { background: #f0fdf4; color: #16a34a; }
    .target-pen { background: #eff6ff; color: #2563eb; }
    .target-general { background: #f8fafc; color: #94a3b8; }
</style>

<div class="task-wrap">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 900; color: var(--deep-slate); margin: 0; letter-spacing: -0.04em;">Task Assignment</h1>
            <p style="font-size: 0.9rem; color: #64748b; font-weight: 500; margin-top: 4px;">Deploy worker protocols and monitor farm operations.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <div style="background: #fff; padding: 12px 24px; border-radius: 20px; border: 1px solid #e2e8f0; text-align: center;">
                <p style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin: 0;">Active Tasks</p>
                <p style="font-size: 1.2rem; font-weight: 900; color: var(--deep-slate); margin: 0;">{{ $tasks->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1.5px solid #bbf7d0; color: #15803d; padding: 16px 24px; border-radius: 20px; margin-bottom: 32px; font-size: 0.9rem; font-weight: 700; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 12px rgba(34, 197, 94, 0.05);">
            <i class='bx bxs-check-circle' style="font-size: 1.25rem;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="task-grid">
        <!-- Task List -->
        <div class="premium-panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h2 style="font-size: 1.1rem; font-weight: 900; color: var(--deep-slate); margin: 0;">Deployment Board</h2>
                <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">Showing latest assignments</div>
            </div>
            
            <div style="overflow-x: auto;">
                <table class="compact-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Due Date</th>
                            <th>Operation Details</th>
                            <th>Priority</th>
                            <th>Personnel</th>
                            <th>Scope</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.9rem;">{{ $task->due_date->format('M d') }}</div>
                                    <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 700;">{{ $task->due_date->format('Y') }}</div>
                                </td>
                                <td>
                                    <div style="font-weight: 800; color: var(--deep-slate); margin-bottom: 4px;">{{ $task->title }}</div>
                                    <div style="font-size: 0.75rem; color: #64748b; line-height: 1.4;">{{ Str::limit($task->description, 45) }}</div>
                                </td>
                                <td>
                                    @php
                                        $priority = strtolower($task->priority ?? 'medium');
                                        $pColor = match($priority) {
                                            'high', 'urgent' => '#ef4444',
                                            'medium' => '#f59e0b',
                                            default => '#3b82f6'
                                        };
                                    @endphp
                                    <span style="color: {{ $pColor }}; font-size: 0.65rem; font-weight: 900; text-transform: uppercase;">{{ $task->priority ?? 'Medium' }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 32px; height: 32px; background: #f1f5f9; color: var(--deep-slate); border-radius: 10px; font-size: 0.75rem; display: flex; align-items: center; justify-content: center; font-weight: 900; border: 1.5px solid #e2e8f0;">
                                            {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                        </div>
                                        <div style="font-weight: 700; color: #475569;">{{ $task->assignee->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($task->pig_id)
                                        <span class="target-badge target-pig">Pig: {{ $task->pig->tag }}</span>
                                    @elseif($task->pen_id)
                                        <span class="target-badge target-pen">Pen: {{ $task->pen->name }}</span>
                                    @else
                                        <span class="target-badge target-general">General</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="width: 60px;">
                                        <div style="height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0;">
                                            <div style="width: {{ $task->progress ?? 0 }}%; height: 100%; background: #22c55e;"></div>
                                        </div>
                                        <div style="font-size: 0.6rem; font-weight: 900; color: #64748b; margin-top: 4px;">{{ $task->progress ?? 0 }}%</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $task->status }}">{{ $task->status }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                        @if($task->status === 'completed' && $task->findings)
                                            <button onclick="viewFindings({{ json_encode($task->findings) }}, '{{ addslashes($task->title) }}')" 
                                                    style="border: none; background: #f0fdf4; color: #16a34a; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; transition: all 0.2s;" title="View Findings">
                                                <i class="bx bx-file-find" style="font-size: 1.1rem;"></i>
                                            </button>
                                        @endif
                                        <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Archive this task?')">
                                            @csrf @method('DELETE')
                                            <button style="border: none; background: #fef2f2; color: #ef4444; width: 36px; height: 36px; border-radius: 10px; cursor: pointer; transition: all 0.2s;" class="hover:scale-110">
                                                <i class="bx bx-trash" style="font-size: 1.1rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" style="text-align: center; padding: 60px; color: #94a3b8; font-weight: 600;">No tasks scheduled for deployment.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assignment Form -->
        <div class="premium-panel" style="position: sticky; top: 100px;">
            <h2 style="font-size: 1.1rem; font-weight: 900; color: var(--deep-slate); margin-bottom: 24px;">New Assignment</h2>
            <form action="{{ route('admin.tasks.store') }}" method="POST">
                @csrf
                <div class="form-group" style="position: relative;">
                    <label class="form-label">Task Objective</label>
                    <input type="text" name="title" id="task-title" class="form-input" placeholder="e.g. Health Inspection" autocomplete="off" required>
                    <div id="suggestion-list" class="suggestion-box custom-scrollbar"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Protocol Details</label>
                    <textarea name="description" class="form-input" style="height: 100px; resize: none;" placeholder="Provide specific instructions for the worker..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Assign Personnel</label>
                    <select name="assigned_to" class="form-input" required>
                        <option value="">Choose worker...</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label class="form-label">Target Pen</label>
                        <select name="pen_id" class="form-input" id="swal-pen-select">
                            <option value="">General</option>
                            @foreach($pens as $pen)
                                <option value="{{ $pen->id }}">{{ $pen->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Target Pig (Opt)</label>
                        <select name="pig_id" class="form-input" id="swal-pig-select">
                            <option value="">Whole Pen</option>
                            @foreach($pigs as $pig)
                                <option value="{{ $pig->id }}" data-pen="{{ $pig->pen_id }}">#{{ $pig->tag }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label class="form-label">Deployment Deadline</label>
                        <input type="date" name="due_date" class="form-input" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Task Priority</label>
                        <select name="priority" class="form-input" required>
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-save">Deploy Task</button>
            </form>

            <div style="margin-top: 24px; padding: 16px; background: #fcfdfe; border-radius: 16px; border: 1.5px dashed #e2e8f0; display: flex; gap: 12px;">
                <i class='bx bx-info-circle' style="color: #22c55e; font-size: 1.2rem; margin-top: 2px;"></i>
                <p style="font-size: 0.75rem; color: #64748b; line-height: 1.5; font-weight: 500;">
                    Task data is synced instantly. Workers will receive notifications on their mobile dashboard.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const taskSuggestions = [
        { title: 'Vaccination Protocol', desc: 'Administer scheduled vaccines. Verify dosage and animal ID before injection.', tag: 'MEDICAL' },
        { title: 'Deworming Session', desc: 'Apply oral deworming treatment. Ensure the animal swallows the full dose.', tag: 'HEALTH' },
        { title: 'Growth Weight-In', desc: 'Record current body weight. Report any animals showing stagnant growth.', tag: 'GROWTH' },
        { title: 'Sanitation Cycle', desc: 'Deep clean the pen surface and feeding system with disinfectant.', tag: 'HYGIENE' },
        { title: 'Feeding Audit', desc: 'Check feeding consistency and mixture quality. Report any feed refusal.', tag: 'FEEDING' },
        { title: 'Iron Supplementation', desc: 'Apply iron injections to newborn piglets.', tag: 'PIGLETS' },
        { title: 'Water System Check', desc: 'Check all nipple drinkers for flow and hygiene.', tag: 'MAINTENANCE' }
    ];

    const titleInput = document.getElementById('task-title');
    const suggestionBox = document.getElementById('suggestion-list');
    const descTextarea = document.querySelector('textarea[name="description"]');
    const penSelect = document.getElementById('swal-pen-select');
    const pigSelect = document.getElementById('swal-pig-select');

    // Filter pigs based on selected pen
    penSelect.addEventListener('change', function() {
        const penId = this.value;
        const options = pigSelect.querySelectorAll('option');
        options.forEach(opt => {
            if (!penId || opt.value === "" || opt.getAttribute('data-pen') === penId) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        });
        if (penId) pigSelect.value = "";
    });

    titleInput.addEventListener('input', function() {
        const value = this.value.toLowerCase();
        suggestionBox.innerHTML = '';
        
        if (!value) {
            suggestionBox.style.display = 'none';
            return;
        }

        const filtered = taskSuggestions.filter(s => 
            s.title.toLowerCase().includes(value) || s.tag.toLowerCase().includes(value)
        );

        if (filtered.length > 0) {
            filtered.forEach(s => {
                const item = document.createElement('div');
                item.className = 'suggestion-item';
                item.innerHTML = `
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight: 800; color: #1e293b;">${s.title}</span>
                        <span style="font-size: 0.7rem; color: #94a3b8;">${s.tag}</span>
                    </div>
                    <i class='bx bx-chevron-right' style="color: #cbd5e1;"></i>
                `;
                item.addEventListener('click', () => {
                    titleInput.value = s.title;
                    descTextarea.value = s.desc;
                    suggestionBox.style.display = 'none';
                });
                suggestionBox.appendChild(item);
            });
            suggestionBox.style.display = 'block';
        } else {
            suggestionBox.style.display = 'none';
        }
    });

    document.addEventListener('click', function(e) {
        if (!suggestionBox.contains(e.target) && e.target !== titleInput) {
            suggestionBox.style.display = 'none';
        }
    });

    function viewFindings(findings, title) {
        let findingsHtml = '<div style="text-align: left; margin-top: 10px;">';
        if (findings && findings.length > 0) {
            findings.forEach(item => {
                findingsHtml += `
                    <div style="margin-bottom: 16px; padding: 12px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <i class='bx bx-check-circle' style="color: #22c55e;"></i>
                            <span style="font-size: 0.7rem; font-weight: 900; color: #64748b; text-transform: uppercase;">${item.text}</span>
                        </div>
                        <p style="font-size: 0.85rem; color: #1e293b; margin: 0; font-weight: 600; line-height: 1.4;">${item.finding || 'No finding recorded.'}</p>
                    </div>
                `;
            });
        } else {
            findingsHtml += '<p style="color: #94a3b8; font-style: italic; text-align: center;">No detailed findings were recorded for this task.</p>';
        }
        findingsHtml += '</div>';

        Swal.fire({
            title: title,
            html: findingsHtml,
            icon: 'info',
            confirmButtonText: 'Close',
            confirmButtonColor: '#0f172a',
            width: '450px',
            customClass: {
                popup: 'rounded-[28px]'
            }
        });
    }
</script>
@endpush


