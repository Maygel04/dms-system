<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register | Building Permit System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
    theme: {
        fontFamily: {
            body: ["League Spartan", "sans-serif"]
        }
    }
}
</script>

<link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body class="font-body">

<div class="h-screen relative overflow-hidden">

    <!-- ✅ HD BACKGROUND (IMPORTANT) -->
    <img src="{{ asset('assets/bg.jpg') }}"
         class="absolute inset-0 w-full h-full object-cover object-center">

    <!-- ✅ OVERLAY (READABILITY FIX) -->
    <div class="absolute inset-0 bg-black/35"></div>

    <!-- CONTENT -->
    <div class="relative flex items-center justify-center h-full px-4">

        <!-- ✅ CARD FIX (MAS READABLE) -->
<div class="bg-black/40 p-6 w-full max-w-md border border-white/20 border-t-4 border-blue-600 rounded-2xl shadow-2xl h-[520px] overflow-y-auto">            <div class="text-center mb-6 mt-3">
                <img src="{{ asset('assets/jasaan.png') }}"
                     class="mx-auto h-16 mb-3">

                <h2 class="text-white text-2xl font-semibold">
                    Applicant Register
                </h2>

                <p class="text-gray-200 text-sm mt-2">
                    Create your account to apply for permits
                </p>
            </div>

            <!-- ALERTS -->
            @if(session('success'))
                <div class="bg-green-500/20 text-green-300 text-sm p-2 rounded mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/20 text-red-300 text-sm p-2 rounded mb-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/register">
                @csrf

                <!-- INPUTS -->
                <input name="name" placeholder="Full Name"
                    class="w-full mb-3 px-3 py-2 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>

                <input type="email" name="email" placeholder="Email Address"
                    class="w-full mb-3 px-3 py-2 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>

                <input name="contact_number" placeholder="Contact Number"
                    class="w-full mb-3 px-3 py-2 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>

                <input name="address" placeholder="Address"
                    class="w-full mb-3 px-3 py-2 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>

                <select name="gender"
                    class="w-full mb-3 px-3 py-2 bg-transparent border border-white/40 rounded text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="" class="text-black">Select Gender</option>
                    <option class="text-black">Male</option>
                    <option class="text-black">Female</option>
                </select>

                <input name="occupation" placeholder="Occupation"
                    class="w-full mb-3 px-3 py-2 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>

                <!-- PASSWORD -->
                <div class="relative mb-2">
                    <input type="password" name="password" id="password"
                        placeholder="Password"
                        class="w-full px-3 py-2 pr-10 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>

                    <button type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300">
                        👁
                    </button>
                </div>

                <!-- RULES -->
                <ul class="text-xs mb-4 space-y-1">
                    <li id="r1" class="text-red-400">• At least 8 characters</li>
                    <li id="r2" class="text-red-400">• One uppercase letter</li>
                    <li id="r3" class="text-red-400">• One lowercase letter</li>
                    <li id="r4" class="text-red-400">• One number</li>
                    <li id="r5" class="text-red-400">• One special character</li>
                </ul>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full mt-2 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
                    Register
                </button>
            </form>

            <div class="text-center mt-5 text-gray-200 text-sm">
                Already have an account?
                <a href="/login" class="text-blue-400 hover:underline font-semibold">
                    Login
                </a>
            </div>

        </div>

    </div>
</div>

<script>
const pass = document.getElementById("password");

pass.addEventListener("keyup", function () {
    const val = pass.value;

    toggleRule("r1", val.length >= 8);
    toggleRule("r2", /[A-Z]/.test(val));
    toggleRule("r3", /[a-z]/.test(val));
    toggleRule("r4", /[0-9]/.test(val));
    toggleRule("r5", /[^A-Za-z0-9]/.test(val));
});

function toggleRule(id, valid) {
    const el = document.getElementById(id);

    if (valid) {
        el.classList.replace("text-red-400","text-green-400");
    } else {
        el.classList.replace("text-green-400","text-red-400");
    }
}

function togglePassword() {
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>