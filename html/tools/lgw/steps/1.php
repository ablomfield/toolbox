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
$_SESSION["orgname"] = $orgname;

// Retrieve Trunk List
$trunksurl = "https://webexapis.com/v1/telephony/config/premisePstn/trunks?orgId=$orgid";
$gettrunks = curl_init($trunksurl);
curl_setopt($gettrunks, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($gettrunks, CURLOPT_RETURNTRANSFER, true);
curl_setopt($gettrunks, CURLOPT_FAILONERROR, true);
curl_setopt(
  $gettrunks,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$trunksdata = curl_exec($gettrunks);
if (curl_errno($gettrunks) == "0") {
  $trunksjson = json_decode($trunksdata);
  $trunksarray = json_decode($trunksdata, true);
  $trunkcount = count($trunksarray['trunks']);
  echo ("					  <p>Found " . $trunkcount . " trunk(s) for $orgname</p>\n");
  echo ("					  <p>Select trunk to build configuration</p>\n");
  echo ("					  <form method=\"post\">\n");
  echo ("					    <input type=\"hidden\" name=\"lgwstep\" value=\"2\">\n");
  echo ("					    <table class=\"default\">\n");
  for ($x = 0; $x < $trunkcount; $x++) {
    if ($trunksjson->trunks[$x]->trunkType == "REGISTERING") {
      echo ("					      <tr>\n");
      echo ("					        <td>\n");
      echo ("     					    <label class=\"radio-container\">\n");
      echo ("			     		        <input type=\"radio\" name=\"trunkid\" value=\"" . $trunksjson->trunks[$x]->id . "\">\n");
      echo ("					            <span class=\"radio-checkmark\"></span>\n");
      echo ("					        </td>\n");
      echo ("					        <td>\n");
      echo ("					         " . $trunksjson->trunks[$x]->name . "</label>\n");
      echo ("					        </td>\n");
      echo ("					      </tr>\n");
    }
  }
  echo ("					      <tr>\n");
  echo ("					        <td colspan=\"2\">\n");
  echo ("					          <input type=\"submit\" value=\"Continue\" class=\"button\"><br/>\n");
  echo ("					        </td>\n");
  echo ("					      </tr>\n");
  echo ("					    </table>\n");
  echo ("					  </form>\n");
} else {
  echo "					  <p>Sorry, no trunks found.</p>\n";
}
echo ("           <table class=\"default\">\n");
echo ("             <tr>\n");
echo ("					      <form method=\"post\">\n");
echo ("					      <input type=\"hidden\" name=\"lgwstep\" value=\"" . ($lgwstep - 1) . "\">\n");
echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("             </tr>\n");
echo ("           </table>\n");
if ($_SESSION['enabledebug']) {
  echo ("  <textarea style=\"width:800px; height:300px;\">\n");
  echo ("URL: $trunksurl\n");
  echo ("Auth Token: $authtoken\n");
  echo ("Error Code: " . curl_getinfo($gettrunks, CURLINFO_HTTP_CODE) . "\n");
  echo ("Trunks Response:\n");
  print_r($trunksdata);
  echo ("  </textarea><br>\n");
}
