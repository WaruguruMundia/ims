<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('supervisor.tasks.index') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
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
                            <span class="text-xs uppercase font-bold text-gray-500 tracking-wider">Intern Task Assignment</span>
                            <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Assigned to: <strong class="text-gray-900">{{ $task->intern->user?->name }}</strong>
                            </p>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
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
                                Assigned: <strong>{{ $task->created_at->format('Y-m-d') }}</strong>
                            </div>
                            <div>
                                Priority: <strong>{{ ucfirst($task->priority) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intern Submission Card -->
            @if ($task->submitted_at)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            Intern Deliverables Submission
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Submission Notes / Deliverables Info</h4>
                                <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $task->submission_notes ?? 'No comments provided with submission.' }}</p>
                            </div>

                            <div class="text-sm text-gray-500">
                                Submitted on: <strong>{{ $task->submitted_at->format('Y-m-d H:i') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Supervisor Review Card -->
            @if ($task->status === 'submitted')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            Task Review & Feedback
                        </h3>

                        <form method="POST" action="{{ route('supervisor.tasks.review', $task) }}" class="space-y-4">
                            @csrf

                            <div>
                                <x-input-label for="reviewer_feedback" :value="__('Feedback / Comments (Optional)')" />
                                <textarea id="reviewer_feedback" name="reviewer_feedback" rows="3" placeholder="Provide feedback to the intern..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
                                <x-input-error :messages="$errors->get('reviewer_feedback')" class="mt-2" />
                            </div>

                            <div class="flex space-x-3">
                                <button type="submit" name="action" value="approve" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-950 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Close Task / Mark Resolved
                                </button>
                                <button type="submit" name="action" value="reject" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-950 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Return for Revision
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @elseif ($task->reviewed_at)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                            Supervisor Feedback
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-700">Review Comments</h4>
                                <p class="text-gray-600 mt-1 whitespace-pre-wrap">{{ $task->reviewer_feedback ?? 'No feedback comments provided.' }}</p>
                            </div>

                            <div class="text-sm text-gray-500">
                                Reviewed on: <strong>{{ $task->reviewed_at->format('Y-m-d H:i') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
