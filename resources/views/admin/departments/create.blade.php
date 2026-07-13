<x-app-layout>
    <div class="container py-4">
        <h2>Add Department</h2>

        <form action="{{ route('admin.departments.store') }}" method="POST" class="mt-3">
            @csrf
            @include('admin.departments._form')
            <button type="submit" class="btn btn-primary mt-3">Save</button>
        </form>
    </div>
</x-app-layout>
