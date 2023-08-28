<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require '../connection.php';

    // Get the user's data from the database
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Get the user's completed courses
    $query = "SELECT COUNT(*) AS num_completed_courses FROM enrollments WHERE user_id='$user_id' AND progress=100";
    $result = mysqli_query($conn, $query);
    $completed_courses = mysqli_fetch_assoc($result)['num_completed_courses'];

    // Determine the user's achievement level
    $achievement_level = '';
    if ($completed_courses == 1) {
        $achievement_level = 'Intermediate';
    } elseif ($completed_courses >= 2) {
        $achievement_level = 'Professional';
    } elseif ($completed_courses > 2) {
        $achievement_level = 'Expert';
    }

    // Get the courses bought by the user
    $query = "SELECT courses.title, courses.price, order_detail.paid_at
              FROM order_detail
              JOIN courses ON order_detail.course_id = courses.id
              WHERE order_detail.user_id='$user_id'
              ORDER BY order_detail.paid_at DESC";

    $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> 
    <title>Profile - Course Catalog</title>
    <link rel="stylesheet" href="../css/user/profile.css">
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Profile</div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
                        <p><strong>Address:</strong> <?php echo $user['address']; ?></p>
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                        <a href="edit_profile.php" class="btn btn-secondary">Edit Profile</a>
                        <a href="remove_account.php" class="btn btn-third custom-btn">Remove Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Achievement</div>
                    <div class="card-body">
                        <p><strong>Total Completed Courses:</strong> <?php echo $completed_courses; ?></p>
                        <p><strong>Achievement Level:</strong> <?php echo $achievement_level; ?></p>
                        <!-- achievement information -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>Course Purchase History</h1>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <div class="course-cards">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="course-card">
                        <h3 class="course-title"><?php echo $row['title']; ?></h3>
                        <p class="course-price">Price: $<?php echo $row['price']; ?></p>
                        <p class="course-purchase-date">Purchased At: <?php echo $row['paid_at']; ?></p>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p>No courses found.</p>
        <?php } ?>

        <?php
            // Calculate the total price of purchases
            $result = mysqli_query($conn, $query); // Re-execute the query to reset the result pointer
            $total_price = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $total_price += $row['price'];
            }
        ?>
        <h2 class="total-price">Total Price of Purchases: $<?php echo $total_price; ?></h2>
    </div>

    <a href="../index.php" class="btn btn-home">Home</a>

</body>
</html>