<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Intern Logbook Review') }}
            </h2>
            <a href="{{ route('supervisor.dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-semibold text-sm">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Intern Profile Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs uppercase font-bold text-gray-500 tracking-wider">Intern Profile</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-1">
                                {{ $intern->user?->name }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $intern->programme }} at {{ $intern->institution }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-600">
                            Department: <strong class="text-gray-900">{{ $intern->department?->name ?? '-' }}</strong><br>
                            Placement Period: <strong>{{ $intern->start_date->format('Y-m-d') }} to {{ $intern->end_date->format('Y-m-d') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logbook History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        Logbook Entry History
                    </h3>

                    @if ($entries->isEmpty())
                        <p class="text-gray-600">
                            This intern has not recorded any logbook entries yet.
                        </p>
                    @else
                        <div class="space-y-6">
                            @foreach ($entries as $entry)
                                <div class="border border-gray-200 rounded p-4 shadow-sm hover:shadow transition bg-white">
                                    <div class="flex justify-between items-start border-b border-gray-100 pb-2 mb-3">
                                        <div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $entry->entry_date->format('l, Y-m-d') }}</span>
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase
                                                @if($entry->entry_type === 'weekly') bg-purple-100 text-purple-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $entry->entry_type }} Log
                                            </span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mt-2">
                                        <div>
                                            <strong class="text-gray-700 block mb-1">Activities Performed:</strong>
                                            <p class="text-gray-600 whitespace-pre-wrap">{{ $entry->activities_performed }}</p>
                                        </div>
                                        <div>
                                            <strong class="text-gray-700 block mb-1">Challenges Encountered:</strong>
                                            <p class="text-gray-600 whitespace-pre-wrap">{{ $entry->challenges_encountered ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <strong class="text-gray-700 block mb-1">Skills Developed:</strong>
                                            <p class="text-gray-600 whitespace-pre-wrap">{{ $entry->skills_developed ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
