<?php
$sitesec = "customers";
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
	$isadmin = $_SESSION["isadmin"];
} else {
	$loggedin = False;
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
							<h1>Select Customer:</h1>
							<?php
							// Retrieve Org List
							$orgsurl = "https://webexapis.com/v1/organizations";
							$getorgs = curl_init($orgsurl);
							curl_setopt($getorgs, CURLOPT_CUSTOMREQUEST, "GET");
							curl_setopt($getorgs, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($getorgs, CURLOPT_FAILONERROR, true);
							curl_setopt(
								$getorgs,
								CURLOPT_HTTPHEADER,
								array(
									'Content-Type: application/json',
									'Authorization: Bearer ' . $authtoken
								)
							);
							$orgsdata = curl_exec($getorgs);
							if (curl_errno($getorgs) == "0") {
								$orgsjson = json_decode($orgsdata);
								$orgsarray = json_decode($orgsdata, true);
								$orgcount = count($orgsarray['items']);
								echo ("					  <p>Select an organization to search for trunks:</p>\n");
								echo ("					  <form method=\"post\" action=\"/lgw/\">\n");
								echo ("					    <input type=\"hidden\" name=\"lgwstep\" value=\"2\">\n");
								echo ("					    <table class=\"default\">\n");
								for ($x = 0; $x < $orgcount; $x++) {
									echo ("					      <tr>\n");
									echo ("					        <td>\n");
									echo ("     					    <label class=\"radio-container\">\n");
									echo ("			     		        <input type=\"radio\" name=\"orgid\" value=\"" . $orgsjson->items[$x]->id . "\">\n");
									echo ("					            <span class=\"radio-checkmark\"></span>\n");
									echo ("					        </td>\n");
									echo ("					        <td>\n");
									echo ("					         " . $orgsjson->items[$x]->displayName . "</label>\n");
									echo ("					        </td>\n");
									echo ("					      </tr>\n");
								}
								echo ("					      <tr>\n");
								echo ("					        <td colspan=\"2\">\n");
								echo ("					          <input type=\"submit\" value=\"Continue\" class=\"button\"><br/>\n");
								echo ("					        </td>\n");
								echo ("					      </tr>\n");
								echo ("					    </table>\n");
								echo ("					  </form>\n");
							} else {
								echo "					  <p>Sorry, you don't have access to any organizations.</p>\n";
							}
							echo ("           <table class=\"default\">\n");
							echo ("             <tr>\n");
							echo ("					      <form method=\"post\">\n");
							echo ("					      <input type=\"hidden\" name=\"lgwstep\" value=\"" . ($lgwstep - 1) . "\">\n");
							echo ("					      <td><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
							echo ("					      </form>\n");
							echo ("					      <form method=\"post\" action=\"/lgw/logout\">\n");
							echo ("					      <td><input type=\"submit\" value=\"Start Over\" class=\"button\"></td>\n");
							echo ("					      </form>\n");
							echo ("             </tr>\n");
							echo ("           </table>\n");
							?>
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