@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="container" style="max-width:700px; margin-top:25px;">

    <div class="card shadow-sm">

        <div class="card-body">

            <h4 class="mb-3">Edit Profile</h4>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif


            {{-- PROFILE PHOTO --}}
            <div class="text-center mb-4">

                <img src="{{ $user->photo ? asset('uploads/'.$user->photo) : asset('assets/user.png') }}"
                     style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid #eee;">

                <form action="{{ route('applicant.upload.photo') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="mt-2">
                    @csrf

                    <input type="file" name="photo" required class="form-control mb-2">
                    <button class="btn btn-sm btn-primary">Upload Photo</button>
                </form>

            </div>


            {{-- EDIT PROFILE FORM --}}
            <form method="POST" action="{{ route('applicant.profile.update') }}">
                @csrf

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ $user->name }}"
                           required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ $user->email }}"
                           required>
                </div>

                <button class="btn btn-success">
                    Save Changes
                </button>

                <a href="{{ route('applicant.profile') }}"
                   class="btn btn-secondary">
                   Back
                </a>

            </form>

        </div>

    </div>

</div>

@endsection