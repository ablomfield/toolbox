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

if (isset($_REQUEST["emails"])) {
  $emails = $_REQUEST["emails"];
  $emailsformatted = str_replace("\r", "", $emails);
  $emailarr = explode("\n", $emailsformatted);
} else {
  die("Sorry, an error has occured.");
}

$orgid = $_SESSION["orgid"];


// Activate/Deactivate Numbers

if ($_SESSION['enabledebug']) {
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
