<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Intern Onboarding Overview
                    </h3>

                    <div class="flex space-x-6">
                        <a href="{{ route('admin.interns.create') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                            Register New Intern
                        </a>
                        <a href="{{ route('admin.departments.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Manage Departments
                        </a>
                        <a href="{{ route('admin.checklist-templates.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                            Manage Checklist Templates
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if ($interns->isEmpty())
                        <p class="text-gray-600">
                            No interns found.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Intern</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Supervisor</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Department</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required Complete</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($interns as $intern)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ $intern->user?->name }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $intern->supervisor?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $intern->department?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $intern->onboardingProgressPercentage() }}%
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @if ($intern->hasCompletedRequiredOnboarding())
                                                <span class="text-green-700">Yes</span>
                                            @else
                                                <span class="text-yellow-700">No</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm space-x-2">
                                            <a href="{{ route('admin.interns.edit', $intern) }}" class="text-blue-600 hover:text-blue-900 font-semibold mr-2">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.interns.destroy', $intern) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this intern? This will delete their checklists, logbooks, and user account.')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                                    Delete
                                                </button>
                                            </form>
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
