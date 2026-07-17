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
            ->orderBy('display_order')
            ->orderBy('id')
            ->get();

        return view('admin.checklist-templates.index', compact('templates'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.checklist-templates.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dept_id' => ['nullable', 'exists:t_departments,id'],
            'item_text' => ['required', 'string', 'max:255'],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        ChecklistTemplate::create([
            'dept_id' => $validated['dept_id'] ?? null,
            'item_text' => $validated['item_text'],
            'display_order' => $validated['display_order'],
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.checklist-templates.index')
            ->with('status', 'Checklist template created successfully.');
    }

    public function edit(ChecklistTemplate $checklistTemplate): View
    {
        $departments = Department::orderBy('name')->get();

        return view('admin.checklist-templates.edit', compact('checklistTemplate', 'departments'));
    }

    public function update(Request $request, ChecklistTemplate $checklistTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'dept_id' => ['nullable', 'exists:t_departments,id'],
            'item_text' => ['required', 'string', 'max:255'],
            'display_order' => ['required', 'integer', 'min:0'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $checklistTemplate->update([
            'dept_id' => $validated['dept_id'] ?? null,
            'item_text' => $validated['item_text'],
            'display_order' => $validated['display_order'],
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.checklist-templates.index')
            ->with('status', 'Checklist template updated successfully.');
    }

    public function destroy(ChecklistTemplate $checklistTemplate): RedirectResponse
    {
        $checklistTemplate->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.checklist-templates.index')
            ->with('status', 'Checklist template deactivated successfully.');
    }
}
