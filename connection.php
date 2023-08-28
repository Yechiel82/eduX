<?php
	$hostname = "localhost";
	$username = "root";
	$password = "";
	$dbname = "katalog";

	$conn = mysqli_connect($hostname, $username, $password, $dbname);

	if (mysqli_connect_errno()){
		echo "Koneksi gagal";
		exit();
	}
?>