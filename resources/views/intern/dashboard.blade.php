<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Onboarding') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Onboarding Progress
                    </h3>

                    <p class="mt-2 text-gray-700">
                        {{ $intern->onboardingProgressPercentage() }}% complete
                    </p>

                    <div class="mt-4 w-full bg-gray-200 rounded-full h-3">
                        <div
                            class="bg-blue-600 h-3 rounded-full"
                            style="width: {{ $intern->onboardingProgressPercentage() }}%"
                        ></div>
                    </div>

                    @if ($intern->hasCompletedRequiredOnboarding())
                        <p class="mt-3 text-green-700">
                            All required onboarding items are complete.
                        </p>
                    @else
                        <p class="mt-3 text-yellow-700">
                            Some required onboarding items are still pending.
                        </p>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        My Checklist
                    </h3>

                    @if ($intern->onboardingChecklists->isEmpty())
                        <p class="text-gray-600">
                            No onboarding checklist items have been assigned yet.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Item</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Completed At</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Action</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($intern->onboardingChecklists as $item)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ $item->item }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $item->is_required ? 'Yes' : 'No' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @if ($item->is_completed)
                                                <span class="text-green-700">Completed</span>
                                            @else
                                                <span class="text-yellow-700">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $item->completed_at?->format('Y-m-d H:i') ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @can('complete', $item)
                                                @if (! $item->is_completed)
                                                    <form method="POST" action="{{ route('onboarding-checklists.complete', $item) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit" class="text-blue-600 hover:text-blue-800">
                                                            Mark complete
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('onboarding-checklists.reopen', $item) }}">
                                                        @csrf
                                                        @method('PATCH')

                                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                                            Reopen
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-gray-500">Not allowed</span>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
