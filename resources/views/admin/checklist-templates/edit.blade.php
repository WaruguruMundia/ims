<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Checklist Template Item</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.checklist-templates.update', $checklistTemplate) }}" method="POST">
                    @csrf @method('PUT')
                    @include('admin.checklist-templates._form', ['departments' => $departments, 'template' => $checklistTemplate])
                    <button type="submit" class="mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
