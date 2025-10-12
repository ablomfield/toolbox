<?php
if (isset($_REQUEST["locationid"])) {
  $locationid = $_REQUEST["locationid"];
  $_SESSION["locationid"] = $locationid;
} elseif (isset($_SESSION["locationid"])) {
  $locationid = $_SESSION["locationid"];
} else {
  die("Sorry, an error has occured.");
}
$orgid = $_SESSION["orgid"];

echo ("<br/>\n");
// Retrieve Location PSTN Information
$locationurl = "https://webexapis.com/v1/telephony/pstn/locations/$locationid/connection?orgId=$orgid";
$getlocation = curl_init($locationurl);
curl_setopt($getlocation, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($getlocation, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
  $getlocation,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$locationdata = curl_exec($getlocation);
$locationjson = json_decode($locationdata);
if (curl_getinfo($getlocation, CURLINFO_HTTP_CODE) == "200") {
  if ($locationjson->pstnConnectionType != "LOCAL_GATEWAY") {
    echo ("					  <p><font color=\"red\">Warning! Invalid PSTN connection type. Cannot activate or deactivate numbers!</font></p>\n");
    echo ("           <table class=\"default\">\n");
    echo ("             <tr>\n");
    echo ("					      <form method=\"post\">\n");
    echo ("					      <input type=\"hidden\" name=\"toolstep\" value=\"" . ($toolstep - 1) . "\">\n");
    echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
    echo ("					      </form>\n");
    echo ("             </tr>\n");
    echo ("           </table>\n");
  } else {
    echo ("					  <form method=\"post\"\">\n");
    echo ("					  <input type=\"hidden\" name=\"toolstep\" value=\"3\">\n");
    echo ("					  <input type=\"hidden\" name=\"locationid\" value=\"$locationid\">\n");
    echo ("					  <table class=\"default\">\n");
    echo ("					    <tr>\n");
    echo ("					      <th align=\"right\" colspan=\"2\">Action:</th>\n");
    echo ("					    </tr>\n");
    echo ("					    <tr>\n");
    echo ("					      <td><label class=\"radio-container\"><input type=\"radio\" name=\"action\" value=\"activate\"><span class=\"radio-checkmark\"></span></td>\n");
    echo ("					      <td>Activate</label></td>\n");
    echo ("					    </tr>\n");
    echo ("					    <tr>\n");
    echo ("					      <td align=\"right\"><label class=\"radio-container\"><input type=\"radio\" name=\"action\" value=\"deactivate\"><span class=\"radio-checkmark\"></span></td>\n");
    echo ("					      <td align=\"left\">Deactivate</label></td>\n");
    echo ("					    </tr>\n");    
    echo ("					    <tr>\n");
    echo ("					      <th colspan =\"2\" align=\"right\">Numbers:</th>\n");
    echo ("					    </tr>\n");
    echo ("					    <tr>\n");
    echo ("					      <th colspan =\"2\" align=\"right\"><textarea name=\"number\"></textarea></th>\n");
    echo ("					    </tr>\n");
    echo ("					    <tr>\n");
    echo ("					      <td align=\"left\"><input type=\"submit\" value=\"Continue\"></td>\n");
    echo ("					      <td align=\"right\">&nbsp;</td>\n");
    echo ("					    </tr>\n");
    echo ("					  </table>\n");
    echo ("					  </form>\n");
    echo ("           <table class=\"default\">\n");
    echo ("             <tr>\n");
    echo ("					      <form method=\"post\">\n");
    echo ("					      <input type=\"hidden\" name=\"toolstep\" value=\"" . ($toolstep - 1) . "\">\n");
    echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
    echo ("					      </form>\n");
    echo ("             </tr>\n");
    echo ("           </table>\n");
  }
} else {
  echo ("					  <p><font color=\"red\">Warning! Not a valid Webex Calling enabled location!</font></p>\n");
  echo ("           <table class=\"default\">\n");
  echo ("             <tr>\n");
  echo ("					      <form method=\"post\">\n");
  echo ("					      <input type=\"hidden\" name=\"toolstep\" value=\"" . ($toolstep - 1) . "\">\n");
  echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
  echo ("					      </form>\n");
  echo ("             </tr>\n");
  echo ("           </table>\n");
}
if ($_SESSION['enabledebug']) {
  echo ("  <textarea style=\"width:800px; height:300px;\">\n");
  echo ("URL: $locationurl\n");
  echo ("Auth Token: $authtoken\n");
  echo ("Error Code: " . curl_getinfo($getlocation, CURLINFO_HTTP_CODE) . "\n");
  echo ("Trunks Response:\n");
  print_r($locationdata);
  echo ("  </textarea><br>\n");
}
