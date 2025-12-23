<?php
$sitesec = "tools";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

// Get Login Details
include($_SERVER['DOCUMENT_ROOT'] . "/includes/checkaccess.php");

// Get LGW Step
if (isset($_REQUEST['lgwstep'])) {
	$lgwstep = $_REQUEST['lgwstep'];
} elseif (isset($_SESSION['lgwstep'])) {
	$lgwstep = $_SESSION['lgwstep'];
} else {
	$lgwstep = 0;
}

?>
<!DOCTYPE HTML>
<html>

<head>
	<title><?php echo ($sitetitle); ?></title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="/assets/css/main.css" />
	<link rel="icon" type="image/x-icon" href="/images/icononly_transparent_nobuffer.png">
	<script src="https://kit.fontawesome.com/5a918f4f0f.js" crossorigin="anonymous"></script>
</head>

<body class="is-preload">
	<script>
		function myFunction(fieldid) {
			// Get the text field
			var copyText = document.getElementById(fieldid);

			// Select the text field
			copyText.select();
			copyText.setSelectionRange(0, 99999); // For mobile devices

			// Copy the text inside the text field
			navigator.clipboard.writeText(copyText.value);

			// Unselect the text field
			window.getSelection().removeAllRanges();
		}
	</script>
	<div id="page-wrapper">
		<!-- Header -->
		<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
		<!-- Main -->
		<section class="wrapper style1">
			<div class="container">
				<div id="content">
					<!-- Content -->
					<article>
						<?php
						if ($lgwstep == 0) {
							include("steps/0.php");
						} elseif ($lgwstep == 1) {
							include("steps/1.php");
						} elseif ($lgwstep == 2) {
							include("steps/2.php");
						} elseif ($lgwstep == 3) {
							include("steps/3.php");
						} elseif ($lgwstep == 4) {
							include("steps/4.php");
						}
						?>
					</article>
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