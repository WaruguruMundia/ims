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
            .dark .text-gray-900, .dark .text-gray-800, .dark .text-gray-700, .dark .text-gray-650, .dark .text-gray-655, .dark .text-gray-600, .dark .text-gray-550, .dark .text-gray-500 {
                color: #CBD5E1 !important;
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
                <aside id="sidebar-menu" class="w-64 bg-[var(--color-sidebar-bg)] text-white border-r border-slate-800 transition-all duration-300 flex flex-col shrink-0">
                    
                    <!-- Sidebar Header (IMS Logo Replacement) -->
                    <div class="flex justify-between items-center p-4 border-b border-slate-800 bg-[var(--color-sidebar-bg)]">
                        <div class="flex items-center space-x-2">
                            <!-- Inline SVG ims-full-scaled-down.svg -->
                            <svg id="sidebar-logo" class="w-9 h-9 object-contain rounded" viewBox="0 0 128 128" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="128" height="128" rx="24" fill="white"/>
                                <path d="M58.5933 49C61.7336 49 64.2793 51.5457 64.2793 54.686V63.5C64.2793 63.7761 64.0554 64 63.7793 64C63.5032 64 63.2793 63.7761 63.2793 63.5V54.686C63.2793 52.098 61.1813 50 58.5933 50C56.0052 50 53.9072 52.098 53.9072 54.686V71.3486C53.9072 76.1267 50.0339 80 45.2559 80C40.4778 80 36.6045 76.1267 36.6045 71.3486V52.8022C36.6045 51.8069 35.7976 51 34.8022 51H33.5C33.2239 51 33 50.7761 33 50.5C33 50.2239 33.2239 50 33.5 50H40.4307C40.7068 50 40.9307 50.2239 40.9307 50.5C40.9307 50.7761 40.7068 51 40.4307 51H39.2676C38.3491 51 37.6045 51.7446 37.6045 52.6631V71.3486C37.6045 75.5744 41.0301 79 45.2559 79C49.4816 79 52.9072 75.5744 52.9072 71.3486V54.686C52.9072 51.5457 55.453 49 58.5933 49Z" fill="#1E293B"/>
                                <path d="M64.2793 54.686H63.7793V63.5H64.2793H64.7793V54.686H64.2793ZM63.2793 63.5H63.7793V54.686H63.2793H62.7793V63.5H63.2793ZM53.9072 54.686H53.4072V71.3486H53.9072H54.4072V54.686H53.9072ZM36.6045 71.3486H37.1045V52.8022H36.6045H36.1045V71.3486H36.6045ZM34.8022 51V50.5H33.5V51V51.5H34.8022V51ZM33.5 50V50.5H40.4307V50V49.5H33.5V50ZM40.4307 51V50.5H39.2676V51V51.5H40.4307V51ZM37.6045 52.6631H37.1045V71.3486H37.6045H38.1045V52.6631H37.6045ZM52.9072 71.3486H53.4072V54.686H52.9072H52.4072V71.3486H52.9072ZM52.9072 54.686H53.4072C53.4072 51.8219 55.7291 49.5 58.5933 49.5V49V48.5C55.1768 48.5 52.4072 51.2696 52.4072 54.686H52.9072ZM45.2559 79V79.5C49.7577 79.5 53.4072 75.8505 53.4072 71.3486H52.9072H52.4072C52.4072 75.2982 49.2055 78.5 45.2559 78.5V79ZM37.6045 71.3486H37.1045C37.1045 75.8505 40.754 79.5 45.2559 79.5V79V78.5C41.3063 78.5 38.1045 75.2982 38.1045 71.3486H37.6045ZM39.2676 51V50.5C38.0729 50.5 37.1045 51.4684 37.1045 52.6631H37.6045H38.1045C38.1045 52.0207 38.6252 51.5 39.2676 51.5V51ZM40.9307 50.5H40.4307V50.5V51V51.5C40.9829 51.5 41.4307 51.0523 41.4307 50.5H40.9307ZM40.4307 50V50.5V50.5H40.9307H41.4307C41.4307 49.9477 40.9829 49.5 40.4307 49.5V50ZM33 50.5H33.5V50.5V50V49.5C32.9477 49.5 32.5 49.9477 32.5 50.5H33ZM33.5 51V50.5H33.5H33H32.5C32.5 51.0523 32.9477 51.5 33.5 51.5V51ZM36.6045 52.8022H37.1045C37.1045 51.5308 36.0737 50.5 34.8022 50.5V51V51.5C35.5215 51.5 36.1045 52.083 36.1045 52.8022H36.6045ZM45.2559 80V79.5C40.754 79.5 37.1045 75.8505 37.1045 71.3486H36.6045H36.1045C36.1045 76.4028 40.2017 80.5 45.2559 80.5V80ZM53.9072 71.3486H53.4072C53.4072 75.8505 49.7577 79.5 45.2559 79.5V80V80.5C50.31 80.5 54.4072 76.4028 54.4072 71.3486H53.9072ZM58.5933 50V49.5C55.7291 49.5 53.4072 51.8219 53.4072 54.686H53.9072H54.4072C54.4072 52.3742 56.2814 50.5 58.5933 50.5V50ZM63.2793 54.686H63.7793C63.7793 51.8219 61.4574 49.5 58.5933 49.5V50V50.5C60.9051 50.5 62.7793 52.3742 62.7793 54.686H63.2793ZM63.7793 64V63.5V63.5H63.2793H62.7793C62.7793 64.0523 63.227 64.5 63.7793 64.5V64ZM64.2793 63.5H63.7793V63.5V64V64.5C64.3316 64.5 64.7793 64.0523 64.7793 63.5H64.2793ZM64.2793 54.686H64.7793C64.7793 51.2696 62.0097 48.5 58.5933 48.5V49V49.5C61.4574 49.5 63.7793 51.8219 63.7793 54.686H64.2793Z" fill="#1E3A8A"/>
                                <path d="M74.6445 69.3223C74.6445 74.6671 78.9774 79 84.3223 79H87C90.866 79 94 75.866 94 72C94 68.134 90.866 65 87 65H85C80.5817 65 77 61.4183 77 57C77 52.5817 80.5817 49 85 49H89.8115C92.8491 49 95.3115 51.4624 95.3115 54.5V59.5C95.3115 59.7761 95.0877 60 94.8115 60C94.5354 60 94.3115 59.7761 94.3115 59.5V55C94.3115 52.2386 92.0729 50 89.3115 50H85C81.134 50 78 53.134 78 57C78 60.866 81.134 64 85 64H87C91.4183 64 95 67.5817 95 72C95 76.4183 91.4183 80 87 80H83.6445C78.1217 80 73.6445 75.5228 73.6445 70V54.3223C73.6445 51.9351 71.7094 50 69.3223 50C66.9351 50 65 51.9351 65 54.3223V63.5C65 63.7761 64.7761 64 64.5 64C64.2239 64 64 63.7761 64 63.5V54.3223C64 51.3829 66.3829 49 69.3223 49C72.2617 49 74.6445 51.3829 74.6445 54.3223V69.3223Z" fill="#0D9488"/>
                                <path d="M84.3223 79V79.5H87V79V78.5H84.3223V79ZM87 65V64.5H85V65V65.5H87V65ZM85 49V49.5H89.8115V49V48.5H85V49ZM95.3115 54.5H94.8115V59.5H95.3115H95.8115V54.5H95.3115ZM94.3115 59.5H94.8115V55H94.3115H93.8115V59.5H94.3115ZM89.3115 50V49.5H85V50V50.5H89.3115V50ZM85 64V64.5H87V64V63.5H85V64ZM87 80V79.5H83.6445V80V80.5H87V80ZM73.6445 70H74.1445V54.3223H73.6445H73.1445V70H73.6445ZM65 54.3223H64.5V63.5H65H65.5V54.3223H65ZM64 63.5H64.5V54.3223H64H63.5V63.5H64ZM74.6445 54.3223H74.1445V69.3223H74.6445H75.1445V54.3223H74.6445ZM69.3223 49V49.5C71.9855 49.5 74.1445 51.659 74.1445 54.3223H74.6445H75.1445C75.1445 51.1067 72.5378 48.5 69.3223 48.5V49ZM64 54.3223H64.5C64.5 51.659 66.659 49.5 69.3223 49.5V49V48.5C66.1067 48.5 63.5 51.1067 63.5 54.3223H64ZM64.5 64V63.5V63.5H64H63.5C63.5 64.0523 63.9477 64.5 64.5 64.5V64ZM65 63.5H64.5V63.5V64V64.5C65.0523 64.5 65.5 64.0523 65.5 63.5H65ZM69.3223 50V49.5C66.659 49.5 64.5 51.659 64.5 54.3223H65H65.5C65.5 52.2113 67.2113 50.5 69.3223 50.5V50ZM73.6445 54.3223H74.1445C74.1445 51.659 71.9855 49.5 69.3223 49.5V50V50.5C71.4332 50.5 73.1445 52.2113 73.1445 54.3223H73.6445ZM83.6445 80V79.5C78.3978 79.5 74.1445 75.2467 74.1445 70H73.6445H73.1445C73.1445 75.799 77.8455 80.5 83.6445 80.5V80ZM95 72H94.5C94.5 76.1421 91.1421 79.5 87 79.5V80V80.5C91.6944 80.5 95.5 76.6944 95.5 72H95ZM87 64V64.5C91.1421 64.5 94.5 67.8579 94.5 72H95H95.5C95.5 67.3056 91.6944 63.5 87 63.5V64ZM78 57H77.5C77.5 61.1421 80.8579 64.5 85 64.5V64V63.5C81.4101 63.5 78.5 60.5899 78.5 57H78ZM85 50V49.5C80.8579 49.5 77.5 52.8579 77.5 57H78H78.5C78.5 53.4101 81.4101 50.5 85 50.5V50ZM94.3115 55H94.8115C94.8115 51.9624 92.3491 49.5 89.3115 49.5V50V50.5C91.7968 50.5 93.8115 52.5147 93.8115 55H94.3115ZM94.8115 60V59.5V59.5H94.3115H93.8115C93.8115 60.0523 94.2592 60.5 94.8115 60.5V60ZM95.3115 59.5H94.8115V59.5V60V60.5C95.3638 60.5 95.8115 60.0523 95.8115 59.5H95.3115ZM89.8115 49V49.5C92.5729 49.5 94.8115 51.7386 94.8115 54.5H95.3115H95.8115C95.8115 51.1863 93.1252 48.5 89.8115 48.5V49ZM77 57H77.5C77.5 52.8579 80.8579 49.5 85 49.5V49V48.5C80.3056 48.5 76.5 52.3056 76.5 57H77ZM85 65V64.5C80.8579 64.5 77.5 61.1421 77.5 57H77H76.5C76.5 61.6944 80.3056 65.5 85 65.5V65ZM94 72H94.5C94.5 67.8579 91.1421 64.5 87 64.5V65V65.5C90.5899 65.5 93.5 68.4101 93.5 72H94ZM87 79V79.5C91.1421 79.5 94.5 76.1421 94.5 72H94H93.5C93.5 75.5899 90.5899 78.5 87 78.5V79ZM84.3223 79V78.5C79.2535 78.5 75.1445 74.391 75.1445 69.3223H74.6445H74.1445C74.1445 74.9433 78.7013 79.5 84.3223 79.5V79Z" fill="#0D9488"/>
                            </svg>
                            <span id="sidebar-logo-title" class="font-bold text-white text-base tracking-wider sidebar-link-text">IMS Portal</span>
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
                                    <span class="sidebar-link-text">Admin Dashboard</span>
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
