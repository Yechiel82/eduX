<?php
session_start();

if (isset($_POST['course_id'])) {
  $course_id = $_POST['course_id'];
  $key = array_search($course_id, $_SESSION['cart']);
  if ($key !== false) {
    array_splice($_SESSION['cart'], $key, 1);
    $response = array('status' => 'success', 'total_price' => array_sum(array_column($cart_courses, 'price')));
    echo json_encode($response);
  }
}
?>