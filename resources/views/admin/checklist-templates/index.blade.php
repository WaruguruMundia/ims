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

                    <a href="{{ route('admin.checklist-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest rounded transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
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
                                        <td class="px-4 py-2 text-sm space-x-2">
                                            <a href="{{ route('admin.checklist-templates.edit', $template) }}" class="inline-flex items-center px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded text-xs font-semibold transition" title="Edit Template">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>

                                            @if ($template->is_active)
                                                <form method="POST" action="{{ route('admin.checklist-templates.destroy', $template) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded text-xs font-semibold transition" title="Deactivate Template">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
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
