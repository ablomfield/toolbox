<?php
if (isset($_REQUEST["orgid"])) {
  $orgid = $_REQUEST["orgid"];
  $_SESSION["orgid"] = $orgid;
} elseif (isset($_SESSION["orgid"])) {
  $orgid = $_SESSION["orgid"];
} else {
  die("Sorry, an error has occured.");
}

// Retrieve Locations
$locationsurl = "https://webexapis.com/v1/locations?orgId=$orgid";
$getlocations = curl_init($locationsurl);
curl_setopt($getlocations, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($getlocations, CURLOPT_RETURNTRANSFER, true);
curl_setopt($getlocations, CURLOPT_FAILONERROR, true);
curl_setopt(
  $getlocations,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$locationsdata = curl_exec($getlocations);
if (curl_errno($getlocations) == "0") {
  $locationsjson = json_decode($locationsdata);
  $locationsarray = json_decode($locationsdata, true);
  $locationcount = count($locationcount['trunks']);
  echo ("					  <p>Found " . $locationcount . " location(s) for $orgname</p>\n");
  if ($locationcount > 0) {
    echo ("					  <p>Select trunk to build configuration</p>\n");
    echo ("					  <form method=\"post\">\n");
    echo ("					    <input type=\"hidden\" name=\"lgwstep\" value=\"2\">\n");
    echo ("					    <table class=\"default\">\n");
    for ($x = 0; $x < $locationcount; $x++) {
        echo ("					      <tr>\n");
        echo ("					        <td>\n");
        echo ("     					    <label class=\"radio-container\">\n");
        echo ("			     		        <input type=\"radio\" name=\"locationid\" value=\"" . $locationsjson->items[$x]->id . "\">\n");
        echo ("					            <span class=\"radio-checkmark\"></span>\n");
        echo ("					        </td>\n");
        echo ("					        <td>\n");
        echo ("					         " . $locationsjson->items[$x]->name . "</label>\n");
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
  }
} else {
  echo "					  <p>Sorry, no trunks found.</p>\n";
}
echo ("           <table class=\"default\">\n");
echo ("             <tr>\n");
echo ("					      <form method=\"post\">\n");
echo ("					      <input type=\"hidden\" name=\"lgwstep\" value=\"" . ($toolstep - 1) . "\">\n");
echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("             </tr>\n");
echo ("           </table>\n");
if ($_SESSION['enabledebug']) {
  echo ("  <textarea style=\"width:800px; height:300px;\">\n");
  echo ("URL: $locationsurl\n");
  echo ("Auth Token: $authtoken\n");
  echo ("Error Code: " . curl_getinfo($getlocations, CURLINFO_HTTP_CODE) . "\n");
  echo ("Locations Response:\n");
  print_r($locationsdata);
  echo ("  </textarea><br>\n");
}
