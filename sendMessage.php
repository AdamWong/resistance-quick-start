<?php
// this line loads the library 
require('Services/Twilio.php');

function sendMessage($message, $number, $name) {
	echo "Sending msg '$message' to $number($name)<br>";

	$account_sid = 'ACa6214c27ed3ccb6845418548b037d7e2'; 
	$auth_token = ''; 
	$client = new Services_Twilio($account_sid, $auth_token); 
	 
	$client->account->messages->create(array( 
		'To' => $number, 
		'From' => "+14378000731", 
		'Body' => $message,   
	));
}
?>
