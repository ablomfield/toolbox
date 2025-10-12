<?php
// Update History
$dbconn->query("UPDATE lgwhistory SET lgwstep = 1 WHERE pkid = '" . $_SESSION["historyid"] . "'");

// Retrieve Org List
$orgsurl = "https://webexapis.com/v1/organizations";
$getorgs = curl_init($orgsurl);
curl_setopt($getorgs, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($getorgs, CURLOPT_RETURNTRANSFER, true);
curl_setopt($getorgs, CURLOPT_FAILONERROR, true);
curl_setopt(
  $getorgs,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $authtoken
  )
);
$orgsdata = curl_exec($getorgs);
if (curl_errno($getorgs) == "0") {
  $orgsjson = json_decode($orgsdata);
  $orgsarray = json_decode($orgsdata, true);
  $orgcount = count($orgsarray['items']);
  echo ("					  <p>Select an organization to search for trunks:</p>\n");
  echo ("					  <form method=\"post\" action=\"/lgw/\">\n");
  echo ("					    <input type=\"hidden\" name=\"lgwstep\" value=\"2\">\n");
  echo ("					    <table class=\"default\">\n");
  for ($x = 0; $x < $orgcount; $x++) {
    echo ("					      <tr>\n");
    echo ("					        <td>\n");
    echo ("     					    <label class=\"radio-container\">\n");
    echo ("			     		        <input type=\"radio\" name=\"orgid\" value=\"" . $orgsjson->items[$x]->id . "\">\n");
    echo ("					            <span class=\"radio-checkmark\"></span>\n");
    echo ("					        </td>\n");
    echo ("					        <td>\n");
    echo ("					         " . $orgsjson->items[$x]->displayName . "</label>\n");
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
} else {
  echo "					  <p>Sorry, you don't have access to any organizations.</p>\n";
}
echo ("           <table class=\"default\">\n");
echo ("             <tr>\n");
echo ("					      <form method=\"post\">\n");
echo ("					      <input type=\"hidden\" name=\"lgwstep\" value=\"" . ($lgwstep - 1) . "\">\n");
echo ("					      <td><input type=\"submit\" value=\"Go Back\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("					      <form method=\"post\" action=\"/lgw/logout\">\n");
echo ("					      <td><input type=\"submit\" value=\"Start Over\" class=\"button\"></td>\n");
echo ("					      </form>\n");
echo ("             </tr>\n");
echo ("           </table>\n");
?>