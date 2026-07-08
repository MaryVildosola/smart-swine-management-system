@extends('layouts.master')
@section('contents')
<style>
    .analytics-wrap { padding: 24px 32px; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; margin-bottom: 28px; }
    .stat-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 20px;
        padding: 24px; transition: all 0.3s;
    }
    .stat-card:hover { border-color: #22c55e; box-shadow: 0 8px 24px rgba(0,0,0,0.03); }
    .stat-label { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 12px; }
    .stat-value { font-size: 1.8rem; font-weight: 900; color: #1e293b; line-height: 1; }
    .stat-sub { font-size: 0.7rem; color: #94a3b8; margin-top: 6px; font-weight: 600; }
    .stat-icon { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 14px; }

    .chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px; }
    .chart-card {
        background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 28px;
    }
    .chart-title { font-size: 1rem; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
    .chart-subtitle { font-size: 0.8rem; color: #94a3b8; margin-bottom: 20px; }

    .three-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin-bottom: 28px; }

    /* Progress bars */
    .bar-row { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .bar-label { font-size: 0.75rem; font-weight: 700; color: #475569; min-width: 80px; }
    .bar-track { flex: 1; height: 10px; background: #f1f5f9; border-radius: 999px; overflow: hidden; }
    .bar-fill { height: 100%; border-radius: 999px; transition: width 0.6s ease; }
    .bar-count { font-size: 0.75rem; font-weight: 800; color: #1e293b; min-width: 30px; text-align: right; }

    /* Activity feed */
    .activity-item {
        display: flex; align-items: flex-start; gap: 14px; padding: 14px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 5px; flex-shrink: 0; }
    .activity-text { font-size: 0.8rem; font-weight: 600; color: #475569; }
    .activity-time { font-size: 0.65rem; color: #94a3b8; margin-top: 2px; }

    /* Pen occupancy */
    .pen-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .pen-name { font-size: 0.7rem; font-weight: 800; color: #475569; min-width: 70px; }
    .pen-track { flex: 1; height: 24px; background: #f1f5f9; border-radius: 8px; overflow: hidden; position: relative; }
    .pen-fill { height: 100%; border-radius: 8px; display: flex; align-items: center; justify-content: flex-end; padding-right: 8px; font-size: 0.6rem; font-weight: 900; color: #fff; transition: width 0.5s ease; min-width: 30px; }

    /* Revenue chart bars */
    .revenue-chart { display: flex; align-items: flex-end; gap: 12px; height: 180px; padding-top: 10px; }
    .revenue-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; }
    .revenue-bar { width: 100%; border-radius: 8px 8px 0 0; transition: height 0.5s ease; position: relative; }
    .revenue-bar:hover { opacity: 0.85; }
    .revenue-bar-label { font-size: 0.65rem; font-weight: 800; color: #94a3b8; margin-top: 8px; text-transform: uppercase; }
    .revenue-bar-value { font-size: 0.6rem; font-weight: 900; color: #1e293b; margin-bottom: 4px; }
</style>

<div class="analytics-wrap">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 28px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -0.03em;">Live Analytics</h1>
            <p style="font-size: 0.9rem; color: #64748b; margin-top: 4px;">Real-time farm performance dashboard — all data is live.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; background: #f0fdf4; padding: 8px 16px; border-radius: 12px; border: 1px solid #bbf7d0;">
            <div style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; animation: pulse 2s infinite;"></div>
            <span style="font-size: 0.7rem; font-weight: 800; color: #15803d; text-transform: uppercase; letter-spacing: 0.05em;">Live Data</span>
        </div>
    </div>

    <!-- Strictly Vertical Biosecurity Intelligence (PRIORITY #1) -->
    <div style="margin-bottom: 32px; background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <!-- Header Stacked Vertically -->
        <div style="display: flex; flex-direction: column; gap: 20px; margin-bottom: 32px; border-bottom: 1px solid #f1f5f9; padding-bottom: 24px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #4f46e5; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(79, 70, 229, 0.2);">
                    <i class='bx bx-radar pulse-icon' style="color: #fff; font-size: 2rem;"></i>
                </div>
                <div>
                    <h2 style="font-size: 1.3rem; font-weight: 800; color: #1e293b; margin: 0;">Smart Disease Intelligence</h2>
                    <div style="display: flex; gap: 8px; margin-top: 4px;">
                        <span style="font-size: 0.7rem; font-weight: 800; color: #4f46e5; background: #eef2ff; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">AI Active Monitoring Engine</span>
                        @php
                            $island   = \App\Models\SystemSetting::get('farm_island_group'); // Old key for fallback display
                            $adm      = \App\Models\SystemSetting::get('farm_region');
                            $province = \App\Models\SystemSetting::get('farm_province');
                            $city     = \App\Models\SystemSetting::get('farm_city');
                            $location = ($city ? "{$city}, " : "") . "{$province}, " . str_replace('Region ', '', $adm);
                        @endphp
                        <span style="font-size: 0.7rem; font-weight: 800; color: #ef4444; background: #fef2f2; padding: 2px 8px; border-radius: 6px; text-transform: uppercase;">📍 Monitoring: {{ $location ?: 'Philippines' }}</span>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 16px 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Overall Farm Biosecurity</div>
                    <div style="font-size: 1.1rem; font-weight: 900; color: #22c55e; display: flex; align-items: center; gap: 6px;">
                        <i class='bx bxs-check-shield'></i> SECURE STATUS
                    </div>
                </div>
                <form action="{{ route('admin.disease-sync') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: #1e293b; border: none; padding: 12px 28px; border-radius: 14px; font-size: 0.9rem; font-weight: 800; color: #fff; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);">
                        <i class='bx bx-refresh' style="font-size: 1.2rem;"></i> Sync Local AI Scout
                    </button>
                </form>
            </div>
        </div>

        <!-- Regional Monitoring (Full Width Section) -->
    <style>
        details.bio-dropdown > summary { list-style: none; }
        details.bio-dropdown > summary::-webkit-details-marker { display: none; }
        details.bio-dropdown[open] > summary .dropdown-icon { transform: rotate(180deg); }
    </style>
        <div style="margin-bottom: 24px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-broadcast' style="color: #ef4444; font-size: 1.2rem;"></i>
                    <span style="font-size: 0.9rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em;">1. Regional Threat Environment</span>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 16px; align-items: start;">
                @forelse($regionalDiseases as $disease)
                    <details class="bio-dropdown" style="padding: 16px; border-radius: 16px; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); cursor: pointer;">
                        <summary style="display: flex; justify-content: space-between; align-items: center; outline: none; list-style: none;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background: {{ $disease->level == 'High' ? '#ef4444' : ($disease->level == 'Medium' ? '#f59e0b' : '#22c55e') }}; box-shadow: 0 0 10px {{ $disease->level == 'High' ? 'rgba(239, 68, 68, 0.5)' : ($disease->level == 'Medium' ? 'rgba(245, 158, 11, 0.5)' : 'rgba(34, 197, 94, 0.5)') }};"></div>
                                <h4 style="font-size: 1rem; font-weight: 900; color: #1e293b; margin: 0;">{{ $disease->name }}</h4>
                            </div>
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="font-size: 0.65rem; font-weight: 900; color: {{ $disease->level == 'High' ? '#ef4444' : ($disease->level == 'Medium' ? '#f59e0b' : '#22c55e') }}; text-transform: uppercase; background: {{ $disease->level == 'High' ? '#fef2f2' : ($disease->level == 'Medium' ? '#fffbeb' : '#f0fdf4') }}; padding: 4px 10px; border-radius: 6px; border: 1px solid {{ $disease->level == 'High' ? '#fee2e2' : ($disease->level == 'Medium' ? '#fef3c7' : '#dcfce7') }};">
                                    {{ $disease->level }} Risk
                                </div>
                                <i class='bx bx-chevron-down dropdown-icon' style="color: #94a3b8; font-size: 1.4rem; transition: transform 0.2s;"></i>
                            </div>
                        </summary>
                        
                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 12px;">
                            <div style="font-size: 0.75rem; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 6px; justify-content: space-between;">
                                <div><i class='bx bx-map-pin' style="color: #6366f1;"></i> {{ $disease->distance }}</div>
                                <div style="text-transform: uppercase;">Trend: <span style="color: {{ $disease->trend == 'spreading' ? '#ef4444' : '#1e293b' }};">{{ $disease->trend }}</span></div>
                            </div>
                            
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                                <div>
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Known Vector / Source</div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: #475569;">{{ $disease->vector ?? 'Not specified' }}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Primary Symptoms</div>
                                    <div style="font-size: 0.75rem; font-weight: 700; color: #475569;">{{ $disease->symptoms ?? 'Not specified' }}</div>
                                </div>
                            </div>

                            <div style="background: #eef2ff; padding: 10px 14px; border-radius: 10px; border: 1px solid #e0e7ff; display: flex; gap: 8px; align-items: flex-start;">
                                <i class='bx bx-shield-quarter' style="color: #4f46e5; font-size: 1.1rem; margin-top: 2px;"></i>
                                <div>
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #4f46e5; text-transform: uppercase; margin-bottom: 2px;">AI Recommended Action</div>
                                    <div style="font-size: 0.8rem; font-weight: 700; color: #3730a3;">{{ $disease->action_required ?? 'Maintain standard biosecurity protocols.' }}</div>
                                </div>
                            </div>
                        </div>
                    </details>
                @empty
                    <div style="text-align: center; padding: 40px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 20px;">
                        <p style="font-size: 0.85rem; color: #94a3b8; font-weight: 600; margin: 0;">No active regional threats recorded. Perform an AI Sync to update.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pen Assessment (Full Width List) -->
        <div>
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class='bx bx-shield-check' style="color: #4f46e5; font-size: 1.2rem;"></i>
                    <span style="font-size: 0.9rem; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em;">2. Individual Pen Safety Analysis</span>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 16px; align-items: start;">
                @forelse($penRisks as $penRisk)
                    <details class="bio-dropdown" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; border-left: 6px solid {{ $penRisk->risk_score >= 75 ? '#ef4444' : ($penRisk->risk_score >= 40 ? '#f59e0b' : '#22c55e') }}; cursor: pointer;">
                        <summary style="display: flex; justify-content: space-between; align-items: center; outline: none; list-style: none;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <h3 style="font-size: 1.1rem; font-weight: 900; color: #1e293b; margin: 0;">{{ $penRisk->pen_name }}</h3>
                                <span style="font-size: 0.65rem; font-weight: 800; color: #fff; background: {{ $penRisk->risk_score >= 75 ? '#ef4444' : ($penRisk->risk_score >= 40 ? '#f59e0b' : '#22c55e') }}; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">
                                    {{ $penRisk->status }}
                                </span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="font-size: 0.65rem; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Risk Score:</span>
                                    <span style="font-size: 1.1rem; font-weight: 900; color: #1e293b;">{{ $penRisk->risk_score }}%</span>
                                </div>
                                <i class='bx bx-chevron-down dropdown-icon' style="color: #94a3b8; font-size: 1.4rem; transition: transform 0.2s;"></i>
                            </div>
                        </summary>

                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f1f5f9; display: flex; flex-direction: column; gap: 16px;">
                            <!-- Full Width Score Breakdown -->
                            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px;">
                                <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; text-align: center;">
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Regional Factor</div>
                                    <div style="font-size: 0.9rem; font-weight: 900; color: #475569;">20%</div>
                                </div>
                                <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; text-align: center;">
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Active Case Weight</div>
                                    <div style="font-size: 0.9rem; font-weight: 900; color: #4f46e5;">{{ $penRisk->active_cases * 25 }}%</div>
                                </div>
                                <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9; text-align: center;">
                                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 2px;">Historical Impact</div>
                                    <div style="font-size: 0.9rem; font-weight: 900; color: #818cf8;">{{ $penRisk->historical_cases * 5 }}%</div>
                                </div>
                            </div>

                            <!-- Action Alert (Full Width) -->
                            <div style="background: {{ $penRisk->risk_score >= 75 ? '#fef2f2' : ($penRisk->risk_score >= 40 ? '#fffbeb' : '#f0fdf4') }}; padding: 14px; border-radius: 12px; border: 1px solid {{ $penRisk->risk_score >= 75 ? '#fee2e2' : ($penRisk->risk_score >= 40 ? '#fef3c7' : '#dcfce7') }}; display: flex; align-items: flex-start; gap: 10px;">
                                <i class='bx bx-info-circle' style="color: {{ $penRisk->risk_score >= 75 ? '#ef4444' : ($penRisk->risk_score >= 40 ? '#d97706' : '#16a34a') }}; font-size: 1.1rem; margin-top: 2px;"></i>
                                <div>
                                    <div style="font-size: 0.65rem; font-weight: 900; color: {{ $penRisk->risk_score >= 75 ? '#b91c1c' : ($penRisk->risk_score >= 40 ? '#92400e' : '#166534') }}; text-transform: uppercase; margin-bottom: 2px;">Recommended Biosecurity Strategy:</div>
                                    <p style="font-size: 0.8rem; color: {{ $penRisk->risk_score >= 75 ? '#991b1b' : ($penRisk->risk_score >= 40 ? '#854d0e' : '#14532d') }}; font-weight: 600; line-height: 1.5; margin: 0;">
                                        {{ $penRisk->recommendation }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </details>
                @empty
                    <div style="text-align: center; padding: 60px; background: #f8fafc; border: 2px dashed #e2e8f0; border-radius: 30px;">
                        <i class='bx bx-check-shield' style="font-size: 3rem; color: #22c55e; opacity: 0.5; margin-bottom: 16px;"></i>
                        <p style="font-size: 1rem; color: #94a3b8; font-weight: 700;">All parameters normal. Biosecurity integrity verified.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Farm Intelligence Summary -->
    @php
        $healthRate = $totalPigs > 0 ? round(($healthyPigs / $totalPigs) * 100) : 0;
        $stockPercent = $totalDelivered > 0 ? round(($availableStock / $totalDelivered) * 100) : 0;
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
    @endphp
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 24px; padding: 32px; margin-bottom: 28px; position: relative; overflow: hidden;">
        <div style="position: absolute; top: -40px; right: -40px; width: 200px; height: 200px; background: rgba(34, 197, 94, 0.08); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -60px; right: 100px; width: 160px; height: 160px; background: rgba(59, 130, 246, 0.06); border-radius: 50%;"></div>

        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
            <i class='bx bx-brain' style="color: #22c55e; font-size: 1.4rem;"></i>
            <h2 style="font-size: 1rem; font-weight: 900; color: #fff; margin: 0; letter-spacing: -0.02em;">Farm Intelligence Summary</h2>
            <span style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; background: rgba(255,255,255,0.08); padding: 3px 10px; border-radius: 8px; text-transform: uppercase; letter-spacing: 0.08em;">{{ now()->format('M d, Y') }}</span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px 32px;">
            <!-- Livestock Health -->
            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; background: rgba(255,255,255,0.04); border-radius: 16px; border: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $healthRate >= 80 ? 'rgba(34,197,94,0.15)' : ($healthRate >= 50 ? 'rgba(245,158,11,0.15)' : 'rgba(239,68,68,0.15)') }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class='bx {{ $healthRate >= 80 ? "bx-check-circle" : ($healthRate >= 50 ? "bx-info-circle" : "bx-error") }}' style="color: {{ $healthRate >= 80 ? '#22c55e' : ($healthRate >= 50 ? '#f59e0b' : '#ef4444') }}; font-size: 1.1rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px;">Livestock Health</div>
                    <div style="font-size: 0.85rem; color: #e2e8f0; line-height: 1.5; font-weight: 500;">
                        You have <strong style="color: #fff;">{{ $totalPigs }} active pigs</strong> across <strong style="color: #fff;">{{ $activePens }} pens</strong>.
                        @if($sickPigs == 0)
                            All animals are in <strong style="color: #22c55e;">healthy condition</strong> — no health concerns detected.
                        @elseif($sickPigs <= 2)
                            <strong style="color: #f59e0b;">{{ $sickPigs }} pig{{ $sickPigs > 1 ? 's' : '' }}</strong> need{{ $sickPigs == 1 ? 's' : '' }} medical attention. Health rate is at <strong style="color: #fff;">{{ $healthRate }}%</strong>.
                        @else
                            <strong style="color: #ef4444;">{{ $sickPigs }} pigs</strong> are currently sick. Immediate veterinary intervention is recommended. Health rate is <strong style="color: #ef4444;">{{ $healthRate }}%</strong>.
                        @endif
                    </div>
                </div>
            </div>

            <!-- Feed & Inventory -->
            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; background: rgba(255,255,255,0.04); border-radius: 16px; border: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $availableStock <= 10 ? 'rgba(239,68,68,0.15)' : ($availableStock <= 25 ? 'rgba(245,158,11,0.15)' : 'rgba(34,197,94,0.15)') }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class='bx bx-package' style="color: {{ $availableStock <= 10 ? '#ef4444' : ($availableStock <= 25 ? '#f59e0b' : '#22c55e') }}; font-size: 1.1rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px;">Feed & Inventory</div>
                    <div style="font-size: 0.85rem; color: #e2e8f0; line-height: 1.5; font-weight: 500;">
                        <strong style="color: #fff;">{{ number_format($availableStock) }} sacks</strong> remaining ({{ $stockPercent }}% of total supply).
                        @if($availableStock <= 10)
                            <strong style="color: #ef4444;">Critical level!</strong> Restock immediately to avoid feeding disruptions.
                        @elseif($availableStock <= 25)
                            Stock is <strong style="color: #f59e0b;">moderate</strong> — consider placing a new order within the week.
                        @else
                            Inventory levels are <strong style="color: #22c55e;">healthy</strong> and sufficient for current operations.
                        @endif
                    </div>
                </div>
            </div>

            <!-- Task Performance -->
            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; background: rgba(255,255,255,0.04); border-radius: 16px; border: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $overdueTasks > 0 ? 'rgba(239,68,68,0.15)' : 'rgba(34,197,94,0.15)' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class='bx bx-task' style="color: {{ $overdueTasks > 0 ? '#ef4444' : '#22c55e' }}; font-size: 1.1rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px;">Task Performance</div>
                    <div style="font-size: 0.85rem; color: #e2e8f0; line-height: 1.5; font-weight: 500;">
                        <strong style="color: #fff;">{{ $completedTasks }}/{{ $totalTasks }}</strong> tasks completed (<strong style="color: #fff;">{{ $taskCompletionRate }}%</strong> rate).
                        @if($overdueTasks > 0)
                            <strong style="color: #ef4444;">{{ $overdueTasks }} task{{ $overdueTasks > 1 ? 's are' : ' is' }} overdue</strong> — follow up with assigned workers.
                        @elseif($pendingTasks > 0)
                            <strong style="color: #f59e0b;">{{ $pendingTasks }}</strong> task{{ $pendingTasks > 1 ? 's' : '' }} still pending for today.
                        @else
                            All tasks are <strong style="color: #22c55e;">up to date</strong>. Great job!
                        @endif
                    </div>
                </div>
            </div>

            <!-- Revenue & Growth -->
            <div style="display: flex; align-items: flex-start; gap: 14px; padding: 16px; background: rgba(255,255,255,0.04); border-radius: 16px; border: 1px solid rgba(255,255,255,0.06);">
                <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(168,85,247,0.15); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class='bx bx-trending-up' style="color: #a855f7; font-size: 1.1rem;"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px;">Revenue & Growth</div>
                    <div style="font-size: 0.85rem; color: #e2e8f0; line-height: 1.5; font-weight: 500;">
                        Total revenue is <strong style="color: #fff;">₱{{ number_format($totalRevenue, 0) }}</strong> from <strong style="color: #fff;">{{ $totalSold }} sold</strong> pigs.
                        Average weight is <strong style="color: #fff;">{{ $avgWeight }} kg</strong>.
                        @if($avgWeight >= 90)
                            Many pigs are at <strong style="color: #22c55e;">market-ready weight</strong> — ideal time for sales.
                        @elseif($avgWeight >= 60)
                            Livestock is in the <strong style="color: #f59e0b;">grower-to-finisher</strong> stage. Market readiness approaching.
                        @else
                            Most animals are still in <strong style="color: #3b82f6;">early growth stages</strong>. Continue feeding protocol.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Row -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #f0fdf4; color: #22c55e;"><i class='bx bx-heart'></i></div>
            <div class="stat-label">Active Livestock</div>
            <div class="stat-value">{{ number_format($totalPigs) }}</div>
            <div class="stat-sub">{{ $healthyPigs }} healthy · {{ $sickPigs }} sick</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;"><i class='bx bx-grid-alt'></i></div>
            <div class="stat-label">Pens Active</div>
            <div class="stat-value">{{ $activePens }}<span style="font-size: 0.9rem; color: #94a3b8;"> / {{ $totalPens }}</span></div>
            <div class="stat-sub">Currently housing pigs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fefce8; color: #eab308;"><i class='bx bx-bar-chart-alt-2'></i></div>
            <div class="stat-label">Avg. Weight</div>
            <div class="stat-value">{{ $avgWeight }}<span style="font-size: 0.9rem; color: #94a3b8;"> kg</span></div>
            <div class="stat-sub">Across all active pigs</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: {{ $availableStock <= 10 ? '#fef2f2' : '#f0fdf4' }}; color: {{ $availableStock <= 10 ? '#ef4444' : '#22c55e' }};"><i class='bx bx-package'></i></div>
            <div class="stat-label">Feed Stock</div>
            <div class="stat-value" style="color: {{ $availableStock <= 10 ? '#ef4444' : '#1e293b' }};">{{ number_format($availableStock) }}</div>
            <div class="stat-sub">{{ number_format($totalConsumed) }} consumed of {{ number_format($totalDelivered) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fdf4ff; color: #a855f7;"><i class='bx bx-money'></i></div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">₱{{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-sub">{{ $totalSold }} sold · {{ $totalDisposed }} disposed</div>
        </div>
    </div>

    <!-- Charts Row (Revenue & Growth) -->
    <div class="chart-grid">
        <!-- Monthly Revenue -->
        <div class="chart-card">
            <div class="chart-title">Monthly Revenue Trend</div>
            <div class="chart-subtitle">Historical and projected sales valuation</div>
            @php $maxRevenue = max(max(array_column($monthlyRevenue, 'value')), 1); @endphp
            <div class="revenue-chart">
                @foreach($monthlyRevenue as $m)
                <div class="revenue-bar-wrap">
                    <div class="revenue-bar-value">{{ $m['value'] > 0 ? '₱' . number_format($m['value'], 0) : '-' }}</div>
                    <div class="revenue-bar" style="height: {{ max(($m['value'] / $maxRevenue) * 100, 4) }}%; background: linear-gradient(180deg, #22c55e, #16a34a);"></div>
                    <div class="revenue-bar-label">{{ $m['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Growth Stage Breakdown -->
        <div class="chart-card">
            <div class="chart-title">Growth Stage Breakdown</div>
            <div class="chart-subtitle">Population by maturity level</div>
            @php
                $stageColors = ['Piglet' => '#f472b6', 'Starter' => '#fb923c', 'Grower' => '#facc15', 'Finisher' => '#22c55e'];
                $maxStage = max($stageBreakdown) ?: 1;
            @endphp
            @foreach($stageBreakdown as $stage => $count)
            <div class="bar-row">
                <div class="bar-label">{{ $stage }}</div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ ($count / $maxStage) * 100 }}%; background: {{ $stageColors[$stage] ?? '#94a3b8' }};"></div>
                </div>
                <div class="bar-count">{{ $count }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pen Utilization Detail -->
    <div style="margin-bottom: 28px;">
        <div class="chart-card">
            <div class="chart-title">Pen Occupancy Overview</div>
            <div class="chart-subtitle">Number of active animals per pen (Functional batches only)</div>
            @php $maxOccupancy = max($penData->max('pigs_count'), 1); @endphp
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                @foreach($penData->take(10) as $pen)
                <div class="pen-bar">
                    <div class="pen-name">{{ $pen->name }}</div>
                    <div class="pen-track">
                        <div class="pen-fill" style="width: {{ max(($pen->pigs_count / $maxOccupancy) * 100, 6) }}%; background: {{ $pen->pigs_count == 0 ? '#e2e8f0' : 'linear-gradient(90deg, #22c55e, #16a34a)' }};">
                            {{ $pen->pigs_count }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- EVERYTHING ELSE (Stacked at the Bottom as requested) -->
    <div class="chart-grid">
        <!-- Health Breakdown -->
        <div class="chart-card">
            <div class="chart-title">Health Status Distribution</div>
            <div class="chart-subtitle">Current clinical status of active herd</div>
            @php
                $healthColors = ['Healthy' => '#22c55e', 'Sick' => '#ef4444', 'Under Observation' => '#f59e0b', 'Recovering' => '#3b82f6'];
                $maxHealth = max($healthBreakdown) ?: 1;
            @endphp
            @foreach($healthBreakdown as $status => $count)
            <div class="bar-row">
                <div class="bar-label" style="min-width: 110px;">{{ $status }}</div>
                <div class="bar-track">
                    <div class="bar-fill" style="width: {{ ($count / $maxHealth) * 100 }}%; background: {{ $healthColors[$status] ?? '#94a3b8' }};"></div>
                </div>
                <div class="bar-count">{{ $count }}</div>
            </div>
            @endforeach
        </div>

        <!-- Weight Distribution -->
        <div class="chart-card">
            <div class="chart-title">Weight Distribution</div>
            <div class="chart-subtitle">Live animals grouped by weight range</div>
            @php
                $weightColors = ['0-10kg' => '#f472b6', '10-30kg' => '#fb923c', '30-60kg' => '#facc15', '60-90kg' => '#22c55e', '90kg+' => '#3b82f6'];
                $maxWeight = max($weightRanges) ?: 1;
            @endphp
            @foreach($weightRanges as $range => $count)
            <div class="bar-row">
                <div class="bar-label">{{ $range }}</div>
                <div class="bar-track">
                            <div class="bar-fill" style="width: {{ ($count / $maxWeight) * 100 }}%; background: {{ $weightColors[$range] ?? '#94a3b8' }};"></div>
                </div>
                <div class="bar-count">{{ $count }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="chart-grid">
        <!-- Task Metrics -->
        <div class="chart-card">
            <div class="chart-title">Task Overview</div>
            <div class="chart-subtitle">Assignment status across {{ $totalWorkers }} active workers</div>
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-top: 8px;">
                <div style="text-align: center; padding: 20px 12px; background: #f8fafc; border-radius: 16px; border: 1px solid #f1f5f9;">
                    <div style="font-size: 1.5rem; font-weight: 900; color: #1e293b;">{{ $totalTasks }}</div>
                    <div style="font-size: 0.6rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">Total</div>
                </div>
                <div style="text-align: center; padding: 20px 12px; background: #fffbeb; border-radius: 16px; border: 1px solid #fef3c7;">
                    <div style="font-size: 1.5rem; font-weight: 900; color: #f59e0b;">{{ $pendingTasks }}</div>
                    <div style="font-size: 0.6rem; font-weight: 800; color: #d97706; text-transform: uppercase; margin-top: 4px;">Pending</div>
                </div>
                <div style="text-align: center; padding: 20px 12px; background: #f0fdf4; border-radius: 16px; border: 1px solid #bbf7d0;">
                    <div style="font-size: 1.5rem; font-weight: 900; color: #22c55e;">{{ $completedTasks }}</div>
                    <div style="font-size: 0.6rem; font-weight: 800; color: #16a34a; text-transform: uppercase; margin-top: 4px;">Done</div>
                </div>
                <div style="text-align: center; padding: 20px 12px; background: #fef2f2; border-radius: 16px; border: 1px solid #fecaca;">
                    <div style="font-size: 1.5rem; font-weight: 900; color: #ef4444;">{{ $overdueTasks }}</div>
                    <div style="font-size: 0.6rem; font-weight: 800; color: #dc2626; text-transform: uppercase; margin-top: 4px;">Overdue</div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="chart-card">
            <div class="chart-title">Recent Activity Feed</div>
            <div class="chart-subtitle">Latest chronological farm events</div>
            @forelse($recentActivities as $activity)
            <div class="activity-item {{ $activity->is_critical_alert ? 'bg-red-50 p-3 rounded-xl border border-red-100 mb-2' : '' }}">
                <div class="activity-dot" style="background: {{ $activity->is_critical_alert ? '#ef4444' : ($activity->type === 'Medical' ? '#ef4444' : ($activity->type === 'Care' ? '#22c55e' : '#3b82f6')) }};"></div>
                <div>
                    <div class="activity-text">
                        @if($activity->is_critical_alert)
                            <span style="color: #ef4444; font-weight: 900; text-transform: uppercase; font-size: 0.7rem; display: block; margin-bottom: 4px;">🚨 CRITICAL ALERT</span>
                        @endif
                        <span style="font-weight: 800; color: #1e293b;">{{ $activity->action }}</span>
                        @if($activity->pig)
                            <span style="background: #f1f5f9; padding: 2px 8px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; margin-left: 6px;">#{{ $activity->pig->tag }}</span>
                        @endif
                    </div>
                    <div class="activity-time">{{ $activity->created_at->diffForHumans() }}</div>
                    @if($activity->is_critical_alert && $activity->details)
                        <p style="font-size: 0.75rem; color: #b91c1c; font-weight: 500; margin-top: 4px; line-height: 1.4;">{{ $activity->details }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; color: #94a3b8; font-weight: 600;">No recent activities recorded.</div>
            @endforelse
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
</style>
@endsection
