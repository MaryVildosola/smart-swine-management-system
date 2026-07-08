@extends('layouts.worker')

@section('content')
<style>
    /* --- Light theme: high-contrast overrides for task page --- */
    body.light-theme .worker-dash { background: #f1f5f9 !important; }

    /* Text */
    body.light-theme .worker-dash .text-white { color: #0f172a !important; }
    body.light-theme .worker-dash .text-white\/30,
    body.light-theme .worker-dash .text-white\/40 { color: #94a3b8 !important; }
    body.light-theme .worker-dash .text-white\/50,
    body.light-theme .worker-dash .text-white\/60 { color: #64748b !important; }
    body.light-theme .worker-dash .text-white\/70,
    body.light-theme .worker-dash .text-white\/80 { color: #475569 !important; }

    /* Card backgrounds — solid white with visible borders & shadows */
    body.light-theme .worker-dash .bg-white\/5,
    body.light-theme .worker-dash .bg-white\/10,
    body.light-theme .worker-dash .bg-white\/15,
    body.light-theme .worker-dash .backdrop-blur-xl {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
    }

    /* Borders */
    body.light-theme .worker-dash .border-white\/10,
    body.light-theme .worker-dash .border-white\/5,
    body.light-theme .worker-dash .border-white\/20 {
        border-color: #e2e8f0 !important;
    }

    /* Hover states */
    body.light-theme .worker-dash .hover\:bg-white\/10:hover,
    body.light-theme .worker-dash .hover\:bg-white\/15:hover,
    body.light-theme .worker-dash .hover\:bg-white\/20:hover {
        background: #f1f5f9 !important;
    }

    /* Active filter button (green) */
    body.light-theme .worker-dash .bg-green-500\/20 {
        background: rgba(34, 197, 94, 0.15) !important;
    }
    body.light-theme .worker-dash .text-green-300 {
        color: #15803d !important;
    }
    body.light-theme .worker-dash .border-green-500\/30 {
        border-color: rgba(34, 197, 94, 0.35) !important;
    }

    /* Amber badges */
    body.light-theme .worker-dash .bg-amber-500\/20 {
        background: #fef3c7 !important;
    }
    body.light-theme .worker-dash .text-amber-300 {
        color: #b45309 !important;
    }
    body.light-theme .worker-dash .border-amber-500\/30 {
        border-color: #fcd34d !important;
    }

    /* Emerald text */
    body.light-theme .worker-dash .text-emerald-400 {
        color: #059669 !important;
    }
    body.light-theme .worker-dash .text-emerald-400\/60 {
        color: #10b981 !important;
    }

    /* Completed task border */
    body.light-theme .worker-dash .border-green-500 {
        border-color: #22c55e !important;
    }

    /* Start Task button in cards */
    body.light-theme .worker-dash .bg-white\/5.rounded-xl {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
</style>
<div class="worker-dash min-h-screen">
    <div class="p-6 md:p-12 max-w-full">

        <!-- Header Section -->
        <div class="mb-8 md:mb-10 flex justify-between items-center w-full">
            <div>
                <p class="text-sm md:text-base font-medium text-white/70 mb-1 md:mb-2">Your Daily Goals</p>
                <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Farm Tasks</h1>
            </div>
        </div>

        <!-- Task Summary -->
        <div class="flex flex-row gap-4 mb-8 overflow-x-auto pb-2 no-scrollbar">
            <div class="backdrop-blur-xl min-w-[140px] rounded-2xl p-4 shadow-sm border border-white/10 bg-white/5">
                <p class="text-[9px] font-bold text-white/40 mb-1 uppercase tracking-[0.2em]">Pending</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-black text-white leading-none">{{ $tasks->count() }}</h3>
                    <span class="text-[9px] text-white/40 mb-1 font-bold">Tasks</span>
                </div>
            </div>
            <div class="backdrop-blur-xl min-w-[140px] rounded-2xl p-4 shadow-sm border border-white/10 bg-white/5">
                <p class="text-[9px] font-bold text-white/40 mb-1 uppercase tracking-[0.2em]">Recently</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-black text-emerald-400 leading-none">{{ $completedTasks->count() }}</h3>
                    <span class="text-[9px] text-emerald-400/60 mb-1 font-bold">Done</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 md:gap-4 mb-8 h-full">
            <button onclick="filterTasks('All')"
                class="px-4 md:px-6 py-2 md:py-3 bg-green-500/20 text-green-300 border border-green-500/30 rounded-xl font-medium hover:bg-green-500/30 transition text-xs md:text-sm shadow-md">
                All Tasks
            </button>
            <button onclick="filterTasks('Today')"
                class="px-4 md:px-6 py-2 md:py-3 bg-white/10 text-white/70 border border-white/20 rounded-xl font-medium hover:bg-white/20 transition text-xs md:text-sm">
                Today
            </button>
            <button onclick="filterTasks('Overdue')"
                class="px-4 md:px-6 py-2 md:py-3 bg-white/10 text-white/70 border border-white/20 rounded-xl font-medium hover:bg-white/20 transition text-xs md:text-sm">
                Overdue
            </button>
            <button onclick="filterTasks('Completed')"
                class="px-4 md:px-6 py-2 md:py-3 bg-white/10 text-white/70 border border-white/20 rounded-xl font-medium hover:bg-white/20 transition text-xs md:text-sm">
                Completed
            </button>
        </div>

        <!-- Task List (Changed from Grid to List) -->
        <div class="flex flex-col gap-4 mb-8">
            @forelse($tasks as $task)
                @php
                    $priority = strtolower($task->priority ?? 'medium');
                    $priorityClass = match($priority) {
                        'high', 'urgent', 'critical' => 'bg-red-500/20 text-red-300 border-red-500/30',
                        'medium' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                        default => 'bg-blue-500/20 text-blue-300 border-blue-500/30'
                    };
                @endphp
                <div onclick="openTaskDetail('{{ addslashes($task->title) }}', '{{ addslashes($task->description) }}', '{{ route('worker.tasks.complete', $task) }}', '{{ route('worker.tasks.progress', $task) }}', '{{ $task->priority }}', {{ $task->progress ?? 0 }}, {{ json_encode($task->findings ?? []) }})" 
                     class="backdrop-blur-xl bg-white/5 rounded-2xl p-5 hover:bg-white/10 transition cursor-pointer group shadow-sm border border-white/10 active:scale-[0.98]">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 {{ $priorityClass }} rounded text-[7px] font-black uppercase tracking-tighter border">{{ $task->priority ?? 'Medium' }}</span>
                                <h3 class="text-lg font-bold text-white">{{ $task->title }}</h3>
                            </div>
                            <p class="text-[10px] text-white/50 leading-relaxed">{{ Str::limit($task->description, 50) }}</p>
                        </div>
                        <div class="px-2 py-1 bg-amber-500/20 text-amber-300 rounded-lg text-[8px] font-bold border border-amber-500/30 uppercase tracking-widest">{{ $task->progress ?? 0 }}%</div>
                    </div>

                    <!-- Small progress bar on card -->
                    <div class="w-full h-1 bg-white/10 rounded-full overflow-hidden mb-4 border border-white/5">
                        <div class="h-full bg-emerald-500 transition-all duration-500" style="width: {{ $task->progress ?? 0 }}%"></div>
                    </div>

                    <div class="flex items-center gap-4 mb-5">
                        <div class="flex items-center gap-2 text-white/50 text-[10px] font-medium">
                            <i class='bx bx-calendar text-emerald-400'></i>
                            <span>{{ $task->due_date->format('M d') }}</span>
                        </div>
                        @if($task->pen_id)
                        <div class="flex items-center gap-2 text-white/50 text-[10px] font-medium">
                            <i class='bx bx-map-pin text-emerald-400'></i>
                            <span>{{ $task->pen->name }}</span>
                        </div>
                        @endif
                    </div>

                    <button class="w-full py-3 bg-white/5 text-white/70 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-white/10 transition border border-white/10">
                        {{ ($task->progress ?? 0) > 0 ? 'Continue Task' : 'Start Task' }}
                    </button>
                </div>
            @empty
                <div class="col-span-2 py-12 text-center">
                    <i class='bx bx-check-double text-5xl text-white/10 mb-4'></i>
                    <p class="text-white/40 text-sm">All caught up! No tasks assigned.</p>
                </div>
            @endforelse
        </div>

        @if($completedTasks->count() > 0)
        <h2 class="text-xs font-black text-white/40 uppercase tracking-[0.2em] mb-4">Recently Completed</h2>
        <div class="space-y-3 mb-8">
            @foreach($completedTasks as $completed)
            <div onclick="viewTaskHistory('{{ addslashes($completed->title) }}', '{{ $completed->completed_at->diffForHumans() }}', '{{ addslashes($completed->description) }}', '{{ json_encode($completed->findings) }}')"
                 class="backdrop-blur-xl bg-white/5 rounded-3xl p-5 md:p-6 border-l-4 border-green-500 hover:bg-white/10 transition cursor-pointer shadow-lg group">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10">
                                <i class='bx bxs-check-circle text-green-400 text-lg'></i>
                            </div>
                            <div>
                                <h4 class="text-base md:text-lg font-bold text-white">{{ $completed->title }}</h4>
                                <p class="text-[10px] md:text-sm text-white/40 uppercase tracking-widest">Done {{ $completed->completed_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-white/5 text-white/40 rounded-full text-[8px] font-bold border border-white/10 uppercase tracking-widest group-hover:text-green-400 group-hover:border-green-400/30 transition-all">View Details</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    <!-- Create Task Modal -->
    <div id="taskModal" class="fixed inset-0 z-[200] hidden bg-slate-900/95 backdrop-blur-3xl flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white dark:bg-[#0b1120] w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl border border-slate-200 dark:border-white/10 animate-fade-in my-auto">
            <div class="p-8 pb-6 border-b border-slate-100 dark:border-white/10 relative">
                <div class="flex justify-between items-start mb-4">
                    <div class="px-3 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 rounded-full text-[10px] font-bold border border-green-200 dark:border-green-500/30 uppercase tracking-widest">New Entry</div>
                    <button onclick="hideTaskModal()" class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-white/5 text-slate-400 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-white/10 transition">
                        <i class='bx bx-x text-3xl'></i>
                    </button>
                </div>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Create New Task</h2>
                <p class="text-slate-500 text-sm mt-2 leading-relaxed font-medium">Worker Log Entry</p>
            </div>
            <div class="p-8 pt-6 space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Task Description</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"><i class='bx bx-task text-xl'></i></span>
                        <input type="text" id="taskTitle" placeholder="e.g. Regular Health Check" 
                               class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl py-5 pl-14 pr-6 text-slate-900 dark:text-white focus:outline-none focus:border-green-500 transition font-medium text-base">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Location / Pen</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"><i class='bx bx-map-pin text-xl'></i></span>
                        <select id="taskPen" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-2xl py-5 pl-14 pr-6 text-slate-900 dark:text-white focus:outline-none focus:border-green-500 transition appearance-none font-medium text-base">
                            <option value="Pen 1">Pen 1 (Piglets)</option>
                            <option value="Pen 5">Pen 5 (Fattening)</option>
                            <option value="Pen 12">Pen 12 (Breeding)</option>
                        </select>
                        <i class='bx bx-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400'></i>
                    </div>
                </div>
                <button onclick="submitTask()" class="w-full bg-green-600 text-white py-5 rounded-[2rem] font-black text-lg hover:shadow-[0_10px_30px_rgba(34,197,94,0.3)] transition active:scale-[0.98]">Confirm & Create</button>
            </div>
        </div>
    </div>

    <!-- Task Detail & Checklist Modal (Floating Popup) -->
    <div id="taskDetailModal" class="fixed inset-0 z-[210] hidden items-center justify-center p-4 overflow-y-auto" style="background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);" onclick="if(event.target === this) hideTaskDetail();">
        <div class="bg-white dark:bg-[#0b1120] w-full max-w-md rounded-3xl overflow-hidden border border-slate-200 dark:border-white/10 animate-fade-in my-auto" style="box-shadow: 0 25px 60px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.05);">
            <!-- Modal Header -->
            <div class="p-6 pb-4 border-b border-slate-100 dark:border-white/10 relative">
                <div class="flex justify-between items-start mb-3">
                    <div id="taskStatusBadge" class="px-3 py-1 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 rounded-full text-[10px] font-bold border border-amber-200 dark:border-amber-500/30 uppercase tracking-widest">Pending</div>
                    <button onclick="hideTaskDetail()" class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 text-slate-400 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-white/10 transition">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                <h2 id="detailTaskTitle" class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Feed Pen 1</h2>
                <p id="detailTaskDesc" class="text-slate-500 text-sm mt-1 leading-relaxed font-medium">Morning feeding routine for the piglets in Pen 1. Ensure they get the starter mix.</p>
            </div>

            <!-- Modal Content -->
            <div class="p-6 pt-5 space-y-6">
                <!-- Progress Section -->
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Completion Progress</label>
                        <span id="progressPercent" class="text-lg font-black text-green-600">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 dark:bg-[#141e36] rounded-full overflow-hidden border border-slate-200 dark:border-white/10">
                        <div id="progressBar" class="h-full bg-green-500 transition-all duration-500 shadow-[0_0_10px_rgba(34,197,94,0.3)]" style="width: 0%"></div>
                    </div>
                </div>

                <!-- Checklist Section -->
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Required Steps & Findings</label>
                    <div id="checklistItems" class="space-y-4">
                        <!-- Dynamic items here -->
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-2 pb-1 flex flex-col gap-3">
                    <button id="completeTaskBtn" onclick="markTaskComplete()" disabled
                            class="w-full flex items-center justify-center gap-2 bg-slate-100 dark:bg-white/5 text-slate-300 dark:text-slate-500 py-4 rounded-2xl font-black text-base border border-slate-200 dark:border-white/10 transition-all duration-300 cursor-not-allowed">
                        <i class='bx bx-check-circle text-xl'></i> Complete Task
                    </button>
                    
                    <button id="recordProgressBtn" onclick="recordProgress()"
                            class="w-full flex items-center justify-center gap-1.5 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white dark:bg-[#141e36] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                        <i class='bx bx-save text-lg'></i> Record Progress
                    </button>

                    <p id="priorityWarning" class="text-[9px] text-red-500 font-bold text-center hidden uppercase tracking-tighter">
                        <i class='bx bx-error-circle mr-1'></i> Priority Task: Must be completed to save progress
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Task History View Modal -->
    <div id="taskHistoryModal" class="fixed inset-0 z-[220] hidden items-center justify-center p-4 overflow-y-auto" style="background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px);" onclick="if(event.target === this) hideTaskHistory();">
        <div class="bg-white dark:bg-[#0b1120] w-full max-w-md rounded-[2.5rem] overflow-hidden border border-slate-200 dark:border-white/10 animate-fade-in my-auto shadow-2xl">
            <div class="p-8 pb-4 border-b border-slate-100 dark:border-white/10 relative bg-slate-50/50 dark:bg-[#141e36]">
                <div class="flex justify-between items-start mb-3">
                    <div class="px-3 py-1 bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 rounded-full text-[10px] font-bold border border-green-200 dark:border-green-500/30 uppercase tracking-widest">Completed Task</div>
                    <button onclick="hideTaskHistory()" class="w-10 h-10 rounded-xl bg-white dark:bg-white/5 text-slate-400 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-white/10 transition shadow-sm border border-slate-100 dark:border-white/10">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                <h2 id="historyTaskTitle" class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Task Name</h2>
                <p id="historyTaskDate" class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Completed 2 hours ago</p>
            </div>
            <div class="p-8 pt-6 space-y-6">
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Description</label>
                    <p id="historyTaskDesc" class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed font-medium">No description provided.</p>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Task Findings & Results</label>
                    <div id="historyFindings" class="space-y-3">
                        <!-- Findings list here -->
                    </div>
                </div>
                <button onclick="hideTaskHistory()" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-sm transition-all hover:bg-slate-800">Close History</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- Task Detail Logic ---
        let currentCompletionUrl = '';
        let currentProgressUrl = '';
        let currentPriority = 'Medium';
        let currentProgress = 0;
        let taskItems = [];

        function openTaskDetail(title, desc, completionUrl, progressUrl, priority, progress, existingFindings) {
            document.getElementById('detailTaskTitle').innerText = title;
            document.getElementById('detailTaskDesc').innerText = desc;
            currentCompletionUrl = completionUrl;
            currentProgressUrl = progressUrl;
            currentPriority = priority || 'Medium';
            currentProgress = progress || 0;

            const findings = existingFindings || [];

            // Generate checklist - using persisted findings if they exist
            taskItems = [
                { id: 1, text: 'Initial check of animals/location', completed: currentProgress >= 33, finding: findings[0]?.finding || '' },
                { id: 2, text: 'Execute main task protocols', completed: currentProgress >= 66, finding: findings[1]?.finding || '' },
                { id: 3, text: 'Final verification and cleanup', completed: currentProgress >= 100, finding: findings[2]?.finding || '' }
            ];

            renderChecklist();
            updateUI();
            
            document.getElementById('taskDetailModal').classList.remove('hidden');
            document.getElementById('taskDetailModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function hideTaskDetail() {
            document.getElementById('taskDetailModal').classList.add('hidden');
            document.getElementById('taskDetailModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function renderChecklist() {
            const container = document.getElementById('checklistItems');
            container.innerHTML = '';
            
            taskItems.forEach((item, index) => {
                const div = document.createElement('div');
                div.className = `p-4 rounded-2xl border transition-all ${item.completed ? 'bg-green-50/50 dark:bg-green-500/10 border-green-100 dark:border-green-500/20' : 'bg-slate-50 dark:bg-white/5 border-slate-100 dark:border-white/10'}`;
                
                div.innerHTML = `
                    <div class="flex items-center gap-3 mb-3 cursor-pointer" onclick="toggleItem(${item.id})">
                        <div class="w-6 h-6 rounded-lg flex items-center justify-center transition-all ${item.completed ? 'bg-green-500 text-white' : 'bg-white dark:bg-black/20 border border-slate-200 dark:border-white/10'}">
                            <i class='bx ${item.completed ? 'bx-check' : ''} text-lg'></i>
                        </div>
                        <span class="text-sm font-bold ${item.completed ? 'text-green-800 dark:text-green-400' : 'text-slate-600 dark:text-slate-300'}">${item.text}</span>
                    </div>
                    <div class="pl-9">
                        <input type="text" placeholder="Add finding / observation..." 
                               onchange="updateFinding(${item.id}, this.value)"
                               value="${item.finding || ''}"
                               class="w-full bg-white/50 dark:bg-black/20 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 text-xs focus:outline-none focus:border-green-500 transition font-medium text-slate-900 dark:text-white">
                    </div>
                `;
                container.appendChild(div);
            });
        }

        function updateFinding(id, val) {
            const item = taskItems.find(i => i.id === id);
            if(item) item.finding = val;
        }

        function toggleItem(id) {
            const item = taskItems.find(i => i.id === id);
            item.completed = !item.completed;
            
            // Calculate new progress
            const completedCount = taskItems.filter(i => i.completed).length;
            currentProgress = Math.round((completedCount / taskItems.length) * 100);
            
            renderChecklist();
            updateUI();
        }

        function updateUI() {
            document.getElementById('progressPercent').innerText = currentProgress + '%';
            document.getElementById('progressBar').style.width = currentProgress + '%';
            
            const isPriority = ['high', 'urgent', 'critical'].includes(currentPriority.toLowerCase());
            const completeBtn = document.getElementById('completeTaskBtn');
            const recordBtn = document.getElementById('recordProgressBtn');
            const warning = document.getElementById('priorityWarning');

            if (currentProgress === 100) {
                completeBtn.disabled = false;
                completeBtn.className = "w-full flex items-center justify-center gap-2 bg-green-600 text-white py-4 rounded-2xl font-black text-base shadow-[0_10px_30px_rgba(34,197,94,0.3)] transition-all duration-300";
            } else {
                completeBtn.disabled = true;
                completeBtn.className = "w-full flex items-center justify-center gap-2 bg-slate-100 dark:bg-white/5 text-slate-300 dark:text-slate-500 py-4 rounded-2xl font-black text-base border border-slate-200 dark:border-white/10 transition-all duration-300 cursor-not-allowed";
            }

            if (isPriority) {
                warning.classList.remove('hidden');
                recordBtn.classList.add('hidden');
            } else {
                warning.classList.add('hidden');
                recordBtn.classList.remove('hidden');
                recordBtn.className = "w-full flex items-center justify-center gap-1.5 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest bg-white dark:bg-[#141e36] text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300";
            }
        }

        async function recordProgress() {
             const result = await Swal.fire({
                title: 'Record Progress?',
                text: 'Your progress and findings will be saved.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes, Save it',
                confirmButtonColor: '#22c55e',
                background: '#ffffff',
                color: '#1e293b'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(currentProgressUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            progress: currentProgress,
                            findings: taskItems 
                        })
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire({ title: 'Saved!', text: 'Your progress has been recorded.', icon: 'success', timer: 1500, showConfirmButton: false });
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire('Error', data.message || 'Failed to save progress', 'error');
                    }
                } catch (e) {
                    Swal.fire('Error', 'Network error', 'error');
                }
            }
        }

        function markTaskComplete() {
            if(!currentCompletionUrl) return;

            Swal.fire({
                title: 'Complete Task?',
                text: 'This will record your findings and mark the task as finished.',
                icon: 'question',
                showCancelButton: true,
                background: '#ffffff',
                color: '#1e293b',
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'Yes, Complete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(currentProgressUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                progress: 100,
                                findings: taskItems
                            })
                        });
                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({ title: 'Completed!', text: 'Findings recorded in farm logs.', icon: 'success', timer: 1500, showConfirmButton: false });
                            setTimeout(() => location.reload(), 1500);
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Failed to complete task', 'error');
                    }
                }
            });
        }

        // --- Task History Logic ---
        function viewTaskHistory(title, date, desc, findingsJson) {
            document.getElementById('historyTaskTitle').innerText = title;
            document.getElementById('historyTaskDate').innerText = 'Completed ' + date;
            document.getElementById('historyTaskDesc').innerText = desc || 'No description provided.';
            
            const findingsContainer = document.getElementById('historyFindings');
            findingsContainer.innerHTML = '';
            
            try {
                const findings = JSON.parse(findingsJson);
                if (findings && findings.length > 0) {
                    findings.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'p-4 rounded-2xl bg-slate-50 dark:bg-[#141e36] border border-slate-100 dark:border-white/10';
                        div.innerHTML = `
                            <div class="flex items-center gap-2 mb-2">
                                <i class='bx bx-check-circle text-green-500'></i>
                                <span class="text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-tighter">${item.text}</span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400 italic font-medium">"${item.finding || 'No findings recorded.'}"</p>
                        `;
                        findingsContainer.appendChild(div);
                    });
                } else {
                    findingsContainer.innerHTML = '<p class="text-slate-400 text-xs italic">No detailed findings recorded.</p>';
                }
            } catch(e) {
                findingsContainer.innerHTML = '<p class="text-slate-400 text-xs italic">No findings history found.</p>';
            }

            document.getElementById('taskHistoryModal').classList.remove('hidden');
            document.getElementById('taskHistoryModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function hideTaskHistory() {
            document.getElementById('taskHistoryModal').classList.add('hidden');
            document.getElementById('taskHistoryModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function showNotifications() {
            Swal.fire({
                title: 'Notifications',
                html: '<div class="text-left space-y-4"><div class="p-3 bg-slate-50 rounded-xl border border-slate-200"><p class="text-xs font-bold text-green-600">BATCH UPDATE</p><p class="text-sm text-slate-700">Batch #22 has been moved to Pen 5.</p></div><div class="p-3 bg-slate-50 rounded-xl border border-slate-200"><p class="text-xs font-bold text-red-600">ALERT</p><p class="text-sm text-slate-700">Temperature spike in Pen 3.</p></div></div>',
                icon: 'info',
                background: '#ffffff',
                color: '#1e293b',
                confirmButtonColor: '#22c55e'
            });
        }

        function showSearch() {
            Swal.fire({
                title: 'Search Focus',
                input: 'text',
                inputPlaceholder: 'Search tasks, pens, or animals...',
                background: '#ffffff',
                color: '#1e293b',
                confirmButtonColor: '#22c55e',
                customClass: {
                    input: 'bg-slate-50 border-slate-200 text-slate-900 border rounded-xl'
                }
            });
        }

        function filterTasks(type) {
             Swal.fire({ title: 'Filter Applied', text: 'Showing ' + type + ' tasks.', icon: 'info', timer: 1000, showConfirmButton: false, background: '#ffffff', color: '#1e293b' });
        }

        function actionTask(name, action) {
            Swal.fire({ title: 'Task Update', text: 'Task "' + name + '" ' + action + '.', icon: 'success', background: '#ffffff', color: '#1e293b', confirmButtonColor: '#22c55e' });
        }

        // --- Page specific theme listener ---
        window.applyPageTheme = function(theme) {
            const dash = document.querySelector('.worker-dash');
            if(theme === 'light') {
                dash.classList.add('light-mode');
            } else {
                dash.classList.remove('light-mode');
            }
        };

        // Sync Logic
        function updateSyncStatus() {
            const statusEl = document.getElementById('syncStatus');
            if (navigator.onLine) {
                statusEl.innerHTML = "<i class='bx bx-check-double mr-1'></i> Synchronized";
                statusEl.className = "text-[10px] md:text-xs transition-all p-1 px-3 rounded-full inline-flex items-center bg-green-500/20 text-green-300 border border-green-500/30";
            } else {
                statusEl.innerHTML = "<i class='bx bx-cloud-off mr-1'></i> Offline Mode";
                statusEl.className = "text-[10px] md:text-xs transition-all p-1 px-3 rounded-full inline-flex items-center bg-yellow-500/20 text-yellow-300 border border-yellow-500/30";
            }
        }
        window.addEventListener('online', updateSyncStatus);
        window.addEventListener('offline', updateSyncStatus);
        updateSyncStatus();
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-spin-slow {
            animation: spin 3s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
