<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Municipal Building Permit System</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
body{background:#f1f5f9;color:#1e293b;}

#sidebar{
position:fixed;top:0;left:0;width:250px;height:100vh;
background:linear-gradient(180deg,#0b3d91,#0f172a);
color:#fff;padding-top:20px;transition:.3s;z-index:1000;
}
#sidebar.hide{width:80px;}
.brand{display:flex;align-items:center;gap:12px;padding:0 20px 25px;border-bottom:1px solid rgba(255,255,255,.2);}
.brand img{width:45px;height:45px;object-fit:contain;}
.brand-text{font-size:15px;font-weight:600;line-height:1.2;}
#sidebar.hide .brand-text{display:none;}

#sidebar a{
display:flex;align-items:center;gap:12px;
padding:12px 20px;margin:6px 12px;
color:#cbd5e1;text-decoration:none;
border-radius:8px;font-size:14px;transition:.2s;
}
#sidebar a:hover{background:rgba(255,255,255,.15);color:#fff;}
#sidebar a.active{background:#fff;color:#0b3d91;font-weight:600;}
#sidebar.hide a span{display:none;}

#content{margin-left:250px;transition:.3s;}
#sidebar.hide ~ #content{margin-left:80px;}

nav{
position:fixed;
top:0;
left:250px;
right:0;
height:70px;
background:#fff;
display:flex;
align-items:center;
justify-content:space-between;
padding:0 30px;
box-shadow:0 3px 12px rgba(0,0,0,.05);
z-index:900;
transition:.3s;
}
#sidebar.hide ~ #content nav{
left:80px;
}

.nav-left{display:flex;align-items:center;gap:15px;}
.toggle{font-size:24px;cursor:pointer;color:#0b3d91;}
.system-title{font-size:17px;font-weight:600;color:#0b3d91;}

.profile-box{position:relative;}
.profile-btn{
display:flex;align-items:center;gap:8px;
cursor:pointer;background:#f1f5f9;
padding:6px 12px;border-radius:8px;
}
.profile-img{width:32px;height:32px;border-radius:50%;object-fit:cover;}
.profile-dropdown{
position:absolute;right:0;top:45px;width:180px;background:#fff;
border-radius:10px;box-shadow:0 6px 20px rgba(0,0,0,.15);
display:none;overflow:hidden;z-index:9999;
}
.profile-dropdown a,
.profile-dropdown button{
width:100%;padding:10px 14px;
display:flex;align-items:center;gap:8px;
border:none;background:none;text-decoration:none;
color:#1e293b;font-size:14px;cursor:pointer;
}
.profile-dropdown a:hover,
.profile-dropdown button:hover{background:#f1f5f9;}

main{padding:35px;margin-top:70px;}
</style>
</head>

<body>

@php
$role = auth()->user()->role;
@endphp

<section id="sidebar">

<div class="brand">
<img src="{{ asset('assets/jasaan.png') }}">
<div class="brand-text">Building Permit<br>System</div>
</div>

<a href="{{ route($role.'.dashboard') }}"
class="{{ request()->routeIs($role.'.dashboard')?'active':'' }}">
<i class='bx bxs-dashboard'></i><span>Dashboard</span>
</a>

@if($role=='admin' || $role=='mpdo' || $role=='meo' || $role=='bfp')
<a href="{{ route($role.'.applications') }}">
<i class='bx bxs-file'></i><span>Applications</span>
</a>
@endif

@if($role=='applicant')

<a href="{{ route('applicant.upload_mpdo') }}">
<i class='bx bx-upload'></i><span>MPDO</span>
</a>

<a href="{{ route('applicant.upload_meo') }}">
<i class='bx bx-upload'></i><span>MEO</span>
</a>

<a href="{{ route('applicant.upload_bfp') }}">
<i class='bx bx-upload'></i><span>BFP</span>
</a>

<a href="{{ route('applicant.view_documents') }}">
<i class='bx bx-folder'></i><span>View Documents</span>
</a>

<a href="{{ route('applicant.track') }}">
<i class='bx bx-map'></i><span>Track Application</span>
</a>

@endif

@if(Route::has($role.'.reports'))
<a href="{{ route($role.'.reports') }}">
<i class='bx bx-bar-chart'></i><span>Reports / Analytics</span>
</a>
@endif

@if(Route::has($role.'.payments'))
<a href="{{ route($role.'.payments') }}">
<i class='bx bx-money'></i><span>Payments / Assessment</span>
</a>
@endif

</section>

<section id="content">

<nav>
<div class="nav-left">
<i class='bx bx-menu toggle'></i>
<div class="system-title">Municipal Building Permit System</div>
</div>

<div class="profile-box">
<div class="profile-btn" id="profileBtn">

@if(Auth::user()->photo)

<img src="{{ asset('profile_photos/'.Auth::user()->photo) }}" class="profile-img">

@else

<img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" class="profile-img">

@endif
<span>{{ auth()->user()->name }}</span>
<i class='bx bx-chevron-down'></i>
</div>

<div class="profile-dropdown" id="profileMenu">

<a href="{{ route('profile.edit') }}">
<i class='bx bx-user'></i> Edit Profile
</a>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit">
<i class='bx bx-log-out'></i> Logout
</button>
</form>

</div>
</div>
</nav>

<main>

{{-- ALERT MESSAGES --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
{{ session('success') }}
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
{{ session('error') }}
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show">
{{ session('warning') }}
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@yield('content')

</main>

</section>

<script>
document.addEventListener("DOMContentLoaded", function(){

const toggle=document.querySelector('.toggle');
const sidebar=document.getElementById('sidebar');
toggle.onclick=()=>sidebar.classList.toggle('hide');

const btn=document.getElementById("profileBtn");
const menu=document.getElementById("profileMenu");

btn.onclick=(e)=>{
e.stopPropagation();
menu.style.display=menu.style.display==="block"?"none":"block";
};

document.addEventListener("click",()=>menu.style.display="none");

});
</script>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>