{{-- resources/views/intern/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Intern Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                Logged in as: {{ auth()->user()->name }}
                ({{ auth()->user()->role->name }})
            </div>
        </div>
    </div>
</x-app-layout>
