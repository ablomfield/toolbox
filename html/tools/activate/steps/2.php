<?php
if (isset($_REQUEST["trunkid"])) {
  $trunkid = $_REQUEST["trunkid"];
  $_SESSION["trunkid"] = $trunkid;
} elseif (isset($_SESSION["trunkid"])) {
  $trunkid = $_SESSION["trunkid"];
} else {
  die("Sorry, an error has occured.");
}
$orgid = $_SESSION["orgid"];

echo ("<br/>\n");
// Retrieve Trunk Information
$trunksurl = "https://webexapis.com/v1/telephony/config/premisePstn/trunks/$trunkid?orgId=$orgid";
$gettrunks = curl_init($trunksurl);
curl_setopt($gettrunks, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($gettrunks, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
  $gettrunks,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$trunksdata = curl_exec($gettrunks);
$trunksjson = json_decode($trunksdata);
if ($trunksjson->status == "online") {
  echo ("					  <p><font color=\"red\">Warning! " . $trunksjson->name . " is currently online!</font></p>\n");
}
echo ("					  <p>Please enter the following information to build the configuration file...</p>\n");
echo ("					  <form method=\"post\"\">\n");
echo ("					  <input type=\"hidden\" name=\"lgwstep\" value=\"3\">\n");
echo ("					  <table class=\"default\">\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">Location</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" value=\"" . $trunksjson->location->name . "\" size=\"25\" disabled></td>\n");
echo ("					      <td align=\"left\">&nbsp</td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">SIP Username</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" value=\"" . $trunksjson->sipAuthenticationUserName . "\" size=\"25\" disabled></td>\n");
echo ("					      <td align=\"left\">&nbsp</td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">SIP Password</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" name=\"sippassword\" size=\"25\" required></td>\n");
echo ("					      <td align=\"left\"><font color=\"red\">*</font></td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">Inside Interface Name</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" name=\"insideint\" size=\"25\" required></td>\n");
echo ("					      <td align=\"left\"><font color=\"red\">*</font></td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">Outside Interface Name</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" name=\"outsideint\" size=\"25\" required></td>\n");
echo ("					      <td align=\"left\"><font color=\"red\">*</font></td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">UCM Node 1</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" name=\"cucm1\" size=\"25\" required></td>\n");
echo ("					      <td align=\"left\"><font color=\"red\">*</font></td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">UCM Node 2</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" name=\"cucm2\" size=\"25\"></td>\n");
echo ("					      <td align=\"left\">&nbsp</td>\n");
echo ("					    </tr>\n");
echo ("					    <tr>\n");
echo ("					      <th align=\"right\">UCM Node 3</th>\n");
echo ("					      <td align=\"left\"><input type=\"text\" name=\"cucm3\" size=\"25\"></td>\n");
echo ("					      <td align=\"left\">&nbsp</td>\n");
echo ("					    </tr>\n");
echo ("					      <td align=\"left\"><input type=\"submit\" value=\"Continue\"></td>\n");
echo ("					      <td align=\"right\">&nbsp;</td>\n");
echo ("					      <td align=\"left\">&nbsp</td>\n");
echo ("					    </tr>\n");
echo ("					  </table>\n");
echo ("					  </form>\n");
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