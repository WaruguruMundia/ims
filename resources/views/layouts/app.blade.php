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
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">📊</span>
                                    <span class="sidebar-link-text">Admin Dashboard</span>
                                </a>
                                <a href="{{ route('admin.checklist-templates.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">📋</span>
                                    <span class="sidebar-link-text">Templates</span>
                                </a>
                            @elseif(Auth::user()->isSupervisor())
                                <a href="{{ route('supervisor.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">👥</span>
                                    <span class="sidebar-link-text">Intern Overview</span>
                                </a>
                                <a href="{{ route('supervisor.tasks.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">🎯</span>
                                    <span class="sidebar-link-text">Task Manager</span>
                                </a>
                            @elseif(Auth::user()->isIntern())
                                <a href="{{ route('intern.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">🏠</span>
                                    <span class="sidebar-link-text">Dashboard</span>
                                </a>
                                <a href="{{ route('intern.tasks.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">✅</span>
                                    <span class="sidebar-link-text">Tasks list</span>
                                </a>
                                <a href="{{ route('intern.logbook.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-semibold hover:bg-gray-100 text-gray-700 transition">
                                    <span class="text-lg">📔</span>
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
