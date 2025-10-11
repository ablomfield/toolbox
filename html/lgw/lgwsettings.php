<?php
$rslgwsettings = mysqli_query($dbconn, "SELECT * FROM lgwsettings") or die("Error in Selecting " . mysqli_error($dbconn));
$rowlgwsettings = mysqli_fetch_assoc($rslgwsettings);
$client_id = $rowlgwsettings["client_id"];
$client_secret = $rowlgwsettings["client_secret"];
$integration_id = $rowlgwsettings["integration_id"];
$oauth_url = $rowlgwsettings["oauth_url"];
?>