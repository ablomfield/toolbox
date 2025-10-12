<?php
if (isset($_REQUEST["locationid"])) {
  $locationid = $_REQUEST["locationid"];
  $_SESSION["locationid"] = $locationid;
} elseif (isset($_SESSION["locationid"])) {
  $locationid = $_SESSION["locationid"];
} else {
  die("Sorry, an error has occured.");
}

if (isset($_REQUEST["action"])) {
  $action = $_REQUEST["action"];
} else {
  die("Sorry, an error has occured.");
}

if (isset($_REQUEST["numbers"])) {
  $numbers = $_REQUEST["numbers"];
  $numbers = str_replace("\r", "", $numbers);
  $numarr = explode("\n",$numbers);
} else {
  die("Sorry, an error has occured.");
}

$orgid = $_SESSION["orgid"];


// Activate/Deactivate Numbers
$postdata = array(
  'phoneNumbers' => $numarr,
  'action' => $action
);
$postjson = json_encode($postdata);

$acturl = "https://webexapis.com/v1/telephony/config/locations/$locationid/numbers?orgId=$orgid";
$putact = curl_init($acturl);
curl_setopt($putact, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($putact, CURLOPT_RETURNTRANSFER, true);
curl_setopt($putact, CURLOPT_POSTFIELDS, $postjson);
curl_setopt(
  $putact,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$actdata = curl_exec($putact);
$actjson = json_decode($actdata);

echo ("           <table class=\"default\">\n");
echo ("             <tr>\n");
echo ("					      <form method=\"post\">\n");
echo ("					      <input type=\"hidden\" name=\"toolstep\" value=\"" . ($toolstep - 1) . "\">\n");
echo ("					      <td colspan=\"2\"><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("             </tr>\n");
echo ("           </table>\n");
if ($_SESSION['enabledebug']) {
  echo ("  <textarea style=\"width:800px; height:300px;\">\n");
  echo ("URL: $acturl\n");
  echo ("Data:\n");
  print_r($postdata);
  echo ("\n");  
  echo ("JSON:\n$postjson\n");
  echo ("Auth Token: $authtoken\n");
  echo ("Error Code: " . curl_getinfo($putact, CURLINFO_HTTP_CODE) . "\n");
  echo ("Activation Response:\n");
  print_r($actdata);
  echo ("  </textarea><br>\n");
}
