<?php
session_start();

require '../connection.php';

// Check if the user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$user_name = 'Guest';

// If the user is not logged in, redirect them to the login page
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $total_price = isset($_POST['total_price']) ? $_POST['total_price'] : null;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;
    if (isset($_POST['course_titles'])) {
        $course_titles = $_POST['course_titles'];
        $course_ids = array();
        foreach ($course_titles as $title) {
            $sql = "SELECT id FROM courses WHERE title = '$title'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $course_ids[] = $row['id'];
            }
        }
    }
    
    // Insert the order details into the database
    $paid_at = date('Y-m-d H:i:s');
    $amount = floatval($total_price);
    $sql = "INSERT INTO order_detail (user_id, payment_method, paid_at, amount, course_id) VALUES ";
    $values = array();
    foreach ($course_ids as $course_id) {
        $values[] = "('$user_id', '$payment_method', '$paid_at', '$amount', '$course_id')";
    }
    $sql .= implode(", ", $values);

    // Insert into enrollments table
    $enrollment_sql = "INSERT INTO enrollments (user_id, course_id, enrollment_date,  progress) VALUES ";
    $enrollment_values = array();
    foreach ($course_ids as $course_id) {
        $enrollment_values[] = "('$user_id', '$course_id', '$paid_at', '0.00')";
    }
    $enrollment_sql .= implode(", ", $enrollment_values);

    if (mysqli_query($conn, $sql) && mysqli_query($conn, $enrollment_sql)) {
        // Clear the cart
        unset($_SESSION['cart']);
        // Set the success message
        $_SESSION['success_message'] = "Your payment has been processed successfully.";
        // Redirect to the enrollments page
        header("Location: enrollments.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    }

    // Close the database connection
    mysqli_close($conn);
?>
