<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js CDN for interactive dashboard statistics -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            @include('layouts.navigation')

            <div class="flex-1 flex overflow-hidden">
                <!-- Collapsible Sidebar Menu -->
                <aside id="sidebar-menu" class="w-64 bg-white border-r border-gray-200 transition-all duration-300 flex flex-col shrink-0">
                    <div class="flex justify-between items-center p-4 border-b border-gray-100">
                        <span id="sidebar-title" class="font-bold text-xs text-gray-500 uppercase tracking-wider">Modules</span>
                        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 focus:outline-none" type="button">
                            <!-- Caret toggle icon -->
                            <svg id="sidebar-toggle-icon" class="w-5 h-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Navigation links based on authenticated user role -->
                    <nav class="flex-1 p-2 space-y-1">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    <span class="sidebar-link-text">Admin Dashboard</span>
                                </a>
                                <a href="{{ route('admin.interns.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.interns.create') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    <span class="sidebar-link-text">Register Intern</span>
                                </a>
                                <a href="{{ route('admin.checklist-templates.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.checklist-templates.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <span class="sidebar-link-text">Checklist Templates</span>
                                </a>
                                <a href="{{ route('admin.departments.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.departments.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="sidebar-link-text">Departments</span>
                                </a>
                            @elseif(Auth::user()->isSupervisor())
                                <a href="{{ route('supervisor.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('supervisor.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="sidebar-link-text">Intern Overview</span>
                                </a>
                                <a href="{{ route('supervisor.tasks.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('supervisor.tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <span class="sidebar-link-text">Task Manager</span>
                                </a>
                            @elseif(Auth::user()->isIntern())
                                <a href="{{ route('intern.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('intern.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    <span class="sidebar-link-text">Dashboard</span>
                                </a>
                                <a href="{{ route('intern.tasks.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('intern.tasks.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <span class="sidebar-link-text">Tasks list</span>
                                </a>
                                <a href="{{ route('intern.logbook.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition {{ request()->routeIs('intern.logbook.*') ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-gray-100 text-gray-700' }}">
                                    <svg class="w-5 h-5 text-gray-550" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span class="sidebar-link-text">Logbook</span>
                                </a>
                            @endif
                        @endauth
                    </nav>
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="flex-grow">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        <script>
            // Keep state on reloads
            document.addEventListener('DOMContentLoaded', () => {
                const sidebar = document.getElementById('sidebar-menu');
                const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                
                if (isCollapsed) {
                    applySidebarState(true);
                } else {
                    applySidebarState(false);
                }
            });

            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar-menu');
                const isCollapsed = sidebar.classList.contains('w-16');
                const newState = !isCollapsed;
                
                localStorage.setItem('sidebar-collapsed', newState);
                applySidebarState(newState);
            }

            function applySidebarState(collapse) {
                const sidebar = document.getElementById('sidebar-menu');
                const title = document.getElementById('sidebar-title');
                const icon = document.getElementById('sidebar-toggle-icon');
                const textElements = document.querySelectorAll('.sidebar-link-text');

                if (collapse) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-16');
                    if (title) title.classList.add('hidden');
                    if (icon) icon.classList.add('rotate-180');
                    textElements.forEach(el => el.classList.add('hidden'));
                } else {
                    sidebar.classList.remove('w-16');
                    sidebar.classList.add('w-64');
                    if (title) title.classList.remove('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                    textElements.forEach(el => el.classList.remove('hidden'));
                }
            }
        </script>
    </body>
</html>
