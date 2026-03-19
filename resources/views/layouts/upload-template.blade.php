<div class="max-w-5xl mx-auto mt-6">

<div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

{{-- HEADER --}}
<div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white px-6 py-5">
<h2 class="text-xl font-bold tracking-wide">
Municipal Planning and Development Office (MPDO)
</h2>

<p class="text-sm opacity-90">
Building Permit Application Requirements
</p>
</div>

<div class="p-6">

@if(session('success'))
<div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded mb-5 text-center shadow-sm">
    {{ session('success') }}
</div>
@endif


{{-- NOT UPLOADED --}}
@if(empty($uploaded) || !$uploaded)

<form method="POST" action="{{ $formAction ?? '#' }}" enctype="multipart/form-data">
@csrf

<input type="hidden" name="application_id" value="{{ $application->id ?? '' }}">

<div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
<table class="w-full text-sm">

<thead class="bg-gray-100 text-gray-700">
<tr>
<th class="px-4 py-3 w-12 text-center">#</th>
<th class="px-4 py-3 text-left">Required Document</th>
<th class="px-4 py-3 w-44 text-center">Upload</th>
</tr>
</thead>

<tbody class="divide-y bg-white">

@foreach(($requirements ?? []) as $i => $req)
<tr>
<td class="px-4 py-3 text-center">{{ $i+1 }}</td>

<td class="px-4 py-3">{{ $req }}</td>

<td class="px-4 py-3 text-center">

<label class="file-label bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded cursor-pointer inline-block">
Select File
<input type="file" name="doc[]" class="hidden doc-input" accept=".pdf,.dwg,.dxf">
</label>

<div class="text-xs text-gray-500 mt-1 file-name"></div>

</td>
</tr>
@endforeach

</tbody>
</table>
</div>

<div class="mt-4 text-xs text-gray-500">
• Accepted formats: <b>PDF, DWG, DXF</b> <br>
• Max size: <b>25MB</b> per file
</div>

<div class="mt-6 text-right">
<button class="bg-blue-900 hover:bg-blue-950 text-white px-7 py-2.5 rounded-lg shadow-md font-semibold">
Submit {{ $officeShort ?? 'Department' }} Requirements
</button>
</div>

</form>

@else

{{-- STATUS --}}
<div class="text-center py-12">

@if(($status ?? '') == 'verified')
<div class="text-5xl mb-4 text-green-600">✔</div>
<h3 class="text-lg font-semibold text-green-700">Documents Verified</h3>

<span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-md mt-3">
Status: VERIFIED
</span>

@else
<div class="text-5xl mb-4 text-yellow-500">⏳</div>
<h3 class="text-lg font-semibold text-yellow-700">Requirements Under Review</h3>

<span class="inline-block bg-yellow-100 text-yellow-700 px-4 py-2 rounded-md mt-3">
Status: UNDER REVIEW
</span>
@endif

</div>
@endif

</div>
</div>

<div class="mt-6 text-center">
<a href="{{ route('applicant.dashboard') }}" class="text-blue-900 hover:underline">
← Return to Dashboard
</a>
</div>

</div>


{{-- ================= SAFE JS ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

document.querySelectorAll(".doc-input").forEach(input => {

input.addEventListener("change", function(){

const label = this.closest("td").querySelector(".file-label");
const nameBox = this.closest("td").querySelector(".file-name");

if(this.files.length > 0){

label.classList.remove("bg-red-600","hover:bg-red-700");
label.classList.add("bg-green-600","hover:bg-green-700");

label.innerText = "File Selected";
label.appendChild(this);

nameBox.textContent = this.files[0].name;

}else{

label.classList.remove("bg-green-600","hover:bg-green-700");
label.classList.add("bg-red-600","hover:bg-red-700");

label.innerText = "Select File";
label.appendChild(this);

nameBox.textContent = "";

}

});

});

});
</script>