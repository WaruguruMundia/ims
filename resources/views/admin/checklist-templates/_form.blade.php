@php $template ??= null; @endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
    <select name="dept_id" class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="">Global (applies to all departments)</option>
        @foreach ($departments as $dept)
            <option value="{{ $dept->id }}" @selected(old('dept_id', $template?->dept_id) == $dept->id)>
                {{ $dept->name }}
            </option>
        @endforeach
    </select>
    @error('dept_id') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Item Text</label>
    <input type="text" name="item_text" value="{{ old('item_text', $template?->item_text) }}"
           class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('item_text') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
    <input type="number" name="display_order" value="{{ old('display_order', $template?->display_order ?? 0) }}"
           class="w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    @error('display_order') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
</div>

<div class="flex items-center">
    <input type="hidden" name="is_required" value="0">
    <input type="checkbox" name="is_required" value="1" id="is_required"
           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
        @checked(old('is_required', $template?->is_required ?? true))>
    <label for="is_required" class="ml-2 text-sm text-gray-700">Required item</label>
</div>
