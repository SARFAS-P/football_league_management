<?php
session_start();
?>

<?php
$servername = "localhost";
$username = "root";
$password = "root"; // Your MySQL root password
$dbname = "dbproj";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if(!$conn)
{
    die("not connected".mysqli_connect_error());
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
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

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .admin-btn {
            background-color: #007bff;
            color: white;
        }

        .manager-btn, .player-btn {
            background-color: #e0e0e0;
            color: #333;
        }

        .admin-btn:hover {
            background-color: #0056b3;
        }

        .manager-btn:hover, .player-btn:hover {
            background-color: #d0d0d0;
        }

        a {
    color: #00c3ff; /* Bright cyan links for visibility */
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    color: #ffcc00; /* Gold color on hover for attention */
    text-decoration: underline;
}
.nav-links {
    margin-right: 1500px ;
    list-style: none;
    display: flex;
    
}
    </style>
</head>
<body>
<nav class="navbar">
        <div class="logo-section">
            <img src="images/logo.png" alt="Football Logo"> <!-- Replace with your logo -->
            <h2>UCL</h2> <!-- New heading added -->
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li> <!-- Home button added -->
    </nav>

    <div class="login-container">
        <h2>Login as</h2>
        <a href="admin_login.php"><button class="admin-btn">Admin</button></a>
        <button class="manager-btn" disabled>Manager (Coming Soon)</button>
        <button class="player-btn" disabled>Player (Coming Soon)</button>
    </div>

</body>
</html>