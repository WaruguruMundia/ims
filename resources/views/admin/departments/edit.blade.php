<x-app-layout>
    <div class="container py-4">
        <h2>Edit Department</h2>

        <form action="{{ route('admin.departments.update', $department) }}" method="POST" class="mt-3">
            @csrf @method('PUT')
            @include('admin.departments._form', ['department' => $department])
            <button type="submit" class="btn btn-primary mt-3">Update</button>
        </form>
    </div>
</x-app-layout>
