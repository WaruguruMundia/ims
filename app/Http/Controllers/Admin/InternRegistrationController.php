<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Intern;
use App\Models\Role;
use App\Models\User;
use App\Services\OnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InternRegistrationController extends Controller
{
    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();        $supervisorRoleId = Role::where('slug', 'supervisor')->value('id');
        $supervisors = User::where('role_id', $supervisorRoleId)->orderBy('name')->get();

        return view('admin.interns.create', compact('departments', 'supervisors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $supervisorRoleId = Role::where('slug', 'supervisor')->value('id');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:t_users,email'],
            'dept_id' => ['required', 'exists:t_departments,id'],
            'supervisor_id' => [
                'required',
                Rule::exists('t_users', 'id')->where('role_id', $supervisorRoleId),
            ],
            'institution' => ['required', 'string', 'max:255'],
            'programme' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        $intern = DB::transaction(function () use ($validated) {
            $internRole = Role::where('slug', 'intern')->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(40)), // placeholder — never sent to anyone
                'role_id' => $internRole->id,
            ]);

            $intern = Intern::create([
                'user_id' => $user->id,
                'dept_id' => $validated['dept_id'],
                'supervisor_id' => $validated['supervisor_id'],
                'institution' => $validated['institution'],
                'programme' => $validated['programme'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);

            app(OnboardingService::class)->initializeChecklist($intern);

            return $intern;
        });

        Password::sendResetLink(['email' => $intern->user->email]);

        return redirect()->route('admin.dashboard')
            ->with('status', "Intern registered. Account setup email sent to {$intern->user->email}.");
    }

    public function edit(Intern $intern): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $supervisorRoleId = Role::where('slug', 'supervisor')->value('id');
        $supervisors = User::where('role_id', $supervisorRoleId)->orderBy('name')->get();

        return view('admin.interns.edit', compact('intern', 'departments', 'supervisors'));
    }

    public function update(Request $request, Intern $intern): RedirectResponse
    {
        $supervisorRoleId = Role::where('slug', 'supervisor')->value('id');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('t_users', 'email')->ignore($intern->user_id)],
            'dept_id' => ['required', 'exists:t_departments,id'],
            'supervisor_id' => [
                'required',
                Rule::exists('t_users', 'id')->where('role_id', $supervisorRoleId),
            ],
            'institution' => ['required', 'string', 'max:255'],
            'programme' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        DB::transaction(function () use ($validated, $intern) {
            $intern->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $intern->update([
                'dept_id' => $validated['dept_id'],
                'supervisor_id' => $validated['supervisor_id'],
                'institution' => $validated['institution'],
                'programme' => $validated['programme'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);
        });

        return redirect()->route('admin.dashboard')
            ->with('status', "Intern details updated successfully.");
    }

    public function destroy(Intern $intern): RedirectResponse
    {
        DB::transaction(function () use ($intern) {
            $intern->user->delete();
        });

        return redirect()->route('admin.dashboard')
            ->with('status', "Intern deleted successfully.");
    }
}
