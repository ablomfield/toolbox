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

if (isset($_REQUEST["emails"])) {
  $emails = $_REQUEST["emails"];
} else {
  $emails = "";
}

echo ("<form method=\"post\">\n");
echo (" <input type=\"hidden\" name=\"toolstep\" value=\"2\">\n");
echo (" <input type=\"hidden\" name=\"action\" value=\"unforward\">\n");
echo (" <p>User Emails:</p>\n");
echo (" <textarea name=\"emails\" rows=\"10\" cols=\"25\">$emails</textarea>\n");
echo (" <br />\n");
echo (" <input type=\"submit\" value=\"Remove Fowarding\">\n");
echo ("</form>\n");

if ($_SESSION['enabledebug']) {
  echo ("  <textarea style=\"width:800px; height:300px;\">\n");
  echo ("URL: $locationurl\n");
  echo ("Auth Token: $authtoken\n");
  echo ("Error Code: " . curl_getinfo($getlocation, CURLINFO_HTTP_CODE) . "\n");
  echo ("Trunks Response:\n");
  print_r($locationdata);
  echo ("  </textarea><br>\n");
}