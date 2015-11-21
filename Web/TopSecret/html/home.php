<?php
if(!isset($conn))
{
	header("Location: /");
	die();
}
?>
<!--banner start here-->
<div class="banner-two">
  <div class="header">
	<?php
		$activePage = "home";
		require_once("menu.php");
	?>
	 </div>
 </div>
<!--banner end here-->
<!--content-->
<div class="container">
<div class="contact">
	<div style="text-align: center; margin-bottom: 25px;">
	<h3 class="typo1">Welcome, <?php echo ucfirst(htmlentities($_SESSION['username'])) ?>.</h3>
	<?php
	if($error)
		echo "<p style=\"color: red;\">" . htmlentities($error) . "</p>";
	?>
	</div>
	<div class="col-md-5 contact-top">
	<form action="/" method="POST">
		<div>
			<input id="secret-field" maxlength="128" name="secret" type="text" placeholder="Enter your secret">
		</div>
		<input type="submit" value="Add Secret">
	</form>
	</div>
	<div class="clearfix"> </div>

	<div class="bs-example" data-example-id="contextual-table" style="border: 1px solid #eee">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>Secret</th>
				</tr>
			</thead>
			<?php
			$result = getSecrets($conn, session_id());
			$i = 1;
			while($row = $result->fetch_assoc())
			{
				$secret = htmlentities($row['secret']);
				if($i % 2)
					$active = "info";
				else
					$active = "";
				echo <<<TABLE
			<tbody>
				<tr class="$active">
					<th scope="row">$i</th>
					<td>$secret</td>
				</tr>
			</tbody>
TABLE;
			$i++;
			}
			?>
		</table>
	</div>
</div>
</div>
<!--contact end here-->
