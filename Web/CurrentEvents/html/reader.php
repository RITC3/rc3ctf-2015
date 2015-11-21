<html>
<head>
	<title>Read an Article!</title>
</head>
<?php
$error = "";

if(isset($_GET['article']))
{
	$val = $_GET['article'];
	if(preg_match("/^\.|\//", $val) || !file_exists($val))
	{
		$val = "";
		$error = "Invalid filename.";
	}
}
?>
<body>
<div>
	<h1>Read one of our amazing articles!</h1>
	<ul>
		<li><a href="reader.php?article=stagefright.php">Stagefright</a></li>
		<li><a href="reader.php?article=ransomware.php">Failed Ransomware</a></li>
		<li><a href="reader.php?article=google.php">Google slaps Symantec</a></li>
	<ul>
</div>


<?php
$top = <<<TOP
<hr>
<div style="text-align: center;">
	<a href="reader.php">Close article</a>
</div>
<div style="margin-top: 25px;">

TOP;
$bottom = "</div>\n";
if(isset($val) && $val != "")
{
	echo $top;

	require_once($val);

	echo $bottom;
}
else if($error != "")
{
	echo $top;

	echo "<p style='color: red;'>" . $error . "</p>\n";

	echo $bottom;
}
?>

</body>
</html>
