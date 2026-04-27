<?php

namespace App\Http\Controllers;

use App\Models\WeeklyReport;
use App\Models\User;
use App\Models\Pig;
use App\Models\Pen;
use App\Models\Task;
use App\Models\FeedDelivery;
use App\Models\FeedConsumption;
use App\Models\PigActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Worker Dashboard
    public function dashboard()
    {
        $user = Auth::user();

        // Stats
        $pendingTasks = Task::where('assigned_to', $user->id)->where('status', 'pending')->count();
        $totalPigs = Pig::whereNotIn('status', ['Sold', 'Disposed'])->count();
        $sickPigsCount = Pig::where('health_status', 'Sick')->count();

        $totalDelivered = FeedDelivery::sum('quantity');
        $totalConsumed = FeedConsumption::sum('quantity');
        $feedStock = max($totalDelivered - $totalConsumed, 0);

        $stats = [
            ['label' => 'Active Tasks', 'val' => str_pad($pendingTasks, 2, '0', STR_PAD_LEFT), 'icon' => 'bx-list-check', 'color' => 'slate'],
            ['label' => 'Total Animals', 'val' => str_pad($totalPigs, 2, '0', STR_PAD_LEFT), 'icon' => 'bx-pig', 'color' => 'slate'],
            ['label' => 'Alerts', 'val' => str_pad($sickPigsCount, 2, '0', STR_PAD_LEFT), 'icon' => 'bx-bell', 'color' => 'red'],
            ['label' => 'Feed Stock', 'val' => round($feedStock) . 'kg', 'icon' => 'bx-bowl-hot', 'color' => 'green'],
        ];

        // Critical Alerts
        $criticalAlerts = Pig::where('health_status', 'Sick')->with('pen')->latest()->limit(1)->get();

        // Pens Overview
        $pens = Pen::with(['pigs' => function ($q) {
            $q->whereNotIn('status', ['Sold', 'Disposed']);
        }])->get()->map(function ($pen) {
            $avgWeight = $pen->pigs->avg('weight') ?? 0;
            $sickCount = $pen->pigs->where('health_status', 'Sick')->count();

            // Map tag and color based on status
            $statusMap = [
                'Good' => ['tag' => 'Good', 'color' => 'green'],
                'Fair' => ['tag' => 'Fair', 'color' => 'amber'],
                'Excellent' => ['tag' => 'Excellent', 'color' => 'indigo'],
            ];
            $statusInfo = $statusMap[$pen->status] ?? $statusMap['Good'];

            return [
            'id' => $pen->id,
            'name' => $pen->name,
            'type' => $pen->section ?? 'N/A',
            'count' => $pen->pigs->count(),
            'sick' => $sickCount,
            'weight' => round($avgWeight),
            'progress' => $pen->progress,
            'tag' => $statusInfo['tag'],
            'color' => $statusInfo['color']
            ];
        });

        // Recent Activity
        $recentActivities = PigActivity::with(['pig', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('worker.dashboard', compact('stats', 'criticalAlerts', 'pens', 'recentActivities'));
    }

    // Admin View: List all workers and their submission status
    public function adminIndex()
    {
        $workers = User::where('role', 'farm_worker')->get();
        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d');

        $reports = WeeklyReport::with('user')
            ->where('week_start_date', $thisWeek)
            ->get()
            ->keyBy('user_id');

        return view('admin.reports.index', compact('workers', 'reports', 'thisWeek'));
    }

    // Admin View: Show specific report details
    public function show($id)
    {
        $report = WeeklyReport::with('user')->findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    // Worker View: Show report submission form
    public function workerIndex()
    {
        $user = Auth::user();
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeek = $thisWeekStart->format('Y-m-d');

        $existingReport = WeeklyReport::where('user_id', $user->id)
            ->where('week_start_date', $thisWeek)
            ->where('status', 'submitted')
            ->first();

        // Real Analytics Data for the Worker
        $activePigs = Pig::whereNotIn('status', ['Sold', 'Disposed']);
        $totalPigs = (clone $activePigs)->count();
        $sickPigs = (clone $activePigs)->where('health_status', 'Sick')->count();
        $avgWeight = (clone $activePigs)->avg('weight') ?? 0;

        $totalDelivered = FeedDelivery::sum('quantity');
        $totalConsumed = FeedConsumption::sum('quantity');
        $availableStock = max($totalDelivered - $totalConsumed, 0);

        $tasksThisWeek = Task::where('assigned_to', $user->id)
            ->whereBetween('created_at', [$thisWeekStart, Carbon::now()->endOfWeek()]);

        $tasksDone = (clone $tasksThisWeek)->where('status', 'completed')->count();
        $tasksPending = (clone $tasksThisWeek)->where('status', 'pending')->count();

        $analytics = [
            'total_pigs' => $totalPigs,
            'sick_pigs' => $sickPigs,
            'avg_weight' => round($avgWeight, 1),
            'feed_stock' => round($availableStock, 1),
            'tasks_done' => $tasksDone,
            'tasks_pending' => $tasksPending,
            'weekly_progress' => [65, 78, 72, 85, 80, 90, 88] // Keep mock for chart for now unless we have historical data
        ];

        $userId = $user->id;
        $pens = Pen::with(['pigs' => function ($q) use ($userId) {
            $q->whereNotIn('status', ['Sold', 'Disposed'])
                ->orderByRaw("CASE health_status WHEN 'Sick' THEN 0 WHEN 'Warning' THEN 1 ELSE 2 END")
                ->with(['tasks' => function ($tq) use ($userId) {
                $tq->where('assigned_to', $userId)->where('status', '!=', 'completed');
            }
                    , 'activities' => function ($aq) {
                $aq->where('is_critical_alert', true)->whereNull('acknowledged_at');
            }
                ]);
        }])->get()->sortByDesc(function ($pen) {
            return $pen->pigs->where('health_status', '!=', 'Healthy')->count();
        });

        return view('worker.reports.index', compact('user', 'existingReport', 'thisWeek', 'analytics', 'pens'));
    }

    // Worker Action: Store weekly report
    public function store(Request $request)
    {
        $request->validate([
            'details' => 'required|string|min:10',
            'total_pigs' => 'required|integer',
            'sick_pigs' => 'required|integer',
            'avg_weight' => 'required|numeric',
            'feed_consumed' => 'required|numeric',
        ]);

        $thisWeek = Carbon::now()->startOfWeek()->format('Y-m-d');

        WeeklyReport::updateOrCreate(
        [
            'user_id' => Auth::id(),
            'week_start_date' => $thisWeek,
        ],
        [
            'total_pigs' => $request->total_pigs,
            'sick_pigs' => $request->sick_pigs,
            'avg_weight' => $request->avg_weight,
            'feed_consumed' => $request->feed_consumed,
            'details' => $request->details,
            'status' => 'submitted'
        ]
        );

        return redirect()->route('worker.reports')->with('success', 'Weekly report submitted successfully!');
    }
}
