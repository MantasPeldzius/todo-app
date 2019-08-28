<!DOCTYPE html>
<html>
<body>

<h2>Password change</h2>

@if ($hash)

<form action="/user/password-change" method="post">
	<input type="hidden" name="hash" value="{{ $hash }}">
	New password: <input type="password" name="password">
	<input type="submit" value="Submit">
</form> 

@else

404

@endif

</body>
</html>