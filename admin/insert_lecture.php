<?php
  // Start the session
  session_start();

  // Check if the admin is logged in
  if (!isset($_SESSION['admin_id'])) {
    // Redirect to the admin login page
    header('Location: admin_login.php');
    exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Add Enrollments</title>
  <link rel="stylesheet" href="../css/admin/add.css">
</head>
<body>

	<h1>Add Enrollments</h1>

	<?php
		require "../connection.php";

		// Check connection
		if (mysqli_connect_errno()) {
		  echo "<p>Failed to connect to MySQL: " . mysqli_connect_error() . "</p>";
		  exit();
		}

		// Check if the form is submitted
		if (isset($_POST['submit'])) {
		  $title = $_POST['title'];
		  $description = $_POST['description'];
		  $price = $_POST['price'];

		  // Check if an image is selected
		  if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $tmp_name = $_FILES['thumbnail']['tmp_name'];
        $img_data = addslashes(file_get_contents($tmp_name));
    
        // Insert course data into the database
        $sql = "INSERT INTO courses (title, description, price, thumbnail) VALUES ('$title', '$description', '$price', '$img_data')";
        if (mysqli_query($conn, $sql)) {
            echo "<p id='message'>Course added successfully.</p>";
            echo "<script>setTimeout(function() { document.getElementById('message').remove(); }, 1000);</script>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
        }
        } else {
            echo "<p>Please select an image to upload.</p>";
        }    
	
		}
		// Close database connection
		mysqli_close($conn);
	?>

	<form method="post" enctype="multipart/form-data">
		<label>Title:</label><br>
		<input type="text" name="title" required><br><br>
		<label>Description:</label><br>
		<textarea name="description"></textarea><br><br>
		<label>Price:</label><br>
		<input type="number" name="price" required><br><br>
		<label>Thumbnail:</label><br>
		<input type="file" name="thumbnail" required><br><br>
		<input type="submit" name="submit" value="Add Course">
	</form>
</body>
</html>