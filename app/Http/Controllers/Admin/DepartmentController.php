<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount(['interns', 'checklistTemplates'])
            ->orderBy('name')
            ->get();

        return view('admin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('admin.departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'Department created.');
    }

    public function edit(Department $department): View
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $this->validated($request, $department);
        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('status', 'Department updated.');
    }

    public function toggleActive(Department $department): RedirectResponse
    {
        $department->update(['is_active' => ! $department->is_active]);

        return back()->with('status', $department->is_active
            ? 'Department reactivated.'
            : 'Department deactivated. Existing interns and checklist templates are unaffected.');
    }

    private function validated(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('t_departments', 'code')->ignore($department?->id),
            ],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
