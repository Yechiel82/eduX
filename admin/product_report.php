<?php
    session_start();

    // Redirect to login page if user is not logged in
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Product Sales Report</title>
    <link rel="stylesheet" href="../css/admin/report.css">
  </head>
  <body>
  <h1>Product Sales Report</h1>
  
  <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <p>Select month:</p>
    <select name="month">
      <?php
        $months = array();
        for ($i = 1; $i <= 12; $i++) {
          $timestamp = mktime(0, 0, 0, $i, 1, date('Y'));
          $month_name = date('F', $timestamp);
          $month_value = date('Y-m', $timestamp);
          $selected = '';
          if (isset($_GET['month']) && $_GET['month'] == $month_value) {
            $selected = 'selected';
          }
          echo '<option value="' . $month_value . '" ' . $selected . '>' . $month_name . '</option>';
        }
      ?>
    </select>
    <br><br>
    <input type="submit" value="Submit">
  </form>
  
  <?php
    require "../connection.php";

    try {
      $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Error: " . $e->getMessage();
    }

    if (isset($_GET['month'])) {
      $month = $_GET['month'];
      $start_date = date('Y-m-01', strtotime($month));
      $end_date = date('Y-m-t', strtotime($month));
    } else {
      $start_date = date('Y-m-01');
      $end_date = date('Y-m-t');
    }


    $stmt = $conn->prepare("SELECT courses.title, COUNT(*) as number_sold FROM order_detail JOIN courses ON order_detail.course_id = courses.id WHERE paid_at >= :start_date AND paid_at <= :end_date GROUP BY course_id");
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $conn = null;
?>

  
  <p>Showing sales for <?php echo date('F Y', strtotime($start_date)); ?></p>
  
  <table>
    <thead>
      <tr>
        <th>Courses</th>
        <th>Number Sold</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($result as $row) { ?>
        <tr>
          <td><?php echo $row['title']; ?></td>
          <td><?php echo $row['number_sold']; ?></td>
        </tr>
      <?php } ?>
      <?php if (count($result) === 0) { ?>
        <tr>
          <td colspan="2" style="text-align: center;">No Records Found</td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
  <a href="dashboard.php" class="button">Back</a>
</body>
</html>