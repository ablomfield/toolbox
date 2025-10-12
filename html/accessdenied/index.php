<?php
$sitesec = "home";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

// Get Login Details
if (isset($_SESSION['authtoken'])) {
	$loggedin = True;
	$displayname = $_SESSION["displayname"];
	$authtoken = $_SESSION["authtoken"];
	$orgname = $_SESSION["orgname"];
} else {
	$loggedin = False;
}

if (isset($_SESSION["personid"])) {
	$personid = $_SESSION["personid"];
} else {
	$personid = "";
}

if (isset($_GET["reason"])) {
	$reason = $_GET["reason"];
} else {
	$reason = "";
}

if (isset($_GET["domain"])) {
	$domain = $_GET["domain"];
} else {
	$domain = "";
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body class="is-preload">
	<div id="page-wrapper">
		<!-- Header -->
		<?php include($_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"); ?>
		<!-- Main Content -->
		<section class="wrapper style1">
			<div class="container">
				<div class="row gtr-200 aln-middle">
					<section class="col-8">
						<div class="box post">
							<h1>Access Denied!</h1>
							<?php
							if ($reason == "selfregistration") {
								echo ("<p>Users cannot log in until set up by an administrator. Please contact the administrator for further assistance.</p>\n");
							} elseif ($reason == "domainlock") {
								echo ("<p>Users from $domain are not allowed to access this system. Please contact the administrator for further assistance.</p>\n");
							}
							?>
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