<?php
    session_start();

    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }

    // Destroy the session data on the server
    session_destroy();

    // Redirect to home.php
    header('Location: ../index.php');
    exit;
?>
