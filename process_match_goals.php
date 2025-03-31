<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $home_team_id = $_POST["home_team"];
    $away_team_id = $_POST["away_team"];
    $score = $_POST["score"];
    $match_date = $_POST["match_date"];
    $stadium_id = $_POST["stadium"];
    
    // Referee IDs from the form
    $main_referee_id = $_POST["main_referee"];
    $assistant_referee_1_id = $_POST["assistant_referee_1"];
    $var_referee_id = $_POST["var_referee"];

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "dbproj";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert into matches table
    $sql_matches = "INSERT INTO matches (score, match_date, stadium_id) VALUES ('$score', '$match_date', '$stadium_id')";

    if (mysqli_query($conn, $sql_matches)) {
        $match_id = mysqli_insert_id($conn); // Get the auto-generated match_id

        // Fetch team names for teamplaymatches table
        $sql_home_team_name = "SELECT team_name FROM team WHERE team_id = $home_team_id";
        $result_home_team_name = mysqli_query($conn, $sql_home_team_name);
        $row_home_team_name = mysqli_fetch_assoc($result_home_team_name);
        $home_team_name = $row_home_team_name['team_name'];

        $sql_away_team_name = "SELECT team_name FROM team WHERE team_id = $away_team_id";
        $result_away_team_name = mysqli_query($conn, $sql_away_team_name);
        $row_away_team_name = mysqli_fetch_assoc($result_away_team_name);
        $away_team_name = $row_away_team_name['team_name'];

        // Insert into teamplaymatches table
        $sql_teamplaymatches = "INSERT INTO teamplaymatches (team_id, match_id, home_team, away_team) VALUES ($home_team_id, $match_id, '$home_team_name', '$away_team_name')";

        if (mysqli_query($conn, $sql_teamplaymatches)) {
            echo "Match and teamplay data added successfully.<br>";
        } else {
            echo "Error adding teamplay data: " . mysqli_error($conn);
        }

        // Insert goals
        if (isset($_POST['player_id']) && isset($_POST['goal_type']) && isset($_POST['goal_time'])) {
            $player_ids = $_POST['player_id'];
            $goal_types = $_POST['goal_type'];
            $goal_times = $_POST['goal_time'];

            for ($i = 0; $i < count($player_ids); $i++) {
                $player_id = $player_ids[$i];
                $goal_type = $goal_types[$i];
                $goal_time = $goal_times[$i];

                $sql_goals = "INSERT INTO goals (match_id, player_id, goal_type, goal_time) VALUES ($match_id, $player_id, '$goal_type', $goal_time)";
                if (!mysqli_query($conn, $sql_goals)) {
                    echo "Error adding goal data: " . mysqli_error($conn);
                }
            }
        }

        // Insert referee details
        $sql_referee_main = "INSERT INTO refereehandlematches (match_id, referee_id, role) VALUES ($match_id, '$main_referee_id', 'Main Referee')";
        $sql_referee_assistant1 = "INSERT INTO refereehandlematches (match_id, referee_id, role) VALUES ($match_id, '$assistant_referee_1_id', 'Assistant 1')";
        $sql_referee_var = "INSERT INTO refereehandlematches (match_id, referee_id, role) VALUES ($match_id, '$var_referee_id', 'VAR')";

        if (mysqli_query($conn, $sql_referee_main) && mysqli_query($conn, $sql_referee_assistant1) && mysqli_query($conn, $sql_referee_var)) {
            echo "Referee details added successfully.<br>";
        } else {
            echo "Error adding referee details: " . mysqli_error($conn);
        }

    } else {
        echo "Error adding match data: " . mysqli_error($conn);
    }

    mysqli_close($conn);

    header("Location: dashboard.php");
    exit();
} else {
    echo "Invalid request.";
}


?>