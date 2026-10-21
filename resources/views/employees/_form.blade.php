@php $e = $employee ?? null; @endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">First Name *</label>
        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror"
               value="{{ old('firstname', $e->firstname ?? '') }}">
        @error('firstname') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Last Name *</label>
        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror"
               value="{{ old('lastname', $e->lastname ?? '') }}">
        @error('lastname') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Date of Birth *</label>
        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
               value="{{ old('date_of_birth', $e ? $e->date_of_birth->format('Y-m-d') : '') }}">
        @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Education Qualification *</label>
        <select name="education_qualification" class="form-select @error('education_qualification') is-invalid @enderror">
            <option value="">-- Select --</option>
            @foreach($qualifications as $q)
                <option value="{{ $q }}" @selected(old('education_qualification', $e->education_qualification ?? '') === $q)>{{ $q }}</option>
            @endforeach
        </select>
        @error('education_qualification') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Email *</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $e->email ?? '') }}">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Phone (10 digits) *</label>
        <input type="text" name="phone" maxlength="10" class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $e->phone ?? '') }}">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label class="form-label">Address *</label>
        <textarea name="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $e->address ?? '') }}</textarea>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Photo (jpg, png, max 2MB)</label>
        <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
        @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if($e && $e->photo)
            <img src="{{ asset('storage/' . $e->photo) }}" class="mt-2 rounded" height="80" alt="current photo">
            <div class="form-text">Leave empty to keep the current photo.</div>
        @endif
    </div>

    <div class="col-md-6">
        <label class="form-label">Resume (pdf, doc, docx, max 5MB)</label>
        <input type="file" name="resume" accept=".pdf,.doc,.docx" class="form-control @error('resume') is-invalid @enderror">
        @error('resume') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if($e && $e->resume)
            <div class="form-text">
                Current: <a href="{{ asset('storage/' . $e->resume) }}" target="_blank">View resume</a>
                (leave empty to keep)
            </div>
        @endif
    </div>
</div>