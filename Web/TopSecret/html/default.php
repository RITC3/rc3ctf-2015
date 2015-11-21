<?php
if(!isset($index))
{
	header("Location: /");
	die();
}
?>
<!--banner start here-->
<div class="banner">
  <div class="header">
	<?php
		$activePage = "home";
		require_once("menu.php");
	?>
	  <section class="slider">
		 <div class="flexslider">
			<ul class="slides">
			  <li>
			  	<div class="banner-bottom">
				 	<h2>Choose Us</h2>
				 	<p>We are the leading provider in secret storage.</p>
				 	<a href="register.php">Upload a Secret Today</a>
			 	</div>
			  </li>
			  <li>
			 	<div class="banner-bottom">
				 	<h2>Built with Security in Mind</h2>
				 	<p>We have all the securities.</p>
				 	<a href="register.php">Sign up now</a>
			 	</div>
			  </li>
			  <li>
			 	<div class="banner-bottom">
				 	<h2>Join the family</h2>
				 	<p>This is a family owned and operated organization.</p>
				 	<a href="register.php">Support Us</a>
			 	</div>
			  </li>
		    </ul>
		 </div>
		 <div class="clearfix"> </div>
	  </section>
 </div>
</div>
<!--banner end here-->
<!-- FlexSlider -->
				  <script defer src="js/jquery.flexslider.js"></script>
				  <script type="text/javascript">
					$(function(){
					 
					});
					$(window).load(function(){
					  $('.flexslider').flexslider({
						animation: "slide",
						start: function(slider){
						  $('body').removeClass('loading');
						}
					  });
					});
				  </script>
			<!-- FlexSlider -->
