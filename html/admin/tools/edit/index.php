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

if (isset($_REQUEST['isactive'])) {
	$setisactive = 1;
} else {
	$setisactive = 0;
}


// User Actions
if ($action == "update" && $pkid <> "") {
	$dbconn->query("UPDATE tools SET name = '" . $_REQUEST['name'] . "', isactive = " . $setisactive . ", icon = '" . $_REQUEST['icon'] . "', path = '" . $_REQUEST['path'] . "' WHERE pkid = '" . $pkid . "'");
	mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','Updated tools " . $_REQUEST['name'] . "')");
	header("Location: /admin/tools/");
}

if ($action == "delete" && $pkid <> "") {
	$dbconn->query("DELETE FROM tools WHERE pkid = '" . $pkid . "'");
	mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','Deleted tool " . $_REQUEST['name'] . "')");
	header("Location: /admin/tools/");
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
							$rstool = mysqli_query($dbconn, "SELECT tools.name, tools.isactive, tools.icon, tools.path, tools.dateadded, users.email FROM tools LEFT JOIN users ON tools.fkuser = users.pkid WHERE tools.pkid = '" . $pkid . "'");
							$row = mysqli_fetch_assoc($rstool);
							$name   = $row["name"];
							$isactive   = $row["isactive"];
							$icon = $row["icon"];
							$path = $row["path"];
							if ($row["dateadded"] != null) {
								$dateadded = new DateTime($row["dateadded"], new DateTimeZone('GMT'));
								$dateadded->setTimezone(new DateTimeZone($timezone));
								$dateadded = $dateadded->format('Y-m-d H:i:s');
							} else {
								$dateadded = "-";
							}
							$addedby = $row["email"];
							?>
							<input type="hidden" name="action" value="update">
							<input type="hidden" name="pkid" value="<?php echo ($pkid); ?>">
							<table class="default">
								<tr>
									<td>Name</td>
									<td><input type="text" name="name" size="50" value="<?php echo ($name); ?>">
								</tr>
								<tr>
									<td>Active</td>
									<td>
										<label class="checkboxcontainer">
											<input type="checkbox" name="isactive" value="1" <?php if ($isactive) {
																									echo (" checked");
																								} ?>>
											<span class="checkmark"></span>
										</label>
										<input type="checkbox" name="isactive" value="1">
									</td>
								</tr>
								<tr>
									<td>Icon</td>
									<td><input type="text" name="icon" size="50" value="<?php echo ($icon); ?>">
								</tr>
								<tr>
									<td>Path</td>
									<td><input type="text" name="path" size="50" value="<?php echo ($path); ?>">
								</tr>
								<tr>
									<td>Added By</td>
									<td><input type="text" name="fkuser" size="50" value="<?php echo ($addedby); ?>" disabled>
								</tr>
								<tr>
									<td>Added On</td>
									<td><input type="text" name="addedon" size="50" value="<?php echo ($dateadded); ?>" disabled>
								</tr>
								<tr>
									<td><input type="submit" value="Update Tool" class="small"></td>
						</form>
						<td>
							<form method="post" onsubmit="return confirm('Are you sure you want to delete <?php echo ($name); ?>?');">
								<input type="hidden" name="action" value="delete">
								<input type="hidden" name="pkid" value="<?php echo ($pkid); ?>">
								<input type="hidden" name="name" value="<?php echo ($name); ?>">
								<input type="submit" value="Delete Tool" class="small">
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