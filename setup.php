<?php
include "credentials.php";

session_start();

if (!isset($_SESSION["id"])) {
	$msg = htmlspecialchars("Please log in to continue");
#	header("Location:index.php?msg=".$msg);
}

$dbConn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$dbConn) {
    die("Connect Fail:" . mysqli_error($dbConn));
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Setup</title>
	</head>
	<body>
		<form action="start.php" method="POST">
		<h1>Roles</h1>
		<input type='checkbox' name='merlin' value='merlin'>Merlin and Assassin<br>
		<input type='checkbox' name='percival' value='percival'>Percival<br>
		<input type='checkbox' name='morgana' value='morgana'>Morgana<br>
		<input type='checkbox' name='mordred' value='mordred'>Mordred<br>
		<input type='checkbox' name='oberon' value='oberon'>Oberon<br>
		<h1>Players</h1>
		<?php
			$sql = "SELECT playerID,name FROM players WHERE userID=?";

			$stmt = mysqli_stmt_init($dbConn);
			mysqli_stmt_prepare($stmt, $sql);
			mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);

			mysqli_stmt_execute($stmt);

			mysqli_stmt_bind_result($stmt, $playerID, $name);

			$i = 0;
			while (mysqli_stmt_fetch($stmt)) {
				echo "<input type='checkbox' name='player$i' value='$playerID'>$name<br>";
				$i++;
			}
		?>
		<br><br>
		<input type="submit" value="Start">
		</form>
		<form action="addPlayer.php" method="POST">
			<h1>Add a Player</h1>
			<input type="text" name="name" placeholder="Player Name" maxlength="32"><br>
			<input type="text" name="number" placeholder="Phone Number" maxlength="10"><br>
			<input type="submit" value="Add">
		</form>
	</body>
</html>
