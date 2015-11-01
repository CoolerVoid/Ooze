<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 
	

<head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<title> Ooze:: AUTH</title>
		

		<link rel="stylesheet" href="resources/css/reset.css" type="text/css" media="screen" />
	  

		<link rel="stylesheet" href="resources/css/style.css" type="text/css" media="screen" />
		

		<link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />	
		

		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
	  
		<!-- jQuery -->
		<script type="text/javascript" src="resources/scripts/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="resources/scripts/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="resources/scripts/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="resources/scripts/jquery.wysiwyg.js"></script>
		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
	</head>
<?php
include('../helper/nocsrf.php');
session_start();
$token = NoCSRF::generate( 'csrf_token' );
?>
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
		

				<img id="logo" src="resources/images/logo.png" alt="Simpla Admin logo" />
			</div> 
			
			<div id="login-content">
				
				<form action="../controller/auth.php?page=login" method="post" >
				
					<div class="notification information png_bg">
						<div>
							Login
						</div>
					</div>
					 <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
					<p>
						<label>User</label>
						<input class="text-input" type="text" name="user"/>
					</p>
					<div class="clear"></div>
					<p>
						<label>Password</label>
						<input class="text-input" type="password" name="pass"/>
					</p>

					<div class="clear"></div>
				
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="login" />
					</p>
					
				</form>
			</div> 
			
		</div> 
		
  </body>
  

</html>
