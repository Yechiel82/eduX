<?php
    session_start();

    require "../connection.php";

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // User exists, set session variable and redirect to dashboard
        $row = $result->fetch_assoc();
        $_SESSION['admin_id'] = $row['id'];
        header('Location: dashboard.php');
        exit();
    } else {
        // User doesn't exist, show error message
        $msg = "Invalid email or password. Please try again.";
        header("Location: index.php?msg=" . urlencode($msg));
    }
?>
