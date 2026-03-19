@extends('adminlte::page')

@section('content')

<div class="container mt-4" style="max-width:700px;">

<div class="card shadow-sm p-4">

<h4 class="mb-3">Edit Profile</h4>

@if(session('success'))

<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- ================= PROFILE PHOTO ================= --}}

<div class="text-center mb-4">

@if(auth()->user()->photo)

<img id="preview"
src="{{ asset('profile_photos/'.auth()->user()->photo) }}"
style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #ddd;">

@else

<img id="preview"
src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:3px solid #ddd;">

@endif

<form method="POST"
action="{{ route('profile.uploadPhoto') }}"
enctype="multipart/form-data"
class="mt-3">

@csrf

<input type="file"
name="photo"
accept="image/*"
onchange="previewImage(event)"
class="form-control mb-2">

<button class="btn btn-primary btn-sm">
Upload Photo
</button>

</form>

</div>

{{-- ================= PROFILE INFO ================= --}}

<form method="POST"
action="{{ route('profile.update') }}">

@csrf

<div class="mb-3">
<label>Name</label>
<input type="text"
name="name"
value="{{ auth()->user()->name }}"
class="form-control">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email"
name="email"
value="{{ auth()->user()->email }}"
class="form-control">
</div>

<div class="mb-3">
<label>Contact Number</label>
<input type="text"
name="contact_number"
value="{{ auth()->user()->contact_number }}"
class="form-control">
</div>

<div class="mb-3">
<label>Address</label>
<input type="text"
name="address"
value="{{ auth()->user()->address }}"
class="form-control">
</div>

<div class="mb-3">
<label>Occupation</label>
<input type="text"
name="occupation"
value="{{ auth()->user()->occupation }}"
class="form-control">
</div>

<div class="mb-3">
<label>Gender</label>
<select name="gender" class="form-control">
<option value="">Select Gender</option>
<option value="Male" {{ auth()->user()->gender == 'Male' ? 'selected' : '' }}>Male</option>
<option value="Female" {{ auth()->user()->gender == 'Female' ? 'selected' : '' }}>Female</option>
</select>
</div>

<button class="btn btn-success">
Save Changes
</button>

</form>

</div>
</div>

<script>
function previewImage(event)
{
const reader = new FileReader();

reader.onload = function(){
document.getElementById('preview').src = reader.result;
}

reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection
