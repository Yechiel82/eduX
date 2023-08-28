<?php
    session_start();

    require 'connection.php';

    // Check if the user is logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $user_name = 'Guest';

    // If the user is logged in, retrieve their name from the database
    if ($user_id) {
        $query = "SELECT name FROM users WHERE id='$user_id'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_assoc($result);
        $user_name = $user['name'];
    }

    // Fetch the courses from the database
    $course_query = "SELECT * FROM courses";
    $course_result = mysqli_query($conn, $course_query);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Homepage</title>
	<link rel="stylesheet" href="css/user/home.css">
</head>
<body>
	<div class="container">
		<div class="logos"></div>
		<div class="logos"></div>
		<div class="logos"></div>
	</div>

	<div class="content-container">
		<nav style="background-color: #993350;">
			<a href="<?php echo isset($_SESSION['user_id']) ? 'user/profile.php' : 'user/login.php'; ?>">
				<img src="pictures/profile_logo.png" alt="Logo">
				<?php echo $user_name; ?>
			</a>
			<ul>
				<li>
					<h1 id="education-logo">eduXC</h1>
				</li>
				<li>
					<a href="index.php">Home</a>
				</li>
				<li>
					<a href="user/lectures.php">Lectures</a>
				</li>
				<li>
					<a href="user/cart.php">
						Shopping Cart 
						<?php
						// Get the number of items in the cart
						$cart_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

						// Display the number in the badge
						if ($cart_items > 0) {
							echo '<span>' . $cart_items . '</span>';
						}
						?>
					</a>
				</li>
				<li>
					<a href="user/enrollments.php">Enrollments</a>
				</li>
			</ul>
		</nav>

		<div class="courses-container">
			<h2>Welcome, <span class="user-name"><?php echo $user_name; ?></span>!</h2><br/>
			<h3>Available Courses:</h3>
			<div class="course-list">
				<?php while ($course = mysqli_fetch_assoc($course_result)) { ?>
					<div class="course-card">
						<div class="course-thumbnail">
							<?php echo '<img src="data:image/jpeg;base64,' . base64_encode($course['thumbnail']) . '" alt="Course Thumbnail">' ?>
						</div>
						<div class="course-info">
							<h4 class="course-title"><?php echo $course['title']; ?></h4>
							<a href="user/view_course.php?id=<?php echo $course['id']; ?>" class="view-course-btn">View Course</a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>

		

		<script>
			var numItems = 0;
			// Update numItems variable when an item is added to cart
			document.querySelector('.add-to-cart-button').addEventListener('click', function() {
				numItems++;
				document.querySelector('.badge').textContent = numItems;
			});
		</script>

	<footer>	
		<div class="footer">
			<p>&copy; 2023 Your Educational Courses. All rights reserved.</p>
		</div>
	</footer>


	</div>

	<script>
		var contentContainer = document.querySelector('.content-container');
		contentContainer.style.visibility = 'hidden';

		// Show the logos
		var logosContainer = document.querySelector('.container');
		logosContainer.style.display = 'block';

		// Function to hide the logos and show the content
		function hidelogos() {
			var logosContainer = document.querySelector('.container');
			var contentContainer = document.querySelector('.content-container');

			logosContainer.style.display = 'none';
			contentContainer.style.visibility = 'visible';
		}

		// Delay the hiding of the logos and showing the content after 5 seconds
		setTimeout(hidelogos, 1500);
	</script>
</body>
</html>