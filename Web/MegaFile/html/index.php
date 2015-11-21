<?php
if(isset($_POST['downfileid']))
{
	session_start();
	if(!isset($_SESSION['id']))
	{
		header("Location: /login.php");
		die;
	}

	if($_POST['downfileid'] ==  "")
		$error = "You must give a file to download";
	else
	{
		require_once("functions.php");
		$conn = connect_to_db();
		$tempResult = getFile($conn, $_POST['downfileid']);
		$conn->close();
		if($tempResult[1])
			$error = "There was an error retrieving the requested file.";
		else
		{
			header("Content-length: " . $tempResult[0]['size']);
			header("Content-type: " . $tempResult[0]['type']);
			header("Content-Disposition: attachment; filename=" .
					$tempResult[0]['name']);
			echo $tempResult[0]['content'];
			exit;
		}
	}
}
?>
<!doctype html>
<html>
<head>
<title>Mega File</title>
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
		var f = document.deleteFile;
		if(f.tagName == "FORM")
			f.submit();
		else
			f[i].submit();
	} 

	function submitform2(i){
		var f = document.downloadFile;
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
		session_start();
		require_once("menu.php");

		if(isset($_SESSION['id']))
		{
			require_once("functions.php");
			$conn = connect_to_db();
			$fileID = $_SESSION['id'];
			if(isset($_POST['accounts']))
			{
				if(is_numeric($_POST['accounts']))
					$fileID = intval($_POST['accounts']);
			}

			if(isset($_POST['deleteFile']))
			{
				if($_POST['deleteFile'] == "")
					$error = "You must give a filename to be deleted.";
				else
				{
					$tempResult = deleteFile($_POST['deleteFile'], $_SESSION['id']);
					if($tempResult)
						$error = "There is no file with that name associated with"
								 . " this account.";
				}
			}

			require_once("home.php");
			$conn->close();
		}
		else
		{
			require_once("default.php");
		}
		?>
<?php
require_once("footers.html");
?>
