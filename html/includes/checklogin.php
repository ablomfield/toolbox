<?php
if (isset($_SESSION['authtoken'])) {
    $loggedin = True;
    $displayname = $_SESSION["displayname"];
    $authtoken = $_SESSION["authtoken"];
    $userpkid = $_SESSION["userpkid"];
    if (isset($_SESSION['orgname'])) {
        $orgname = $_SESSION["orgname"];
    } else {
        $orgname = "";
    }
    if (isset($_SESSION['enabledebug'])) {
        $enabledebug = $_SESSION["enabledebug"];
    } else {
        $enabledebug = 0;
    }

    $isadmin = $_SESSION["isadmin"];
    $timezone = $_SESSION["timezone"];
    $email = $_SESSION["email"];
} else {
    $loggedin = False;
    if ($sitesec != "home") {
        header('Location: /');
    }
}
