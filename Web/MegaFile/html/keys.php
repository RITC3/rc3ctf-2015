<?php
session_start();
if(!isset($_SESSION['id']))
{
	header("Location: /");
	die();
}

if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == "for=0.0.0.0; proto=http; by=253.254.255.256" && isset($_POST['passcode']) && $_POST['passcode'] != "")
{
	require_once("functions.php");
	$conn = connect_to_db();
	$results = getKeys($conn, $_POST['passcode']);

	if($results[1] == "" && !!$results[0])
	{
		echo "Be careful formatting.<br>\n";
		echo "key:<br><br>\n";
		while($row = $results[0]->fetch_assoc())
		{
			echo $row['privkey'] . "\n\n<br><br>";
		}
	}
	else
		echo "go away.";

	$conn->close();
}
else
{
	header("Location: /");
	die();
}

?>
