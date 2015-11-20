<html>
<head>
	<title>More Diagnostics!</title>

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

if(isset($_POST['nslookup']) && $_POST['nslookup'] != "")
{
	$f = fopen("logs2", 'a');
	fwrite($f, getClientIp() . " -- " . $_POST['nslookup'] . "\n");

	if(!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}/', $_POST['nslookup']))
	{
		$stdout = "That wasn't a real domain...";
	}
	else
	{
		$_POST['nslookup'] = str_replace(">", " ", $_POST['nslookup']);
		$_POST['nslookup'] = scrub($_POST['nslookup']);
		//$ret = exec('ping -c 1 \'' . $_POST['ping'] . "'", $pingResults);
		$ret = proc_open('nslookup \'' . $_POST['nslookup'] . "'", $descriptorspec,
						 $pipes, $cwd);

		//parse stdout so formatting is preserved
		$stdout = stream_get_contents($pipes[1]);
		$stdout = htmlentities($stdout); //prevent xss
		fclose($pipes[1]);
		$stdout = str_replace("\n", "<br>", $stdout);
	}
}

?>

<form action="index.php" method="POST" onsubmit="return validate();">
	<div>
		<h1 style="margin-bottom: 5px;">Make DNS queries with nslookup!</h1>
		<p>Hostnames only!</p>
		<input type="text" id="nslookup_text" placeholder="Host" name="nslookup" maxlength="16">
		<br><br>
		<?php
		//print stdout and a line break if not empty
		if($stdout != "" && strpos($stdout, "<?php") === false &&
			strpos($stdout, ";") === false)

		{
			echo $stdout;
			echo "\n<br>\n";
		}

		?>
	</div>
</form>

<script>
	function validate()
	{
		temp = document.getElementById("nslookup_text").value;
		if(temp == "")
			return false;

		if(!temp.match(/'|"|\(|\)|\$/))
		{
			return true;
		}
		else
		{
			alert("That doesn't seem like a real host");
			return false;
		}
	}
</script>

</html>
