<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign New Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('supervisor.tasks.store') }}" class="space-y-6">
                        @csrf

                        <!-- Intern Selection -->
                        <div>
                            <x-input-label for="intern_id" :value="__('Select Intern')" />
                            <select id="intern_id" name="intern_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Choose an Intern --</option>
                                @foreach ($interns as $intern)
                                    <option value="{{ $intern->id }}" {{ old('intern_id', $selectedInternId) == $intern->id ? 'selected' : '' }}>
                                        {{ $intern->user?->name }} ({{ $intern->department?->name ?? 'No Dept' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('intern_id')" class="mt-2" />
                        </div>

                        <!-- Task Title -->
                        <div>
                            <x-input-label for="title" :value="__('Task Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Task Description -->
                        <div>
                            <x-input-label for="description" :value="__('Task Description')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Priority -->
                            <div>
                                <x-input-label for="priority" :value="__('Priority')" />
                                <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="low" {{ old('priority', 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', 'medium') === 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            </div>

                            <!-- Due Date -->
                            <div>
                                <x-input-label for="due_date" :value="__('Due Date')" />
                                <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date')" required />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Expected Deliverables -->
                        <div>
                            <x-input-label for="deliverable_notes" :value="__('Expected Deliverables')" />
                            <textarea id="deliverable_notes" name="deliverable_notes" rows="3" placeholder="Describe what the intern needs to submit to complete this task (e.g. GitHub link, code documentation, report)..." class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deliverable_notes') }}</textarea>
                            <x-input-error :messages="$errors->get('deliverable_notes')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('supervisor.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Assign Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
