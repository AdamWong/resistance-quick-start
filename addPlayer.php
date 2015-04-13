<?php
include "credentials.php";

session_start();

if (!isset($_SESSION["id"])) {
	$msg = htmlspecialchars("Please log in to continue");
    header("Location:index.php?msg=".$msg);
}

$name = strip_tags(substr(trim($_POST['name']), 0, 32));
$number = strip_tags(substr(trim($_POST['number']), 0, 10));

if (preg_match("/\A\d{10}\z/", $number) == 0) {
	echo "'$number' is not a valid number";
	return;
}

$dbConn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$dbConn) {
    die("Connect Fail:" . mysqli_error($dbConn));
}

$sql = "INSERT INTO players(userID,name,number) VALUES(?,?,?)";

$stmt = mysqli_stmt_init($dbConn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "iss", $_SESSION["id"], $name, $number);

mysqli_stmt_execute($stmt);

header("Location:setup.php");
?>	
