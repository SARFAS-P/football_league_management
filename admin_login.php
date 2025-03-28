<?php
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_user = "admin";
    $admin_pass = "admin";

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-form {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-form h2 {
            margin-bottom: 30px;
            color: #333;
        }

        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #0056b3;
        }

        .login-form .error {
            color: red;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="login-form">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    </div>

</body>
</html>