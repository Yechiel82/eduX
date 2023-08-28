<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require '../connection.php';

    // Get the user's id from the session
    $user_id = $_SESSION['user_id'];

    // Delete the user's data from the database
    $query = "DELETE FROM users WHERE id='$user_id'";
    mysqli_query($conn, $query);

    // Destroy the session and redirect to the login page
    session_destroy();
    header('Location: login.php');
    exit;
?>
