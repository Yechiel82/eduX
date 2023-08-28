<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }

    require "../connection.php";

    // Check if the ID parameter is set
    if (!isset($_GET["id"])) {
        header("Location: lectures.php");
        exit();
    }

    // Get the ID parameter
    $id = $_GET["id"];

    // Prepare the SQL query to delete the lecture
    $sql = "DELETE FROM courses WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the lectures page if the lecture was deleted successfully
        header("Location: dashboard.php");
        exit();
    } else {
        // Display an error message if there was an error deleting the lecture
        echo "Error deleting lecture: " . $conn->error;
    }

    // Close the connection
    $conn->close();
?>