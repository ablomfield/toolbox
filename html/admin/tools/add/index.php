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

// User Actions
if ($action == "add") {
	$dbconn->query("INSERT INTO tools (`fkuser`, `dateadded`, `isactive`, `name`, `icon`, `path`) VALUES('$userpkid', now(), " . $_REQUEST['isactive'] . ", '" . $_REQUEST['name'] . "', '" . $_REQUEST['icon'] . "', '" . $_REQUEST['path'] . "')");
	mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','Added tool " . $_REQUEST['name'] . "')");
	header("Location: /admin/users/");
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
							<input type="hidden" name="action" value="add">
							<table>
								<tr>
									<td>Name</td>
									<td><input type="text" name="name" size="50" value="">
								</tr>
								<tr>
									<td>
										<label class="checkboxcontainer">
											<input type="checkbox" name="isactive" value="1">
											<span class="checkmark"></span>
										</label>
										<input type="checkbox" name="isactive" value="1">
									</td>
								</tr>
								<tr>
									<td>Icon</td>
									<td><input type="text" name="icon" size="50" value="">
								</tr>
								<tr>
									<td>Path</td>
									<td><input type="text" name="path" size="50" value="">
								</tr>
								<tr>
									<td><input type="submit" value="Add Tool" class="small">
						</form>
						<td></td>
						</tr>
						</table>
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