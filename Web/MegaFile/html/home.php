<?php
if(!isset($_SESSION['id']) || !isset($conn))
{
	header('Location: /login.php');
	die();
}

if(!isset($_SESSION['first']))
{
	$result = get_info($conn, $_SESSION['id']);

	if(isset($result['error']) || !isset($result['first']) || $result['first'] == "")
		$error = "Something went wrong finding information about your account. Try logging out and logging back in. If that doesn't work, try registering a new account. If that doesn't work you should probably let us know.";
	else
		$_SESSION['first'] = $result['first'];
}

function getOption($id, $selID)
{
	$ret = "<option ";
	if($id == $selID)
		$ret .= "selected=\"selected\" ";

	$ret .= "value=\"" . htmlentities($id) . "\">" . htmlentities($id)
		 . "</option>";
	return $ret;
}

?>
			<div class="header-banner">
					<!-- Top Navigation -->
					<section class="bgi banner5"><h2>Welcome<?php
					if(isset($_SESSION['first']))
						echo ", " . htmlentities(ucfirst($_SESSION['first']));
					?>!</h2> </section>
		<div class="typrography">
	<div class="container">
		<h3 class="bars">Choose Account</h3>
		<form name="account-form" action="/" method="POST">
			<select name="accounts">
				<?php
					echo getOption($_SESSION['id'], $fileID) . "\n";
					$shareResult = getShares($conn, $_SESSION['id']);

					if(!$shareResult[1])
					{
						while($row = $shareResult[0]->fetch_assoc())
						{
							echo getOption($row['ownerid'], $fileID) . "\n";
						}
					}
				?>
			</select>
			<br /><br />
			<input type="submit" value="Show files">
		</form>
		
		<br /><hr /><br />

		<h3 class="bars">Uploaded files</h3>
		<ul class="list-group">
			<?php
			function getElement($filename, $filesize, $fileid, $num, $err = "")
			{
				$element = <<<TOP
			<li class="list-group-item">
			<div class="left" id="filename-div"
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
					$element .= ">";
					$element .= <<<FORM
			<form name="downloadFile" action="/" method="POST">
				<input type="hidden" name="downfileid" value="$fileid" />
			</form>

FORM;
					$element .= "<a href=\"javascript: submitform2($num)\" "
						. ">" . htmlentities($filename)
						. " (" .htmlentities($filesize) . " bytes)</a>";
					$element .= <<<TOPMIDDLE

			</div>

TOPMIDDLE;
					if(!is_numeric($err))
					{
						$safeFilename = htmlentities($filename);
						$element .= <<<MIDDLE
			<div class="right" id="file-button-div">
				<form name="deleteFile" action="/" method="POST">
					<input type="hidden" name="deleteFile" value="$safeFilename" />
				</form>
				<div class="file-link"><a class="cancel" href="javascript: submitform($num)">Delete</a></div>
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
			//get files for the requested account
			$result = getFiles($conn, $fileID);
			if(isset($error))
				echo getElement("", "", 0, 0, $error);
			else if($result[1])
			{
				$str = "There was an error retrieving files for the requested"
					. " account. Please try again.";
				echo getElement("", "", 0, 0, $str);
			}
			else if($result[0]->num_rows < 1)
			{
				echo getElement("", "", 0, 0, "No files associated with this account.");
			}
			else
			{
				$i = 0;
				//if our id, we can delete
				if($fileID == $_SESSION['id'])
					$del = "";
				else //not our id, cannot delete
					$del = 1;

				while($row = $result[0]->fetch_assoc())
				{
					echo getElement($row['name'], $row['size'], $row['id'],
									$i++, $del);
				}
			}
			?>
		</ul>
	</div>
</div>		  
<!--</section>-->
		  
<!--</div>
</div>-->
</div>
</div>
