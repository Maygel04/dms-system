@extends('adminlte::page')
@section('css')
<link rel="stylesheet" href="{{ asset('css/custom-adminlte.css') }}">
@endsection


@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="mb-4">
        <h4 class="fw-bold">👥 Manage Users</h4>
        <p class="text-muted">Create and manage system accounts</p>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    {{-- VALIDATION ERRORS --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- CREATE USER CARD --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- FULL NAME --}}
                    <div class="col-md-3">
                        <label class="form-label">Full Name</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter full name"
                            value="{{ old('name') }}"
                            required>
                    </div>

                    {{-- EMAIL --}}
                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter email address"
                            value="{{ old('email') }}"
                            required>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="col-md-3">
                        <label class="form-label">Password</label>

                        <div class="input-group">
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                onclick="togglePassword()">
                                <i class='bx bx-hide' id="eyeIcon"></i>
                            </button>
                        </div>

                        <div class="mt-2">
                            <div class="progress" style="height:6px;">
                                <div
                                    id="passwordStrength"
                                    class="progress-bar bg-danger"
                                    style="width:0%">
                                </div>
                            </div>

                            <small id="strengthText" class="text-danger">
                                Weak password
                            </small>
                        </div>
                    </div>

                    {{-- ROLE --}}
                    <div class="col-md-3">
                        <label class="form-label">Role</label>

                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="mpdo" {{ old('role') == 'mpdo' ? 'selected' : '' }}>MPDO</option>
                            <option value="meo" {{ old('role') == 'meo' ? 'selected' : '' }}>MEO</option>
                            <option value="bfp" {{ old('role') == 'bfp' ? 'selected' : '' }}>BFP</option>
                        </select>
                    </div>

                </div>

                {{-- PASSWORD NOTE --}}
                <div class="mt-2 text-muted small">
                    Password must contain at least 8 characters with uppercase, lowercase, number, and special character.
                </div>

                {{-- BUTTON --}}
                <div class="mt-4 text-end">
                    <button class="btn btn-primary px-4 rounded-3">
                        ➕ Create User
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- USERS TABLE --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users as $user)
                        <tr>

                            <td class="fw-semibold">
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                <span class="badge
                                    @if($user->role == 'admin') bg-dark
                                    @elseif($user->role == 'mpdo') bg-primary
                                    @elseif($user->role == 'meo') bg-success
                                    @elseif($user->role == 'bfp') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>

                            <td>
                                @if(isset($user->is_active) && $user->is_active == 1)
                                    <span class="badge bg-success">ACTIVE</span>
                                @else
                                    <span class="badge bg-secondary">INACTIVE</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}" style="display:inline-block;">
                                    @csrf

                                    @if(isset($user->is_active) && $user->is_active == 1)
                                        <button
                                            type="submit"
                                            class="btn btn-warning btn-sm"
                                            onclick="return confirm('Are you sure you want to deactivate this user?')">
                                            Deactivate
                                        </button>
                                    @else
                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm"
                                            onclick="return confirm('Are you sure you want to activate this user?')">
                                            Activate
                                        </button>
                                    @endif
                                </form>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                No users found
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

{{-- PASSWORD TOGGLE SCRIPT --}}
<script>
const passwordInput = document.getElementById("password");
const strengthBar = document.getElementById("passwordStrength");
const strengthText = document.getElementById("strengthText");

if (passwordInput) {
    passwordInput.addEventListener("keyup", function () {

        let password = passwordInput.value;
        let strength = 0;

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[@$!%*?&]/.test(password)) strength++;

        if (strength <= 2) {
            strengthBar.style.width = "33%";
            strengthBar.className = "progress-bar bg-danger";
            strengthText.innerText = "Weak password";
            strengthText.className = "text-danger";
        } else if (strength == 3 || strength == 4) {
            strengthBar.style.width = "66%";
            strengthBar.className = "progress-bar bg-warning";
            strengthText.innerText = "Medium password";
            strengthText.className = "text-warning";
        } else {
            strengthBar.style.width = "100%";
            strengthBar.className = "progress-bar bg-success";
            strengthText.innerText = "Strong password";
            strengthText.className = "text-success";
        }

    });
}

function togglePassword() {
    const password = document.getElementById("password");
    const icon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        icon.classList.remove("bx-hide");
        icon.classList.add("bx-show");
    } else {
        password.type = "password";
        icon.classList.remove("bx-show");
        icon.classList.add("bx-hide");
    }
}
</script>

@endsection