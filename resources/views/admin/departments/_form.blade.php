@php $department ??= null; @endphp

<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $department?->name) }}">
    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Code (optional)</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $department?->code) }}">
    @error('code') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="form-check">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
        @checked(old('is_active', $department?->is_active ?? true))>
    <label class="form-check-label" for="is_active">Active</label>
</div>
