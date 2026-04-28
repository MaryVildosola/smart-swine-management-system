<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Pen;
use App\Models\Pig;
use App\Models\PigActivity;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Admin view to see all tasks and assignment form.
     */
    public function adminIndex()
    {
        $tasks = Task::with(['assignee', 'pen', 'pig'])->latest()->get();
        $workers = User::where('role', 'farm_worker')->get();
        $pens = Pen::all();
        $pigs = Pig::all();

        return view('admin.tasks.index', compact('tasks', 'workers', 'pens', 'pigs'));
    }

    /**
     * Admin method to store a new task.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'priority' => 'nullable|string|max:50',
            'pen_id' => 'nullable|exists:pens,id',
            'pig_id' => 'nullable|exists:pigs,id',
        ]);

        $validated['status'] = 'pending';

        $task = Task::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task assigned successfully.',
                'task' => $task
            ]);
        }

        return back()->with('success', 'Task assigned successfully.');
    }

    /**
     * Worker view to see their own tasks.
     */
    public function workerIndex()
    {
        $tasks = Task::with(['pen', 'pig'])
            ->where('assigned_to', Auth::id())
            ->where('status', '!=', 'completed')
            ->oldest() // First in, first out (oldest tasks at the top)
            ->get();

        $completedTasks = Task::with(['pen', 'pig'])
            ->where('assigned_to', Auth::id())
            ->where('status', 'completed')
            ->latest('completed_at')
            ->limit(5)
            ->get();

        return view('worker.task', compact('tasks', 'completedTasks'));
    }

    /**
     * Worker method to mark task as completed and sync to pig records.
     */
    public function updateStatus(Request $request, Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            abort(403);
        }

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // AUTOMATION: Log this as an activity for the relevant pigs
        $type = 'Care';
        $medicalKeywords = ['vaccine', 'medical', 'medicine', 'sick', 'deworm', 'treatment', 'clinic', 'checkup', 'vet'];
        foreach ($medicalKeywords as $word) {
            if (stripos($task->title, $word) !== false || stripos(($task->description ?? ''), $word) !== false) {
                $type = 'Medical';
                break;
            }
        }

        $pigsToLog = collect();

        // If specific pig, log for that pig
        if ($task->pig_id) {
            $pigsToLog->push(Pig::find($task->pig_id));
        } 
        // If whole pen, log for all pigs in pen
        elseif ($task->pen_id) {
            $penPigs = Pig::where('pen_id', $task->pen_id)->where('status', '!=', 'Sold')->where('status', '!=', 'Disposed')->get();
            $pigsToLog = $pigsToLog->concat($penPigs);
        }

        foreach ($pigsToLog as $pig) {
            if ($pig) {
                PigActivity::create([
                    'pig_id' => $pig->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'action' => 'Task Completed: ' . $task->title,
                    'details' => $task->description ?: 'Assigned task completed as part of regular duties.',
                ]);
            }
        }

        return back()->with('success', 'Task completed and animal records updated!');
    }

    /**
     * Worker method to update task progress.
     */
    public function updateProgress(Request $request, Task $task)
    {
        if ($task->assigned_to !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'findings' => 'nullable|array',
        ]);

        // Logic check: Urgent/High priority tasks MUST be completed, not just progress-updated to < 100
        $priority = strtolower($task->priority ?? '');
        $isPriority = in_array($priority, ['high', 'urgent', 'critical']);
        
        if ($isPriority && $validated['progress'] < 100) {
            return response()->json([
                'success' => false,
                'message' => 'This is a priority task and must be completed. You cannot save partial progress.'
            ], 422);
        }

        $task->update([
            'progress' => $validated['progress'],
            'findings' => $validated['findings'] ?? $task->findings,
        ]);

        if ($validated['progress'] == 100) {
            $task->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Sync to activities
            $this->syncTaskToActivities($task);
            
            return response()->json([
                'success' => true,
                'message' => 'Task completed successfully!',
                'completed' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Progress saved successfully.',
            'progress' => $task->progress
        ]);
    }

    /**
     * Helper to sync completed task to pig/pen activities.
     */
    private function syncTaskToActivities(Task $task)
    {
        $type = 'Care';
        $medicalKeywords = ['vaccine', 'medical', 'medicine', 'sick', 'deworm', 'treatment', 'clinic', 'checkup', 'vet'];
        foreach ($medicalKeywords as $word) {
            if (stripos($task->title, $word) !== false || stripos(($task->description ?? ''), $word) !== false) {
                $type = 'Medical';
                break;
            }
        }

        // Prepare findings text
        $findingsText = "";
        if ($task->findings) {
            $findingsText = "\n\nFindings:\n";
            foreach ($task->findings as $item) {
                if (!empty($item['finding'])) {
                    $findingsText .= "- " . $item['text'] . ": " . $item['finding'] . "\n";
                }
            }
        }

        $pigsToLog = collect();
        if ($task->pig_id) {
            $pigsToLog->push(Pig::find($task->pig_id));
        } elseif ($task->pen_id) {
            $penPigs = Pig::where('pen_id', $task->pen_id)
                ->whereNotIn('status', ['Sold', 'Disposed'])
                ->get();
            $pigsToLog = $pigsToLog->concat($penPigs);
        }

        foreach ($pigsToLog as $pig) {
            if ($pig) {
                PigActivity::create([
                    'pig_id' => $pig->id,
                    'user_id' => Auth::id(),
                    'type' => $type,
                    'action' => 'Task Completed: ' . $task->title,
                    'details' => ($task->description ?: 'Assigned task completed.') . $findingsText,
                ]);
            }
        }
    }

    public function destroy(Task $task)
    {
        if (!Auth::user()->role === 'admin') {
            abort(403);
        }
        $task->delete();
        return back()->with('success', 'Task deleted.');
    }
}
