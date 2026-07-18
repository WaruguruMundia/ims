<div class="space-y-4">
    <div>
        <label for="dept_id" class="block text-sm font-medium text-gray-700">
            Department
        </label>

        <select name="dept_id" id="dept_id" class="mt-1 block w-full rounded border-gray-300">
            <option value="">Global</option>

            @foreach ($departments as $department)
                <option
                    value="{{ $department->id }}"
                    @selected(old('dept_id', $checklistTemplate->dept_id ?? null) == $department->id)
                >
                    {{ $department->name }}
                </option>
            @endforeach
        </select>

        @error('dept_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="item_text" class="block text-sm font-medium text-gray-700">
            Item Text
        </label>

        <input
            type="text"
            name="item_text"
            id="item_text"
            value="{{ old('item_text', $checklistTemplate->item_text ?? '') }}"
            class="mt-1 block w-full rounded border-gray-300"
            required
        >

        @error('item_text')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="display_order" class="block text-sm font-medium text-gray-700">
            Display Order
        </label>

        <input
            type="number"
            name="display_order"
            id="display_order"
            value="{{ old('display_order', $checklistTemplate->display_order ?? 0) }}"
            class="mt-1 block w-full rounded border-gray-300"
            min="0"
            required
        >

        @error('display_order')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center space-x-2">
        <input
            type="checkbox"
            name="is_required"
            id="is_required"
            value="1"
            @checked(old('is_required', $checklistTemplate->is_required ?? true))
        >

        <label for="is_required" class="text-sm text-gray-700">
            Required
        </label>
    </div>

    <div class="flex items-center space-x-2">
        <input
            type="checkbox"
            name="is_active"
            id="is_active"
            value="1"
            @checked(old('is_active', $checklistTemplate->is_active ?? true))
        >

        <label for="is_active" class="text-sm text-gray-700">
            Active
        </label>
    </div>
</div>
