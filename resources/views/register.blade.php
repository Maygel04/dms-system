<h2>Applicant Register</h2>

<form method="POST">
@csrf

<input name="name" placeholder="Full Name" required>
<input name="email" type="email" placeholder="Email" required>
<input name="contact_number" placeholder="Contact Number" required>
<input name="address" placeholder="Address" required>

<select name="gender" required>
<option value="">Gender</option>
<option>Male</option>
<option>Female</option>
</select>

<input name="occupation" placeholder="Occupation" required>
<input name="password" type="password" placeholder="Password" required>

<button>Register</button>
</form>
