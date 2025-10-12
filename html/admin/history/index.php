<?php
$sitesec = "none";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

// Get Login Details
include($_SERVER['DOCUMENT_ROOT'] . "/includes/checklogin.php");

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
						<table class="default">
							<thead>
								<tr>
									<th style="text-align: left;">Time</th>
									<th style="text-align: left;">Source</th>
									<th style="text-align: left;">Details</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$rsdata = mysqli_query($dbconn, "SELECT eventdate, eventsource, eventdesc FROM history ORDER BY eventdate DESC") or die("Error in Selecting " .
									mysqli_error($dbconn));
								if ($rsdata) {
									if (mysqli_num_rows($rsdata) > 0) {
										while ($row = mysqli_fetch_assoc($rsdata)) {
											echo "										<tr>\n";
											if ($row["eventdate"] != null) {
												$eventdate = new DateTime($row["eventdate"], new DateTimeZone('America/New_York'));
												$eventdate->setTimezone(new DateTimeZone($timezone));
												$eventdate = $eventdate->format('Y-m-d H:i:s');
											} else {
												$eventdate = "-";
											}
											echo "											<td style=\"text-align: left;\">" . $eventdate . "</td>\n";
											echo "											<td style=\"text-align: left;\">" . $row["eventsource"] . "</td>\n";
											echo "											<td style=\"text-align: left;\">" . $row["eventdesc"] . "</td>\n";
											echo "										</tr>\n";
										}
									}
								}
								?> </tbody>
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