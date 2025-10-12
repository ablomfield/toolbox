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
$putjson = "{\n    \"callForwarding\": {\n        \"always\": {\n            \"enabled\": false\n        }\n    }\n}";

// Unforward Users
$usercount = count($emailarr);
if ($usercount > 0) {
  echo ("Attempting to unforward $usercount user(s)...<br>\n");
  echo ("<table class=\"default\">\n");
  echo ("<thead>\n");
  echo ("<tr>\n");
  echo ("<th>User</th>\n");
  echo ("<th>Result</th>\n");
  echo ("</tr>\n");
  echo ("</thead>\n");
  echo ("<tbody>\n");
  for ($x = 0; $x < $usercount; $x++) {
    //echo ("Checking $emailarr[$x].<br />\n");
    $personid = webexgetpersonid($authtoken, $emailarr[$x]);
    if ($personid != NULL) {
      $fwdurl = "https://webexapis.com/people/$personid/features/callForwarding?orgId=$orgid";
      $putfwd = curl_init($fwdurl);
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

      if (curl_getinfo($putfwd, CURLINFO_HTTP_CODE) == "204") {
        echo ("<tr>\n");
        echo ("<td>$emailarr[$x]</td>\n");
        echo ("<td>Successful</td>\n");
        echo ("</tr>\n");
      } else {
        echo ("<tr>\n");
        echo ("<td>$emailarr[$x]</td>\n");
        echo ("<td>Failed</td>\n");
        echo ("</tr>\n");
      }
    } else {
      echo ("<tr>\n");
      echo ("<td>$emailarr[$x]</td>\n");
      echo ("<td>Not Found</td>\n");
      echo ("</tr>\n");
    }
  }
  echo ("</tbody>\n");
  echo ("</table>\n");
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
  echo ("URL: $puturl\n");
  echo ("\n");
  echo ("JSON:\n$putjson\n");
  echo ("Auth Token: $authtoken\n");
  echo ("Error Code: " . curl_getinfo($putfwd, CURLINFO_HTTP_CODE) . "\n");
  echo ("Activation Response:\n");
  print_r($fwddata);
  echo ("  </textarea><br>\n");
}
