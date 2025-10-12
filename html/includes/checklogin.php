<?php
if (isset($_SESSION['authtoken'])) {
	$loggedin = True;
	$displayname = $_SESSION["displayname"];
	$authtoken = $_SESSION["authtoken"];
	if (isset($_SESSION['orgname'])) {
		$orgname = $_SESSION["orgname"];
	} else {
		$orgname = "";
	}
	$isadmin = $_SESSION["isadmin"];
	$timezone = $_SESSION["timezone"];
	$email = $_SESSION["email"];
} else {
	$loggedin = False;
}
?>