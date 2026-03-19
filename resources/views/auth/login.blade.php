<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Building Permit System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
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

<!-- 🔥 BACKGROUND -->
<div class="h-screen relative overflow-hidden">

    <!-- ✅ HD IMAGE (SIGURADUHA NAA SA public/assets/bg.jpg) -->
    <img src="{{ asset('assets/bg.jpg') }}"
         class="absolute inset-0 w-full h-full object-cover object-center">

    <!-- ✅ OVERLAY (PARA READABLE) -->
    <div class="absolute inset-0 bg-black/35"></div>

    <!-- CONTENT -->
    <div class="relative flex items-center justify-center h-full px-4">

        <!-- ✅ CARD (MAS SOLID PARA DI MALUSAW TEXT) -->
<div class="bg-black/40 p-6 w-full max-w-md border border-white/20 border-t-4 border-blue-600 rounded-2xl shadow-2xl h-[520px] overflow-y-auto">            <div class="text-center mb-6 mt-3">

            <!-- HEADER -->
            <div class="text-center mb-6 mt-2">
                <img src="{{ asset('assets/jasaan.png') }}"
                     class="mx-auto h-16 mb-3">

                <h2 class="text-white text-2xl font-semibold">Sign In</h2>

                <p class="text-gray-200 text-sm mt-2">
                    Enter your credentials to access your account
                </p>
            </div>

            <!-- SUCCESS -->
            @if(session('success'))
                <div class="bg-green-500/20 text-green-300 text-sm p-2 rounded mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- ERROR -->
            @if($errors->any())
                <div class="bg-red-500/20 text-red-300 text-sm p-2 rounded mb-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <!-- EMAIL -->
                <div class="mb-4">
                    <label class="text-gray-200 text-sm">Email Address</label>
                    <input type="email" name="email" required
                        value="{{ old('email') }}"
                        class="w-full mt-1 px-3 py-2 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your email">
                </div>

                <!-- PASSWORD -->
                <div class="mb-4">
                    <label class="text-gray-200 text-sm">Password</label>

                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full mt-1 px-3 py-2 pr-10 bg-transparent border border-white/40 rounded text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter your password">

                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-300 hover:text-white">
                            👁
                        </button>
                    </div>
                </div>

                <!-- BUTTON -->
                <button type="submit"
                    class="w-full mt-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition duration-300">
                    Login
                </button>
            </form>

            <!-- LINK -->
            <div class="text-center mt-6 text-gray-200 text-sm">
                Don’t have an account?
                <a href="/register" class="text-blue-400 hover:underline font-semibold">
                    Register
                </a>
            </div>

        </div>

    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = (pass.type === "password") ? "text" : "password";
}
</script>

</body>
</html>