<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Onboarding Checklist Templates</h2>
            <a href="{{ route('admin.checklist-templates.create') }}" class="btn btn-primary">Add Item</a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @foreach ($templates as $groupName => $items)
            <h5 class="mt-4">{{ $groupName }}</h5>
            <table class="table table-bordered align-middle">
                <thead>
                <tr>
                    <th>Order</th>
                    <th>Item</th>
                    <th>Required</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $template)
                    <tr class="{{ !$template->is_active ? 'text-muted' : '' }}">
                        <td>{{ $template->display_order }}</td>
                        <td>{{ $template->item_text }}</td>
                        <td>
                            @if ($template->is_required)
                                <span class="badge bg-danger">Required</span>
                            @else
                                <span class="badge bg-secondary">Optional</span>
                            @endif
                        </td>
                        <td>
                            @if ($template->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.checklist-templates.edit', $template) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.checklist-templates.toggle-active', $template) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    {{ $template->is_active ? 'Deactivate' : 'Reactivate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</x-app-layout>
