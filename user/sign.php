<?php

  require '../connection.php';

  // Check if the form was submitted
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if the user already exists
    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
      echo "This email address is already registered. Please try again.";
    } else {
      // Insert the new user into the database
      $query = "INSERT INTO users (name, email, phone, address,  password) VALUES ('$name', '$email', '$phone', '$address', '$password')";
      mysqli_query($conn, $query);
      if (mysqli_affected_rows($conn) > 0) {
        // Redirect to home.php
        header('Location: login.php');
        exit();
      } else {
        echo "An error occurred while inserting the new user.";
      }
    }
  }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title>Sign Up - Course Catalog</title>
	<link rel="stylesheet" href="../css/user/sign_up_page.css">
</head>
<body>

  <form method="post" action="">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <label for="phone">Phone:</label>
    <input type="tel" name="phone" id="phone" required>

    <label for="address">Address:</label>
    <textarea name="address" id="address" required></textarea>

    <button type="submit">Sign Up</button>
  </form>
  
</body>
</html>