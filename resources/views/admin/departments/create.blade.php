<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Department</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.departments.store') }}" method="POST">
                    @csrf
                    @include('admin.departments._form')
                    <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
