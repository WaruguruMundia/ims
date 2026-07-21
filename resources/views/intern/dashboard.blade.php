<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Onboarding & Dashboard') }}
            </h2>
            <div class="flex space-x-3 text-sm">
                <a href="{{ route('intern.tasks.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                    My Tasks
                </a>
                <a href="{{ route('intern.logbook.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                    Digital Logbook
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $hour = now()->hour;
                if ($hour < 12) {
                    $greeting = 'Good morning';
                } elseif ($hour < 18) {
                    $greeting = 'Good afternoon';
                } else {
                    $greeting = 'Good evening';
                }
            @endphp
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded text-indigo-900 text-sm font-semibold shadow-sm">
                👋 {{ $greeting }}, {{ Auth::user()->name }}! Welcome to your onboarding dashboard.
            </div>

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Onboarding Progress Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Onboarding Progress
                        </h3>

                        <p class="mt-2 text-gray-700">
                            {{ $intern->onboardingProgressPercentage() }}% complete
                        </p>

                        <div class="mt-4 w-full bg-gray-200 rounded-full h-3">
                            <div
                                class="bg-blue-600 h-3 rounded-full"
                                style="width: {{ $intern->onboardingProgressPercentage() }}%"
                            ></div>
                        </div>

                        @if ($intern->hasCompletedRequiredOnboarding())
                            <p class="mt-3 text-green-700">
                                All required onboarding items are complete.
                            </p>
                        @else
                            <p class="mt-3 text-yellow-700">
                                Some required onboarding items are still pending.
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Logbook Logging Streak Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-orange-500">
                    <div class="p-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Daily Logging Streak
                            </h3>
                            @php
                                $streak = $intern->logbookStreak();
                            @endphp
                            <div class="flex items-baseline mt-2 space-x-2">
                                <span class="text-3xl font-extrabold text-orange-650">{{ $streak }}</span>
                                <span class="text-gray-650 text-sm font-semibold">consecutive {{ Str::plural('day', $streak) }}</span>
                            </div>
                            <p class="text-sm text-gray-550 mt-3">
                                @if($streak > 0)
                                    🔥 Great job! Keep recording your logs daily to keep your streak alive!
                                @else
                                    ⚡ No active streak. Record today's log entry to start your streak!
                                @endif
                            </p>
                        </div>
                        <div class="text-5xl">
                            @if($streak > 0)
                                🔥
                            @else
                                ⚡
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intern Work Statistics Graph Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        My Work Statistics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <!-- Chart container -->
                        <div class="md:col-span-1 flex justify-center">
                            <div class="w-48 h-48 relative">
                                <canvas id="internStatsChart"></canvas>
                            </div>
                        </div>
                        <!-- Statistics details -->
                        <div class="md:col-span-2 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-indigo-50 p-4 rounded border border-indigo-100 flex items-center space-x-3">
                                    <span class="text-2xl">📔</span>
                                    <div>
                                        <div class="text-2xl font-extrabold text-indigo-750">{{ $logbookEntriesCount }}</div>
                                        <div class="text-xs text-indigo-650 font-bold uppercase">Logbook Entries</div>
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded border border-green-100 flex items-center space-x-3">
                                    <span class="text-2xl">✅</span>
                                    <div>
                                        <div class="text-2xl font-extrabold text-green-750">{{ $tasksCount['completed'] }}</div>
                                        <div class="text-xs text-green-650 font-bold uppercase">Tasks Completed</div>
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded border border-yellow-100 flex items-center space-x-3">
                                    <span class="text-2xl">⏳</span>
                                    <div>
                                        <div class="text-2xl font-extrabold text-yellow-750">{{ $tasksCount['in_progress'] }}</div>
                                        <div class="text-xs text-yellow-650 font-bold uppercase">Tasks In Progress</div>
                                    </div>
                                </div>
                                <div class="bg-red-50 p-4 rounded border border-red-100 flex items-center space-x-3">
                                    <span class="text-2xl">📋</span>
                                    <div>
                                        <div class="text-2xl font-extrabold text-red-750">{{ $tasksCount['pending'] }}</div>
                                        <div class="text-xs text-red-650 font-bold uppercase">Pending Tasks</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 cursor-pointer" onclick="toggleElement('intern-checklist-container')">
                        <h3 class="text-lg font-semibold text-gray-900">
                            My Checklist
                        </h3>
                        <button class="text-gray-500 hover:text-gray-700 focus:outline-none flex items-center">
                            <span id="intern-checklist-container-toggle-text" class="text-sm mr-1">Hide</span>
                            <svg id="intern-checklist-container-icon" class="w-5 h-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <div id="intern-checklist-container" class="transition-all duration-350">
                        @if ($intern->onboardingChecklists->isEmpty())
                            <p class="text-gray-600">
                                No onboarding checklist items have been assigned yet.
                            </p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Item</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Completed At</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Action</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($intern->onboardingChecklists as $item)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ $item->item }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $item->is_required ? 'Yes' : 'No' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @if ($item->is_completed)
                                                <span class="text-green-700">Completed</span>
                                            @else
                                                <span class="text-yellow-700">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $item->completed_at?->format('Y-m-d H:i') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @can('complete', $item)
                                                @if (! $item->is_completed)
                                                    <form method="POST" action="{{ route('onboarding-checklists.complete', $item) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit" class="text-blue-600 hover:text-blue-800">
                                                            Mark complete
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('onboarding-checklists.reopen', $item) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                                            Reopen
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-gray-500">Not allowed</span>
                                            @endcan
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

            @if ($evaluation)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">
                            My Performance Evaluation
                        </h3>
                        <p class="text-sm text-gray-600">
                            Your supervisor has submitted your final performance evaluation. Here are your ratings and feedback:
                        </p>

                        <div class="space-y-4">
                            @foreach ($evaluation->evaluationScores as $score)
                                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50 flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $score->criteria?->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $score->criteria?->description }}</p>
                                        @if ($score->comment)
                                            <p class="mt-2 text-xs text-gray-600 italic">
                                                "{{ $score->comment }}"
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <span class="text-lg font-bold text-indigo-700">{{ $score->score }}</span>
                                        <span class="text-xs text-gray-500">/ {{ $score->criteria?->max_score }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-150 pt-4">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Overall Feedback</h4>
                            <p class="text-gray-600 text-sm bg-gray-50 p-4 rounded border border-gray-200 whitespace-pre-wrap">
                                {{ $evaluation->overall_feedback }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <script>
        function toggleElement(id) {
            const container = document.getElementById(id);
            const toggleText = document.getElementById(id + '-toggle-text');
            const icon = document.getElementById(id + '-icon');
            
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                if (toggleText) toggleText.textContent = 'Hide';
                if (icon) icon.classList.remove('rotate-180');
            } else {
                container.classList.add('hidden');
                if (toggleText) toggleText.textContent = 'Show';
                if (icon) icon.classList.add('rotate-180');
            }
        }

        // Draw the Chart.js doughnut chart for task stats
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('internStatsChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'In Progress', 'Pending'],
                        datasets: [{
                            data: [
                                {{ $tasksCount['completed'] }},
                                {{ $tasksCount['in_progress'] }},
                                {{ $tasksCount['pending'] }}
                            ],
                            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        cutout: '70%'
                    }
                });
            }
        });
    </script>
</x-app-layout>
