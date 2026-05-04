<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\FeedMixController;
use App\Http\Controllers\FeedIngredientController;
use App\Http\Controllers\Worker\WorkerFeedFormulaController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PenController;
use App\Http\Controllers\PigController;
use App\Http\Controllers\HealthController;

use App\Http\Controllers\AnalyticsController;

// --- PUBLIC & REDIRECTS ---
Route::get('/', function (Request $request) {
    if ($request->user()) {
        $url = $request->user()->role === 'admin' ? '/admin/dashboard' : '/worker/dashboard';
        return redirect($url);
    }
    return view('landing');
})->name('landing');

Route::get('/dashboard', function (Request $request) {
    if ($request->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }
    elseif ($request->user()->role === 'farm_worker') {
        return redirect('/worker/dashboard');
    }
    return abort(403);
})->middleware(['auth', 'verified'])->name('dashboard');

// --- ADMIN ZONE ---
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
            $pendingTasks = \App\Models\Task::where('status', 'pending')->count();
            $totalPigs = \App\Models\Pig::whereNotIn('status', ['Sold', 'Disposed'])->count();
            $sickPigs = \App\Models\Pig::whereNotIn('status', ['Sold', 'Disposed'])->where('health_status', 'Sick')->count();

            $totalDelivered = \App\Models\FeedDelivery::sum('quantity');
            $totalConsumed = \App\Models\FeedConsumption::sum('quantity');
            $availableStock = max($totalDelivered - $totalConsumed, 0);
            $recentTasks = \App\Models\Task::with('assignee')->latest()->limit(5)->get();

            // Fetch unacknowledged critical alerts from workers
            $criticalAlerts = \App\Models\PigActivity::with('pig')
                ->where('is_critical_alert', true)
                ->whereNull('acknowledged_at')
                ->latest()
                ->get();

            // --- DISEASE RISK PREDICTION (Smart Engine) ---
            $regionalDiseases = \App\Models\RegionalDisease::where('is_active', true)->get();
            
            // Calculate base regional risk factor
            $baseRegionalRisk = 0;
            foreach($regionalDiseases as $rd) {
                if ($rd->level == 'High') $baseRegionalRisk += 15;
                elseif ($rd->level == 'Medium') $baseRegionalRisk += 5;
            }
            
            $penRisks = [];
            $pens = \App\Models\Pen::withCount(['pigs as sick_count' => function($q) {
                $q->where('health_status', 'Sick')->whereNotIn('status', ['Sold', 'Disposed']);
            }])->get();

            foreach($pens as $pen) {
                $historicalSickness = \App\Models\PigActivity::where('type', 'Medical')
                    ->whereHas('pig', function($q) use ($pen) {
                        $q->where('pen_id', $pen->id);
                    })->count();

                $activeSickness = $pen->sick_count;
                $riskScore = min($baseRegionalRisk + ($activeSickness * 25) + ($historicalSickness * 5), 100);

                $status = 'Safe';
                $color = 'bg-green-100 text-green-700';
                $recommendation = 'Maintain standard biosecurity protocols.';

                if ($riskScore >= 75) {
                    $status = 'Critical Risk';
                    $color = 'bg-red-100 text-red-700';
                    $recommendation = 'Immediate isolation required. High probability of ASF or severe infection spread.';
                } elseif ($riskScore >= 40) {
                    $status = 'Elevated Risk';
                    $color = 'bg-yellow-100 text-yellow-700';
                    $recommendation = 'Increase sanitation frequency. Monitor closely for symptoms.';
                }

                if ($activeSickness > 0 || $riskScore > 15) {
                    $penRisks[] = (object)[
                        'pen_name' => $pen->name,
                        'risk_score' => $riskScore,
                        'active_cases' => $activeSickness,
                        'historical_cases' => $historicalSickness,
                        'status' => $status,
                        'color' => $color,
                        'recommendation' => $recommendation
                    ];
                }
            }
            
            usort($penRisks, function($a, $b) { return $b->risk_score <=> $a->risk_score; });

            return view('users.dashboard', compact(
                'pendingTasks', 'totalPigs', 'sickPigs', 'availableStock', 'recentTasks', 'criticalAlerts', 'regionalDiseases', 'penRisks'
            ));
        }
        )->name('admin.dashboard');

        Route::post('/pigs/{pig}/sell-dispose', [PigController::class, 'sellOrDispose'])->name('pigs.sellOrDispose');

        // Pens Management
        Route::get('/pens/index', [PenController::class, 'index'])->name('pens.index');
        Route::post('/pens/store', [PenController::class, 'store'])->name('pens.store');
        Route::put('/pens/{pen}', [PenController::class, 'update'])->name('pens.update');
        Route::delete('/pens/{pen}', [PenController::class, 'destroy'])->name('pens.destroy');
        Route::get('/pens/{pen}', [PenController::class, 'show'])->name('pens.show');
        Route::get('/api/pens/next-tag', [PenController::class, 'nextTag'])->name('pens.next-tag');

        // Individual Pig management for Admin
        Route::get('/admin/pigs/{pig}', [PigController::class, 'adminShow'])->name('admin.pigs.show');
        Route::post('/admin/pigs/store', [PigController::class, 'store'])->name('admin.pigs.store');
        Route::post('/admin/pigs/{pig}/move-pen', [PigController::class, 'movePen'])->name('admin.pigs.move-pen');
        Route::post('/admin/pigs/{pig}/update', [PigController::class, 'updateRecord'])->name('admin.pigs.update');
        Route::delete('/admin/pigs/{pig}', [PigController::class, 'destroy'])->name('admin.pigs.destroy');

        // User Management
        Route::get('users/index', [ProfileController::class, 'getAllUsers'])->name('users.index');
        Route::get('users/create', [ProfileController::class, 'create'])->name('users.create');
        Route::get('users/{id}/edit', [ProfileController::class, 'editUser'])->name('users.edit');
        Route::post('users/store', [ProfileController::class, 'store'])->name('users.store');
        Route::put('users/update/{id}', [ProfileController::class, 'updateUser'])->name('users.update');
        Route::delete('users/destroy/{id}', [ProfileController::class, 'destroyUser'])->name('users.destroy');

        // Feed & Inventory
        Route::get('/admin/feed-stock', [InventoryController::class, 'index'])->name('admin.feed-stock.index');
        Route::post('/admin/feed-stock', [InventoryController::class, 'store'])->name('admin.feed-stock.store');
        Route::get('/admin/qr-labels', [InventoryController::class, 'qrGenerator'])->name('admin.qr.index');


        Route::resource('admin/feed-mix', FeedMixController::class)->names('admin.feed-mix');
        Route::resource('admin/feed-ingredients', FeedIngredientController::class)->names('admin.feed-ingredients');

        // Admin Tasks & Reports
        Route::get('/admin/tasks', [TaskController::class, 'adminIndex'])->name('admin.tasks.index');
        Route::post('/admin/tasks', [TaskController::class, 'store'])->name('admin.tasks.store');
        Route::delete('/admin/tasks/{task}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');

        Route::get('/admin/weekly-reports', [ReportController::class, 'adminIndex'])->name('admin.reports');
        Route::get('/admin/weekly-reports/{id}', [ReportController::class, 'show'])->name('admin.reports.show');

        // Live Analytics
        Route::get('/admin/analytics', [AnalyticsController::class, 'index'])->name('admin.analytics');

        Route::post('/admin/pigs/activities/{activity}/acknowledge', [PigController::class, 'acknowledgeActivity'])->name('admin.pigs.activities.acknowledge');
        Route::get('/admin/api/unacknowledged-alerts', function() {
            return response()->json(\App\Models\PigActivity::unacknowledgedAlerts()->with('pig.pen')->latest()->get());
        })->name('admin.api.alerts');

        Route::post('/admin/disease-sync', [App\Http\Controllers\DiseaseSyncController::class, 'sync'])->name('admin.disease-sync');

        // System Settings
        Route::get('/admin/settings', [App\Http\Controllers\SystemSettingsController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings', [App\Http\Controllers\SystemSettingsController::class, 'update'])->name('admin.settings.update');
    });

// --- WORKER ZONE ---
Route::middleware(['auth', 'verified', 'role:farm_worker'])->group(function () {
    Route::get('/worker', function () {
        return redirect()->route('worker.dashboard');
    });
        Route::get('/worker/dashboard', [ReportController::class, 'dashboard'])->name('worker.dashboard');

        Route::get('/worker/tasks', [TaskController::class, 'workerIndex'])->name('worker.tasks');
        Route::post('/worker/tasks/{task}/complete', [TaskController::class, 'updateStatus'])->name('worker.tasks.complete');
        Route::post('/worker/tasks/{task}/progress', [TaskController::class, 'updateProgress'])->name('worker.tasks.progress');

        Route::get('/worker/alerts', function () {
            return view('worker.alerts');
        }
        )->name('worker.alerts');

        Route::get('/worker/activity-log', function () {
            return view('worker.activityLog');
        }
        )->name('worker.activity-log');

        Route::get('/worker/settings', function () { return view('worker.settings'); })->name('worker.settings');

        // Health & Monitoring API (Used by QR Scanner)
        Route::get('/api/health/pig/{tag}', [HealthController::class, 'getPigData'])->name('api.health.pig');
        Route::get('/api/health/pen/{id}', [HealthController::class, 'getPenData'])->name('api.health.pen');
        Route::post('/api/health/report', [HealthController::class, 'saveHealthReport'])->name('api.health.report');
        Route::post('/api/health/pen/log', [HealthController::class, 'savePenLog'])->name('api.health.pen.log');
        Route::get('/api/health/history/{pigId}', [HealthController::class, 'getPigHealthHistory'])->name('api.health.history');
        Route::post('/worker/settings/update', [ProfileController::class, 'updateWorkerSettings'])->name('worker.settings.update');

        Route::get('/worker/weekly-reports', [ReportController::class, 'workerIndex'])->name('worker.reports');
        Route::post('/worker/weekly-reports/store', [ReportController::class, 'store'])->name('worker.reports.store');

        // --- SWINE DETAILS ---
        Route::get('/worker/swine-details', function () {
            $user = auth()->user();
            $thisWeek = \Carbon\Carbon::now()->startOfWeek();

            // Get only pens assigned to this worker
            $pens = \App\Models\Pen::where('assigned_to', $user->id)
                ->with(['pigs' => function($q) {
                    $q->whereNotIn('status', ['Sold', 'Disposed'])
                        ->with(['tasks' => function($tq) {
                            $tq->where('status', '!=', 'completed');
                        }])
                        ->with(['activities' => function($aq) {
                            $aq->latest()->limit(1);
                        }]);
                }])->get();

            // Transform pigs for instant UI
            $pens->each(function($pen) {
                $pen->pigs->each(function($pig) use ($pen) {
                    $pig->last_check_human = $pig->activities->first() 
                        ? $pig->activities->first()->created_at->diffForHumans() 
                        : 'No Recent Check';
                    $pig->feed_formula_name = 'Standard Mix'; // Fallback since relation is missing
                    $pig->growth_stage_label = $pig->growth_stage;
                });
            });

            $assignedPenIds = $pens->pluck('id');
            
            // Fetch unique breeds from the system
            $breeds = \App\Models\Pig::distinct()->whereNotNull('breed')->pluck('breed');

            try {
                $existingReport = \App\Models\Task::where(function ($q) use ($user) {
                            $q->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
                        }
                        )->where('status', 'completed')->whereBetween('updated_at', [$thisWeek, \Carbon\Carbon::now()->endOfWeek()])->exists();
            }
            catch (\Exception $e) {
                $existingReport = false;
            }

            try {
                $activePigs = \App\Models\Pig::whereIn('pen_id', $assignedPenIds)->whereNotIn('status', ['Sold', 'Disposed']);
                $activePigsList = (clone $activePigs)->get();
                $totalActiveCount = $activePigsList->count();
                
                // Count pigs that have been updated today by this worker
                $checkedTodayCount = \App\Models\PigActivity::whereIn('pig_id', $activePigsList->pluck('id'))
                    ->where('user_id', $user->id)
                    ->whereDate('created_at', \Carbon\Carbon::today())
                    ->distinct('pig_id')
                    ->count();

                $analytics = [
                    'total_pigs' => $totalActiveCount ?? 0,
                    'checked_today' => $checkedTodayCount ?? 0,
                    'progress_percent' => $totalActiveCount > 0 ? round(($checkedTodayCount / $totalActiveCount) * 100) : 0,
                    'sick_pigs' => (clone $activePigs)->where('health_status', 'Sick')->count() ?? 0,
                    'avg_weight' => (clone $activePigs)->avg('weight') ?? 0,
                    'active_pens' => $pens->count() ?? 0,
                ];
            }
            catch (\Exception $e) {
                $analytics = ['total_pigs' => 0, 'checked_today' => 0, 'progress_percent' => 0, 'sick_pigs' => 0, 'avg_weight' => 0, 'active_pens' => 0];
            }

            try {
                $recentLogs = \App\Models\PigActivity::where('user_id', $user->id)
                    ->with('pig')
                    ->latest()
                    ->limit(10)
                    ->get();
            }
            catch (\Exception $e) {
                $recentLogs = collect();
            }

            return view('worker.swineDetails', compact('thisWeek', 'existingReport', 'analytics', 'pens', 'breeds', 'recentLogs'));
        })->name('worker.swineDetails');

        Route::get('/worker/pigs/{pig}', [PigController::class, 'show'])->name('worker.pigs.show');
        Route::post('/worker/pigs/{pig}/update', [PigController::class, 'updateRecord'])->name('worker.pigs.update');
        Route::post('/worker/pigs/{pig}/log-activity', [PigController::class, 'logActivity'])->name('worker.pigs.log-activity');

        Route::post('/worker/sync-logs', [App\Http\Controllers\Worker\SyncController::class, 'sync'])->name('worker.sync');
        Route::get('/worker/feed-formulas', [WorkerFeedFormulaController::class, 'index'])->name('worker.feed-formulas');
    });

// --- SHARED ZONE (All Auth Users) ---
Route::middleware('auth')->group(function () {
    Route::get('profile/edit', [ProfileController::class, 'editOwnProfile'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'updateOwnProfile'])->name('profile.update');

    // TEST ROUTE: Generate a critical alert for the first pig found
    Route::get('/admin/medical-emergency-test', function() {
        $pig = \App\Models\Pig::first();
        if(!$pig) return "No pigs found in database.";
        
        \App\Models\PigActivity::create([
            'pig_id' => $pig->id,
            'type' => 'Medical',
            'action' => '🚨 CRITICAL ALERT — Health In Danger',
            'details' => 'TEST ALERT: This is a simulated emergency for testing the notification system.',
            'is_critical_alert' => true,
            'created_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Critical alert generated for Pig #' . $pig->tag);
    })->name('admin.test-alert');
});

require __DIR__ . '/auth.php';