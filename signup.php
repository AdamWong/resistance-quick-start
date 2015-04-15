<?php
include "credentials.php";

$username = strip_tags(substr(trim($_POST['username']), 0, 64));
$password = strip_tags(substr(trim($_POST['password']), 0, 128));
$password2 = strip_tags(substr(trim($_POST['password']), 0, 128));

if ($password != $password2)
{
    $msg = htmlspecialchars("Passwords did not match");
    header("Location:index.php?msg=".$msg);
}

$hashedpassword = hash("sha256", $username . $password);

$dbConn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$dbConn) {
    die("Connect Fail:" . mysqli_error($dbConn));
}

$sql = "SELECT userID FROM users WHERE username=? AND password=?";

$stmt = mysqli_stmt_init($dbConn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "ss", $username, $hashedpassword);

mysqli_stmt_execute($stmt);

if (mysqli_stmt_fetch($stmt)) {
    $msg = htmlspecialchars("User name '".$username."' already exists");
    header("Location:index.php?msg=".$msg);
} else {
	$sql = "INSERT INTO users(username, password) VALUES(?,?)";

	$stmt = mysqli_stmt_init($dbConn);
	mysqli_stmt_prepare($stmt, $sql);
	mysqli_stmt_bind_param($stmt, "ss", $username, $hashedpassword);

	mysqli_stmt_execute($stmt);

	$msg = htmlspecialchars("Registration successful");
	header("Location:index.php?msg=".$msg);
}
?>	
