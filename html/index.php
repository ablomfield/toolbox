<?php
$sitesec = "home";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo($sitetitle); ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/assets/css/main.css" />
  		<link rel="icon" type="image/x-icon" href="/images/icononly_transparent_nobuffer.png">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
	</head>
	<body class="is-preload">
		<div id="page-wrapper">
			<!-- Header -->
<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
			<!-- Highlights -->
				<section class="wrapper style1">
					<div class="container">
						<div class="row gtr-200">
							<section class="col-6 col-12-narrower">
								<div class="box highlight">
									<i class="icon major solid fa-network-wired" style="text-decoration: none;"></i>
									<h3><a href="/lgw">LGW Config Generator</a></h3>
								</div>
								<div class="box highlight">
									<i class="icon major solid fa-network-wired" style="text-decoration: none;"></i>
									<h3><a href="/lgw">Analog Gateway Tool</a></h3>
								</div>								
							</section>
						</div>
					</div>
				</section>

			<!-- Footer -->
<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"); ?>
		</div>
		<!-- Scripts -->
			<script src="/assets/js/jquery.min.js"></script>
			<script src="/assets/js/jquery.dropotron.min.js"></script>
			<script src="/assets/js/browser.min.js"></script>
			<script src="/assets/js/breakpoints.min.js"></script>
			<script src="/assets/js/util.js"></script>
			<script src="/assets/js/main.js"></script>		
	</body>
</html>