@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection

@section('content')

<div class="mb-4">
    <h4 class="fw-bold">📋 Applicants</h4>
    <p class="text-muted">View and manage building permit applicants</p>
</div>
<!-- ================= APPLICANT INFO ================= -->
<div style="
background:#fff;
padding:20px;
border-radius:10px;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
margin-bottom:20px;
">

<h3 style="font-size:16px;font-weight:600;margin-bottom:15px;">
Applicant Information
</h3>

<div style="
display:grid;
grid-template-columns:repeat(2,1fr);
gap:12px;
font-size:14px;
color:#334155;
">

<div><b>Contact:</b><br>{{ $user->contact_number }}</div>
<div><b>Address:</b><br>{{ $user->address }}</div>
<div><b>Gender:</b><br>{{ $user->gender }}</div>
<div><b>Occupation:</b><br>{{ $user->occupation }}</div>

</div>
</div>


<!-- ================= APPLICATION FLOW ================= -->
<div style="
background:#fff;
padding:20px;
border-radius:10px;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
margin-bottom:20px;
">

<h3 style="font-size:16px;font-weight:600;margin-bottom:18px;">
Application Flow
</h3>

<div style="display:flex;gap:15px;">

<div style="flex:1;background:#e0f2fe;padding:15px;border-radius:8px;text-align:center;">
<div style="font-weight:600;">STEP 1</div>
<div style="font-size:15px;font-weight:600;margin-top:3px;">MPDO</div>
<div style="font-size:12px;color:#475569;">
Municipal Planning and Development Office
</div>
</div>

<div style="flex:1;background:#dcfce7;padding:15px;border-radius:8px;text-align:center;">
<div style="font-weight:600;">STEP 2</div>
<div style="font-size:15px;font-weight:600;margin-top:3px;">MEO</div>
<div style="font-size:12px;color:#475569;">
Municipal Engineering Office
</div>
</div>

<div style="flex:1;background:#fee2e2;padding:15px;border-radius:8px;text-align:center;">
<div style="font-weight:600;">STEP 3</div>
<div style="font-size:15px;font-weight:600;margin-top:3px;">BFP</div>
<div style="font-size:12px;color:#475569;">
Bureau of Fire Protection
</div>
</div>

</div>
</div>


@php

$application = DB::table('applications')
->where('applicant_id',auth()->id())
->latest()
->first();

if(!$application){
$application = (object)[
'id' => 0,
'mpdo_status' => 'pending',
'meo_status' => 0,
'meo_verified' => 0,
'meo_endorsed' => 0,
'meo_paid' => 0,
'bfp_verified' => 0,
'bfp_paid' => 0
];
}

$appId = $application->id ?? 0;


/* ================= DOCUMENT COUNT ================= */

$mpdoDocs = DB::table('documents')
->where('application_id',$appId)
->where('department','mpdo')
->count();

$meoDocs = DB::table('documents')
->where('application_id',$appId)
->where('department','meo')
->count();

$bfpDocs = DB::table('documents')
->where('application_id',$appId)
->where('department','bfp')
->count();


/* ================= STATUS ================= */

$mpdoStatus = $application->mpdo_status ?? 'pending';

$meoStatus = $application->meo_status ?? 'pending';
$meoEndorsed = $application->meo_endorsed ?? 0;
$meoPaid = $application->meo_paid ?? 0;

$bfpStatus = $application->bfp_status ?? 'pending';
$bfpIssued = $application->permit_issued ?? 0;
$bfpPaid = $application->bfp_paid ?? 0;


/* ================= REMARKS ================= */

$mpdoRemark = DB::table('remarks')
->where('application_id',$appId)
->where('department','mpdo')
->latest()
->first();

$meoRemark = DB::table('remarks')
->where('application_id',$appId)
->where('department','meo')
->latest()
->first();

$bfpRemark = DB::table('remarks')
->where('application_id',$appId)
->where('department','bfp')
->latest()
->first();

@endphp



<div style="
background:#fff;
padding:25px;
border-radius:10px;
box-shadow:0 2px 6px rgba(0,0,0,0.05);
text-align:center;
">

<h3 style="font-size:16px;font-weight:600;margin-bottom:15px;">
Application Status
</h3>







{{-- FIRST STEP --}}
@if($mpdoRemark)

<a href="{{ route('applicant.upload_mpdo') }}" style="
background:#dc2626;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;
text-decoration:none;
display:inline-block;">
⚠ Re-upload MPDO Documents
</a>

@elseif($mpdoDocs == 0)

<a href="{{ route('applicant.upload_mpdo') }}" style="
background:#2563eb;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;
text-decoration:none;
display:inline-block;">
📄 Upload MPDO Documents
</a>

@elseif($mpdoStatus == 'pending')

<span style="
background:#94a3b8;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
⏳ Waiting for MPDO Assessment
</span>

@elseif($mpdoStatus == 'assessed')

<span style="
background:#0ea5e9;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
📝 MPDO Assessed
</span>

@elseif($mpdoStatus == 'verified' && $meoRemark)

<a href="{{ route('applicant.upload_meo') }}" style="
background:#dc2626;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;
text-decoration:none;
display:inline-block;">
⚠ Re-upload MEO Documents
</a>

@elseif($mpdoStatus == 'verified' && $meoDocs == 0)

<a href="{{ route('applicant.upload_meo') }}" style="
background:#16a34a;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;
text-decoration:none;
display:inline-block;">
📄 Upload MEO Documents
</a>

@elseif($meoDocs > 0 && $meoStatus == 'pending')

<span style="
background:#94a3b8;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
⏳ Waiting for MEO Assessment
</span>

@elseif($meoStatus == 'assessed')

<span style="
background:#0ea5e9;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
📝 MEO Assessed
</span>

@elseif($meoStatus == 'verified' && $meoEndorsed == 0)

<span style="
background:#22c55e;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
✅ MEO Verified
</span>

@elseif($meoEndorsed == 1 && $meoPaid == 0)

<span style="
background:#6366f1;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
📨 MEO Endorsed / Waiting for Payment
</span>

@elseif($meoPaid == 1 && $bfpRemark)

<a href="{{ route('applicant.upload_bfp') }}" style="
background:#dc2626;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;
text-decoration:none;
display:inline-block;">
⚠ Re-upload BFP Documents
</a>

@elseif($meoPaid == 1 && $bfpDocs == 0)

<a href="{{ route('applicant.upload_bfp') }}" style="
background:#f97316;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;
text-decoration:none;
display:inline-block;">
📄 Upload BFP Documents
</a>

@elseif($bfpPaid == 1)

<div style="
background:#dcfce7;
padding:18px;
border-radius:8px;
color:#166534;
font-weight:600;
font-size:15px;
line-height:1.6;">
🎉 Congratulations!<br>
All of your requirements have been successfully completed.
</div>

@elseif($bfpIssued == 1 && $bfpPaid == 0)

<span style="
background:#2563eb;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
📄 BFP Issued
</span>

@elseif($bfpStatus == 'verified' && $bfpIssued == 0)

<span style="
background:#22c55e;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
✅ BFP Verified
</span>

@elseif($bfpStatus == 'assessed')

<span style="
background:#0ea5e9;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
📝 BFP Assessed
</span>

@elseif($bfpDocs > 0 && $bfpStatus == 'pending')

<span style="
background:#94a3b8;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
⏳ BFP Under Review
</span>

@else

<span style="
background:#94a3b8;
color:#fff;
padding:10px 22px;
border-radius:6px;
font-size:14px;">
⏳ Application is being processed
</span>

@endif
</div>

@endsection