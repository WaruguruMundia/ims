<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Logbook Entry') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('intern.logbook.update', $logbook) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Entry Date (Disabled/Read-only) -->
                            <div>
                                <x-input-label for="entry_date" :value="__('Entry Date')" />
                                <x-text-input id="entry_date" class="block mt-1 w-full bg-gray-100 cursor-not-allowed" type="date" name="entry_date" :value="$logbook->entry_date->format('Y-m-d')" disabled />
                            </div>

                            <!-- Entry Type -->
                            <div>
                                <x-input-label for="entry_type" :value="__('Entry Type')" />
                                <select id="entry_type" name="entry_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="daily" {{ old('entry_type', $logbook->entry_type) === 'daily' ? 'selected' : '' }}>Daily Log</option>
                                    <option value="weekly" {{ old('entry_type', $logbook->entry_type) === 'weekly' ? 'selected' : '' }}>Weekly Summary</option>
                                </select>
                                <x-input-error :messages="$errors->get('entry_type')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Activities Performed -->
                        <div>
                            <x-input-label for="activities_performed" :value="__('Activities Performed')" />
                            <textarea id="activities_performed" name="activities_performed" rows="4" placeholder="Detail the duties and tasks worked on today..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('activities_performed', $logbook->activities_performed) }}</textarea>
                            <x-input-error :messages="$errors->get('activities_performed')" class="mt-2" />
                        </div>

                        <!-- Challenges Encountered -->
                        <div>
                            <x-input-label for="challenges_encountered" :value="__('Challenges Encountered (Optional)')" />
                            <textarea id="challenges_encountered" name="challenges_encountered" rows="3" placeholder="Outline any blockers, system issues, or technical challenges faced..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('challenges_encountered', $logbook->challenges_encountered) }}</textarea>
                            <x-input-error :messages="$errors->get('challenges_encountered')" class="mt-2" />
                        </div>

                        <!-- Skills Developed -->
                        <div>
                            <x-input-label for="skills_developed" :value="__('Skills Developed / Learning points (Optional)')" />
                            <textarea id="skills_developed" name="skills_developed" rows="3" placeholder="Explain what technical or soft skills you acquired or improved..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('skills_developed', $logbook->skills_developed) }}</textarea>
                            <x-input-error :messages="$errors->get('skills_developed')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('intern.logbook.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
