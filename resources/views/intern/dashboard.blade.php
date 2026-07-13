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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="font-semibold text-lg mb-4">Onboarding Checklist</h3>

                @foreach ($intern->onboardingChecklists as $checklistItem)
                    <div class="flex items-center justify-between border-b py-3">
                        <span class="{{ $checklistItem->is_completed ? 'line-through text-gray-400' : 'text-gray-800' }}">
                            {{ $checklistItem->item }}
                            @if ($checklistItem->is_required)
                                <span class="text-xs text-red-500 ml-1">(required)</span>
                            @endif
                        </span>

                        @can('complete', $checklistItem)
                            @if (!$checklistItem->is_completed)
                                <form method="POST" action="{{ route('onboarding-checklist.complete', $checklistItem) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            class="px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                                        Mark Complete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-500">
                                    Completed {{ $checklistItem->completed_at->diffForHumans() }}
                                    by {{ $checklistItem->completedBy->name }}
                                </span>
                            @endif
                        @endcan
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
