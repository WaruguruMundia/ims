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
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Dark & Modern Sleek Theme Styles (Teal & Sapphire Palette) -->
        <style>
            :root {
                --color-canvas-workspace: #F8FAFC;
                --color-surface-card: #FFFFFF;
                --color-accent-primary: #0D9488;
            }

            .dark {
                --color-canvas-workspace: #030712;
                --color-surface-card: #0F172A;
            }

            body {
                background-color: var(--color-canvas-workspace) !important;
                color: #334155 !important;
            }
            .bg-gray-100 {
                background-color: var(--color-canvas-workspace) !important;
            }
            .bg-white {
                background-color: var(--color-surface-card) !important;
            }

            .dark input {
                background-color: #1E293B !important;
                border-color: #334155 !important;
                color: #F8FAFC !important;
            }
            
            /* Dark mode text visibility overrides */
            .dark label, .dark span, .dark a {
                color: #CBD5E1 !important;
            }
            .dark a:hover {
                color: #FFFFFF !important;
            }

            /* Replace all traces of Indigo buttons and focuses with Teal */
            .bg-indigo-650, .bg-indigo-600, .bg-blue-600 {
                background-color: var(--color-accent-primary) !important;
            }
            .bg-indigo-650:hover, .bg-indigo-600:hover, .bg-blue-600:hover {
                background-color: #0F766E !important; /* Teal 700 */
            }
            .text-indigo-600, .text-blue-600 {
                color: var(--color-accent-primary) !important;
            }
            .focus\:border-indigo-500:focus, .focus\:ring-indigo-500:focus {
                border-color: var(--color-accent-primary) !important;
                --tw-ring-color: var(--color-accent-primary) !important;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div class="mb-4">
                <a href="/">
                    <span class="text-3xl font-black tracking-wider text-teal-600 dark:text-teal-400 select-none">IMS Portal</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
