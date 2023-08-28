<?php
    $payment_methods = array(
        "Credit Card" => "Credit Card",
        "GoPay" => "GoPay",
        "OVO" => "OVO"
    );
    
    $selected_payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : "";

    if(isset($_POST['course_title'])) {
        $course_titles = $_POST['course_title'];
    }
    
    $total_price = $_POST['total_price'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link rel="stylesheet" href="../css/user/payment.css">
</head>
<body>
    <h1>Item Details</h1>
    <?php
        if (!empty($course_titles)) {
            foreach ($course_titles as $title) {
                echo $title . "</p>";
            }
        }
      
    ?>

    <h1>Select Payment Method</h1>

    <p>Total Price: $<?php echo $total_price; ?></p>

    <form method="post" action="confirmation.php">
    <?php foreach ($payment_methods as $method => $label) : ?>
        <label>
            <input type="radio" name="payment_method" value="<?php echo $method; ?>" <?php if ($selected_payment_method == $method) echo "checked"; ?>>
            <?php echo $label; ?>
        </label>
    <?php endforeach; ?>

    <?php foreach ($course_titles as $title) : ?>
        <input type="hidden" name="course_titles[]" value="<?php echo $title; ?>">
    <?php endforeach; ?>

    <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
    <button type="submit" class="button">Pay</button>
</form>
</body>
</html>