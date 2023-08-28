<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }

    // Logout function
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: admin_login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Admin Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-link nav-link">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    
    <div class="container mt-4">
        <h1>Welcome to the Admin Dashboard</h1>
        <p class="lead">You are logged in as an administrator.</p>
        <a href="insert_lecture.php" class="btn btn-primary">Add Lecture</a>
        <a href="member_purchase.php" class="btn btn-primary">Member Purchase</a>
        <a href="product_report.php" class="btn btn-primary">Product Report</a>
        <a href="sales_report.php" class="btn btn-primary">Sales Report</a>
        <a href="add_instructor.php" class="btn btn-primary">Add Instructor</a>
    </div>
    <?php
        require "../connection.php";

        // Prepare the SQL query
        $sql = "SELECT id, title, description FROM courses";

        // Execute the query and get the result set
        $result = $conn->query($sql);

        // Generate the HTML markup for the table
        echo '<table class="table mt-4">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Title</th>';
        echo '<th>Description</th>';
        echo '<th>Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if ($result->num_rows > 0) {
            // Loop through the result set
            while ($row = $result->fetch_assoc()) {
                // Generate a row for each lecture
                echo '<tr>';
                echo '<td>' . $row["title"] . '</td>';
                echo '<td>' . $row["description"] . '</td>';
                echo '<td><a href="edit_lecture.php?id=' . $row["id"] . '">Edit</a> | <a href="remove_lecture.php?id=' . $row["id"] . '">Delete</a></td>';
                echo '</tr>';
            }
        } else {
            // Handle the case when there are no lectures
            echo '<tr><td colspan="4">No lectures found</td></tr>';
        }

        echo '</tbody>';
        echo '</table>';

        // Close the connection
        $conn->close();
    ?>
</body>
</html>