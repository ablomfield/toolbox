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

if (isset($_REQUEST['lockdomains'])) {
	$setlockdomains = 1;
} else {
	$setlockdomains = 0;
}

if (isset($_REQUEST['selfregistration'])) {
	$setselfregistration = 1;
} else {
	$setselfregistration = 0;
}


// User Actions
if ($action == "update") {
	$dbconn->query("UPDATE settings SET `sitetitle` = '" . $_REQUEST['sitetitle'] . "', `client_id` = '" . $_REQUEST['client_id'] . "', `client_secret` = '" . $_REQUEST['client_secret'] . "', `integration_id` = '" . $_REQUEST['integration_id'] . "', `oauth_url` = '" . $_REQUEST['oauth_url'] . "', `lockdomains` = $setlockdomains, `selfregistration` = $setselfregistration");
	mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','Updated settings')");
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
							$rstool = mysqli_query($dbconn, "SELECT * FROM settings");
							$row = mysqli_fetch_assoc($rstool);
							$sitetitle   = $row["sitetitle"];
							$client_id   = $row["client_id"];
							$client_secret   = $row["client_secret"];
							$integration_id   = $row["integration_id"];
							$oauth_url = $row["oauth_url"];
							$lockdomains = $row["lockdomains"];
							$selfregistration = $row["selfregistration"];
							?>
							<input type="hidden" name="action" value="update">
							<table class="default">
								<tr>
									<td colspan="2"><b>General Settings</b></td>
								</tr>
								<tr>
									<td>Site Title</td>
									<td><input type="text" name="sitetitle" size="50" value="<?php echo ($sitetitle); ?>">
								</tr>
								<tr>
									<td colspan="2"><b>Integration Settings</b></td>
								</tr>
								<tr>
									<td>Client ID</td>
									<td><input type="text" name="client_id" size="50" value="<?php echo ($client_id); ?>">
								</tr>
								<tr>
									<td>Client Secret</td>
									<td><input type="text" name="client_secret" size="50" value="<?php echo ($client_secret); ?>">
								</tr>
								<tr>
									<td>Integration ID</td>
									<td><input type="text" name="integration_id" size="50" value="<?php echo ($integration_id); ?>">
								</tr>
								<tr>
									<td>OAuth URL</td>
									<td><input type="text" name="oauth_url" size="50" value="<?php echo ($oauth_url); ?>">
								</tr>
								<tr>
									<td colspan="2"><b>Login Settings</b></td>
								</tr>
								<tr>
									<td>Lock Domains</td>
									<td>
										<label class="checkboxcontainer">
											<input type="checkbox" name="lockdomains" value="1" <?php if ($lockdomains) {
																									echo (" checked");
																								} ?>>
											<span class="checkmark"></span>
										</label>
										<input type="checkbox" name="lockdomains" value="1">
									</td>
								</tr>
								<tr>
									<td>Self Registration</td>
									<td>
										<label class="checkboxcontainer">
											<input type="checkbox" name="selfregistration" value="1" <?php if ($selfregistration) {
																											echo (" checked");
																										} ?>>
											<span class="checkmark"></span>
										</label>
										<input type="checkbox" name="selfregistration" value="1">
									</td>
								</tr>
								<tr>
									<td colspan="2"><input type="submit" value="Update Settings" class="small"></td>
								</tr>
							</table>
						</form>
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