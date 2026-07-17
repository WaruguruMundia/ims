<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisee Onboarding') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        My Interns
                    </h3>

                    @if ($interns->isEmpty())
                        <p class="text-gray-600">
                            You do not currently have assigned interns.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Intern</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Department</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Progress</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Required Complete</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach ($interns as $intern)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ $intern->user?->name }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $intern->department?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $intern->onboardingProgressPercentage() }}%
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            @if ($intern->hasCompletedRequiredOnboarding())
                                                <span class="text-green-700">Yes</span>
                                            @else
                                                <span class="text-yellow-700">No</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @foreach ($intern->onboardingChecklists as $item)
                                        <tr class="bg-gray-50">
                                            <td colspan="2" class="px-8 py-2 text-sm text-gray-700">
                                                {{ $item->item }}
                                            </td>
                                            <td class="px-4 py-2 text-sm">
                                                {{ $item->is_completed ? 'Completed' : 'Pending' }}
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
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
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
