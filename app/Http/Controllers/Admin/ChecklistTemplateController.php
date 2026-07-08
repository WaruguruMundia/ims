<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChecklistTemplateController extends Controller
{
    public function index(): View
    {
        $templates = ChecklistTemplate::with('department')
            ->orderByRaw('dept_id IS NULL DESC') // global items first
            ->orderBy('dept_id')
            ->orderBy('display_order')
            ->get()
            ->groupBy(fn ($t) => $t->department?->name ?? 'Global (all departments)');

        return view('admin.checklist-templates.index', compact('templates'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.checklist-templates.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        ChecklistTemplate::create($validated);

        return redirect()->route('admin.checklist-templates.index')
            ->with('status', 'Checklist template created.');
    }

    public function edit(ChecklistTemplate $checklistTemplate): View
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.checklist-templates.edit', compact('checklistTemplate', 'departments'));
    }

    public function update(Request $request, ChecklistTemplate $checklistTemplate): RedirectResponse
    {
        $validated = $this->validated($request);
        $checklistTemplate->update($validated);

        return redirect()->route('admin.checklist-templates.index')
            ->with('status', 'Checklist template updated.');
    }

    public function toggleActive(ChecklistTemplate $checklistTemplate): RedirectResponse
    {
        $checklistTemplate->update(['is_active' => ! $checklistTemplate->is_active]);

        return back()->with('status', $checklistTemplate->is_active
            ? 'Template reactivated.'
            : 'Template deactivated. Existing intern checklists are unaffected.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'dept_id' => ['nullable', 'exists:t_departments,id'],
            'item_text' => ['required', 'string', 'max:255'],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_required' => ['required', 'boolean'],
        ]);
    }
}
