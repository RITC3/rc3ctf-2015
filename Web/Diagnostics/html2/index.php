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

//for the other idea, we could have nslookup or dig, and verify that it starts with a valid name or something so that way it is much harder to perform the injection. also don't show error output and use maxsize for the field in the html and use javascript to do some client side validation. make it really annoying and somewhat difficult.

//one big issue will be making it necessary to complete both challenges to get the flag.
$descriptorspec = array(
   0 => array("pipe", "r"),  // stdin
   1 => array("pipe", "w"),  // stdout
   2 => array("pipe", "w"),  // stderr
);

//should we allow pipes, ><, $(), $, `` in the command they give? <-- probably doesn't matter

$cwd = "/var/www/chal2/";
$ret = "";
$stdout = "";
$stderr = "";

if(isset($_POST['nslookup']) && $_POST['nslookup'] != "")
{
	$f = fopen("logs2", 'a');
	fwrite($f, getClientIp() . " -- " . $_POST['nslookup'] . "\n");

	#if(!preg_match('/$\w+\./', $_POST['nslookup']))
	if(!preg_match('/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}/', $_POST['nslookup']))
	{
		$stdout = "That wasn't a real domain...";
	}
	else
	{
		//$chars = array(">", "$", "&", "`");
		//$_POST['nslookup'] = str_replace($chars, " ", $_POST['nslookup']);
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

	//don't print errors for this
	//parse stderr the same
/*	$stderr = stream_get_contents($pipes[2]);
	$stderr = htmlentities($stderr); //prevent xss
	fclose($pipes[2]);
	$stderr = str_replace("\n", "<br>", $stderr);
*/
}

?>

<form action="more.php" method="POST" onsubmit="return validate();">
	<div>
		<h1 style="margin-bottom: 5px;">Make DNS queries with nslookup!</h1>
		<p>Hostnames only!</p>
		<input type="text" id="nslookup_text" placeholder="Host" name="nslookup" maxlength="16">
		<br><br>
		<?php
		//print stdout and a line break if not empty
		if($stdout != "")
		{
			echo $stdout;
			echo "\n<br>\n";
		}

#		echo $stderr; //print stderr no matter what
#		foreach($pingResults as $key => $value)
#			echo $value . "\n<br>";
//		print_r(array_values($results));
//		var_dump($results);
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
