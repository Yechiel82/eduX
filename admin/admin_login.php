<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../css/admin/login.css">
</head>
<body>
    <form action="admin.php" method="POST">
        <div class="login-box">
            <h2>Admin Login</h2>

            <?php
                if(isset($_GET['msg'])) {
                    echo "<p style='color:red;'>".$_GET['msg']."</p>";
                }
            ?>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>
