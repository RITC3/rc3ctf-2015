<?php
//function tryRegister() //wrap the if statment below in this function
if(isset($_SESSION['id']))
{
	header("Location: /");
	die();
}

require_once("functions.php");

$error = "";
$conn = connect_to_db();
//if we got some post data try to register the user
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['repassword']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$repassword = $_POST['repassword'];

	if($username == "") //blank user name
		$error = "Username must not be blank.";
	else if(check_user($conn, $username)) //check if user exists
		$error = "That username already exists.";

	if($password == "")
		$error = "Password must not be blank.";

	if($password != $repassword)
		$error = "Passwords do not match.";

	if(!$error)
	{
		$name = strtolower($username);
		$retVals = register_user($conn, $name, $password);
		session_id($retVals[1]);
		session_start();
		$_SESSION['id'] = $retVals[0];
		header("Location: /");
		die();
	}
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Sign up - Top Secret</title>
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
</head>
<body>
<!--banner start here-->
<div class="banner-two">
  <div class="header">
	<?php
		$activePage = "sign up";
		require_once("menu.php");
	?>
	 </div>
 </div>
<!--banner end here-->
<!--content-->
<div class="container">
   <div class="contact">
	 <div class="contact-md">
			 <h3>Sign up</h3>
			<?php
			if($error)
			{
				echo "<p style=\"color: red;\" class=\"error\">" . htmlentities($error) . "</p>";
			}
			?>
		 </div>
				<div class="col-md-6 contact-top">
					<form action="register.php" method="POST">
						<div>
							<span>Username</span>
							<input id="user-field" name="username" type="text" placeholder="Username">
						</div>
						<div>
							<span>Password</span>
							<input id="pass-field" name="password" type="password" placeholder="Password">
						</div>
						<div style="margin-bottom: 10px;">
							<span>Confirm Password</span>
							<input id="repass-field" name="repassword" type="password" placeholder="Confirm Password">
						</div>
						<input type="submit" value="Sign up">
				  </form>
				</div>
			<div class="clearfix"> </div>
	</div>
</div>
<!--contact end here-->
<?php
require_once("footers.html");
?>
