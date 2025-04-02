<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Add any additional styles for the table here */
        table {
    width: 80%;
    border-collapse: collapse;
    margin: 100px auto 20px auto; /* Center the table with top margin */
    background-color: #222; /* Dark background */
    box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1); /* Soft shadow */
    border-radius: 8px;
    overflow: hidden;
}

th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #444; /* Subtle row separation */
    color: #ddd; /* Light text for contrast */
}

th {
    background-color: #333; /* Darker header for distinction */
    color: #fff;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #2a2a2a; /* Slightly lighter alternate row */
}

tr:hover {
    background-color: #3a3a3a; /* Highlight row on hover */
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

    </style>
</head>
<body>

    <nav class="navbar">
        <div class="logo-section">
            <img src="images/logo.png" alt="Football Logo">
            <h2>UCL</h2>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li class="dropdown">
                <a href="#">About</a>
                <ul class="dropdown-menu">
                    <li><a href="teams.php">Teams</a></li>
                    <li><a href="players.php">Players</a></li>
                    <li><a href="managers.php">Managers</a></li>
                    <li><a href="stadiums.php">Stadiums</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">Matches</a>
                <ul class="dropdown-menu">
                    <li><a href="history.php">Match History</a></li>
                    <li><a href="goalstats.php">Goal stats</a></li>
                    <li><a href="standing.php">Standings</a></li>
                </ul>
            </li>
          
        </ul>
        <div class="auth-buttons">
        <a href="login.php"><button class="login">Log In</button></a>
        </div>
    </nav>
    <br><br>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "dbproj";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT * FROM team";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Team ID</th><th>Team Name</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["team_id"]. "</td><td><a href='team_details.php?team_id=" . $row["team_id"] . "'>" . $row["team_name"]. "</a></td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align: center; margin-top: 100px;'>0 results</p>";
    }

    mysqli_close($conn);
    ?>

    <script>
        $(document).ready(function(){
            $(".dropdown").hover(function(){
                $(this).find(".dropdown-menu").stop(true, true).slideDown();
            }, function(){
                $(this).find(".dropdown-menu").stop(true, true).slideUp();
            });
        });
    </script>

</body>
</html>

