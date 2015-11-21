			<nav class="navbar navbar-default">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/"><h1>Mega</h1><br /><span>File</span></a>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav navbar-right margin-top cl-effect-2">
								<li><a href="/"><span data-hover="Home">Home</span></a></li>
								<?php
								if(isset($_SESSION['id']))
								{
									echo <<<OUT
								<li><a href="upload.php"><span data-hover="Upload">Upload</span></a></li>
								<li><a href="share.php"><span data-hover="Share Files">Share Files</span></a></li>
								<li><a href="settings.php"><span data-hover="Settings">Settings</span></a></li>
								<li><a href="logout.php"><span data-hover="Logout">Logout</span></a></li>

OUT;
								}
								else
								{
									echo <<<OUT
								<li><a href="login.php"><span data-hover="Login">Login</span></a></li>
								<li><a href="register.php"><span data-hover="Signup">Signup</span></a></li>

OUT;
								}
								?>
							</ul>
							<div class="clearfix"></div>
						</div><!-- /.navbar-collapse -->
						<div class="clearfix"></div>
				</div><!-- /container-fluid -->
			</nav>
