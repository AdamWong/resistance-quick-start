<?php
include "credentials.php";
include "sendMessage.php";

session_start();

if (!isset($_SESSION["id"])) {
	$msg = htmlspecialchars("Please log in to continue");
    header("Location:index.php?msg=".$msg);
}

$dbConn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$dbConn) {
    die("Connect Fail:" . mysqli_error($dbConn));
}

$rules = array(
    "5"  => "3",
    "6"  => "4",
	"7"  => "4",
	"8"  => "5",
	"9"  => "6",
	"10" => "6",
);

$numPlayers = 0;
$players = array();
$playerInfo = array();

for ($i = 0; $i < 20; $i++) {
	if (isset($_POST["player$i"])) {
		$playerID = $_POST["player$i"];
		array_push($players, $playerID);
		$numPlayers++;
		
		$sql = "SELECT name,number FROM players WHERE playerID=?";

		$stmt = mysqli_stmt_init($dbConn);
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "i", $playerID);

		mysqli_stmt_execute($stmt);
		
		mysqli_stmt_bind_result($stmt, $name, $number);

		if (mysqli_stmt_fetch($stmt)) {
			$playerInfo["$playerID"]["name"] = $name;
			$playerInfo["$playerID"]["number"] = $number;
		}
	}
}

shuffle($players);

$resistance = array_splice($players, 0, $rules["$numPlayers"]);
$spy = $players;

$resIndex = 0;
$spyIndex = 0;

$merlin = $assassin = $percival = $morgana = $mordred = $oberon = '';
$merlinArray = array();

if (isset($_POST["oberon"])) {
	$oberon = $spy[$spyIndex];
	$spyIndex++;
	
	sendMessage("You are Oberon.", $playerInfo["$oberon"]["number"], $playerInfo["$oberon"]["name"]);
}

if (isset($_POST["mordred"])) {
	$mordred = $spy[$spyIndex];
	$spyIndex++;
	
	$msg = "You are Mordred. Spies are ";
	
	foreach ($spy as $playerID) {
		if ($playerID != $mordred) {
			if (!isset($_POST["oberon"]) || $playerID != $oberon) {
				$msg .= $playerInfo["$playerID"]["name"] . ", ";
			}
		}
	}
	sendMessage($msg, $playerInfo["$mordred"]["number"], $playerInfo["$mordred"]["name"]);
}

if (isset($_POST["merlin"])) {
	$merlin = $resistance[$resIndex];
	$resIndex++;
	array_push($merlinArray, $merlin);
	
	$msg = "You are Merlin. Spies are ";
	
	foreach ($spy as $playerID) {
		if ($playerID != $mordred) {
			$msg .= $playerInfo["$playerID"]["name"] . ", ";
		}
	}
	sendMessage($msg, $playerInfo["$merlin"]["number"], $playerInfo["$merlin"]["name"]);
	
	$assassin = $spy[$spyIndex];
	$spyIndex++;

	$msg = "You are the Assassin. Spies are ";
	foreach ($spy as $playerID) {
		if ($playerID != $assassin) {
			if (!isset($_POST["oberon"]) || $playerID != $oberon) {
				$msg .= $playerInfo["$playerID"]["name"] . ", ";
			}
		}
	}
	sendMessage($msg, $playerInfo["$assassin"]["number"], $playerInfo["$assassin"]["name"]);	
}

if (isset($_POST["morgana"])) {
	$morgana = $spy[$spyIndex];
	$spyIndex++;
	array_push($merlinArray, $morgana);
	shuffle($merlinArray);
	
	$msg = "You are Morgana. Spies are ";
	
	foreach ($spy as $playerID) {
		if ($playerID != $morgana) {
			if (!isset($_POST["oberon"]) || $playerID != $oberon) {
				$msg .= $playerInfo["$playerID"]["name"] . ", ";
			}
		}
	}
	sendMessage($msg, $playerInfo["$morgana"]["number"], $playerInfo["$morgana"]["name"]);
}

if (isset($_POST["percival"])) {
	$percival = $resistance[$resIndex];
	$resIndex++;
	
	$msg = "You are Percival. Merlin is among ";
	
	foreach ($merlinArray as $playerID) {
		$msg .= $playerInfo["$playerID"]["name"] . ", ";
	}
	sendMessage($msg, $playerInfo["$percival"]["number"], $playerInfo["$percival"]["name"]);
}

while ($spyIndex < count($spy)) {
	$msg = "You are a Spy. Spies are ";
	foreach ($spy as $playerID) {
		$spyID = $spy[$spyIndex];
		if ($playerID != $spyID) {
			if (!isset($_POST["oberon"]) || $playerID != $oberon) {
				$msg .= $playerInfo["$playerID"]["name"] . ", ";
			}
		}
	}
	sendMessage($msg, $playerInfo["$spyID"]["number"], $playerInfo["$spyID"]["name"]);
	$spyIndex++;
}

while ($resIndex < count($resistance)) {
	$msg = "You are a Resistance member.";
	$resID  = $resistance[$resIndex];
	sendMessage($msg, $playerInfo["$resID"]["number"], $playerInfo["$resID"]["name"]);
	$resIndex++;
}
?>	
