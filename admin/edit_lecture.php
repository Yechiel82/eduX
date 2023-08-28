<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }

    require "../connection.php";

    // Check if the ID parameter is set
    if (!isset($_GET["id"])) {
        header("Location: ../user/lectures.php");
        exit();
    }

    // Get the ID parameter
    $id = $_GET["id"];

    // Prepare the SQL query to retrieve the lecture with the specified ID
    $sql = "SELECT id, title, description, price, thumbnail FROM courses WHERE id = $id";

    // Execute the query and get the result set
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Get the lecture data from the result set
        $row = $result->fetch_assoc();
        $title = $row["title"];
        $description = $row["description"];
        $price = $row["price"];
        $thumbnail = $row["thumbnail"];
    } else {
        // Redirect to the lectures page if the lecture is not found
        header("Location: ../user/lectures.php");
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form data
        $title = $_POST["title"];
        $description = $_POST["description"];
        $price = $_POST["price"];

        if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["error"] == 0) {
            $thumbnail = addslashes(file_get_contents($_FILES["thumbnail"]["tmp_name"]));
        } else {
            $thumbnail = null;
        }

        // Prepare the SQL query to update the lecture data
        $sql = "UPDATE courses SET title = '$title', description = '$description', price = $price";
        if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["error"] == 0) {
            $thumbnail = addslashes(file_get_contents($_FILES["thumbnail"]["tmp_name"]));
            $sql .= ", thumbnail = '$thumbnail'";
        }
        $sql .= " WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            // Redirect to the lectures page if the lecture was updated successfully
            header("Location: dashboard.php");
            exit();
        } else {
            // Display an error message if there was an error updating the lecture
            echo "Error updating lecture: " . $conn->error;
        }
    }

    // Close the connection
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Lecture</title>
    <link rel="stylesheet" href="../css/admin/edit_lecture.css">
</head>
<body>
    <h1>Edit Lecture</h1>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <label for="title">Title:</label>
            <input type="text" name="title" value="<?php echo $title; ?>" required>
        </div>
        <div>
            <label for="description">Description:</label>
            <textarea name="description" required><?php echo $description; ?></textarea>
        </div>
        <div>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo $price; ?>" required>
        </div>
        <div>
            <label for="thumbnail">Thumbnail:</label>
            <input type="file" name="thumbnail">
        <div>
        <button type="submit">Save</button>
        <a href="dashboard.php">Cancel</a>
        </div>
    </form>
</body>
</html>