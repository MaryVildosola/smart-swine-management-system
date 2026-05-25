@extends('layouts.master')
@section('contents')
<style>
.dashboard-wrap { padding: 32px; max-width: 1400px; margin: 0 auto; }
.page-title { font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.page-subtitle { color: #64748b; font-size: 0.875rem; margin-bottom: 32px; }

/* KPI Grid */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 32px; }
.kpi-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; transition: all 0.3s; }
.kpi-card:hover { border-color: #22c55e; box-shadow: 0 10px 20px rgba(0,0,0,0.02); }
.kpi-header { display: flex; justify-content: space-between; align-items: flex-start; color: #64748b; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin-bottom: 16px; }
.kpi-value { font-size: 1.75rem; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
.kpi-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }

/* Main Grid Layout */
.main-grid { display: grid; grid-template-columns: 1fr 380px; gap: 24px; }
.panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 28px; }
.panel-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; }

/* Task List Styling */
.task-item { border-bottom: 1px solid #f8fafc; padding: 16px 0; display: flex; gap: 16px; align-items: center; }
.task-item:last-child { border-bottom: none; }
.task-icon { width: 44px; height: 44px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b; flex-shrink: 0; }
.task-details { flex: 1; }
.task-name { font-size: 0.95rem; font-weight: 700; color: #1e293b; margin-bottom: 2px; }
.task-meta { font-size: 0.75rem; color: #94a3b8; }
.status-pill { padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; }
.pill-pending { background: #fff7ed; color: #c2410c; border: 1px solid #fdba74; }

/* Quick Actions Card */
.action-card { background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 20px; padding: 28px; color: #fff; position: sticky; top: 24px; }
.action-btn { display: flex; align-items: center; gap: 12px; padding: 14px; background: rgba(255,255,255,0.05); border-radius: 12px; margin-bottom: 12px; text-decoration: none; color: #cbd5e1; transition: all 0.2s; font-size: 0.9rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.05); }
.action-btn:hover { background: rgba(255,255,255,0.1); color: #fff; border-color: rgba(255,255,255,0.2); transform: translateX(4px); }
.action-btn i { font-size: 1.25rem; }
</style>

<div class="dashboard-wrap">
    <div class="header-block">
        <h1 class="page-title">Farm Dashboard</h1>
        <p class="page-subtitle">Real-time overview of your piggery operations</p>
    </div>

    <!-- KPI Summary Row -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-header"><span>Total Pigs</span><i class="bx bxs-group kpi-icon" style="color:#6366f1; background:#eef2ff;"></i></div>
            <div class="kpi-value">{{ $totalPigs }}</div>
            <div style="font-size:0.7rem; color:#94a3b8;">Active livestock counted</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-header"><span>Pending Tasks</span><i class="bx bx-list-check kpi-icon" style="color:#f59e0b; background:#fffbeb;"></i></div>
            <div class="kpi-value">{{ $pendingTasks }}</div>
            <div style="font-size:0.7rem; color:#94a3b8;">Needs attention today</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-header"><span>Sick Pigs</span><i class="bx bx-plus-medical kpi-icon" style="color:#ef4444; background:#fef2f2;"></i></div>
            <div class="kpi-value">{{ $sickPigs }}</div>
            <div style="font-size:0.7rem; color:#94a3b8;">Reported from pens</div>
        </div>
        <div class="kpi-card" style="border-left: 4px solid {{ $availableStock <= 10 ? '#ef4444' : '#22c55e' }};">
            <div class="kpi-header"><span>Stock Levels</span><i class="bx bx-package kpi-icon" style="color:#22c55e; background:#f0fdf4;"></i></div>
            <div class="kpi-value">{{ $availableStock }}</div>
            <div style="font-size:0.7rem; font-weight: 700; color:{{ $availableStock <= 10 ? '#ef4444' : '#22c55e' }}; text-transform: uppercase;">{{ $availableStock <= 10 ? 'Critical Low' : 'Healthy Stock' }}</div>
        </div>
    </div>
    </div>

    <!-- Smart Disease Engine Prediction -->
    <div style="margin-bottom: 32px; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 32px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #eef2ff, #e0e7ff); display: flex; align-items: center; justify-content: center; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <i class='bx bx-shield-quarter' style="color: #6366f1; font-size: 1.8rem;"></i>
                </div>
                <div>
                    <h2 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.02em;">Biosecurity Smart Engine</h2>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 4px;">
                        <span style="font-size: 0.65rem; font-weight: 800; color: #6366f1; background: #eef2ff; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">Real-time Analysis</span>
                        @php
                            $adm      = \App\Models\SystemSetting::get('farm_region');
                            $province = \App\Models\SystemSetting::get('farm_province');
                            $city     = \App\Models\SystemSetting::get('farm_city');
                            $location = ($city ? "{$city}, " : "") . "{$province}, " . str_replace('Region ', '', $adm);
                        @endphp
                        <span style="font-size: 0.65rem; font-weight: 800; color: #ef4444; background: #fef2f2; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">📍 Monitoring: {{ $location ?: 'Philippines' }}</span>
                        <span style="font-size: 0.65rem; color: #94a3b8; font-weight: 500;">Last Sync: {{ count($regionalDiseases) > 0 ? 'Recently' : 'Never' }}</span>
                    </div>
                </div>
            </div>
            
            <form action="{{ route('admin.disease-sync') }}" method="POST">
                @csrf
                <button type="submit" class="sync-btn" style="display: flex; align-items: center; gap: 10px; background: #1e293b; border: none; padding: 12px 20px; border-radius: 14px; font-size: 0.85rem; font-weight: 700; color: #fff; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);">
                    <i class='bx bx-refresh' style="font-size: 1.2rem;"></i>
                    Sync with AI Scout
                </button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: 350px 1fr; gap: 32px;">
            <!-- Regional Alerts Column -->
            <div style="background: #f8fafc; border-radius: 20px; padding: 24px; border: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                    <i class='bx bx-broadcast' style="color: #ef4444; font-size: 1.2rem;"></i>
                    <span style="font-size: 0.85rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Regional Threats</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @forelse($regionalDiseases as $disease)
                        <div style="padding: 16px; border-radius: 16px; background: #fff; border: 1px solid #e2e8f0; transition: transform 0.2s;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                <span style="font-size: 0.9rem; font-weight: 800; color: #1e293b;">{{ $disease->name }}</span>
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $disease->level == 'High' ? '#ef4444' : ($disease->level == 'Medium' ? '#f59e0b' : '#22c55e') }}; box-shadow: 0 0 10px {{ $disease->level == 'High' ? '#ef4444' : ($disease->level == 'Medium' ? '#f59e0b' : '#22c55e') }};"></div>
                            </div>
                            
                            <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                                <i class='bx bx-map-pin' style="color: #6366f1;"></i> {{ $disease->distance }}
                            </div>

                            <div style="background: #f8fafc; padding: 10px; border-radius: 10px; border: 1px solid #f1f5f9; margin-bottom: 12px; display: flex; flex-direction: column; gap: 8px;">
                                <div>
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Vector</div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: #475569;">{{ $disease->vector ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Action</div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: #4f46e5;">{{ $disease->action_required ?? 'Monitor' }}</div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; background: #f1f5f9; padding: 6px 10px; border-radius: 8px;">
                                <span style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase;">Status</span>
                                <span style="font-size: 0.65rem; font-weight: 800; color: {{ $disease->trend == 'spreading' ? '#ef4444' : '#64748b' }}; text-transform: capitalize;">
                                    {{ $disease->trend }} <i class='bx bx-trending-{{ $disease->trend == 'spreading' ? 'up' : ($disease->trend == 'decreasing' ? 'down' : 'flat') }}'></i>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 32px 10px; color: #94a3b8;">
                            <i class='bx bx-radar' style="font-size: 2.5rem; margin-bottom: 12px; opacity: 0.3;"></i>
                            <p style="font-size: 0.8rem; font-weight: 500;">No regional data synced yet. Use AI Scout to begin monitoring.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pen Safety Status Column -->
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                    <i class='bx bx-home-alt-2' style="color: #6366f1; font-size: 1.2rem;"></i>
                    <span style="font-size: 0.85rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.05em;">Pen Safety Assessment</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    @forelse($penRisks as $penRisk)
                        <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px; position: relative; overflow: hidden; border-left: 5px solid {{ $penRisk->risk_score >= 75 ? '#ef4444' : ($penRisk->risk_score >= 40 ? '#f59e0b' : '#22c55e') }};">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                                <div>
                                    <h3 style="font-size: 1.1rem; font-weight: 900; color: #1e293b; margin: 0;">{{ $penRisk->pen_name }}</h3>
                                    <p style="font-size: 0.7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; margin-top: 4px;">Assessment: <span style="color: {{ $penRisk->risk_score >= 75 ? '#ef4444' : ($penRisk->risk_score >= 40 ? '#f59e0b' : '#22c55e') }};">{{ $penRisk->status }}</span></p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.25rem; font-weight: 900; color: #1e293b;">{{ $penRisk->risk_score }}%</div>
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Risk Score</div>
                                </div>
                            </div>

                            <div style="background: #f8fafc; border-radius: 12px; padding: 12px; margin-bottom: 16px;">
                                <div style="display: flex; gap: 12px; margin-bottom: 8px;">
                                    <div style="flex: 1; text-align: center; border-right: 1px solid #e2e8f0;">
                                        <div style="font-size: 0.9rem; font-weight: 800; color: #1e293b;">{{ $penRisk->active_cases }}</div>
                                        <div style="font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Active Sick</div>
                                    </div>
                                    <div style="flex: 1; text-align: center;">
                                        <div style="font-size: 0.9rem; font-weight: 800; color: #1e293b;">{{ $penRisk->historical_cases }}</div>
                                        <div style="font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Past Issues</div>
                                    </div>
                                </div>
                            </div>

                            <div style="display: flex; align-items: flex-start; gap: 8px; background: {{ $penRisk->risk_score >= 75 ? '#fef2f2' : ($penRisk->risk_score >= 40 ? '#fffbeb' : '#f0fdf4') }}; padding: 12px; border-radius: 12px;">
                                <i class='bx bx-info-circle' style="color: {{ $penRisk->risk_score >= 75 ? '#ef4444' : ($penRisk->risk_score >= 40 ? '#d97706' : '#16a34a') }}; font-size: 1rem; margin-top: 2px;"></i>
                                <p style="font-size: 0.75rem; color: {{ $penRisk->risk_score >= 75 ? '#b91c1c' : ($penRisk->risk_score >= 40 ? '#92400e' : '#166534') }}; font-weight: 600; line-height: 1.4; margin: 0;">
                                    {{ $penRisk->recommendation }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: span 2; text-align: center; padding: 60px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 24px;">
                            <i class='bx bx-check-shield' style="font-size: 3.5rem; color: #22c55e; margin-bottom: 16px; opacity: 0.5;"></i>
                            <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b;">System Clear</h4>
                            <p style="font-size: 0.85rem; color: #94a3b8; max-width: 300px; margin: 8px auto 0;">Your farm has no active medical cases and no regional threats detected. All pens are currently in safe status.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="main-grid">
        <div class="left-col space-y-6">
            <!-- Emergency Alerts (If any) -->
            @if($criticalAlerts->count() > 0)
            <div class="panel" style="border: 2px solid #ef4444; background: #fff5f5;">
                <div class="panel-title" style="color: #b91c1c;">
                    <span class="flex items-center gap-2">
                        <i class="bx bxs-error-circle animate-pulse text-2xl"></i>
                        Critical Medical Alerts ({{ $criticalAlerts->count() }})
                    </span>
                    <span style="font-size: 0.7rem; background: #fee2e2; padding: 4px 10px; border-radius: 8px; color: #ef4444; font-weight: 800;">ACTION REQUIRED</span>
                </div>
                <div class="space-y-4">
                    @foreach($criticalAlerts as $alert)
                    <div class="flex items-start justify-between p-4 rounded-xl bg-white border border-red-100 shadow-sm">
                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                                <i class="bx bx-plus-medical text-red-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-900">Pig #{{ $alert->pig->tag }} — {{ $alert->action }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $alert->details }}</p>
                                <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mt-2">{{ $alert->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <a href="{{ route('pens.index') }}" onclick="sessionStorage.setItem('pending_pig_modal', '{{ $alert->pig->id }}'); sessionStorage.setItem('pending_pen_id', '{{ $alert->pig->pen_id }}');" class="px-4 py-2 rounded-lg bg-red-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition-colors">
                            Handle Now
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Activity Panel -->
            <div class="panel">
                <div class="panel-title">
                    <span>Recent Farm Activity</span>
                    <a href="{{ route('admin.tasks.index') }}" style="font-size: 0.75rem; color: #22c55e; text-decoration: none; font-weight: 700;">VIEW ALL</a>
                </div>
            
            <div class="task-list">
                @forelse($recentTasks as $task)
                    <div class="task-item">
                        <div class="task-icon"><i class="bx bx-task"></i></div>
                        <div class="task-details">
                            <div class="task-name">{{ $task->title }}</div>
                            <div class="task-meta">Assigned to {{ $task->assignee->name }} • {{ $task->due_date->format('M d') }}</div>
                        </div>
                        <span class="status-pill pill-{{ $task->status }}">{{ $task->status }}</span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; color: #94a3b8;">
                        <i class="bx bx-calendar-x" style="font-size: 2rem; margin-bottom: 8px;"></i>
                        <p style="font-size: 0.85rem;">No tasks logged recently</p>
                    </div>
                @endforelse
            </div>
        </div>
        </div>

        <!-- Right Side Actions -->
        <div>
            <div class="action-card">
                <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: 24px;">Quick Actions</h3>
                
                <a href="{{ route('admin.tasks.store') }}" class="action-btn">
                    <i class="bx bx-plus-circle"></i>
                    <span>Create New Task</span>
                </a>
                <a href="{{ route('pens.index') }}" class="action-btn">
                    <i class="bx bx-door-open"></i>
                    <span>Manage Farm Pens</span>
                </a>
                <a href="{{ route('admin.feed-stock.index') }}" class="action-btn">
                    <i class="bx bx-box"></i>
                    <span>Check Inventory</span>
                </a>
                <a href="{{ route('users.index') }}" class="action-btn">
                    <i class="bx bx-user-plus"></i>
                    <span>Add Farm Worker</span>
                </a>

                <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <p style="font-size: 0.75rem; color: #64748b; line-height: 1.6;">
                        Current Shift: <b>Morning Operations</b><br>
                        System Status: <span style="color: #22c55e;">● Operational</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
