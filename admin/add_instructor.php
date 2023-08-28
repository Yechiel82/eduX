<!DOCTYPE html>
<html>
<head>
    <title>Add Instructor</title>
    <link rel="stylesheet" href="../css/admin/instructor.css">
</head>
<body>

    <div class="container">
        <h1>Add Instructor</h1>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="course_id">Course ID:</label>
            <select id="course_id" name="course_id" required>
            <?php
                require "../connection.php";

                // Prepare the SQL query
                $sql = "SELECT id, title FROM courses";

                // Execute the query and get the result set
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id"] . '">' . $row["title"] . '</option>';
                    }
                } else {
                    echo '<option value="">No courses found</option>';
                }

                // Close the connection
                $conn->close();
            ?>
            </select>

            <input type="submit" value="Add Instructor">
        </form>

        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Retrieve the form data
                $name = $_POST['name'];
                $course_id = $_POST['course_id'];

                require "../connection.php";

                // Prepare the SQL query
                $sql = "INSERT INTO instructor (name, course_id) VALUES ('$name', '$course_id')";

                // Execute the query
                if ($conn->query($sql) === TRUE) {
                    echo '<p class="success-msg">Instructor added successfully.</p>';
                } else {
                    echo '<p class="error-msg">Error: ' . $conn->error . '</p>';
                }

                // Close the connection
                $conn->close();
            }
        ?>
        <a href="dashboard.php" class="button">Back</a>
    </div>
</body>
</html>