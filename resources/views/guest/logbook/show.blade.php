<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Guest Review - Digital Logbook</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 bg-gray-100 antialiased">
        <div class="min-h-screen flex flex-col">
            
            <!-- Navbar Header -->
            <header class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        <span class="font-bold text-lg text-gray-900">IMS External Portal</span>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            Valid Guest Access
                        </span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                    <!-- Intern Profile Overview Card -->
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <span class="text-xs uppercase font-bold text-gray-500 tracking-wider">Intern Logbook Review</span>
                                    <h3 class="text-2xl font-bold text-gray-900 mt-1">
                                        {{ $intern->user?->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $intern->programme }} at {{ $intern->institution }}
                                    </p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    Assigned Department: <strong class="text-gray-900">{{ $intern->department?->name ?? 'None' }}</strong><br>
                                    Onboarding Status: <strong class="text-gray-900">{{ $intern->onboardingProgressPercentage() }}% complete</strong><br>
                                    Placement Period: <strong>{{ $intern->start_date->format('Y-m-d') }} to {{ $intern->end_date->format('Y-m-d') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logbook Entry History List -->
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-100 pb-2">
                                Digital Logbook Entries
                            </h3>

                            @if ($entries->isEmpty())
                                <p class="text-gray-600">
                                    No logbook entries have been recorded by the intern yet.
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
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-6 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }} Intern Management System. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>
