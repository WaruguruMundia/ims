<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Performance Evaluation') }}
            </h2>
            <a href="{{ route('supervisor.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Intern Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-4">
                        Evaluation for {{ $intern->user?->name }}
                    </h3>
                    <div class="text-sm text-gray-600 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            University: <strong>{{ $intern->institution }}</strong><br>
                            Degree Course: <strong>{{ $intern->programme }}</strong>
                        </div>
                        <div>
                            Department: <strong>{{ $intern->department?->name ?? 'None' }}</strong><br>
                            Placement Period: <strong>{{ $intern->start_date->format('Y-m-d') }} to {{ $intern->end_date->format('Y-m-d') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
                    <strong class="font-semibold">Please correct the following errors:</strong>
                    <ul class="list-disc pl-5 mt-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Evaluation Form -->
            <form method="POST" action="{{ route('supervisor.evaluations.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="intern_id" value="{{ $intern->id }}">

                <!-- Competency Criteria Scores -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">
                            Competency Criteria Scoring
                        </h3>
                        <p class="text-sm text-gray-600">
                            Rate the intern's capability on the following core criteria. Each score is out of the specified maximum points.
                        </p>

                        @foreach ($criteria as $criterion)
                            @php
                                $existingScore = $existingScores[$criterion->id]['score'] ?? '';
                                $existingComment = $existingScores[$criterion->id]['comment'] ?? '';
                            @endphp
                            <div class="p-4 border border-gray-200 rounded-lg space-y-3 bg-gray-50">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $criterion->name }}</h4>
                                    <p class="text-xs text-gray-650 mt-1">{{ $criterion->description }}</p>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                    <div class="md:col-span-1">
                                        <x-input-label for="score_{{ $criterion->id }}" :value="__('Score (Max ' . $criterion->max_score . ')')" />
                                        <x-text-input 
                                            id="score_{{ $criterion->id }}" 
                                            class="block mt-1 w-full" 
                                            type="number" 
                                            name="scores[{{ $criterion->id }}][score]" 
                                            value="{{ old('scores.' . $criterion->id . '.score', $existingScore) }}" 
                                            min="0" 
                                            max="{{ $criterion->max_score }}" 
                                            required 
                                        />
                                    </div>
                                    <div class="md:col-span-3">
                                        <x-input-label for="comment_{{ $criterion->id }}" :value="__('Criterion Comments (Optional)')" />
                                        <x-text-input 
                                            id="comment_{{ $criterion->id }}" 
                                            class="block mt-1 w-full" 
                                            type="text" 
                                            name="scores[{{ $criterion->id }}][comment]" 
                                            value="{{ old('scores.' . $criterion->id . '.comment', $existingComment) }}" 
                                            placeholder="Provide reasoning for this score..." 
                                        />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Overall Feedback -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">
                            Overall Assessment
                        </h3>

                        <div>
                            <x-input-label for="overall_feedback" :value="__('Overall Summary & Professional Feedback')" />
                            <textarea id="overall_feedback" name="overall_feedback" rows="4" placeholder="Summarize the intern's strengths, areas of growth, and overall performance during the attachment..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('overall_feedback', $evaluation->overall_feedback ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('overall_feedback')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Submission Controls -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">
                                Save options
                            </h3>
                            <p class="text-xs text-gray-600 mt-1">
                                Save as draft to edit later, or submit formally to complete the evaluation (cannot be edited after submission).
                            </p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button type="submit" name="status" value="draft" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Draft
                            </button>
                            <button type="submit" name="status" value="submitted" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-950 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onsubmit="return confirm('Are you sure you want to submit this evaluation? This action is permanent.');">
                                Submit Evaluation
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
