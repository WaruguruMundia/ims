<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checklist Templates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Templates
                    </h3>

                    <a href="{{ route('admin.checklist-templates.create') }}" class="text-blue-600 hover:text-blue-800">
                        Add Template
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($templates->isEmpty())
                        <p class="text-gray-600">
                            No checklist templates found.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Item</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Department</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Order</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Active</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($templates as $template)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ $template->item_text }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $template->department?->name ?? 'Global' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $template->display_order }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $template->is_required ? 'Yes' : 'No' }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-700">{{ $template->is_active ? 'Yes' : 'No' }}</td>
                                        <td class="px-4 py-2 text-sm space-x-3">
                                            <a href="{{ route('admin.checklist-templates.edit', $template) }}" class="text-blue-600 hover:text-blue-800">
                                                Edit
                                            </a>

                                            @if ($template->is_active)
                                                <form method="POST" action="{{ route('admin.checklist-templates.destroy', $template) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                                        Deactivate
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
