<?php
    session_start();

    require '../connection.php';
    
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

    // Check if cart exists in session, create it if not
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }
    
    // Check if a course was added to the cart
    if (isset($_POST["course_id"])) {
        // Add course ID to cart
        $course_id = $_POST["course_id"];
        // Check if the user is already enrolled in the course
        $enrollment_query = "SELECT * FROM enrollments WHERE user_id='$user_id' AND course_id='$course_id'";
        $enrollment_result = mysqli_query($conn, $enrollment_query);
        if (mysqli_num_rows($enrollment_result) > 0) {
            // User is already enrolled
            $response = 'You are already enrolled in this course';
            // Return response as JSON
            header('Content-Type: application/json');
            echo json_encode(array('message' => $response));
            exit;
        }
    
        // Check if the course is already in the cart
        if (in_array($course_id, $_SESSION["cart"])) {
            // Course already in cart
            $response = 'You have already added this course';
            // Return response as JSON
            header('Content-Type: application/json');
            echo json_encode(array('message' => $response));
            exit;
        } else {
            // Add course to cart
            $_SESSION["cart"][] = $course_id;
            $response = 'Course added to cart!';
            // Return response as JSON
            header('Content-Type: application/json');
            echo json_encode(array('message' => $response));
            exit;
        }
    }

    // Hitung total harga dari kursus di cart
    $total_price = 0;
    foreach ($_SESSION["cart"] as $course_id) {
        $sql = "SELECT * FROM courses WHERE id=$course_id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $course = mysqli_fetch_assoc($result);
            $total_price += $course['price'];
        }
    }

    // Fetch courses from the database that are in the cart
    $cart_courses = array();
    foreach ($_SESSION["cart"] as $course_id) {
        $sql = "SELECT * FROM courses WHERE id=$course_id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $cart_courses[] = mysqli_fetch_assoc($result);
        }
    }

    // Close database connection
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="../css/user/shopping_cart.css">
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
				<a href="enrollments.php">Enrollments</a>
			</li>
		</ul>
	</nav>

    <h1>Shopping Cart</h1>
    
    <?php if (!empty($cart_courses)): ?>
    <form method="post" action="payment.php">
        <table id="cart-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_courses as $course): ?>
                    <tr>
                        <td><?= $course['title'] ?></td>
                        <td><?= $course['description'] ?></td>
                        <td>$<?= $course['price'] ?></td>
                        <td>
                            <button type="button" class="remove-course" data-id="<?= $course['id'] ?>">Remove</button>
                            <input type="hidden" name="course_title[]" value="<?= $course['title'] ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>Total Price:</strong></td>
                    <td colspan="2"><strong>$<?= $total_price ?></strong><input type="hidden" name="total_price" value="<?= $total_price ?>"></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <button type="button" id="clear-cart" class="button">Clear Cart</button>
                        <button type="submit" name='checkout' class="button">Checkout</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <script>
        // Redirect to payment.php when checkout button is clicked
        document.querySelector('button[name="checkout"]').addEventListener('click', function() {
            window.location.href = 'payment.php';
        });

        // Add click event listener to all remove buttons
        const removeButtons = document.querySelectorAll('.remove-course');
        removeButtons.forEach(removeButton => {
        removeButton.addEventListener('click', () => {
            // Get the course ID from the data attribute
            const courseId = removeButton.getAttribute('data-id');
            // Remove the row from the table
            const row = removeButton.parentNode.parentNode;
            row.parentNode.removeChild(row);
            // Remove the course from the cart
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'remove_course.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = () => {
            if (xhr.status === 200) {
                // Update the total price
                location.reload();
            }
            };
            xhr.send('course_id=' + courseId);
        });
        });

        document.getElementById('clear-cart').addEventListener('click', function() {
        var confirmation = confirm("Are you sure you want to clear your cart?");
        if (confirmation) {
            // Remove all courses from the cart
            var courses = document.querySelectorAll('.remove-course');
            for (var i = 0; i < courses.length; i++) {
                courses[i].click();
            }
        }
        });
    </script>
    <footer>	
        <div class="footer">
                <p>&copy; 2023 Your Educational Courses. All rights reserved.</p>
        </div>
    </footer>
</footer>
</body>
</html>