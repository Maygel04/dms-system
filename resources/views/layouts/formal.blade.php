<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Municipal Building Permit System</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
background:#f1f5f9;
color:#1e293b;
}

/* SIDEBAR */

#sidebar{
position:fixed;
top:0;
left:0;
width:250px;
height:100vh;
background:linear-gradient(180deg,#0b3d91,#0f172a);
color:#fff;
padding-top:20px;
z-index:999;
}

.brand{
display:flex;
align-items:center;
gap:12px;
padding:0 20px 25px;
border-bottom:1px solid rgba(255,255,255,.2);
}

.brand img{
width:45px;
height:45px;
object-fit:contain;
}

.brand-text{
font-size:15px;
font-weight:600;
line-height:1.2;
}

#sidebar a{
display:flex;
align-items:center;
gap:12px;
padding:12px 20px;
margin:6px 12px;
color:#cbd5e1;
text-decoration:none;
border-radius:8px;
font-size:14px;
transition:.2s;
}

#sidebar a:hover{
background:rgba(255,255,255,.15);
color:#fff;
}

/* CONTENT */

#content{
margin-left:250px;
min-height:100vh;
}

/* NAVBAR */

nav{
height:70px;
background:#fff;
display:flex;
align-items:center;
justify-content:space-between;
padding:0 30px;
box-shadow:0 3px 12px rgba(0,0,0,.05);
}

.system-title{
font-size:17px;
font-weight:600;
color:#0b3d91;
}

.nav-right{
display:flex;
align-items:center;
gap:20px;
}

.profile-box{
position:relative;
}

.profile-btn{
display:flex;
align-items:center;
gap:8px;
cursor:pointer;
background:#f1f5f9;
padding:6px 12px;
border-radius:8px;
}

.profile-img{
width:32px;
height:32px;
border-radius:50%;
object-fit:cover;
}

.profile-dropdown{
position:absolute;
right:0;
top:45px;
width:180px;
background:#fff;
border-radius:10px;
box-shadow:0 6px 20px rgba(0,0,0,.15);
display:none;
overflow:hidden;
}

.profile-dropdown a,
.profile-dropdown button{
width:100%;
padding:10px 14px;
display:flex;
align-items:center;
gap:8px;
border:none;
background:none;
text-decoration:none;
color:#1e293b;
font-size:14px;
cursor:pointer;
}

.profile-dropdown a:hover,
.profile-dropdown button:hover{
background:#f1f5f9;
}

main{
padding:35px;
}

</style>

</head>

<body>

@php
$app = \App\Models\Application::where('applicant_id', auth()->id())->latest()->first();

$mpdoVerified = false;
$meoPaid = false;

if($app){
    $mpdoVerified = $app->mpdo_status === 'verified';
    $meoPaid = $app->meo_paid == 1;
}
@endphp

<!-- SIDEBAR -->

<section id="sidebar">

<div class="brand">
<img src="{{ asset('assets/jasaan.png') }}">
<div class="brand-text">Building Permit<br>System</div>
</div>

<a href="{{ url('/applicant/dashboard') }}">
<i class='bx bxs-dashboard'></i>
<span>Dashboard</span>
</a>

<a href="{{ url('/applicant/upload_mpdo') }}">
<i class='bx bxs-file'></i>
<span>MPDO</span>
</a>

@if($mpdoVerified) <a href="{{ url('/applicant/upload_meo') }}"> <i class='bx bxs-file'></i> <span>MEO</span> </a>
@else <a href="javascript:void(0)" onclick="alert('Finish MPDO verification first')" style="opacity:.5;"> <i class='bx bxs-lock'></i> <span>MEO (Locked)</span> </a>
@endif

@if($meoPaid)
<a href="{{ url('/applicant/upload_bfp') }}">
<i class='bx bxs-file'></i>
<span>BFP</span>
</a>
@else
<a href="javascript:void(0)" onclick="alert('Finish MEO payment first')" style="opacity:.5;">
<i class='bx bxs-lock'></i>
<span>BFP (Locked)</span>
</a>
@endif

<a href="{{ url('/applicant/view_documents') }}">
<i class='bx bxs-file'></i>
<span>View Documents</span>
</a>

<a href="{{ url('/applicant/track') }}">
<i class='bx bxs-map'></i>
<span>Track Application</span>
</a>

</section>

<!-- CONTENT -->

<section id="content">

<nav>

<div class="system-title">
Municipal Building Permit System
</div>

<div class="nav-right">

<div class="profile-box">

<div class="profile-btn" id="profileBtn">

@if(auth()->user()->photo)

<img
src="{{ asset('storage/profile/'.auth()->user()->photo) }}"
class="profile-img">

@else

<img
src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}"
class="profile-img">

@endif

<span>{{ auth()->user()->name }}</span>

</div>

<div class="profile-dropdown" id="profileMenu">

<a href="{{ route('profile.edit') }}">
<i class='bx bx-user'></i>
Edit Profile
</a>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit">
<i class='bx bx-log-out'></i>
Logout
</button>
</form>

</div>

</div>

</div>

</nav>

<main>

@yield('content')

</main>

</section>

<script>

document.getElementById("profileBtn").onclick = function(e){

e.stopPropagation();

let menu = document.getElementById("profileMenu");

menu.style.display = menu.style.display === "block" ? "none" : "block";

};

document.addEventListener("click", function(){

document.getElementById("profileMenu").style.display = "none";

});

</script>

</body>
</html>
