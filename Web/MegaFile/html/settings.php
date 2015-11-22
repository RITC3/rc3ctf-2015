<?php
session_start();
if(!isset($_SESSION['id']))
{
	header("Location: /");
	die();
}

require_once("functions.php");
$conn = connect_to_db();
$error = "";
$UPLOAD_LIMIT = 1000000;

//if we got some post data try to update user info
if(isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['bio']))
{
	$first = $_POST['firstname'];
	$last = $_POST['lastname'];
	$bio = $_POST['bio'];

	if($first == "")
		$error = "First Name must not be blank.";
	else if (strlen($first) > 64)
		$error = "First Name must not be more than 64 characters.";
	else if($last == "")
		$error = "Last Name must not be blank.";
	else if (strlen($last) > 64)
		$error = "Last Name must not be more than 64 characters.";
	else if(strlen($bio) > 8192)
		$error = "Bio must not exceed 8192 characters.";

	if(!$error)
	{
		$result = updateUser($first, $last, $bio, $_SESSION['id']);
		if($result)
			$error = "There was an error updating your information.";
		else
			$_SESSION['first'] = $first;
	}
}

$userinfo = get_info($conn, $_SESSION['id']);
if(isset($userinfo['errors']))
	$error = $userinfo['errors'];
else
{
	$username = $userinfo['username'];
	$lastname = $userinfo['last'];
	$bio = $userinfo['bio'];
}

$firstname = $_SESSION['first'];

if(isset($_POST['shareid']) && $_POST['shareid'] != "")
{
	$error = deleteShare($_SESSION['id'], $_POST['shareid']);
}

if(isset($_FILES['upl']))
{
	if($_FILES['upl']['error'] != 0)
		$error = "There was an error uploading the file. Please try again.";
	else if(!isset($_FILES['upl']['name']) || $_FILES['upl']['name'] == ""
		|| !isset($_FILES['upl']['type']) || $_FILES['upl']['type'] == ""
		|| !isset($_FILES['upl']['size']) || $_FILES['upl']['size'] == ""
		|| !isset($_FILES['upl']['tmp_name']) || $_FILES['upl']['tmp_name'] == "")
	{
		$error = "You cannot upload a file without all of the " .
				 "required attributes. Please try again.";
	}
	else if(intval($_FILES['upl']['size']) > $UPLOAD_LIMIT)
		$error = "XML file cannot exceed 1 MB in size.";

	if(!$error)
	{
		//change some ini settings to make us less vulnerable to DoS attacks via XXE
		ini_set("max_execution_time", "15");
		ini_set("max_input_time", "15");

		//log xml given
		$logFile = "/var/www/logs/xml";
		$ip = getClientIp() . "\n-----------------------------";
		$f = fopen($_FILES['upl']['tmp_name'], "a");
		$contents = fread($f, $_FILES['upl']['size']);
		fclose($f);

		$f = fopen($logFile, 'w');
		fwrite($f, $ip . "\n" . $contents . "\n\n=============================\n");

		$first = "";
		$last = "";
		$bioStr = "";
		$dd = new DOMDocument;
		$ret = $dd->load($_FILES['upl']['tmp_name'], LIBXML_NOENT);

		$firstElement = $dd->getElementById("firstname");
		if($firstElement)
			$first = $firstElement->nodeValue;

		$lastElement = $dd->getElementById("lastname");
		if($lastElement)
			$last = $lastElement->nodeValue;

		$bioElement = $dd->getElementById("bio");
		if($bioElement)
			$bioStr = $bioElement->nodeValue;
		
		if($first == "" && $last == "" && $bioStr == "")
			$error = "Error parsing XML file: no relevant nodes or values found.";
		else if(strlen($first) > 64)
			$error = "First Name must not be more than 64 characters.";
		else if(strlen($last) > 64)
			$error = "Last Name must not be more than 64 characters.";
		else if(strlen($bio) > 8192)
			$error = "Bio must not exceed 8192 characters.";

		if(!$error)
		{
			if($first != "")
				$firstname = $first;

			if($last != "")
				$lastname = $last;

			if($bioStr != "")
				$bio = $bioStr;

			//update info in db
			$result = updateUser($firstname, $lastname, $bio, $_SESSION['id']);
			if($result)
				$error = "There was an error updating your information.";
			else
				$_SESSION['first'] = $firstname;
		}
	}
}

?>
<!doctype html>
<html>
<head>
<title>Settings - Mega File</title>
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
<script>
	function submitform(i){
		var f = document.deleteShare;
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
					<section class="bgi banner5"><h2>Settings</h2></section>
					
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
				<h3 class="bars">Account Information</h3>
				<div style="float: none; width: 100%;" class="col-md-6 contact-form">
					<form action="settings.php" method="POST">
						<input maxlength="64" type="text" readonly value="<?php
						if($username) echo htmlentities($username); else echo "Username" ?>">
						<input maxlength="64" type="text" name="firstname"
						value="<?php if($firstname) echo htmlentities($firstname); else echo
						"First Name";?>">
						<input maxlength="64" type="text" name="lastname" value="<?php
						if($lastname) echo htmlentities($lastname); else echo "Last Name";?>">
						<textarea maxlength="8192" name="bio"><?php
						if($bio) echo htmlentities($bio);else echo "Bio";?></textarea>
						<input type="submit" value="Update">
					</form>

					<br /><br />
					<hr />
					<div id="xml-container" style="margin-top: 50px;">
					<p>Upload an XML file with your first name, last name and bio.<br /> You can see a sample XML file <a href="xml/" target="_blank">here</a>.</p>
					<!--<div class="upload">-->
						<div class="login-form" style="margin-top: 30px; text-align: center;">
							<form name="xml-upload" id="upload" enctype="multipart/form-data" action="settings.php" method="POST">
								<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $UPLOAD_LIMIT ?>" />
								<input name="upl" type="file" accept=".xml" style="margin-left: 175px;" />
								<p style="margin-top: 5px;">Max of 1 MB.</p>
								<input type="submit" value="Upload">
							</form>
						</div>
					</div>
					<!--</div>-->
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<div style="width: 80%; text-align: center;" class="container">
		<hr />
		<div style="width: 600px; text-align: center;" class="container">
			<h3 class="bars">Manage Sharing</h3>
		<ul class="list-group" style="text-align: left;"><?php
			function getElement($username, $userid, $num, $err = "")
			{
				$element = <<<TOP

			<li class="list-group-item">
			<div class="left" id="share-mgmt-div"
TOP;
				if(!is_numeric($err) && $err != "")
				{
					$element .= " style=\"color: red; margin-top: 0px;\">" . $err;
					$element .= <<<BOTTOM

			</div>
			<div class="clearfix"></div>
			</li>

BOTTOM;
				}
				else
				{
					$element .= ">" . htmlentities($username) . "</div>\n";
					if(!is_numeric($err))
					{
						$element .= <<<MIDDLE
			<div class="right" style="margin-right: 0px;" id="share-button-div">
				<form name="deleteShare" action="/settings.php" method="POST">
					<input type="hidden" name="shareid" value="$userid" />
				</form>
				<div class="file-link"><a class="cancel" href="javascript: submitform($num)">Unshare</a></div>
			</div>

MIDDLE;
					}

					$element .= <<<BOTTOM
			<div class="clearfix"></div>
			</li>

BOTTOM;
				}

				return $element;
			}

			//get users shared with this account
			$result = getMyShares($conn, $_SESSION['id']);
			if(isset($error) && $error)
				echo getElement("", 0, 0, $error);
			else if($result[1])
			{
				$str = "There was an error retrieving users shared with this"
					. " account. Please try again.";
				echo getElement("", 0, 0, $str);
			}
			else if($result[0]->num_rows < 1)
			{
				echo getElement("", 0, 0, "You are not currently sharing files with anyone. You can <a href=\"share.php\">fix</a> that.");
			}
			else
			{
				$i = 0;

				while($row = $result[0]->fetch_assoc())
				{
					$temp = get_info($conn, $row['shareid']);
					//doesn't print out error which may happen
					if(!isset($temp['error']))
					{
						echo getElement($temp['username'], $row['shareid'], $i++);
					}
				}
			}

			$conn->close();
			?>
		</ul>
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
