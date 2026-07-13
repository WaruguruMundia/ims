<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Onboarding Checklist Templates</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('admin.checklist-templates.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        Add Item
                    </a>
                </div>

                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
                @endif

                @foreach ($templates as $groupName => $items)
                    <h3 class="font-semibold text-md mt-6 mb-2 text-gray-700">{{ $groupName }}</h3>

                    <table class="w-full text-left border-collapse mb-4">
                        <thead>
                        <tr class="border-b">
                            <th class="py-2 pr-4">Order</th>
                            <th class="py-2 pr-4">Item</th>
                            <th class="py-2 pr-4">Required</th>
                            <th class="py-2 pr-4">Status</th>
                            <th class="py-2 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($items as $template)
                            <tr class="border-b {{ !$template->is_active ? 'text-gray-400' : '' }}">
                                <td class="py-2 pr-4">{{ $template->display_order }}</td>
                                <td class="py-2 pr-4">{{ $template->item_text }}</td>
                                <td class="py-2 pr-4">
                                    @if ($template->is_required)
                                        <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded">Required</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Optional</span>
                                    @endif
                                </td>
                                <td class="py-2 pr-4">
                                    @if ($template->is_active)
                                        <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Active</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-2 text-right space-x-2">
                                    <a href="{{ route('admin.checklist-templates.edit', $template) }}"
                                       class="text-sm text-indigo-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.checklist-templates.toggle-active', $template) }}"
                                          method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-sm text-gray-600 hover:underline">
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
        </div>
    </div>
</x-app-layout>
