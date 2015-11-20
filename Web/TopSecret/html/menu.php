	<div class="container">
		 <div class="header-main">
				<div class="logo">
					<h1><a href="/">Top Secret</a></h1>
				</div>
				<div class="top-nav">
					<span class="menu"> <img src="images/icon.png" alt=""/></span>
				  <nav class="cl-effect-1">
					<ul class="res">
						<li><a <?php if($activePage == "home") echo 'class="active"';?> href="/">Home</a></li>
						<?php
						if(isset($_SESSION['id']))
						{
							$findClass = "";
							if($activePage == "find")
								$findClass = "class=\"active\"";
							echo <<<EOF
						<li><a <?php $findClass href="find.php">Find Friends</a></li>
						<li><a <?php href="logout.php">Logout</a></li>
EOF;
						}
						else
						{
							$loginClass = "";
							$signupClass = "";
							if($activePage == "login")
								$loginClass = "class=\"active\"";

							if($activePage == "sign up")
								$signupClass = "class=\"active\"";
							echo <<<EOF
						<li><a <?php $loginClass href="login.php">Login</a></li>
						<li><a <?php $signupClass href="register.php">Sign Up</a></li>
EOF;
						}
						?>
				   </ul>
				  </nav>
					<!-- script-for-menu -->
						 <script>
						   $( "span.menu" ).click(function() {
							 $( "ul.res" ).slideToggle( 300, function() {
							 // Animation complete.
							  });
							 });
						</script>
		        <!-- /script-for-menu -->
				</div>
				 <div class="clearfix"> </div>
		 </div>
	  </div>
