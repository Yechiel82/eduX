<?php
	session_start();

	require '../connection.php';

	$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
	$user_name = 'Guest';

	if ($user_id) {
		$query = "SELECT name FROM users WHERE id='$user_id'";
		$result = mysqli_query($conn, $query);
		$user = mysqli_fetch_assoc($result);
		$user_name = $user['name'];
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Course Catalog</title>
	<link rel="stylesheet" href="../css/user/lectures.css">
</head>
<body>
	
	<nav style="background-color: #993350;">
		<a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>">
			<img src="../pictures/profile_logo.png" alt="Logo">
			<?php echo $user_name; ?>
		</a>
		<ul >
			<li>
				<h1 id="education-logo">eduXC</h1>
			</li>
			<li>
				<a href="../index.php">Home</a>
			</li>
			<li>
				<a href="lectures.php">Lectures</a>
			</li>
			<li>
				<a href="cart.php">
					Shopping Cart 
					<?php
						// Get the items in the cart
						$cart_items = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
						
						if ($cart_items > 0) {
							echo '<span>' . $cart_items . '</span>';
						}
					?>
				</a>
			</li>
			<li>
				<a href="enrollments.php">Enrollments</a>
			</li>
		</ul>
	</nav>

	<div class="courses-container">
		<?php
			$sql = "SELECT * FROM courses";
			$result = mysqli_query($conn, $sql);

			// Display the courses with thumbnails
			while ($row = mysqli_fetch_assoc($result)) {
				echo "<div class='course'>";
				echo "<img src='data:image/jpeg;base64," . base64_encode($row["thumbnail"]) . "' alt='" . $row["title"] . "' class='course-thumbnail'>";
				echo "<div class='course-info'>";
				echo "<h3 class='course-title'>" . $row["title"] . "</h3>";
				echo "<p class='course-description'>" . $row["description"] . "</p>";
				echo "<p class='course-price'>Price: $" . $row["price"] . "</p><br/>";
				echo "<div class='add-to-cart-wrapper'>
						<button type='button' class='add-to-cart-button' onclick='addToCart(" . $row["id"] . ")'>Add to Cart</button>
					  </div>";
				echo "</div>";
				echo "</a>";
				echo "</div>";
			}
			
			mysqli_close($conn);
		?>
	</div>

	<script>
		function addToCart(courseId) {
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "cart.php", true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					var response = JSON.parse(xhr.responseText);
					alert(response.message);
					if (response.message === "Course added to cart!") {
						window.location.href = window.location.href;
					}
				} else {
					alert("An error occurred while adding the course to the cart.");
				}
			}
		};
		xhr.send("course_id=" + courseId);
		}
	</script>
	<footer>	
        <div class="footer">
                <p>&copy; 2023 Your Educational Courses. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>