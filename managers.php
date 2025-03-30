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
    margin: 50px auto; /* Centering the table */
    background-color: #222; /* Dark background */
    color: white;
    font-family: Arial, sans-serif;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow */
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
/* Table Header */
th {
    background-color: #333; /* Darker shade for header */
    color: #f2f2f2;
    padding: 15px;
    font-size: 18px;
    text-transform: uppercase;
    border-bottom: 2px solid #444;
}

/* Table Cells */
td {
    padding: 12px;
    text-align: center;
    font-size: 16px;
    border-bottom: 1px solid #444; /* Row separator */
}

/* Alternating Row Colors */
tr:nth-child(even) {
    background-color: #292929;
}

/* Hover Effect */
tr:hover {
    background-color: #383838;
    transition: 0.3s ease-in-out;
}

/* Goal Styling */
.goal {
    font-size: 14px;
    color: #bbb;
    font-style: italic;
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
           <!--    <button class="register">Register</button>--> 
        </div>
    </nav>
    <br><br><br><br><br><br>

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

    $sql = "SELECT managers.manager_id, managers.managername, managers.manager_DOB, team.team_name, managers.playstyle FROM managers JOIN TEAMMANAGERS ON managers.manager_id = TEAMMANAGERS.manager_id JOIN team ON TEAMMANAGERS.team_id = team.team_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Manager ID</th><th>Manager Name</th><th>DOB</th><th>Team Name</th><th>Play Style</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["manager_id"]. "</td><td>" . $row["managername"]. "</td><td>" . $row["manager_DOB"]. "</td><td>" . $row["team_name"]. "</td><td>" . $row["playstyle"]. "</td></tr>";
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