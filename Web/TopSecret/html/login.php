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
$conn = connect_to_db();
$post = False;
$error = "";

if(isset($_POST['username']) && isset($_POST['password']))
{
	$post = True;
    if($_POST['username'] == "") {
        $error = "You must enter a username";
    }
    if($_POST['password']== ""){
        $error = "You must enter a password";   
    }
}

if($post && !$error){
    $username = strtolower($_POST['username']);
    $password = $_POST['password'];
    $login = login_user($conn, $username, $password);
    if($login){
        if(gettype($login) == "string")
            $error = $login;
        else{
			session_id($login['session']);
            session_start();
            $_SESSION['id'] = $login['id'];
            $_SESSION['username'] = $login['username'];
            header('Location: /');
            die();
        }
    }
    else
        $error = "Invalid username or password";
}

$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Login - Top Secret</title>
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
		$activePage = "login";
		require_once("menu.php");
	?>
	 </div>
 </div>
<!--banner end here-->
<!--content-->
<div class="container">
   <div class="contact">
	 <div class="contact-md">
			 <h3>Login</h3>
			 <?php
				if($error)
				{
					echo "<p style=\"color: red;\" class=\"error\">" . htmlentities($error) . "</p>";
				}
			 ?>
		 </div>
				<div class="col-md-6 contact-top">
					<form action="login.php" method="POST">
						<div>
							<span>Username</span>
							<input id="user-field" name="username" type="text" placeholder="Username">
						</div>
						<div>
							<span>Password</span>
							<input id="pass-field" name="password" type="password" placeholder="Password">
						</div>
						<div style="margin-bottom: 10px;">
							<span><a href="forgot.php">Forgot your password?</a></span>
						</div>
						<input type="submit" value="Login">
				  </form>
				</div>
			<div class="clearfix"> </div>
	</div>
</div>
<!--contact end here-->
<?php
require_once("footers.html");
?>
