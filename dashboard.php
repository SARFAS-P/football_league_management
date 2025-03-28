<?php

session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Database connection details
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

// Fetch teams for dropdown
$teams = [];
$teamResult = mysqli_query($conn, "SELECT team_id, team_name FROM team");
if (mysqli_num_rows($teamResult) > 0) {
    while ($row = mysqli_fetch_assoc($teamResult)) {
        $teams[] = $row;
    }
} else {
    echo "Error fetching teams: " . mysqli_error($conn);
}

// Fetch stadiums for dropdown
$stadiums = [];
$stadiumResult = mysqli_query($conn, "SELECT stadium_id, stadium_name FROM stadium");
if (mysqli_num_rows($stadiumResult) > 0) {
    while ($row = mysqli_fetch_assoc($stadiumResult)) {
        $stadiums[] = $row;
    }
} else {
    echo "Error fetching stadiums: " . mysqli_error($conn);
}

// Fetch players for dropdown
$players = [];
$playerResult = mysqli_query($conn, "SELECT players.player_id, players.playername, team.team_name FROM players JOIN team ON players.team_id = team.team_id");
if (mysqli_num_rows($playerResult) > 0) {
    while ($row = mysqli_fetch_assoc($playerResult)) {
        $players[] = $row;
    }
} else {
    echo "Error fetching players: " . mysqli_error($conn);
}

// Fetch referees for dropdown
$referees = [];
$refereeResult = mysqli_query($conn, "SELECT referee_id, referee_name FROM referee");
if (mysqli_num_rows($refereeResult) > 0) {
    while ($row = mysqli_fetch_assoc($refereeResult)) {
        $referees[] = $row;
    }
} else {
    echo "Error fetching referees: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .nav.navbar {
            background-color: #1e1e1e;
            color: #e0e0e0;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav.navbar h2 {
            margin: 0;
        }

        .nav.navbar .logout {
            background-color: #d32f2f;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .nav.navbar .logout:hover {
            background-color: #b71c1c;
        }

        .dashboard-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
        }

        .dashboard-section {
            width: 600px;
            padding: 20px;
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            overflow-y: auto;
        }

        .dashboard-section h2 {
            color: #bb86fc;
            margin-bottom: 20px;
            text-align: center;
        }

        .dashboard-section form label {
            display: block;
            margin-bottom: 5px;
            color: #bb86fc;
        }

        .dashboard-section form input,
        .dashboard-section form select {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #373737;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #2c2c2c;
            color: #e0e0e0;
        }

        .dashboard-section form button {
            background-color: #bb86fc;
            color: #121212;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            display: block;
            margin: 20px auto;
        }

        .dashboard-section form button:hover {
            background-color: #a052de;
        }

        .goal {
            border: 1px solid #373737;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .nav.navbar .logout-container {
            margin-left: auto;
            margin-right: 20px;
        }

    </style>
</head>
<body>
    <nav class="nav navbar">
        <div class="logo-section">
            <h2>Admin Panel</h2>
        </div>
        <div class="logout-container">
            <a href="logout.php"><button class="logout">Logout</button></a>
        </div>
    </nav>
    <br><br>
    <div class="dashboard-container">
        <div class="dashboard-section">
            <h2>Add Match History and Goals</h2>
            <form action="process_match_goals.php" method="POST">
                <label for="home_team">Home Team:</label>
                <select name="home_team" id="home_team">
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['team_id']; ?>"><?php echo $team['team_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="away_team">Away Team:</label>
                <select name="away_team" id="away_team">
                    <?php foreach ($teams as $team): ?>
                        <option value="<?php echo $team['team_id']; ?>"><?php echo $team['team_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="score">Score (e.g., 3-2):</label>
                <input type="text" name="score" id="score" required>

                <label for="match_date">Match Date (YYYY-MM-DD):</label>
                <input type="text" name="match_date" id="match_date" required>

                <label for="stadium">Stadium:</label>
                <select name="stadium" id="stadium">
                    <?php foreach ($stadiums as $stadium): ?>
                        <option value="<?php echo $stadium['stadium_id']; ?>"><?php echo $stadium['stadium_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <h3>Goals</h3>
                <div id="goalsContainer">
                    <div class="goal">
                        <label for="player_id[]">Player:</label>
                        <select name="player_id[]">
                            <?php foreach ($players as $player): ?>
                                <option value="<?php echo $player['player_id']; ?>"><?php echo $player['playername']."  (".$player['team_name'].")" ; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="goal_type[]">Goal Type:</label>
                        <select name="goal_type[]">
                            <option value="longball">Longball</option>
                            <option value="header">Header</option>
                            <option value="penalty">Penalty</option>
                            <option value="freekick">Freekick</option>
                            <option value="volley">Volley</option>
                        </select>

                        <label for="goal_time[]">Goal Time (Minute):</label>
                        <input type="number" name="goal_time[]" required>
                    </div>
                </div>

                <button type="button" id="addGoal">Add Goal</button>

                <h3>Referee Details</h3>
                <label for="main_referee">Main Referee:</label>
                <select name="main_referee" id="main_referee">
                    <?php foreach ($referees as $referee): ?>
                        <option value="<?php echo $referee['referee_id']; ?>"><?php echo $referee['referee_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="assistant_referee_1">Assistant Referee 1:</label>
                <select name="assistant_referee_1" id="assistant_referee_1">
                    <?php foreach ($referees as $referee): ?>
                        <option value="<?php echo $referee['referee_id']; ?>"><?php echo $referee['referee_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="var_referee">VAR Referee:</label>
                <select name="var_referee" id="var_referee">
                    <?php foreach ($referees as $referee): ?>
                        <option value="<?php echo $referee['referee_id']; ?>"><?php echo $referee['referee_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Add Match and Goals</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('addGoal').addEventListener('click', function() {
            const goalsContainer = document.getElementById('goalsContainer');
            const newGoal = document.querySelector('.goal').cloneNode(true);
            goalsContainer.appendChild(newGoal);
        });
    </script>
</body>
</html>