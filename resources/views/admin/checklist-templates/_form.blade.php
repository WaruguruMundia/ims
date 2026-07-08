@php $template ??= null; @endphp

<div class="mb-3">
    <label class="form-label">Department</label>
    <select name="dept_id" class="form-select">
        <option value="">Global (applies to all departments)</option>
        @foreach ($departments as $dept)
            <option value="{{ $dept->id }}" @selected(old('dept_id', $template?->dept_id) == $dept->id)>
                {{ $dept->name }}
            </option>
        @endforeach
    </select>
    @error('dept_id') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Item Text</label>
    <input type="text" name="item_text" class="form-control" value="{{ old('item_text', $template?->item_text) }}">
    @error('item_text') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Display Order</label>
    <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $template?->display_order ?? 0) }}">
    @error('display_order') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-check">
    <input type="hidden" name="is_required" value="0">
    <input type="checkbox" name="is_required" value="1" class="form-check-input" id="is_required"
        @checked(old('is_required', $template?->is_required ?? true))>
    <label class="form-check-label" for="is_required">Required item</label>
</div>
