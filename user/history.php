<?php
    session_start();

    require '../connection.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    // Get the user's data from the database
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

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
    <title>Course Purchase History</title>
    <link rel="stylesheet" href="../css/user/history.css">
</head>
<body>
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
        $result = mysqli_query($conn, $query); // Re-execute the query to reset the result
        $total_price = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $total_price += $row['price'];
        }
        ?>
        <h2 class="total-price">Total Price of Purchases: $<?php echo $total_price; ?></h2>
    </div>
</body>
</html>