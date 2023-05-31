<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
</head>
<body>
    <p>
        Hello <?=$user->first_name." ".$user->last_name?>,
    </p>

    Please verify your account with bellow link: <br>
    ---------------------------------------------------------- <br>
    <a href="{{ route('user.verify', $user->remember_token) }}">Verify Email</a>
</body>
</html>