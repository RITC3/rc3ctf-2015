<?php
session_start();
if(!isset($_SESSION['id']))
{
	header("Location: /login.php");
	die();
}

$head = <<<HEAD
	<div class="bs-example" data-example-id="contextual-table" style="border: 1px solid #eee">
		<table class="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>User</th>
				</tr>
			</thead>
HEAD;

$tail = <<<TAIL
		</table>
	</div>
TAIL;
require_once("functions.php");
$conn = connect_to_db(1);
$error = "";
$result = "";
$users = "";

if(isset($_POST['username']))
{
	$username = $_POST['username'];
	if($username == "")
		$error = "Username must not be blank.";
	
	if(!$error)
	{
		$result = lookup_user($conn, $username);
		$users = $result[0];
		$error = $result[1];
	}
}

$conn->close();
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Find Friends - Top Secret</title>
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

<!--banner start here-->
<div class="banner-two">
  <div class="header">
	<?php
		$activePage = "find";
		require_once("menu.php");
	?>
	 </div>
 </div>
<!--banner end here-->
<!--content-->
<div class="container">
<div class="contact">
	<div style="text-align: center; margin-bottom: 25px;">
	<h3 class="typo1">Find your friends!</h3>
	<?php
	if($error)
		echo "<p style=\"color: red;\">" . $error . "</p>";
	?>
	</div>
	<div class="col-md-5 contact-top">
	<form action="find.php" method="POST">
		<div>
			<input id="user-field" maxlength="64" name="username" type="text" placeholder="Enter your friend's name">
		</div>
		<input type="submit" value="Search">
	</form>
	</div>
	<div class="clearfix"> </div>

<?php
if($users)
{
	echo $head;

	$i = 1;
	foreach($users as $row)
	{
		$tempID = htmlentities($row[0]);
		$user = htmlentities($row[1]);
		if($i % 2)
			$active = "info";
		else
			$active = "";
		echo <<<TABLE
			<tbody>
				<tr class="$active">
					<th scope="row">$tempID</th>
					<td>$user</td>
				</tr>
			</tbody>
TABLE;
		$i++;
	}

	echo $tail;
}
?>
</div>
</div>

<?php
require_once("footers.html");
?>
