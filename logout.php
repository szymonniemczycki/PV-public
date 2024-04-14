<?php
    //stop session
    session_start();
    unset($_SESSION['userName']);
    unset($_SESSION['userId']);
    session_destroy();
    header("Location: ./login.php");
