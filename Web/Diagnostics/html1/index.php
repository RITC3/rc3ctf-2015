<html>
<head>
	<title>Diagnostics!</title>

</head>

<?php

function scrub($inputStr) {
	$strlen = strlen($inputStr);
	for($i = 0; $i <= $strlen; $i++) {
		$char = substr($inputStr, $i, 1);
		if($char == '&') {
			$nextChar = substr($inputStr, ++$i, 1);
			if($nextChar != '&')
				$inputStr[$i-1] = " ";
		}
	}

	return $inputStr;
}

function getClientIp() {
    $ipaddress = '';
    if (array_key_exists('HTTP_CLIENT_IP', $_SERVER) && $_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(array_key_exists('HTTP_X_FORWARDED', $_SERVER) && $_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(array_key_exists('HTTP_FORWARDED_FOR', $_SERVER) && $_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(array_key_exists('HTTP_FORWARDED', $_SERVER) && $_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(array_key_exists('REMOTE_ADDR', $_SERVER) && $_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin
   1 => array("pipe", "w"),  // stdout
   2 => array("pipe", "w"),  // stderr
);

$cwd = "/var/www/html/";
$ret = "";
$stdout = "";
$stderr = "";

if(isset($_POST['ping']) && $_POST['ping'] != "")
{
	//write entry to log file
	$f = fopen("logs", 'a');
	fwrite($f, getClientIp() . " -- " . $_POST['ping'] . "\n");

	//do some sanitization
	$_POST['ping'] = str_replace(">", " ", $_POST['ping']);
	$_POST['ping'] = scrub($_POST['ping']);
	//$ret = exec('ping -c 1 \'' . $_POST['ping'] . "'", $pingResults);
	$ret = proc_open('ping -c 1 \'' . $_POST['ping'] . "'", $descriptorspec, $pipes,
					 $cwd);

	//parse stdout so formatting is preserve
	$stdout = stream_get_contents($pipes[1]);
	$stdout = htmlentities($stdout); //prevent xss
	fclose($pipes[1]);
	$stdout = str_replace("\n", "<br>", $stdout);

	//parse stderr the same
	$stderr = stream_get_contents($pipes[2]);
	$stderr = htmlentities($stderr); //prevent xss
	fclose($pipes[2]);
	$stderr = str_replace("\n", "<br>", $stderr);
}

?>

<form action="index.php" method="POST" onsubmit="return validate();">
	<div>
		<h1 style="margin-bottom: 5px;">Test connectivity with ping!</h1>
		<input type="text" id="ping_text" placeholder="Host" name="ping">
		<br><br>
		<?php
		//print stdout and a line break if not empty
		if($stdout != "" && strpos($stdout, "<?php") === false &&
			strpos($stdout, ";") === false)
		{
			echo $stdout;
			echo "\n<br>\n";
		}

		echo $stderr; //print stderr no matter what
		?>
	</div>
</form>

<script>
	function validate()
	{
		temp = document.getElementById("ping_text").value;
		if(temp != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
</script>

</html>
