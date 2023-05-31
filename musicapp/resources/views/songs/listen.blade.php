<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Hepi Music</title>
</head>
<body style="background: #0e0e0e;">
	<audio preload="auto" controls controlsList="nodownload noplaybackrate" style="position: absolute; top: 50%; right: 40%;">
		<source src="{{ asset('storage') }}/uploads/{{ $model->mp3_file }}" type="audio/mpeg">
	</audio>
</body>
</html>