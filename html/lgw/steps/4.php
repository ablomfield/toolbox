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

$dbconn->query("UPDATE lgwhistory SET lgwstep = 4 WHERE pkid = '" . $_SESSION["historyid"] . "'");

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
echo("<p><b>Configuration for " . $trunksjson->name . ":</b></p>\n");
echo("<textarea rows=\"30\" cols=\"120\" id=\"lgwconfig\">\n");
echo("!\n");
echo("!\n");
echo("!\n");
echo("voice service voip\n");
echo(" ip address trusted list\n");
// Insert Local CM Subnets
if ($_POST["cucm1"] != "") {
echo("  ipv4 " . $_POST["cucm1"] . " 255.255.255.255\n");
}
if ($_POST["cucm2"] != "") {
echo("  ipv4 " . $_POST["cucm2"] . " 255.255.255.255\n");
}
if ($_POST["cucm3"] != "") {
echo("  ipv4 " . $_POST["cucm3"] . " 255.255.255.255\n");
}
echo("  ipv4 23.89.0.0 255.255.0.0\n");
echo("  ipv4 85.119.56.0 255.255.254.0\n");
echo("  ipv4 128.177.14.0 255.255.255.0\n");
echo("  ipv4 128.177.36.0 255.255.255.0\n");
echo("  ipv4 135.84.168.0 255.255.248.0\n");
echo("  ipv4 139.177.64.0 255.255.248.0\n");
echo("  ipv4 139.177.72.0 255.255.254.0\n");
echo("  ipv4 144.196.0.0 255.255.0.0\n");
echo("  ipv4 150.253.128.0 255.255.128.0\n");
echo("  ipv4 163.129.0.0 255.255.128.0\n");
echo("  ipv4 170.72.0.0 255.255.0.0\n");
echo("  ipv4 170.133.128.0 255.255.192.0\n");
echo("  ipv4 185.115.196.0 255.255.252.0\n");
echo("  ipv4 199.19.196.0 255.255.254.0\n");
echo("  ipv4 199.19.199.0 255.255.255.0\n");
echo("  ipv4 199.59.64.0 255.255.248.0\n");
echo(" allow-connections sip to sip\n");
echo(" media statistics\n");
echo(" media bulk-stats\n");
echo(" no supplementary-service sip refer\n");
echo(" no supplementary-service sip handle-replaces\n");
echo(" fax protocol t38 version 0 ls-redundancy 0 hs-redundancy 0 fallback none\n");
echo(" stun\n");
echo("  stun flowdata agent-id 1 boot-count 4\n");
echo("  stun flowdata shared-secret 0 Password123$\n");
echo(" sip\n");
echo("  early-offer forced\n");
echo("  g729 annexb-all\n");
echo("!\n");
echo("!\n");
echo("voice class uri " . $trunksjson->name . " sip\n");
echo(" pattern dtg=" . str_replace("_",".",$trunksjson->otgDtgId) . "\n");
echo("!\n");
echo("voice class uri 300 sip\n");
// Add CUCM IP Addresses
if ($_POST["cucm1"] != "") {
echo(" host ipv4:" . $_POST["cucm1"] . "\n");
}
if ($_POST["cucm2"] != "") {
echo(" host ipv4:" . $_POST["cucm2"] . "\n");
}
if ($_POST["cucm3"] != "") {
echo(" host ipv4:" . $_POST["cucm3"] . "\n");
}
echo("voice class codec 99\n");
echo(" codec preference 1 g711ulaw\n");
echo(" codec preference 2 g711alaw\n");
echo(" codec preference 3 opus\n");
echo("!\n");
echo("voice class stun-usage 200\n");
echo(" stun usage firewall-traversal flowdata\n");
echo(" stun usage ice lite\n");
echo("!\n");
echo("!\n");
echo("voice class sip-profiles 200\n");
echo(" rule 1 request ANY sip-header SIP-Req-URI modify \"sips:(.*)\" \"sip:\\1\"\n");
echo(" rule 2 request ANY sip-header To modify \"<sips:(.*)\" \"<sip:\\1\"\n");
echo(" rule 3 request ANY sip-header From modify \"<sips:(.*)\" \"<sip:\\1\"\n");
echo(" rule 4 request ANY sip-header Contact modify \"<sips:(.*)>\" \"<sip:\\1;transport=tls>\"\n");
echo(" rule 5 response ANY sip-header To modify \"<sips:(.*)\" \"<sip:\\1\"\n");
echo(" rule 6 response ANY sip-header From modify \"<sips:(.*)\" \"<sip:\\1\"\n");
echo(" rule 7 response ANY sip-header Contact modify \"<sips:(.*)\" \"<sip:\\1\"\n");
echo(" rule 8 request ANY sip-header From modify \">\" \";otg=" . $trunksjson->otgDtgId . ">\"\n");
echo(" rule 9 request ANY sip-header P-Asserted-Identity modify \"sips:(.*)\" \"sip:\\1\"\n");
echo("!\n");
echo("!\n");
echo("!\n");
echo("dial-peer voice 301 voip\n");
echo("dial-peer voice 200201 voip\n");
echo("dial-peer voice 300 voip\n");
echo("!\n");
echo("!\n");
echo("voice class dpg 300\n");
echo(" description Incoming WxC (DP200201) to CUCM(DP301)\n");
echo(" dial-peer 301 preference 1\n");
echo("!\n");
echo("voice class dpg 200\n");
echo(" description Incoming CUCM (DP300) to WxC(DP200201)\n");
echo(" dial-peer 200201 preference 1\n");
echo("!\n");
echo("voice class server-group 301\n");
// Add CUCM IP Addresses
if ($_POST["cucm1"] != "") {
echo(" ipv4 " . $_POST["cucm1"] . " port 5060\n");
}
if ($_POST["cucm2"] != "") {
echo(" ipv4 " . $_POST["cucm2"] . " port 5060\n");
}
if ($_POST["cucm3"] != "") {
echo(" ipv4 " . $_POST["cucm3"] . " port 5060\n");
}
echo("!\n");
echo("voice class tenant 100\n");
echo("  session transport udp\n");
echo("  url sip\n");
echo("  error-passthru\n");
echo("  bind control source-interface " . $_POST["insideint"] . "\n");
echo("  bind media source-interface " . $_POST["insideint"] . "\n");
echo("  no pass-thru content custom-sdp\n");
echo("!\n");
echo("voice class tenant 300\n");
echo("  bind control source-interface " . $_POST["outsideint"] . "\n");
echo("  bind media source-interface " . $_POST["outsideint"] . "\n");
echo("  no pass-thru content custom-sdp\n");
echo("!\n");
echo("voice class srtp-crypto 200\n");
echo(" crypto 1 AES_CM_128_HMAC_SHA1_80\n");
echo("!\n");
echo("voice class tenant 200\n");
echo("  registrar dns:" . substr($trunksjson->linePort,(strlen($trunksjson->linePort)-strpos($trunksjson->linePort,"@")-1)*-1) . " scheme sips expires 240 refresh-ratio 50 tcp tls\n");
echo("  credentials number " . substr($trunksjson->linePort,0,strpos($trunksjson->linePort,"@")) . " username " . $trunksjson->sipAuthenticationUserName . " password 0 " . $_POST["sippassword"] . " realm BroadWorks\n");
echo("  authentication username " . $trunksjson->sipAuthenticationUserName . " password 0 " . $_POST["sippassword"] . " realm BroadWorks\n");
echo("  authentication username " . $trunksjson->sipAuthenticationUserName . " password 0 " . $_POST["sippassword"] . " realm " . substr($trunksjson->linePort,(strlen($trunksjson->linePort)-strpos($trunksjson->linePort,"@")-1)*-1) . "\n");
echo("  no remote-party-id\n");
echo("  sip-server dns:" . substr($trunksjson->linePort,(strlen($trunksjson->linePort)-strpos($trunksjson->linePort,"@")-1)*-1) . "\n");
echo("  connection-reuse\n");
echo("  srtp-crypto 200\n");
echo("  session transport tcp tls\n");
echo("  url sips\n");
echo("  error-passthru\n");
echo("  asserted-id pai\n");
echo("  bind control source-interface " . $_POST["outsideint"] . "\n");
echo("  bind media source-interface " . $_POST["outsideint"] . "\n");
echo("  no pass-thru content custom-sdp\n");
echo("  sip-profiles 200\n");
echo("  outbound-proxy dns:" . $trunksjson->outboundProxy->outboundProxy . "\n");
echo("  privacy-policy passthru\n");
echo("!\n");
echo("crypto pki trustpoint WebexTP\n");
echo(" revocation-check crl\n");
echo("!\n");
echo("!\n");
echo("crypto pki certificate chain WebexTP\n");
echo("!\n");
echo("dial-peer voice 301 voip\n");
echo(" description Outgoing dial-peer to Unified CM Webex Calling Trunk for inbound\n");
echo(" destination-pattern BAD.BAD\n");
echo(" session protocol sipv2\n");
echo(" session server-group 301\n");
echo(" voice-class codec 99  \n");
echo(" voice-class sip tenant 100\n");
echo(" dtmf-relay rtp-nte\n");
echo(" no vad\n");
echo("!\n");
echo("dial-peer voice 200201 voip\n");
echo(" description Inbound/Outbound Webex Calling\n");
echo(" max-conn 250\n");
echo(" destination-pattern BAD.BAD\n");
echo(" session protocol sipv2\n");
echo(" session target sip-server\n");
echo(" destination dpg 300\n");
echo(" incoming uri request " . $trunksjson->name . "\n");
echo(" voice-class codec 99  \n");
echo(" voice-class stun-usage 200\n");
echo(" no voice-class sip localhost\n");
echo(" voice-class sip tenant 200\n");
echo(" dtmf-relay rtp-nte\n");
echo(" srtp\n");
echo(" no vad\n");
echo("!\n");
echo("dial-peer voice 300 voip\n");
echo(" description Incoming dial-peer from Unified CM for Webex\n");
echo(" session protocol sipv2\n");
echo(" destination dpg 200\n");
echo(" incoming uri via 300\n");
echo(" voice-class codec 99  \n");
echo(" voice-class sip tenant 300\n");
echo(" dtmf-relay rtp-nte\n");
echo(" no vad\n");
echo("!\n");
echo("!\n");
echo("sip-ua \n");
echo(" transport tcp tls v1.2\n");
echo(" crypto signaling default trustpoint WebexTP cn-san-validate server    \n");
echo(" tcp-retry 1000\n");
echo("!\n");
echo("!\n");
echo("</textarea>\n");
echo("<button onclick=\"myFunction('lgwconfig')\" class=\"button\">Copy</button>\n");
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
