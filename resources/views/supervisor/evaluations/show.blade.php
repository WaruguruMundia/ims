<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Performance Evaluation Record') }}
            </h2>
            <a href="{{ route('supervisor.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs uppercase font-bold text-gray-500 tracking-wider">Evaluation Details</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-1">
                                {{ $evaluation->intern->user?->name }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $evaluation->intern->programme }} at {{ $evaluation->intern->institution }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-600">
                            Department: <strong class="text-gray-900">{{ $evaluation->intern->department?->name ?? 'None' }}</strong><br>
                            Status: <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase bg-green-100 text-green-800">Formal Submission</span><br>
                            Submitted: <strong>{{ $evaluation->submitted_at?->format('Y-m-d H:i') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scores Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">
                        Competency Criteria Ratings
                    </h3>

                    <div class="space-y-4">
                        @foreach ($evaluation->evaluationScores as $score)
                            <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $score->criteria?->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $score->criteria?->description }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-indigo-700">{{ $score->score }}</span>
                                        <span class="text-xs text-gray-500">/ {{ $score->criteria?->max_score }}</span>
                                    </div>
                                </div>
                                @if ($score->comment)
                                    <div class="mt-2 text-sm text-gray-600 border-t border-gray-150 pt-2">
                                        <strong>Feedback:</strong> {{ $score->comment }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Overall Assessment -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2 mb-3">
                        Overall Assessment Summary
                    </h3>
                    <p class="text-gray-700 whitespace-pre-wrap bg-gray-50 p-4 rounded border border-gray-200 text-sm">
                        {{ $evaluation->overall_feedback }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
