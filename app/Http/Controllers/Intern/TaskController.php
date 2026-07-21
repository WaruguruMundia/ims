<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a list of tasks assigned to the authenticated intern.
     */
    public function index(): View
    {
        $intern = Intern::where('user_id', Auth::id())->firstOrFail();
        $tasks = Task::where('intern_id', $intern->id)
            ->with('creator')
            ->latest()
            ->get();

        return view('intern.tasks.index', compact('tasks', 'intern'));
    }

    /**
     * Display a specific task.
     */
    public function show(Task $task): View
    {
        $this->authorizeInternForTask($task);

        return view('intern.tasks.show', compact('task'));
    }

    /**
     * Update status (e.g. mark as in_progress or submit deliverables).
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorizeInternForTask($task);

        $request->validate([
            'status' => ['required', 'in:in_progress,submitted'],
            'submission_notes' => ['required_if:status,submitted', 'nullable', 'string'],
        ]);

        if ($request->status === 'in_progress') {
            if ($task->status === 'pending') {
                $task->update([
                    'status' => 'in_progress',
                ]);
                $message = 'Task marked as in progress.';
            } else {
                $message = 'Task is already in progress or has been submitted.';
            }
        } else {
            // submitted
            if (in_array($task->status, ['pending', 'in_progress', 'rejected'])) {
                $task->update([
                    'status' => 'submitted',
                    'submission_notes' => $request->submission_notes,
                    'submitted_at' => now(),
                ]);
                $message = 'Task deliverables submitted to supervisor for review.';
            } else {
                $message = 'Task cannot be submitted in its current state.';
            }
        }

        return redirect()->route('intern.dashboard')->with('status', $message);
    }

    /**
     * Authorize that the authenticated intern is assigned to this task.
     */
    protected function authorizeInternForTask(Task $task): void
    {
        $intern = Intern::where('user_id', Auth::id())->firstOrFail();
        if ($task->intern_id !== $intern->id) {
            abort(403, 'Unauthorized action.');
        }
    }
}
