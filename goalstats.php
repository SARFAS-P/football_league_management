<?php



$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbproj";


$conn = mysqli_connect($servername, $username, $password, $dbname);


if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to get goal scorers with team names
$sql = "SELECT p.playername, t.team_name, COUNT(g.goal_id) AS goal_count 
        FROM goals g
        JOIN players p ON g.player_id = p.player_id
        JOIN team t ON p.team_id = t.team_id
        GROUP BY g.player_id
        ORDER BY goal_count DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Scorers</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 60%;
            margin: 100px auto 20px auto; /* Center the table with top margin */
            border-collapse: collapse;
            background-color: #222;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        th {
            background-color: #333;
            color: white;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        tr:hover {
            background-color: #3a3a3a;
        }
        .rank {
            width: 10%;
            text-align: center;
        }
        .goals {
            width: 10%;
            text-align: center;
            font-weight: bold;
        }
        .player {
            font-weight: bold;
            font-size: 16px;
        }
        .club {
            font-size: 14px;
            color: #bbb;
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
    </nav>
    <br><br>

    <h2>Top Scorers</h2>

    <table>
        <thead>
            <tr>
                <th class="rank">#</th>
                <th>Player</th>
                <th class="goals">Goals</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $order = 1;
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td class=\"rank\">" . $order . "</td>";
                    echo "<td>";
                    echo "<span class=\"player\">" . $row["playername"] . "</span><br>";
                    echo "<span class=\"club\">" . $row["team_name"] . "</span>";
                    echo "</td>";
                    echo "<td class=\"goals\">" . $row["goal_count"] . "</td>";
                    echo "</tr>";
                    $order++;
                }
            } else {
                echo "<tr><td colspan='3'>No goal scorers found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

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

<?php
mysqli_close($conn);
?>