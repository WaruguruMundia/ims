<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Assigned Tasks
                    </h3>

                    @if ($tasks->isEmpty())
                        <p class="text-gray-600">
                            No tasks have been assigned to you yet.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Task Title</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Supervisor</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Priority</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Due Date</th>
                                        <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($tasks as $task)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900 font-medium">
                                                {{ $task->title }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $task->creator?->name }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <span class="px-2 py-1 rounded text-xs font-semibold
                                                    @if($task->priority === 'high') bg-red-100 text-red-800
                                                    @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <span class="px-2 py-1 rounded text-xs font-semibold
                                                    @if($task->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($task->status === 'submitted') bg-blue-100 text-blue-800
                                                    @elseif($task->status === 'in_progress') bg-indigo-100 text-indigo-800
                                                    @elseif($task->status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    @if($task->status === 'approved')
                                                        Closed / Resolved
                                                    @elseif($task->status === 'submitted')
                                                        Completed
                                                    @else
                                                        {{ str_replace('_', ' ', ucfirst($task->status)) }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $task->due_date->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <a href="{{ route('intern.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                    @if($task->status === 'pending')
                                                        Start Task
                                                    @elseif($task->status === 'in_progress' || $task->status === 'rejected')
                                                        Mark Complete
                                                    @else
                                                        View Details
                                                    @endif
                                                </a>
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
