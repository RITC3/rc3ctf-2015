<?php
session_start();
if(isset($_SESSION['id']))
{
	header("Location: /");
	die();
}
session_unset();
session_destroy();

require_once("functions.php");

$error = "";
$conn = connect_to_db();
//if we got some post data try to register the user
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['repassword']) && isset($_POST['firstname']) && isset($_POST['lastname']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$repassword = $_POST['repassword'];
	$first = $_POST['firstname'];
	$last = $_POST['lastname'];

	if($username == "") //blank user name
		$error = "Username must not be blank.";
	else if(strlen($username) > 64)
		$error = "Username cannot be longer than 64 characters.";
	else if(check_user($conn, strtolower($username))) //check if user exists
		$error = "That username already exists.";
	else if($password == "")
		$error = "Password must not be blank.";
	else if($first == "")
		$error = "First Name must not be blank.";
	else if($last == "")
		$error = "Last Name must not be blank.";
	else if($password != $repassword)
		$error = "Passwords do not match.";

	if(!$error)
	{
		$name = strtolower($username);
		$result = register_user($conn, $name, $password, $first, $last);
		session_start();
		$_SESSION['id'] = $result;
		$_SESSION['first'] = $first;
		header("Location: /");
		die();
	}
}

$conn->close();
?>
<!doctype html>
<html>
<head>
<title>Sign up - Mega File</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--flexslider-css-->
<!--bootstrap-->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<!--coustom css-->
<link href="css/style.css" rel="stylesheet" type="text/css"/>
<!--fonts-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,800italic,800,700italic,700,600,600italic' rel='stylesheet' type='text/css'>
<!--/fonts-->
<!--script-->
<script src="js/jquery.min.js"> </script>
	<!-- js -->
		 <script src="js/bootstrap.js"></script>
	<!-- js -->
		<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<!--/script-->
<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},900);
				});
			});
</script>
<!--/script-->
	</head>
	<body>
		<div class="header" id="home">
			<?php
			require_once("menu.php");
			?>
			<div class="header-banner">
					<!-- Top Navigation -->
					<section class="bgi banner5"><h2>Sign Up</h2></section>
					
	<!-- contact -->
	<div class="contact-top">
		<!-- container -->
		<div style="width: 600px; text-align: center;" class="container">
				<?php
				if($error)
				{
					echo "<div style=\"margin-bottom: 30px;\">\n";
					echo "<p style=\"color: red;\" class=\"error\">" . htmlentities($error) . "</p>\n";
					echo "</div>\n";
				}
				?>
			<div style="margin: 0 0 2.3em 0" class="mail-grids">
				<div style="float: none; width: 100%;" class="col-md-6 contact-form">
					<form action="register.php" method="POST">
						<input maxlength="64" type="text" name="username" placeholder="Username">
						<input maxlength="64" type="text" name="firstname" placeholder="First Name">
						<input maxlength="64" type="text" name="lastname" placeholder="Last Name">
						<input type="password" name="password" placeholder="Password">
						<input type="password" name="repassword" placeholder="Confirm Password">
						<input type="submit" value="Sign up">
					</form>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<!-- //container -->
	</div>
	<!-- //contact -->
		</div>
</div>
<?php
require_once("footers.html");
?>
