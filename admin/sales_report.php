<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }

?>

<?php
  require "../connection.php";

  // Get the list of available courses
  $sql = "SELECT id, title FROM courses";
  $result = mysqli_query($conn, $sql);
  $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // Set default values for start and end dates
  $start_date = "2023-01-01";
  $end_date = "2024-04-30";

  // Get the course ID selected by the user (if any)
  $selected_course_id = isset($_POST['course_id']) ? $_POST['course_id'] : null;

  // If a course ID was selected, get the number of sales for that course for each month
  $sales_data = array();
  if ($selected_course_id) {
    $sql = "SELECT c.id AS course_id, c.title AS course_title, MONTH(o.paid_at) AS month, COUNT(*) AS number_sold
    FROM order_detail o
    JOIN courses c ON o.course_id = c.id
    WHERE o.course_id = $selected_course_id AND o.paid_at BETWEEN '$start_date' AND '$end_date'
    GROUP BY c.id, c.title, MONTH(o.paid_at)";
    $result = mysqli_query($conn, $sql);
    $sales_data = mysqli_fetch_all($result, MYSQLI_ASSOC);
  }  
?>

<!DOCTYPE html>
<html>
<head>
  <title>Sales Report</title>
  <link rel="stylesheet" href="../css/admin/sales.css">
  </style>
</head>
<body>
  <h1>Sales Report</h1>

  <form method="post">
  <label for="course_id">Select Course:</label>
  <select name="course_id" id="course_id">
    <?php foreach ($courses as $course) { ?>
      <option value="<?php echo $course['id']; ?>" <?php if ($course['id'] == $selected_course_id) { echo 'selected'; } ?>><?php echo $course['title']; ?></option>
  <?php } ?>
  </select>
  <button type="submit">Show Sales</button>
  </form>

  <?php if ($sales_data && count($sales_data) > 0) { ?>
  <h2>Sales for Course <?php echo $sales_data[0]['course_title']; ?></h2>
  <table>
    <thead>
      <tr>
        <th>Month</th>
        <th>Number Sold</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($sales_data as $row) { ?>
        <tr>
          <td><?php echo date('F', strtotime('2022-' . $row['month'] . '-01')); ?></td>
          <td><?php echo $row['number_sold']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } else { ?>
    <p>No sales data available for the selected course and period.</p>
  <?php } ?>
  <a href="dashboard.php" class="button">Back</a>
</body>
</html>