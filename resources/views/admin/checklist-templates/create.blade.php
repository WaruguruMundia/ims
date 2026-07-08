<x-app-layout>
    <div class="container py-4">
        <h2>Add Checklist Template Item</h2>

        <form action="{{ route('admin.checklist-templates.store') }}" method="POST" class="mt-3">
            @csrf
            @include('admin.checklist-templates._form', ['departments' => $departments])
            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
</x-app-layout>
