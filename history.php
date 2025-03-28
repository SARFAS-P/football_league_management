<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match History</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .match-container {
            width: 1000px;
            background-color: #222;
            color: white;
            padding: 15px;
            margin: 10px auto;
            border-radius: 10px;
            font-family: Arial, sans-serif;
        }
        .match-header {
            font-size: 14px;
            color: #bbb;
            text-align: center;
            margin-bottom: 10px;
        }
        .team-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .team {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            width: 45%;
        }
        .score {
            font-size: 24px;
            margin: 0 10px;
        }
        .goal {
            font-size: 14px;
            margin-top: 5px;
            color: #bbb;
            text-align: center;
        }
        .goals-list {
            margin-top: 10px;
        }
        .referee-dropdown {
            margin-top: 10px;
        }
        .referee-list {
            display: none;
            margin-top: 5px;
        }
        .referee-list.show {
            display: block;
        }
        .referee-detail {
            margin: 5px 0;
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
                    <li><a href="test.php">Managers</a></li>
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
            <li><a href="#">#####</a></li>
        </ul>
        <div class="auth-buttons">
        <a href="login.php"><button class="login">Log In</button></a>
             <!--    <button class="register">Register</button>--> 
        </div>
    </nav>
    <br><br><br><br><br><br><br><br>

    <div class="container">
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "dbproj";

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT m.match_id, m.score, m.match_date, s.stadium_name, tpm.home_team, tpm.away_team
                FROM matches m
                JOIN stadium s ON m.stadium_id = s.stadium_id
                JOIN teamplaymatches tpm ON m.match_id = tpm.match_id
                ORDER BY m.match_date DESC";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='match-container'>";
                echo "<div class='match-header'>" . date("Y-m-d", strtotime($row["match_date"])) . " · " . $row["stadium_name"] . "</div>";
                echo "<div class='team-container'>";
                echo "<div class='team'>" . $row["home_team"] . "</div>";
                echo "<div class='score'>" . $row["score"] . "</div>";
                echo "<div class='team'>" . $row["away_team"] . "</div>";
                echo "</div>";
                echo "<hr>";

                $goal_sql = "SELECT g.goal_time, g.goal_type, p.playername, tpm.home_team, tpm.away_team
                            FROM goals g
                            LEFT JOIN players p ON g.player_id = p.player_id
                            JOIN teamplaymatches tpm ON g.match_id = tpm.match_id
                            WHERE g.match_id = " . $row["match_id"] . "
                            ORDER BY g.goal_time";

                $goal_result = mysqli_query($conn, $goal_sql);

                if (mysqli_num_rows($goal_result) > 0) {
                    echo "<div class='goals-list'>";
                    while ($goal_row = mysqli_fetch_assoc($goal_result)) {
                        $score_parts = explode("-", $row['score']);
                        $home_score = (int)$score_parts[0];
                        $away_score = (int)$score_parts[1];

                        $team_to_display = ($goal_row['home_team'] == $row['home_team'] && $home_score >= $away_score) || ($goal_row['away_team'] == $row['away_team'] && $away_score >= $home_score) ? ($goal_row['home_team'] == $row['home_team'] ? $row['home_team'] : $row['away_team']) : ($goal_row['home_team'] == $row['home_team'] ? $row['away_team'] : $row['home_team']);

                        echo "<div class='goal'>" . $goal_row["playername"] . " (" . $goal_row["goal_time"] . "')</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p class='goal'>No goals scored.</p>";
                }

                echo "<div class='referee-dropdown'>";
                echo "<button onclick='toggleReferees(" . $row["match_id"] . ")'>Match Handlers</button>";
                echo "<div class='referee-list' id='referees-" . $row["match_id"] . "'>";
                $referee_sql = "SELECT r.referee_name, rhm.role
                                FROM refereehandlematches rhm
                                JOIN referee r ON rhm.referee_id = r.referee_id
                                WHERE rhm.match_id = " . $row["match_id"];
                $referee_result = mysqli_query($conn, $referee_sql);
                if (mysqli_num_rows($referee_result) > 0) {
                    $referees = array();
                    while ($referee_row = mysqli_fetch_assoc($referee_result)) {
                        $referees[$referee_row['role']] = $referee_row['referee_name'];
                    }
                    if (isset($referees['Main Referee'])) {
                        echo "<div class='referee-detail'>Main Referee: " . $referees['Main Referee'] . "</div>";
                    }
                    if (isset($referees['Assistant 1'])) {
                        echo "<div class='referee-detail'>Assistant 1: " . $referees['Assistant 1'] . "</div>";
                    }
                    if (isset($referees['VAR'])) {
                        echo "<div class='referee-detail'>VAR: " . $referees['VAR'] . "</div>";
                    }
                } else {
                    echo "<p>No referees assigned.</p>";
                }
                echo "</div>";
                echo "</div>";

                echo "</div>";
            }
        } else {
            echo "No match history found.";
        }
        mysqli_close($conn);
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".dropdown").hover(function(){
                $(this).find(".dropdown-menu").stop(true, true).slideDown();
            }, function(){
                $(this).find(".dropdown-menu").stop(true, true).slideUp();
            });
        });
        function toggleReferees(matchId) {
            var refereesList = document.getElementById('referees-' + matchId);
            refereesList.classList.toggle('show');
        }
    </script>
</body>
</html>