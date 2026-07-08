<x-app-layout>
    <div class="container py-4">
        <h2>Edit Checklist Template Item</h2>

        <form action="{{ route('admin.checklist-templates.update', $checklistTemplate) }}" method="POST" class="mt-3">
            @csrf @method('PUT')
            @include('admin.checklist-templates._form', ['departments' => $departments, 'template' => $checklistTemplate])
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</x-app-layout>
