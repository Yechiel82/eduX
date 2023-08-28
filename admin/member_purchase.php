<!DOCTYPE html>
<html>
<head>
  <title>Total Purchase Value by Member</title>
  <link rel="stylesheet" href="../css/admin/member_purchases.css">
</head>
<body>
  <h1>Total Purchase Value by Member</h1>
  <?php
    require "../connection.php";

    // SQL query to calculate the total purchase value for each member
    $sql = "SELECT users.name, order_detail.user_id, amount as total_purchase_value FROM order_detail JOIN users ON order_detail.user_id = users.id GROUP BY order_detail.user_id";

    // Execute the query and get the result
    $result = mysqli_query($conn, $sql);

    // Check if there are any results
    if (mysqli_num_rows($result) > 0) {
      // Output the results as a table
      echo "<table>";
      echo "<tr><th>Users</th><th>Total Purchase Value</th></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["total_purchase_value"] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    } else {
      echo "No results found.";
    }

    // Close the database connection
    mysqli_close($conn);
  ?>
  <a href="dashboard.php" class="button">Back</a>
</body>
</html>