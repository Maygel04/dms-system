@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<h2>Create Verifier Account</h2>

@if(session('success'))
<div style="color:green">{{ session('success') }}</div>
@endif

<form method="POST" action="/admin/create-user">
@csrf

<input type="text" name="name" placeholder="Name" required><br><br>
<input type="email" name="email" placeholder="Email" required><br><br>
<input type="password" name="password" placeholder="Password" required><br><br>

<select name="role" required>
    <option value="">Select Role</option>
    <option value="admin">Admin</option>
    <option value="mpdo">MPDO</option>
    <option value="meo">MEO</option>
    <option value="bfp">BFP</option>
</select>

<br><br>
<button>Create Account</button>

</form>

@endsection