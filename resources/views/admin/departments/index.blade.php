<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Departments</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <a href="{{ route('admin.departments.create') }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">
                        Add Department
                    </a>
                </div>

                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('status') }}</div>
                @endif

                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="border-b">
                        <th class="py-2 pr-4">Name</th>
                        <th class="py-2 pr-4">Code</th>
                        <th class="py-2 pr-4">Interns</th>
                        <th class="py-2 pr-4">Checklist Templates</th>
                        <th class="py-2 pr-4">Status</th>
                        <th class="py-2 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($departments as $department)
                        <tr class="border-b {{ !$department->is_active ? 'text-gray-400' : '' }}">
                            <td class="py-2 pr-4">{{ $department->name }}</td>
                            <td class="py-2 pr-4">{{ $department->code ?? '—' }}</td>
                            <td class="py-2 pr-4">{{ $department->interns_count }}</td>
                            <td class="py-2 pr-4">{{ $department->checklist_templates_count }}</td>
                            <td class="py-2 pr-4">
                                @if ($department->is_active)
                                    <span class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded">Inactive</span>
                                @endif
                            </td>
                             <td class="py-2 text-right space-x-2">
                                 <a href="{{ route('admin.departments.edit', $department) }}"
                                    class="inline-flex items-center px-2 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded text-xs font-semibold transition" title="Edit Department">
                                     <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                     </svg>
                                     Edit
                                 </a>
                                 <form action="{{ route('admin.departments.toggle-active', $department) }}"
                                       method="POST" class="inline">
                                     @csrf @method('PATCH')
                                     <button type="submit" class="inline-flex items-center px-2 py-1 {{ $department->is_active ? 'bg-red-50 hover:bg-red-100 text-red-700 border border-red-200' : 'bg-green-50 hover:bg-green-100 text-green-700 border border-green-200' }} rounded text-xs font-semibold transition"
                                             @if ($department->is_active && $department->interns_count > 0)
                                                 onclick="return confirm('This department has {{ $department->interns_count }} intern(s) assigned. Deactivate anyway?')"
                                             @endif>
                                         <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                             @if ($department->is_active)
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                             @else
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                             @endif
                                         </svg>
                                         {{ $department->is_active ? 'Deactivate' : 'Reactivate' }}
                                     </button>
                                 </form>
                             </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
