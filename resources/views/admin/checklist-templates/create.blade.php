<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Checklist Template') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.checklist-templates.store') }}" class="p-6 space-y-6">
                    @csrf

                    @include('admin.checklist-templates._form')

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.checklist-templates.index') }}" class="text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
