<p>Hello, {{ $name }}</p> <br>
Click the below link to reset your password : <br>
------------------------------------------------------- <br>
<a href="{{ route('reset.password.get', $token) }}">Reset Password</a>