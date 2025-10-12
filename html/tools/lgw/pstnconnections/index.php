<?php
$sitesec = "tools";
session_start();
date_default_timezone_set("America/Chicago");

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");
include($_SERVER['DOCUMENT_ROOT'] . "/lgw/lgwsettings.php");

// Get Login Details
if (isset($_SESSION['authtoken'])) {
	$loggedin = True;
	$displayname = $_SESSION["displayname"];
	$authtoken = $_SESSION["authtoken"];
	$orgname = $_SESSION["orgname"];
} else {
	$loggedin = False;
}

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
	<title>CollabToolbox</title>
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
if (isset($_REQUEST["orgid"])) {
  $orgid = $_REQUEST["orgid"];
  $_SESSION["orgid"] = $orgid;
} elseif (isset($_SESSION["orgid"])) {
  $orgid = $_SESSION["orgid"];
} else {
  die("Sorry, an error has occured.");
}

// Retrieve Organization Details
$orgurl = "https://webexapis.com/v1/organizations/$orgid";
$getorg = curl_init($orgurl);
curl_setopt($getorg, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($getorg, CURLOPT_RETURNTRANSFER, true);
curl_setopt($getorg, CURLOPT_FAILONERROR, true);
curl_setopt(
  $getorg,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$orgdata = curl_exec($getorg);
$orgjson = json_decode($orgdata);
$orgname = $orgjson->displayName;
$_SESSION["orgname"] = $orgname ;

// Retrieve Location List
$locsurl = "https://webexapis.com/v1/locations?orgId=$orgid";
$getlocs = curl_init($locsurl);
curl_setopt($getlocs, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($getlocs, CURLOPT_RETURNTRANSFER, true);
curl_setopt($getlocs, CURLOPT_FAILONERROR, true);
curl_setopt(
  $getlocs,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$locsdata = curl_exec($getlocs);
if (curl_errno($getlocs) == "0") {
  $locsjson = json_decode($locsdata);
  $locsarray = json_decode($locsdata, true);
  $locscount = count($locsarray['items']);
  echo ("					  <p>Found " . $locscount . " locations(s) for $orgname</p>\n");
  echo ("					    <table class=\"default\">\n");
      echo ("					      <tr>\n");
      echo ("					        <th>Location</th>\n");
	  echo ("					        <th>PSTN Connection</th>\n");
	  echo ("					      </tr>\n");
  for ($x = 0; $x < $locscount; $x++) {
      echo ("					      <tr>\n");
      echo ("					        <td>\n");
      echo ("					         " . $locsjson->items[$x]->name . "</label>\n");
      echo ("					        </td>\n");
	  // Retrieve PSTN Connection List
$connurl = "https://webexapis.com/v1/telephony/pstn/locations/" . $locsjson->items[$x]->id . "/connection?orgId=$orgid";
$getconn = curl_init($connurl);
curl_setopt($getconn, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($getconn, CURLOPT_RETURNTRANSFER, true);
curl_setopt($getconn, CURLOPT_FAILONERROR, true);
curl_setopt(
  $getconn,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$conndata = curl_exec($getconn);
if (curl_errno($getconn) == "0") {
	$connjson = json_decode($conndata);
	$conntype = $connjson->displayName;
 } else {
	$conntype = "";
 }
      echo ("					        <td>" . $conntype . "</td>\n");
      echo ("					      </tr>\n");
  }
  echo ("					    </table>\n");
} else {
  echo "					  <p>Sorry, you don't have access to any organizations.</p>\n";
}
echo ("           <table class=\"default\">\n");
echo ("             <tr>\n");
echo ("					      <form method=\"post\" action=\"/lgw/logout\">\n");
echo ("					      <td><input type=\"submit\" value=\"Start Over\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("             </tr>\n");
echo ("           </table>\n");
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