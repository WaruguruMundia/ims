<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks assigned by the supervisor.
     */
    public function index(): View
    {
        $internIds = Intern::where('supervisor_id', Auth::id())->pluck('id');
        $tasks = Task::whereIn('intern_id', $internIds)
            ->with(['intern.user', 'creator'])
            ->latest()
            ->get();

        return view('supervisor.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request): View
    {
        $interns = Intern::where('supervisor_id', Auth::id())->with('user')->get();
        $selectedInternId = $request->query('intern_id');

        return view('supervisor.tasks.create', compact('interns', 'selectedInternId'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): RedirectResponse
    {
        $internIds = Intern::where('supervisor_id', Auth::id())->pluck('id')->toArray();

        $validated = $request->validate([
            'intern_id' => ['required', Rule::in($internIds)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'deliverable_notes' => ['nullable', 'string'],
        ]);

        Task::create([
            'intern_id' => $validated['intern_id'],
            'created_by' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'status' => 'pending',
            'due_date' => $validated['due_date'],
            'deliverable_notes' => $validated['deliverable_notes'] ?? null,
        ]);

        // Note: TaskAssigned notification can be fired here in reporting phase

        return redirect()->route('supervisor.dashboard')
            ->with('status', 'Task assigned to intern successfully.');
    }

    /**
     * Show the form for editing/reviewing a task.
     */
    public function show(Task $task): View
    {
        $this->authorizeSupervisorForTask($task);

        return view('supervisor.tasks.show', compact('task'));
    }

    /**
     * Update the task details (before submission).
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeSupervisorForTask($task);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'deliverable_notes' => ['nullable', 'string'],
        ]);

        $task->update($validated);

        return redirect()->route('supervisor.tasks.index')
            ->with('status', 'Task details updated successfully.');
    }

    /**
     * Review the submitted task (approve or reject).
     */
    public function review(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeSupervisorForTask($task);

        $validated = $request->validate([
            'action' => ['required', 'in:approve,reject'],
            'reviewer_feedback' => ['nullable', 'string'],
        ]);

        if ($validated['action'] === 'approve') {
            $task->update([
                'status' => 'approved',
                'reviewer_feedback' => $validated['reviewer_feedback'] ?? null,
                'reviewed_at' => now(),
            ]);
            $message = 'Task submission approved.';
        } else {
            $task->update([
                'status' => 'rejected',
                'reviewer_feedback' => $validated['reviewer_feedback'] ?? null,
                'reviewed_at' => now(),
            ]);
            $message = 'Task submission returned/rejected with feedback.';
        }

        // Note: TaskStatusUpdated notification can be fired here in reporting phase

        return redirect()->route('supervisor.dashboard')
            ->with('status', $message);
    }

    /**
     * Authorize that the authenticated supervisor is assigned to this task's intern.
     */
    protected function authorizeSupervisorForTask(Task $task): void
    {
        if ($task->intern->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
