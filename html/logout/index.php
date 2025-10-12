<?php
session_start();

// Import Settings
include($_SERVER['DOCUMENT_ROOT'] . "/includes/settings.php");

// Write History
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
    mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'" . $email . "','LOGOUT')");
} else {
    mysqli_query($dbconn, "INSERT INTO history (eventdate, eventsource, eventdesc) VALUES(NOW(),'UNKNOWN','LOGOUT')");
}

// Log Out
session_destroy();
header('Location: /');
?>