<?php

$print = False;
if(isset($_GET['winning']) && isset($val))
{
	if($_GET['winning'] == "yes")
		$print = True;
}

$flag = "";
$flag = file_get_contents("../flag.txt");

if($print)
	echo $flag;
else
{
	echo "Sorry, you can't have the flag :p\n";
	echo "<!--Seriously, you can't have it.-->";
}
?>
