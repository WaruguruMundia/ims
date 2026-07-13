<x-app-layout>
    <div class="container py-4">
        <h2>Register New Intern</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.interns.store') }}" method="POST" class="mt-3">
            @csrf

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="dept_id" class="form-select">
                    <option value="">-- Select --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(old('dept_id') == $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Supervisor</label>
                <select name="supervisor_id" class="form-select">
                    <option value="">-- Select --</option>
                    @foreach ($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}" @selected(old('supervisor_id') == $supervisor->id)>{{ $supervisor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Institution</label>
                <input type="text" name="institution" class="form-control" value="{{ old('institution') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Programme</label>
                <input type="text" name="programme" class="form-control" value="{{ old('programme') }}">
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                </div>
                <div class="col mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Register Intern</button>
        </form>
    </div>
</x-app-layout>
