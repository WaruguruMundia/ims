<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Departments</h2>
            <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">Add Department</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Interns</th>
                <th>Checklist Templates</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($departments as $department)
                <tr class="{{ !$department->is_active ? 'text-muted' : '' }}">
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->code ?? '—' }}</td>
                    <td>{{ $department->interns_count }}</td>
                    <td>{{ $department->checklist_templates_count }}</td>
                    <td>
                        @if ($department->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.departments.toggle-active', $department) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                    @if ($department->is_active && $department->interns_count > 0)
                                        onclick="return confirm('This department has {{ $department->interns_count }} intern(s) assigned. Deactivate anyway?')"
                                @endif>
                                {{ $department->is_active ? 'Deactivate' : 'Reactivate' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
