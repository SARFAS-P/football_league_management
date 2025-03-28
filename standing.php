<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football League Standings</title>
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
            width: 80%;
            margin: 100px auto 20px auto; /* Center the table with top margin */
            border-collapse: collapse;
            background-color: #222;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #444;
            color: #ddd;
        }
        th {
            background-color: #333;
            color: white;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #2a2a2a;
        }
        tr:hover {
            background-color: #3a3a3a;
        }
        .club {
            text-align: left;
            font-weight: bold;
        }
        .points {
            font-weight: bold;
            color: #FFD700;
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
                    <li><a href="standings.php">Standings</a></li>
                </ul>
            </li>
            <li><a href="#">Contact</a></li>
        </ul>
        <div class="auth-buttons">
        <a href="login.php"><button class="login">Log In</button></a>
        <!--    <button class="register">Register</button>--> 
        </div>
    </nav>
    <br><br>

    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "dbproj";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (mysqli_connect_errno()) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch teams
    $teams_query = "SELECT team_id, team_name FROM team";
    $teams_result = mysqli_query($conn, $teams_query);

    $standings = [];

    if (mysqli_num_rows($teams_result) > 0) {
        while ($team = mysqli_fetch_assoc($teams_result)) {
            $team_id = $team['team_id'];
            $team_name = $team['team_name'];

            $standings[$team_id] = [
                'team_name' => $team_name,
                'played' => 0,
                'wins' => 0,
                'draws' => 0,
                'losses' => 0,
                'points' => 0,
            ];

            // Fetch matches for each team
            $matches_query = "SELECT m.score, tpm.home_team, tpm.away_team FROM matches m JOIN teamplaymatches tpm ON m.match_id = tpm.match_id WHERE tpm.home_team = '$team_name' OR tpm.away_team = '$team_name'";
            $matches_result = mysqli_query($conn, $matches_query);

            if (mysqli_num_rows($matches_result) > 0) {
                while ($match = mysqli_fetch_assoc($matches_result)) {
                    $score = $match['score'];
                    $home_team = $match['home_team'];
                    $away_team = $match['away_team'];

                    $scores = explode('-', $score);
                    $home_score = (int)trim($scores[0]);
                    $away_score = (int)trim($scores[1]);

                    $standings[$team_id]['played']++;

                    if ($team_name == $home_team) {
                        if ($home_score > $away_score) {
                            $standings[$team_id]['wins']++;
                            $standings[$team_id]['points'] += 3;
                        } elseif ($home_score < $away_score) {
                            $standings[$team_id]['losses']++;
                        } else {
                            $standings[$team_id]['draws']++;
                            $standings[$team_id]['points'] += 1;
                        }
                    } else {
                        if ($away_score > $home_score) {
                            $standings[$team_id]['wins']++;
                            $standings[$team_id]['points'] += 3;
                        } elseif ($away_score < $home_score) {
                            $standings[$team_id]['losses']++;
                        } else {
                            $standings[$team_id]['draws']++;
                            $standings[$team_id]['points'] += 1;
                        }
                    }
                }
            }
        }
    }

    // Sort standings by points
    usort($standings, function ($a, $b) {
        return $b['points'] - $a['points'];
    });
    ?>

    <h2>League Standings</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th class="club">Club</th>
                <th>MP</th>
                <th>W</th>
                <th>D</th>
                <th>L</th>
                <th class="points">Pts</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $order = 1;
            foreach ($standings as $team) {
                echo "<tr>";
                echo "<td>" . $order++ . "</td>";
                echo "<td class=\"club\">" . $team['team_name'] . "</td>";
                echo "<td>" . $team['played'] . "</td>";
                echo "<td>" . $team['wins'] . "</td>";
                echo "<td>" . $team['draws'] . "</td>";
                echo "<td>" . $team['losses'] . "</td>";
                echo "<td class=\"points\">" . $team['points'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
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