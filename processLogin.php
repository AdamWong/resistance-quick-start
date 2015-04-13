<?php
include "credentials.php";

session_start();

$username = strip_tags(substr(trim($_POST['username']), 0, 64));
$password = strip_tags(substr(trim($_POST['password']), 0, 128));
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

mysqli_stmt_bind_result($stmt, $userID);

if (mysqli_stmt_fetch($stmt)) {
    $_SESSION["id"] = $userID;
    header("Location:setup.php");
}
else
{
    $msg = htmlspecialchars("Incorrect login credentials");
    header("Location:index.php?msg=".$msg);
}
?>	