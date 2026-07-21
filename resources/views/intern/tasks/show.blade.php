<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Work Area') }}
            </h2>
            <a href="{{ route('intern.tasks.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                &larr; Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Task Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="text-xs uppercase font-bold text-gray-500 tracking-wider">Assigned Task Details</span>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Assigned by: <strong class="text-gray-900">{{ $task->creator?->name }}</strong>
                            </p>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($task->status === 'approved') bg-green-100 text-green-800
                                @elseif($task->status === 'submitted') bg-blue-100 text-blue-800
                                @elseif($task->status === 'in_progress') bg-indigo-100 text-indigo-800
                                @elseif($task->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ str_replace('_', ' ', ucfirst($task->status)) }}
                            </span>
                            <span class="text-xs text-gray-600">
                                Due: <strong>{{ $task->due_date->format('Y-m-d') }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 space-y-4">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700">Description</h4>
                            <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $task->description ?? 'No description provided.' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-700">Expected Deliverables</h4>
                            <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $task->deliverable_notes ?? 'No specific deliverable notes provided.' }}</p>
                        </div>

                        <div class="flex space-x-6 text-sm text-gray-500">
                            <div>
                                Priority: <strong>{{ ucfirst($task->priority) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Actions & Submission Form -->
            @if ($task->status === 'pending')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Ready to start this task?
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Mark this task as in-progress to let your supervisor know you are working on it.
                            </p>
                        </div>
                        <form method="POST" action="{{ route('intern.tasks.update', $task) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                                Start Task
                            </button>
                        </form>
                    </div>
                </div>
            @elseif (in_array($task->status, ['in_progress', 'rejected']))
                @if ($task->status === 'rejected')
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex">
                            <div>
                                <h3 class="text-sm font-semibold text-red-800">Task Returned for Revision</h3>
                                <div class="text-sm text-red-700 mt-1">
                                    <p>Your supervisor has returned this task. Review feedback: </p>
                                    <p class="mt-2 font-mono whitespace-pre-wrap bg-white p-2 border border-red-200 rounded text-gray-800">
                                        {{ $task->reviewer_feedback }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            Submit Deliverables
                        </h3>

                        <form method="POST" action="{{ route('intern.tasks.update', $task) }}" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="submitted">

                            <div>
                                <x-input-label for="submission_notes" :value="__('Submission Comments / Deliverables Info')" />
                                <textarea id="submission_notes" name="submission_notes" rows="4" placeholder="Provide link to code repository, document, or explain completed work..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('submission_notes', $task->submission_notes) }}</textarea>
                                <x-input-error :messages="$errors->get('submission_notes')" class="mt-2" />
                            </div>

                            <div>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                                    Submit Deliverables
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Submission Details (For already submitted/approved status) -->
            @if (in_array($task->status, ['submitted', 'approved']))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            Your Submission Details
                        </h3>
                        <div class="space-y-3">
                            <p class="text-sm text-gray-600">
                                Submitted on: <strong>{{ $task->submitted_at?->format('Y-m-d H:i') }}</strong>
                            </p>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Submission Comments</h4>
                                <p class="text-gray-700 mt-1 whitespace-pre-wrap bg-gray-50 p-3 rounded border border-gray-200">{{ $task->submission_notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($task->reviewed_at)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                                Supervisor Feedback
                            </h3>
                            <div class="space-y-3">
                                <p class="text-sm text-gray-600">
                                    Reviewed on: <strong>{{ $task->reviewed_at->format('Y-m-d H:i') }}</strong>
                                </p>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700">Review Comments</h4>
                                    <p class="text-gray-750 mt-1 whitespace-pre-wrap bg-green-50 p-3 rounded border border-green-200">{{ $task->reviewer_feedback ?? 'No feedback comments provided.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
