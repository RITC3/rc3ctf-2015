<?php
session_start();
if(!isset($_SESSION['id']))
{
	header("Location: /login.php");
	die();
}

require_once("functions.php");
$conn = connect_to_db();
$UPLOAD_LIMIT = 15000000;
$error = "";

//so we need to create a different file i think that handles the file(s)?
//or maybe the post does hit this part of the code we just don't see it print out on the user end and there are some random errors we can't see and need to fix. probably this one
if(isset($_FILES['upl']))
{
	ini_set("memory_limit", "128M");
	if(!isset($_FILES['upl']['name']) || $_FILES['upl']['name'] == "")
		$error = "You cannot upload a file without all of the required " .
				 "attributes. Please try again.";
	else
		$safeName = htmlentities($_FILES['upl']['name']);

	//make sure no errors when uploading
	if(!$error && $_FILES['upl']['error'] != 0)
	{
		$error = "File '" . $safeName . "' experienced error " .
				 $_FILES['upl']['error']." when uploading. Please try again.";
	}

	if(!$error && (!isset($_FILES['upl']['type']) || $_FILES['upl']['type'] == ""
		|| !isset($_FILES['upl']['size']) || $_FILES['upl']['size'] == ""
		|| !isset($_FILES['upl']['tmp_name']) || $_FILES['upl']['tmp_name'] == ""))
	{
		$error = "You cannot upload a file without all of the " .
				 "required attributes. Please try again.";
	}

	//get file size
	$size = $_FILES['upl']['size'];

	//if size bigger than our limit
	if(!$error && intval($size) > $UPLOAD_LIMIT)
	{
		$error = "File '" . $safeName . "' exceeded the maximum allowed file"
				 . " size of " . $UPLOAD_LIMIT . " bytes.";
	}
	else if(!$error && intval($size) < 1) //if no size
	{
		$error = "File '" . $safeName . "' must be at least 1 byte.";
	}

	//make sure file is good
	if(!$error && filesize($_FILES['upl']['tmp_name']) < 1)
	{
		$error = "Error reading in file '" . $safeName . "'.";
	}
	else if(!$error)
	{
		//get file into var and verify it uploaded "correctly"
		$f = fopen($_FILES['upl']['tmp_name'], 'r');
		$contents = fread($f, $size);
		fclose($f);
	}

	if(!$error)
	{
		$result = getSize($conn, $_SESSION['id']);
		if(!is_numeric($result) || $result > $UPLOAD_LIMIT)
			$error = "You cannot have more than 15 MB of uploaded content.";
	}

	if(!$error)
	{
		$contents = $contents;
		$filename = basename($_FILES['upl']['name']);

		//make call to function that adds to db
		$error = addFile($conn, $filename, $_FILES['upl']['type'], $size, $contents, $_SESSION['id']);

		//disallow sql errors from showing
		if($error)
			$error = "There was a database error.";
	}
}

$conn->close();
?>
<!doctype html>
<html>
<head>
<title>Upload - Mega File</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--flexslider-css-->
<!--bootstrap-->
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<!--coustom css-->
<link href="css/style.css" rel="stylesheet" type="text/css"/>
<link href="css/upload.css" rel="stylesheet" type="text/css"/>
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
	function submitform(){
		document.upload.submit();
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
					<section class="bgi banner5"><h2>Upload Files</h2></section>
					
	<!-- contact -->
	<div class="contact-top">
		<!-- container -->
		<!--<div style="width: 600px; text-align: center;" class="container">-->
		<div class="container">
			<?php
			if($error)
			{
				echo "<div style=\"margin-bottom: 30px; text-align: center;\">\n";
				echo "<p style=\"color: red;\" class=\"error\">" . htmlentities($error) . "</p>\n";
				echo "</div>\n";
			}
			?>

	<div class="upload">
		<div class="login-form" style="text-align: center;">
			<form name="upload" id="upload" enctype="multipart/form-data" action="upload.php" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $UPLOAD_LIMIT ?>" />
		    	<input name="upl" type="file" />
			</form>
			<p style="margin-top: 5px;">Max of 15 MB.</p>
		</div>
		<div class="button">
		<div class="cancel"><a href="upload.php">Cancel</a></div>
		<div class="done"><a href="javascript: submitform()">Upload</a></div>
		<div class="clear"> </div>
		</div>
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
