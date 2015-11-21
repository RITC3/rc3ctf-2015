<!DOCTYPE HTML>
<html>
<head>
<title>Top Secret</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery-1.11.0.min.js"></script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>
<!-- Custom Theme files -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--Google Fonts-->
<link href='//fonts.googleapis.com/css?family=Squada+One' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Source+Sans+Pro:400,200,300,600,700,900' rel='stylesheet' type='text/css'>
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
<!-- //end-smoth-scrolling -->
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
</head>
<body>
<?php
session_start();
require_once("functions.php");
$conn = connect_to_db();

if(!isset($_SESSION['id']) || !isset($_SESSION['username']))
{
	$result = get_info($conn, session_id());
	if($result)
	{
		$_SESSION['id'] = $result['id'];
		$_SESSION['username'] = $result['username'];
	}
}

if(isset($_SESSION['id'])) //logged in
{
	/*require_once("functions.php");
	$conn = connect_to_db();
	$result = get_info($conn, session_id());
	$tempID = $result['id'];
	$username = $result['username'];
	*/
	$error = "";

	if(isset($_POST['secret']))
	{
		$tempSecret = $_POST['secret'];
		if($tempSecret == "")
			$error = "Secret must not be blank.";

		if(!$error)
		{
			$count = getNumSecrets($conn, $_SESSION['id']);
			if($count > 500)
				$error = "You have exceeded the maximum number of allowed stored secrets. You must be a very secretive person.";

			if(strlen($tempSecret) > 128)
				$tempSecret = substr($tempSecret, 0, 127);

			if(!$error)
				$error = addSecret($conn, $tempSecret, $_SESSION['id']);
		}
	}

	require_once("home.php");
}
else //not logged in
{
	$index = "1";
	require_once("default.php");
}

$conn->close();

require_once("footers.html");
?>
