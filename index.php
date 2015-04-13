<!DOCTYPE HTML>
<html>
	<head>
		<title>Start a Resistance</title>
	</head>
	<body>
		<div>
			<?php if (isset($_GET["msg"])) { print '<h1 style="color:red">'.$_GET["msg"].'</h1>'; }?>
			<div>
				<form action="processLogin.php" method="POST">
					<h2>Login</h2>
					<input type="text" name="username" placeholder="User name" maxlength="32"><br>
					<input type="password" name="password" placeholder="Password" maxlength="128"><br>
					<input type="submit" value="Login">
				</form>
			</div>
			<div>
				<form action="signup.php" method="POST">
					<h2>Sign Up</h2>
					<input type="text" name="username" placeholder="User name" maxlength="32"><br>
					<input type="password" name="password" placeholder="Password" maxlength="128"><br>
					<input type="password" name="password2" placeholder="Confirm Password" maxlength="128"><br>
					<input type="submit" value="Register">
				</form>
			</div>
		</div>
	</body>
</html>