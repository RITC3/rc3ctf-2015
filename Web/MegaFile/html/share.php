<?php
session_start();
if(!isset($_SESSION['id']))
{
	header("Location: /login.php");
	die();
}

function getElement($username, $userid, $num, $err = "")
{
	$element = <<<TOP
			<li class="list-group-item">
			<div class="left" id="username-div"
TOP;
	if($err != "")
	{
		$element .= " style=\"color: red; margin-bottom: 11px;\">" . $err . "\n";
		$element .= <<<BOTTOM
			</div>
			<div class="clearfix"></div>
			</li>

BOTTOM;
	}
	else
	{
		$element .= ">" . htmlentities($username) . "\n";
		$safeuserid = htmlentities($userid);
		$element .= <<<BOTTOM
			</div>
			<div class="right" id="file-button-div">
				<form name="shareFiles" action="share.php" method="POST">
					<input type="hidden" name="sharetoID" value="$safeuserid" />
				</form>
				<div class="file-link"><a style="margin: 0px;" class="done" href="javascript: submitform($num)">Share</a></div>
			</div>
			<div class="clearfix"></div>
			</li>

BOTTOM;
	}

	return $element;
}


require_once("functions.php");
$conn = connect_to_db();
$error = "";
$success = "";
$results = "";

if(isset($_GET['user']) && $_GET['user'] != "")
{
	$result = lookup_user($conn, $_GET['user']);

		$results = <<<TOP
		<br /><br />
		<div id="search-results">
		<h3 class="bars">Results</h3>
		<ul class="list-group" style="text-align: left;">

TOP;

	if($result[1] != "")
	{
		$error = "Error finding the user specified.";
		$results .= getElement("", "", 0, $error);
		$error = "";
	}
	else
	{
		if($result[0]->num_rows < 1)
		{
			$results .= getElement("", "", 0, "No users found.");
		}
		else
		{
			$i = 0;
			while($row = $result[0]->fetch_assoc())
			{
				$results .= getElement($row['username'], $row['id'], $i++);
			}
		}
	}

		$results .= <<<BOTTOM
		</ul>
		</div>

BOTTOM;
}

if(isset($_POST['sharetoID']))
{
	if(is_numeric($_POST['sharetoID']))
	{
		$error = addShare($conn, $_SESSION['id'], $_POST['sharetoID']);
		if(!$error)
			$success = "Shared files with user successfully!";
	}
}

$conn->close();
?>
<!doctype html>
<html>
<head>
<title>Share Files - Mega File</title>
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
<script type="text/javascript"> 
	function submitform(i){
		var f = document.shareFiles;
		if(f.tagName == "FORM")
			f.submit();
		else
			f[i].submit();
	} 
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
					<section class="bgi banner5"><h2>Share Files</h2> </section>
					
	<!-- contact -->
	<div class="contact-top">
		<!-- container -->
		<div style="width: 600px; text-align: center;" class="container">
			<div style="margin: 0 0 2.3em 0" class="mail-grids">
				<div style="float: none; width: 100%; padding-right: 15px;" class="col-md-6 contact-form">
					<p style="margin-bottom: 25px;">Search for users to share your files with!</p>
					<form action="share.php" method="GET">
						<input maxlength="64" type="text" name="user" placeholder="Search for a username">
						<input type="submit" value="Search">
					</form>
				</div>
				<div class="clearfix"> </div>
			<?php
			if($error)
			{
				$safeError = htmlentities($error);
				echo <<<ERROR
				<div style="margin-top: 30px;">
				<p style="color: red;">$safeError</p>
				</div>

ERROR;
			}
			else if($success)
			{
				echo <<<ERROR
				<div style="margin-top: 30px;">
				<p style="color: green;">$success</p>
				</div>

ERROR;
			}
			else if($results)
			{
				echo $results . "\n";
			}
			?>
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
