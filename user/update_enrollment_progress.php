<?php
    session_start();

    require '../connection.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['courseId']) && isset($_POST['progress'])) {
            $courseId = $_POST['courseId'];
            $progress = $_POST['progress'];
            $userId = $_SESSION['user_id'];

            if ($progress == 100) {
                $completionDate = date('Y-m-d');
                $updateQuery = "UPDATE enrollments SET completion_date = '$completionDate', progress = $progress WHERE user_id = $userId AND course_id = $courseId";
            } else {
                $updateQuery = "UPDATE enrollments SET progress = $progress WHERE user_id = $userId AND course_id = $courseId";
            }

            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                echo "Enrollment progress updated successfully.";
            } else {
                echo "Error updating enrollment progress.";
            }
        } else {
            echo "Missing required parameters.";
        }
    } else {
        echo "Invalid request method.";
    }

    mysqli_close($conn);
?>