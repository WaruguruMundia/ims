<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
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
                        Welcome to your admin dashboard. Let's manage departments, supervisors, and interns.
                    </p>
                </div>
            </div>

            <!-- Grouped Interns by Supervisor -->
            @if ($groupedInterns->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-gray-650 text-sm">No interns found in the system.</p>
                    </div>
                </div>
            @else
                @foreach ($groupedInterns as $supervisorName => $interns)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                        <!-- Collapsible Header Toggle -->
                        <button onclick="toggleGroup('group-{{ $loop->index }}')" class="w-full p-5 flex justify-between items-center focus:outline-none hover:bg-gray-50 transition" type="button">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl text-indigo-600">👤</span>
                                <div class="text-left">
                                    <h3 class="text-base font-bold text-gray-900">
                                        {{ $supervisorName }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Supervisor Group
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    {{ $interns->count() }} {{ Str::plural('intern', $interns->count()) }}
                                </span>
                                <svg id="chevron-group-{{ $loop->index }}" class="w-5 h-5 text-gray-500 transform transition-transform duration-250" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>

                        <!-- Group Content Table -->
                        <div id="group-{{ $loop->index }}" class="border-t border-gray-100 p-6 hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Intern Name</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Department</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required Complete</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($interns as $intern)
                                            <tr>
                                                <td class="px-4 py-2.5 text-sm text-gray-900 font-semibold">
                                                    {{ $intern->user?->name }}
                                                </td>
                                                <td class="px-4 py-2.5 text-sm text-gray-750">
                                                    {{ $intern->department?->name ?? '-' }}
                                                </td>
                                                <td class="px-4 py-2.5 text-sm text-gray-750">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-bold text-gray-900">{{ $intern->onboardingProgressPercentage() }}%</span>
                                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $intern->onboardingProgressPercentage() }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2.5 text-sm">
                                                    @if ($intern->hasCompletedRequiredOnboarding())
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Yes
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-yellow-100 text-yellow-800">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                            No
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2.5 text-sm flex items-center space-x-2">
                                                    <!-- Completion Report Link -->
                                                    <a href="{{ route('shared.interns.report', $intern) }}" class="inline-flex items-center px-2.5 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 rounded text-xs font-semibold transition" title="Completion Report">
                                                        <svg class="w-3.5 h-3.5 mr-1 text-purple-650" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Report
                                                    </a>
                                                    <!-- Edit Link -->
                                                    <a href="{{ route('admin.interns.edit', $intern) }}" class="inline-flex items-center px-2.5 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded text-xs font-semibold transition" title="Edit Profile">
                                                        <svg class="w-3.5 h-3.5 mr-1 text-blue-650" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                        Edit
                                                    </a>
                                                    <!-- Delete Form -->
                                                    <form action="{{ route('admin.interns.destroy', $intern) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this intern?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 rounded text-xs font-semibold transition" title="Delete Intern">
                                                            <svg class="w-3.5 h-3.5 mr-1 text-red-650" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Delete
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
                @endforeach
            @endif
        </div>
    </div>

    <script>
        function toggleGroup(groupId) {
            const container = document.getElementById(groupId);
            const chevron = document.getElementById('chevron-' + groupId);
            
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                container.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }
    </script>
</x-app-layout>
