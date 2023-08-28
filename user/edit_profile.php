<?php
	session_start();

	require '../connection.php';

	// Check if the user is logged in
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
		exit;
	}

	// Get the user's data from the database
	$user_id = $_SESSION['user_id'];
	$query = "SELECT * FROM users WHERE id='$user_id'";
	$result = mysqli_query($conn, $query);
	$user = mysqli_fetch_assoc($result);

	// Handle form submission
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];

		// Update the user's data in the database
		$query = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address' WHERE id='$user_id'";
		mysqli_query($conn, $query);

		// Redirect to the profile page
		header('Location: profile.php');
		exit;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title>Edit Profile - Course Catalog</title>
	<link rel="stylesheet" href="../css/user/edit_profile.css">
</head>
<body>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">Edit Profile</div>
					<div class="card-body">
						<form method="POST" action="home.php">
							<div class="form-group">
								<label for="name">Name</label>
								<input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
							</div>
							<div class="form-group">
								<label for="phone">Phone</label>
								<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
							</div>
							<div class="form-group">
								<label for="address">Address</label>
								<textarea class="form-control" id="address" name="address"><?php echo $user['address']; ?></textarea>
							</div>
							<button type="submit" class="btn btn-primary">Save</button>
							<a href="profile.php" class="btn btn-secondary">Cancel</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>