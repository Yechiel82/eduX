<?php
    session_start();

    require '../connection.php';

    // Check if the user is logged in
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $user_name = 'Guest';

    // If the user is not logged in, redirect to the login page
    if (!$user_id) {
        header("Location: login.php");
        exit();
    }

    // If the user is logged in, retrieve the user's name
    $query = "SELECT name FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    $user_name = $user['name'];

    // Query to get user's enrollments
    $query = "SELECT enrollments.course_id, enrollments.progress, enrollments.enrollment_date, enrollments.completion_date, courses.title, courses.thumbnail 
    FROM enrollments 
    JOIN courses ON enrollments.course_id = courses.id 
    WHERE enrollments.user_id = $user_id";

    // Execute the query
    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Enrollments</title>
    <link rel="stylesheet" href="../css/user/enrollment.css">
</head>
<body>

    <nav style="background-color: #993350;">
        <a href="<?php echo isset($_SESSION['user_id']) ? 'profile.php' : 'login.php'; ?>">
            <img src="../pictures/profile_logo.png" alt="Logo">
            <?php echo $user_name; ?>
        </a>
        <ul>
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

    <div class="enrollments-container">
        <h1 class="page-title">My Enrollments</h1>
        <?php
            // Display the user's enrollments
            if (mysqli_num_rows($result) > 0) {
                while ($enrollment = mysqli_fetch_assoc($result)) {
                    echo "<div class='enrollment'>";
                    echo "<h3 class='course-title'>" . $enrollment["title"] . "</h3>";
                    echo "<img src='data:image/jpeg;base64," . base64_encode($enrollment["thumbnail"]) . "' alt='Thumbnail'>";
                    echo "<p class='enrollment-date'>Enrolled on " . date("F j, Y", strtotime($enrollment["enrollment_date"])) . "</p>";
                    echo "<p class='enrollment-progress'>Progress: " . $enrollment["progress"] . "%</p>";

                    // Add button for course detail
                    $courseId = $enrollment["course_id"];

                    if ($enrollment["completion_date"] === null) {
                        // Check if progress is 100% to display completion status as "Complete"
                        if ($enrollment["progress"] == 100) {
                            // Set session variable to mark the course as completed
                            $_SESSION['completed_courses'][$courseId] = true;
                        } else if ($enrollment["progress"] > 0) {
                            echo "<p class='completion-status'>Completion Status: In Progress</p>";
                            echo "<button onclick='viewCourseDetail($courseId, " . $enrollment["progress"] . ")' class='btn btn-primary' data-status='in-progress'>Continue Course</button>";
                        } else {
                            echo "<button onclick='viewCourseDetail($courseId, " . $enrollment["progress"] . ")' class='btn btn-primary' data-status='not-started'>View Course Detail</button>";
                        }
                    } else {
                        echo "<p class='completion-status'>Completion Date: " . date("F j, Y", strtotime($enrollment["completion_date"])) . "</p>";
                        echo "<button onclick='viewCourseDetail($courseId, 100)' class='btn btn-primary' data-status='complete'>Completed</button>";
                    }            
                    echo "</div>";
                }
            } else {
                echo "<p>You have not enrolled in any courses yet.</p>";
            }

            // Close database connection
            mysqli_close($conn);
        ?>
    </div>

    <script>
        function viewCourseDetail(courseId, progress) {
            window.location.href = 'course_detail.php?id=' + courseId;
        }
    </script>
    <footer>	
        <div class="footer">
                <p>&copy; 2023 Your Educational Courses. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>