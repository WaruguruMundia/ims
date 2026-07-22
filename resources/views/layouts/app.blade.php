<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-200">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Dark Mode Guard to prevent flash of light theme -->
        <script>
            if (localStorage.getItem('theme-dark') === 'true') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            function toggleDarkMode() {
                const isDark = document.documentElement.classList.contains('dark');
                const newState = !isDark;
                if (newState) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme-dark', 'true');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme-dark', 'false');
                }
                window.dispatchEvent(new Event('theme-changed'));
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js CDN for interactive dashboard statistics -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Custom Dark & Modern Sleek Theme Styles (Teal & Sapphire Palette) -->
        <style>
            :root {
                /* Canvas & Foundations */
                --color-canvas-workspace: #F8FAFC;
                --color-surface-card: #FFFFFF;
                --color-sidebar-bg: #1E293B; /* Mellow Slate Sapphire */
                --color-border-subtle: #E2E8F0;

                /* Interactive Accents */
                --color-accent-primary: #0D9488; /* Dark Teal */
                --color-accent-highlight: #2DD4BF; /* Vibrant Teal */
                --color-hover-row: #F0F9FF;

                /* Semantic Status Badges */
                --color-badge-verified-bg: #CCFBF1;
                --color-badge-verified-text: #115E59;
                --color-badge-warning-bg: #FEF3C7;
                --color-badge-warning-text: #92400E;
                --color-badge-danger-bg: #FFE4E6;
                --color-badge-danger-text: #9F1239;
            }

            .dark {
                --color-canvas-workspace: #030712; /* Slate-black */
                --color-surface-card: #0F172A; /* Slate-900 */
                --color-sidebar-bg: #090E1A; /* Deeper midnight sapphire */
                --color-border-subtle: #1E293B;

                --color-accent-primary: #0D9488;
                --color-accent-highlight: #2DD4BF;
                --color-hover-row: #1E293B;
            }

            body {
                background-color: var(--color-canvas-workspace) !important;
                color: #334155 !important;
            }
            .dark body {
                color: #CBD5E1 !important;
            }
            
            /* Dark Mode Universal Text Visibility Protection */
            .dark p, .dark span, .dark label, .dark td, .dark th, .dark li, .dark a:not(.sidebar-link-active), .dark div:not(#sidebar-menu):not(.bg-gradient-to-br) {
                color: #CBD5E1 !important;
            }
            .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6, .dark strong, .dark th {
                color: #FFFFFF !important;
            }
            .dark .text-gray-900, .dark .text-gray-800, .dark .text-gray-700, .dark .text-gray-655, .dark .text-gray-650, .dark .text-gray-600, .dark .text-gray-550, .dark .text-gray-500 {
                color: #CBD5E1 !important;
            }
            
            /* Dark Mode Priority & Status Badge Contrast Adjustments */
            .dark .bg-red-100 {
                background-color: #7f1d1d !important;
                color: #fca5a5 !important;
            }
            .dark .bg-yellow-100 {
                background-color: #78350f !important;
                color: #fde047 !important;
            }
            .dark .bg-green-100 {
                background-color: #064e3b !important;
                color: #86efac !important;
            }
            .dark .bg-blue-100 {
                background-color: #1e3a8a !important;
                color: #93c5fd !important;
            }
            .dark .bg-indigo-100 {
                background-color: #311042 !important;
                color: #e9d5ff !important;
            }

            /* Replace all traces of Indigo buttons and focuses with Teal */
            .bg-indigo-600, .bg-indigo-500, .bg-blue-600, .bg-blue-500, .bg-purple-650, .bg-purple-600 {
                background-color: var(--color-accent-primary) !important;
            }
            .bg-indigo-600:hover, .bg-indigo-500:hover, .bg-blue-600:hover, .bg-blue-500:hover, .bg-purple-650:hover, .bg-purple-600:hover {
                background-color: #0F766E !important; /* Teal 700 */
            }
            .hover\:bg-indigo-700:hover, .hover\:bg-indigo-600:hover, .hover\:bg-blue-700:hover {
                background-color: #0F766E !important;
            }
            .text-indigo-600, .text-indigo-700, .text-blue-600, .text-blue-700, .text-purple-700 {
                color: var(--color-accent-primary) !important;
            }
            .focus\:border-indigo-500:focus, .focus\:ring-indigo-500:focus, .focus\:border-blue-500:focus, .focus\:ring-blue-500:focus {
                border-color: var(--color-accent-primary) !important;
                --tw-ring-color: var(--color-accent-primary) !important;
            }
            .border-indigo-500, .border-indigo-600, .border-blue-500 {
                border-color: var(--color-accent-primary) !important;
            }

            /* Dark Mode Action Buttons (edit, delete, report, etc) Visibility Protection */
            .dark .bg-blue-50, .dark .bg-indigo-50 {
                background-color: rgba(13, 148, 136, 0.2) !important;
                color: #2DD4BF !important;
                border-color: rgba(45, 212, 191, 0.3) !important;
            }
            .dark .bg-blue-50:hover, .dark .bg-indigo-50:hover {
                background-color: rgba(13, 148, 136, 0.4) !important;
            }
            
            .dark .bg-purple-50 {
                background-color: rgba(168, 85, 247, 0.2) !important;
                color: #c084fc !important;
                border-color: rgba(168, 85, 247, 0.3) !important;
            }
            .dark .bg-purple-50:hover {
                background-color: rgba(168, 85, 247, 0.4) !important;
            }

            .dark .bg-red-55, .dark .bg-red-50 {
                background-color: rgba(239, 68, 68, 0.2) !important;
                color: #fca5a5 !important;
                border-color: rgba(239, 68, 68, 0.3) !important;
            }
            .dark .bg-red-55:hover, .dark .bg-red-50:hover {
                background-color: rgba(239, 68, 68, 0.4) !important;
            }

            .dark .bg-green-55, .dark .bg-green-50 {
                background-color: rgba(16, 185, 129, 0.2) !important;
                color: #86efac !important;
                border-color: rgba(16, 185, 129, 0.3) !important;
            }
            .dark .bg-green-55:hover, .dark .bg-green-50:hover {
                background-color: rgba(16, 185, 129, 0.4) !important;
            }
            
            aside {
                background-color: var(--color-sidebar-bg) !important;
                border-color: var(--color-border-subtle) !important;
            }
            
            /* Apply dynamic variable tokens throughout */
            .bg-white {
                background-color: var(--color-surface-card) !important;
            }
            .border-gray-200, .border-gray-100, .border-gray-300 {
                border-color: var(--color-border-subtle) !important;
            }
            .dark .bg-gray-50, .dark .bg-gray-100 {
                background-color: #1E293B !important;
            }
            .dark .bg-gray-200 {
                background-color: #334155 !important;
            }
            .dark input, .dark select, .dark textarea {
                background-color: #1E293B !important;
                border-color: #334155 !important;
                color: #F8FAFC !important;
            }
            .dark td, .dark th {
                color: #CBD5E1 !important;
                border-color: #1E293B !important;
            }

            /* Active Navigation highlighting - Vibrant Teal Indicator */
            .sidebar-link-active {
                background-color: rgba(45, 212, 191, 0.12) !important;
                color: var(--color-accent-highlight) !important;
                border-left: 4px solid var(--color-accent-highlight) !important;
            }
            .sidebar-link-active svg {
                color: var(--color-accent-highlight) !important;
            }

            /* Primary CTAs & Accents mapping */
            .bg-indigo-600 {
                background-color: var(--color-accent-primary) !important;
            }
            .bg-indigo-600:hover {
                background-color: #0F766E !important; /* Teal 700 */
            }
            .text-indigo-600 {
                color: var(--color-accent-primary) !important;
            }
            .text-indigo-700 {
                color: #0F766E !important;
            }
            .border-indigo-500 {
                border-color: var(--color-accent-primary) !important;
            }
            .bg-indigo-50 {
                background-color: #F0FDFA !important;
                color: var(--color-accent-primary) !important;
            }
            .text-indigo-900 {
                color: #115E59 !important;
            }
            .dark .bg-indigo-50 {
                background-color: #115E59 !important;
                color: #CCFBF1 !important;
            }

            /* Semantic status mapping */
            .bg-green-100 {
                background-color: var(--color-badge-verified-bg) !important;
                color: var(--color-badge-verified-text) !important;
            }
            .bg-yellow-100 {
                background-color: var(--color-badge-warning-bg) !important;
                color: var(--color-badge-warning-text) !important;
            }
            .bg-red-100 {
                background-color: var(--color-badge-danger-bg) !important;
                color: var(--color-badge-danger-text) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            
            <div class="flex-1 flex overflow-hidden">
                <!-- Collapsible Sidebar Menu (Sapphire Canvas) -->
                <aside id="sidebar-menu" class="w-67 bg-[var(--color-sidebar-bg)] text-white border-r border-slate-800 transition-all duration-300 flex flex-col shrink-0">
                    
                    <!-- Sidebar Header (IMS Simple Text Logo) -->
                    <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[var(--color-sidebar-bg)]">
                        <div class="flex items-center space-x-2">
                            <span id="sidebar-logo-title" class="font-black text-white text-lg tracking-wider select-none">IMS Portal</span>
                        </div>
                        <button onclick="toggleSidebar()" class="text-slate-200 hover:text-teal-350 focus:outline-none" type="button">
                            <!-- Caret toggle icon -->
                            <svg id="sidebar-toggle-icon" class="w-5 h-5 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Navigation Links Section -->
                    <nav class="flex-1 p-2 space-y-1">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    <span class="sidebar-link-text">HR Admin Dashboard</span>
                                </a>
                                <a href="{{ route('admin.interns.create') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('admin.interns.create') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    <span class="sidebar-link-text">Register Intern</span>
                                </a>
                                <a href="{{ route('admin.checklist-templates.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('admin.checklist-templates.*') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    <span class="sidebar-link-text">Checklist Templates</span>
                                </a>
                                <a href="{{ route('admin.departments.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('admin.departments.*') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="sidebar-link-text">Departments</span>
                                </a>
                            @elseif(Auth::user()->isSupervisor())
                                <a href="{{ route('supervisor.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('supervisor.dashboard') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="sidebar-link-text">Intern Overview</span>
                                </a>
                                <a href="{{ route('supervisor.tasks.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('supervisor.tasks.*') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <span class="sidebar-link-text">Task Manager</span>
                                </a>
                            @elseif(Auth::user()->isIntern())
                                <a href="{{ route('intern.dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('intern.dashboard') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 14a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    <span class="sidebar-link-text">Dashboard</span>
                                </a>
                                <a href="{{ route('intern.tasks.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('intern.tasks.*') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <span class="sidebar-link-text">Tasks list</span>
                                </a>
                                <a href="{{ route('intern.logbook.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:bg-blue-800 {{ request()->routeIs('intern.logbook.*') ? 'sidebar-link-active' : 'text-slate-100' }}">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <span class="sidebar-link-text">Logbook</span>
                                </a>
                            @endif
                        @endauth
                    </nav>

                    <!-- Sidebar Footer (Profile User Info) -->
                    @auth
                        <div class="p-4 border-t border-slate-800 bg-[#1E293B] dark:bg-[#030712] mt-auto">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold text-xs select-none">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endauth
                </aside>

                <!-- Main Content Area -->
                <div class="flex-1 flex flex-col overflow-y-auto">
                    <!-- Top Minimal Navbar (accommodating ONLY dark mode toggle & logout) -->
                    <header class="bg-white dark:bg-[#0F172A] border-b border-gray-200 dark:border-slate-800 h-14 flex items-center justify-end px-6 space-x-4 shrink-0">
                        <!-- Dark Mode Toggle Button -->
                        <button onclick="toggleDarkMode()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200 focus:outline-none transition" type="button" title="Toggle Dark/Light Mode">
                            <!-- Sun (visible in dark mode) -->
                            <svg class="w-5 h-5 hidden dark:block text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            <!-- Moon (visible in light mode) -->
                            <svg class="w-5 h-5 block dark:hidden text-gray-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                        
                        <!-- Logout Button/Icon -->
                        <form method="POST" action="{{ route('logout') }}" class="flex items-center">
                            @csrf
                            <button type="submit" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-500 hover:text-red-600 dark:text-slate-400 dark:hover:text-red-400 focus:outline-none transition" title="Log Out">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </header>

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
                const logoTitle = document.getElementById('sidebar-logo-title');

                if (collapse) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-16');
                    if (title) title.classList.add('hidden');
                    if (icon) icon.classList.add('rotate-180');
                    if (logoTitle) logoTitle.classList.add('hidden');
                    textElements.forEach(el => el.classList.add('hidden'));
                } else {
                    sidebar.classList.remove('w-16');
                    sidebar.classList.add('w-64');
                    if (title) title.classList.remove('hidden');
                    if (icon) icon.classList.remove('rotate-180');
                    if (logoTitle) logoTitle.classList.remove('hidden');
                    textElements.forEach(el => el.classList.remove('hidden'));
                }
            }
        </script>
    </body>
</html>
