<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team details</title>
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

        h1, h2, h3{
            text-align: center;
            margin-top: 30px; /* Increased margin-top for headings */
            color: #fff;
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
    <br><br><br><br><br><br><br>

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbproj";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['team_id'])) {
    $team_id = $_GET['team_id'];

    // Get Team Name
    $sql_team = "SELECT team_name FROM team WHERE team_id = $team_id";
    $result_team = mysqli_query($conn, $sql_team);
    if ($row_team = mysqli_fetch_assoc($result_team)) {
        echo "<h1><u>-- " . $row_team['team_name'] . " --</u></h1>";
    }
    
    // Get Manager Details
    $sql_manager = "SELECT managers.* FROM managers JOIN TEAMMANAGERS ON managers.manager_id = TEAMMANAGERS.manager_id WHERE TEAMMANAGERS.team_id = $team_id";
    $result_manager = mysqli_query($conn, $sql_manager);
    if ($row_manager = mysqli_fetch_assoc($result_manager)) {
        echo "<br><br>";
        echo "<h2><u>Manager Details</u></h2>";
        echo "<table>";
        echo "<tr><th>Manager Name</th><th>DOB</th><th>Play Style</th></tr>";
        echo "<tr><td>" . $row_manager['managername'] . "</td><td>" . $row_manager['manager_DOB'] . "</td><td>" . $row_manager['playstyle'] . "</td></tr>";
        echo "</table>";
    }

    // Get Player Details
    $sql_players = "SELECT * FROM players WHERE team_id = $team_id";
    $result_players = mysqli_query($conn, $sql_players);
    if (mysqli_num_rows($result_players) > 0) {
        echo "<h2><u>Player Details</u></h2>";
        echo "<table>";
        echo "<tr><th>Player Name</th><th>Position</th><th>Nationality</th></tr>";
        while ($row_player = mysqli_fetch_assoc($result_players)) {
            echo "<tr><td>" . $row_player['playername'] . "</td><td>" . $row_player['position'] . "</td><td>" . $row_player['nationality'] . "</td></tr>";
        }
        echo "</table>";
        echo "<br><br>";
    }
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