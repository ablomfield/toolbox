<?php
function webexgetpersonid($accesstoken, $emailaddr)
{
    $personurl = "https://webexapis.com/v1/people/?email=" . $emailaddr;
    $getperson = curl_init($personurl);
    curl_setopt($getperson, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $persondata = curl_exec($getperson);
    $personjson = json_decode($persondata);
    if (count($personjson->items) != 0) {
        $personid = $personjson->items[0]->id;
    } else {
        $personid = NULL;
    }
    return $personid;
}

function webexgetpersonname($accesstoken, $personid)
{
    $personurl = "https://webexapis.com/v1/people/" . $personid;
    $getperson = curl_init($personurl);
    curl_setopt($getperson, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $persondata = curl_exec($getperson);
    $personjson = json_decode($persondata);
    $displayname = $personjson->displayName;
    if ($displayname == "") {
        $displayname = "Error!";
    }
    return $displayname;
}

function webexgetpersoncreated($accesstoken, $personid)
{
    $personurl = "https://webexapis.com/v1/people/" . $personid;
    $getperson = curl_init($personurl);
    curl_setopt($getperson, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $persondata = curl_exec($getperson);
    $personjson = json_decode($persondata);
    $strcreatedate = $personjson->created;
    $strcreatedate = substr($strcreatedate, 0, 10);
    //$createdate = new DateTime($strcreatedate);
    //$strcreatedate = $createdate->format("F Y");
    return $strcreatedate;
}

function webexgetmessage($accesstoken, $messageid)
{
    $messageurl = "https://webexapis.com/v1/messages/" . $messageid;
    $getmessage = curl_init($messageurl);
    curl_setopt($getmessage, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($getmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $getmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $messagedata = curl_exec($getmessage);
    $messagejson = json_decode($messagedata);
    $messagetext = $messagejson->text;
    if ($messagetext == "") {
        $messagetext = "Error!";
    }
    return $messagetext;
}

function webexsendmessage($accesstoken, $roomid, $messagetext)
{
    $senddata = array(
        'roomId'      => $roomid,
        'text'        => $messagetext,
    );
    $sendjson = json_encode($senddata);
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replydata = curl_exec($postmessage);
    $replyjson = json_decode($replydata);
    return true;
}

function webexsendformatted($accesstoken, $roomid, $messagetext)
{
    $senddata = array(
        'roomId'      => $roomid,
        'markdown'    => $messagetext,
    );
    $sendjson = json_encode($senddata);
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replydata = curl_exec($postmessage);
    $replyjson = json_decode($replydata);
    return true;
}

function webexsendfile($accesstoken, $roomid, $messagefile)
{
    $senddata = array(
        'roomId'      => $roomid,
        'files'       => $messagefile,
    );
    $sendjson = json_encode($senddata);
    $replyurl = "https://webexapis.com/v1/messages/";
    $postmessage = curl_init($replyurl);
    curl_setopt($postmessage, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($postmessage, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($postmessage, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $postmessage,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $replydata = curl_exec($postmessage);
    $replyjson = json_decode($replydata);
    return true;
}

function webexinviteperson($accesstoken, $roomid, $emailaddr)
{
    $senddata = array(
        'roomId'      => $roomid,
        'personEmail' => $emailaddr,
    );
    $sendjson = json_encode($senddata);
    $inviteurl = "https://webexapis.com/v1/memberships/";
    $inviteperson = curl_init($inviteurl);
    curl_setopt($inviteperson, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($inviteperson, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($inviteperson, CURLOPT_POSTFIELDS, $sendjson);
    curl_setopt(
        $inviteperson,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accesstoken
        )
    );
    $responsedata = curl_exec($inviteperson);
    $responsejson = json_decode($responsedata);
    return true;
}

function ping($host, $timeout = 1)
{
    /* ICMP ping packet with a pre-calculated checksum */
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $socket  = socket_create(AF_INET, SOCK_RAW, 1);
    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
    socket_connect($socket, $host, null);
    $ts = microtime(true);
    socket_send($socket, $package, strLen($package), 0);
    if (socket_read($socket, 255)) {
        $result = microtime(true) - $ts;
    } else {
        $result = "Error!";
    }
    socket_close($socket);
    return $result;
}
