<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Supervisee Onboarding & Management') }}
            </h2>
            <a href="{{ route('supervisor.tasks.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                Task Management
            </a>
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
            <div class="bg-gradient-to-r from-teal-100 to-emerald-100 dark:from-slate-900 dark:to-slate-850 border border-teal-200 dark:border-slate-800 border-l-4 border-l-teal-500 p-6 rounded-xl shadow-md flex items-center space-x-4">
                <div class="p-3 bg-teal-100 dark:bg-slate-800 rounded-lg">
                    <svg class="w-7 h-7 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-teal-700 dark:text-teal-400 tracking-tight">
                        {{ $greeting }}, {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-sm text-teal-700 dark:text-teal-400 mt-1 font-medium">
                        Welcome to your supervisor dashboard. Let's manage intern checklists, logs, and tasks.
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        My Interns
                    </h3>

                    @if ($interns->isEmpty())
                        <p class="text-gray-600">
                            You do not currently have assigned interns.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Intern</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Department</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required Complete</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($interns as $intern)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 flex items-center">
                                            <button onclick="toggleChecklistRows({{ $intern->id }})" class="mr-2 focus:outline-none text-gray-500 hover:text-gray-700 flex items-center" type="button">
                                                <svg id="chevron-{{ $intern->id }}" class="w-4 h-4 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </button>
                                            <span>{{ $intern->user?->name }}</span>
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
                                        <td class="px-4 py-2 text-sm space-x-3">
                                            <a href="{{ route('supervisor.interns.logbook', $intern) }}" class="text-blue-600 hover:text-blue-900 font-semibold">Logbook</a>
                                            <a href="{{ route('supervisor.tasks.create', ['intern_id' => $intern->id]) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Assign Task</a>
                                            <a href="{{ route('supervisor.evaluations.create', ['intern_id' => $intern->id]) }}" class="text-green-600 hover:text-green-900 font-semibold">Evaluate</a>
                                            <a href="{{ route('shared.interns.report', $intern) }}" class="text-purple-600 hover:text-purple-900 font-semibold">Report</a>
                                        </td>
                                    </tr>

                                    @foreach ($intern->onboardingChecklists as $item)
                                        <tr class="bg-gray-50 intern-checklist-{{ $intern->id }} hidden">
                                            <td colspan="3" class="px-8 py-2 text-sm text-gray-700">
                                                {{ $item->item }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $item->is_completed ? 'Completed' : 'Pending' }}
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
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <script>
        function toggleChecklistRows(internId) {
            const rows = document.querySelectorAll('.intern-checklist-' + internId);
            const chevron = document.getElementById('chevron-' + internId);
            
            rows.forEach(row => {
                row.classList.toggle('hidden');
            });
            
            if (chevron) {
                chevron.classList.toggle('rotate-90');
            }
        }
    </script>
</x-app-layout>
