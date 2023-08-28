<?php
session_start();

require '../connection.php';

// Check if the login form has been submitted
if (isset($_POST['submit'])) {

    // Get the user's email and password from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query the database for the user with the given email and password
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    // If a matching user is found, log them in and redirect to the homepage
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../index.php');
        exit;
    } else {
        // If no matching user is found, display an error message
        $error_msg = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title>Login - Course Catalog</title>
	<link rel="stylesheet" href="../css/user/login_page.css">
</head>
<body>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Login</div>
					<div class="card-body">
						<?php if (isset($error_msg)): ?>
							<div class="alert alert-danger"><?php echo $error_msg; ?></div>
						<?php endif; ?>
						<form method="post">
							<div class="form-group">
								<label for="email">Email address</label>
								<input type="email" class="form-control" id="email" name="email" required>
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password" required>
							</div>
							<button type="submit" name="submit" class="btn btn-primary">Login</button><br/>
							<a href="sign.php" class="btn btn-outline-primary btn-sm ml-2">Sign Up</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>