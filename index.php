<?php
require_once "config.php";

// session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT admin_id, password FROM  admins WHERE username = ?";

    $run = $conn->prepare($sql);
    $run->bind_param("s", $username);
    $run->execute();

    $res = $run->get_result();

    if ($res->num_rows == 1) {

        $admin = $res->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            echo "user postoji";
            $_SESSION["admin_id"] = $admin["admin_id"];
            $conn->close();

            header("location: admin_dashboard.php");
        } else {
            $_SESSION["error"] = "Invalid credentials!";
            $conn->close();

            header("location: index.php");
            exit();
        }
    } else {

        $_SESSION["error"] = "Invalid credentials!";
        $conn->close();
        header("location: index.php");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin login</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>


    <?php


    if (isset($_SESSION["error"])) {

        unset($_SESSION["error"]);
    }


    ?>

    <form action="" method="post" class="login-form">
        <div>

            <label for="username">
                Username
            </label>
            <input type="text" name="username" value="admin" />
        </div>
        <div>

            <label for="password">
                password
            </label>
            <input type="password" name="password" value="admin" />
        </div>
        <input type="submit" value="Login" />
    </form>
</body>

</html>