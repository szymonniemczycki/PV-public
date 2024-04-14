<?php
session_start();

// set the inactivity time - required in seconds
$inactivity_time = 5 * 60;

// check if the last_timestamp is set
if (isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
    session_unset();
    session_destroy();

    //redirect to login page
	header("Location: ./login.php");
    exit();
  }else{
    // regenerate new session id and delete old one to prevent session fixation attack
    session_regenerate_id(true);

    // update the last timestamp
    $_SESSION['last_timestamp'] = time();
  }
?>