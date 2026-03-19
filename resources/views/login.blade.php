<h2>Login</h2>

@if(session('error'))
<p>{{ session('error') }}</p>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<input name="email" type="email" placeholder="Email" required>
<input name="password" type="password" placeholder="Password" required>

<button type="submit">Login</button>

</form>

<a href="/register">Register</a>