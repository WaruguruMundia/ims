<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    @php
        $activeTasks = $tasks->filter(fn($t) => in_array($t->status, ['pending', 'in_progress', 'rejected']));
        $completedTasks = $tasks->filter(fn($t) => in_array($t->status, ['submitted', 'approved']));
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Active Tasks Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Active Assigned Tasks
                    </h3>

                    @if ($activeTasks->isEmpty())
                        <p class="text-gray-650 text-sm">
                            No active tasks assigned to you at the moment.
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
                                    @foreach ($activeTasks as $task)
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
                                                <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
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

            <!-- Completed Tasks Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Task History (Completed / Resolved)
                    </h3>

                    @if ($completedTasks->isEmpty())
                        <p class="text-gray-655 text-sm">
                            No completed tasks in your history.
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
                                    @foreach ($completedTasks as $task)
                                        <tr class="opacity-75">
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
                                                    @else bg-blue-100 text-blue-800 @endif">
                                                    @if($task->status === 'approved')
                                                        Closed / Resolved
                                                    @else
                                                        Completed
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-700">
                                                {{ $task->due_date->format('Y-m-d') }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                <a href="{{ route('intern.tasks.show', $task) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                    View Details
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
