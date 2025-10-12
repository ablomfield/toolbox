<?php
include($_SERVER['DOCUMENT_ROOT'] . "/includes/webexfunctions.php");

if (isset($_REQUEST["action"])) {
  $action = $_REQUEST["action"];
} else {
  die("Sorry, an error has occured.");
}

if (isset($_REQUEST["emails"])) {
  $emails = $_REQUEST["emails"];
  $emailsformatted = str_replace("\r", "", $emails);
  $emailarr = explode("\n", $emailsformatted);
} else {
  die("Sorry, an error has occured.");
}

$orgid = $_SESSION["orgid"];
$putjson = "{\"callForwarding\":{\"always\":{\"enabled\":false}}}";

// Unforward Users
$usercount = count($emailarr);
echo ("Attempting to unforward $usercount user(s)...<br>\n");
for ($x = 0; $x < $usercount; $x++) {
  //echo ("Checking $emailarr[$x].<br />\n");
  $personid = webexgetpersonid($authtoken, $emailarr[$x]);
  if ($personid != NULL) {
    $fwdurl = "https://webexapis.com/people/$personid/features/callForwarding?orgId=$orgid";
    $putfwd = curl_init($acturl);
    curl_setopt($putfwd, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($putfwd, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($putfwd, CURLOPT_POSTFIELDS, $putjson);
    curl_setopt(
      $putfwd,
      CURLOPT_HTTPHEADER,
      array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $authtoken
      )
    );
    $fwddata = curl_exec($putfwd);
    $fwdjson = json_decode($fwddata);
    $fwdarray = json_decode($fwddata, true);

    if (curl_getinfo($putact, CURLINFO_HTTP_CODE) == "204") {
      echo ("$emailarr[$x] - Successfully unforwarded.<br />\n");
    } else {
      echo ("$emailarr[$x] - Unforwarded failed.<br />\n");
    }
  } else {
    echo ("$emailarr[$x] - User not found.<br />\n");
  }
}
echo ("           <table class=\"default\">\n");
echo ("             <tr>\n");
echo ("					      <form method=\"post\">\n");
echo ("					      <input type=\"hidden\" name=\"toolstep\" value=\"" . ($toolstep - 1) . "\">\n");
echo ("					      <input type=\"hidden\" name=\"emails\" value=\"$emails\">\n");
echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("             </tr>\n");
echo ("           </table>\n");
if ($enabledebug) {
  echo ("  <textarea style=\"width:800px; height:300px;\">\n");
  //echo ("URL: $acturl\n");
  //echo ("Data:\n");
  //print_r($postdata);
  //echo ("\n");
  //echo ("JSON:\n$postjson\n");
  //echo ("Auth Token: $authtoken\n");
  //echo ("Error Code: " . curl_getinfo($putact, CURLINFO_HTTP_CODE) . "\n");
  //echo ("Activation Response:\n");
  //print_r($actdata);
  echo ("  </textarea><br>\n");
}
