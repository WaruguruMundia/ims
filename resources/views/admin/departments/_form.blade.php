@php $department ??= null; @endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
    <input type="text" name="name" value="{{ old('name', $department?->name) }}"
           class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Code (optional)</label>
    <input type="text" name="code" value="{{ old('code', $department?->code) }}"
           class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('code') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
</div>

<div class="flex items-center">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" id="is_active"
           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
        @checked(old('is_active', $department?->is_active ?? true))>
    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
</div>
