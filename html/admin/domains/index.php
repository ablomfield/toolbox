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
									<th>Domain</th>
									<th>Added By</th>
									<th>Added On</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							<?php
							$rsdata = mysqli_query($dbconn, "SELECT regdomains.pkid, regdomains.domain, regdomains.dateadded, users.email FROM regdomains LEFT JOIN users ON regdomains.fkuser = users.pkid ORDER BY regdomains.domain") or die("Error in Selecting " .
								mysqli_error($dbconn));
							if ($rsdata) {
								if (mysqli_num_rows($rsdata) > 0) {
									while ($row = mysqli_fetch_assoc($rsdata)) {
										echo "      <tr>\n";
										echo "        <td>" . $row["domain"] . "</td>\n";
										if ($row["dateadded"] != null) {
											$dateadded = new DateTime($row["dateadded"], new DateTimeZone('GMT'));
											$dateadded->setTimezone(new DateTimeZone($timezone));
											$dateadded = $dateadded->format('Y-m-d H:i:s');
										} else {
											$dateadded = "-";
										}
										echo "        <td>" . $row["email"] . "</td>\n";
										echo "        <td>" . $dateadded . "</td>\n";
										echo "        <form action=\"edit/\" method=\"post\">\n";
										echo "        <input type=\"hidden\" name=\"pkid\" value=\"" . $row["pkid"] . "\">\n";
										echo "        <td><input type=\"submit\" value=\"Edit\" class=\"small\">\n";
										echo "        </form>\n";
										echo "      </tr>\n";
									}
								}
							}
							?>
							</tbody>
						</table>
						<p>
						<form method="post" action="/admin/domains/add/">
							<input type="submit" value="Add Domain" class="small">
						</form>
						</p>
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