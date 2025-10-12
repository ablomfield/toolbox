<?php
$sitesec = "admin";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

// Get Login Details
include($_SERVER['DOCUMENT_ROOT'] . "/includes/checklogin.php");

// Check Admin
if ($isadmin == false) {
	header('Location: /');
}

// Check Post
if (isset($_REQUEST['action'])) {
	$action = $_REQUEST['action'];
} elseif (isset($_GET['action'])) {
	$action = $_GET['action'];
} else {
	$action = '';
}

if (isset($_REQUEST['pkid'])) {
	$pkid = $_REQUEST['pkid'];
} elseif (isset($_GET['pkid'])) {
	$pkid = $_GET['pkid'];
} else {
	$pkid = '';
}

if (isset($_REQUEST['domain'])) {
	$domain = $_REQUEST['domain'];
} elseif (isset($_GET['domain'])) {
	$domain = $_GET['domain'];
} else {
	$domain = '';
}

// User Actions
if ($action == "update" && $pkid <> "") {
	$dbconn->query("UPDATE regdomains SET domain = '" . $domain . "' WHERE pkid = '" . $pkid . "'");
	mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','Updated domain $domain')");
	header("Location: /admin/domains/");
}

if ($action == "delete" && $pkid <> "") {
	$dbconn->query("DELETE FROM regdomains WHERE pkid = '" . $pkid . "'");
	mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','Deleted domain $domain')");
	header("Location: /admin/domains/");
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
				<div class="row">
					<section class="col-12">
						<form method="post">
							<?php
							$rsuser = mysqli_query($dbconn, "SELECT * FROM regdomains WHERE pkid = '" . $pkid . "'");
							$rowuser = mysqli_fetch_assoc($rsuser);
							$domain   = $rowuser["domain"];
							?>
							<input type="hidden" name="action" value="update">
							<input type="hidden" name="pkid" value="<?php echo ($pkid); ?>">
							<table class="default">
								<tr>
									<td>Domain</td>
									<td><input type="text" name="domain" size="50" value="<?php echo ($domain); ?>">
								</tr>
								<tr>
									<td><input type="submit" value="Update Domain" class="small"></td>
						</form>
						<td>
							<form method="post" onsubmit="return confirm('Are you sure you want to delete <?php echo ($domain); ?>?');">
								<input type="hidden" name="action" value="delete">
								<input type="hidden" name="pkid" value="<?php echo ($pkid); ?>">
								<input type="hidden" name="domain" value="<?php echo ($domain); ?>">
								<input type="submit" value="Delete Domain" class="small">
							</form>
						</td>
						</tr>
						</table>
						<small>
							<p>Times are displayed in <?php echo ($timezone); ?>.</p>
						</small>
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