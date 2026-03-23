<?php include '../db.php'; ?>

<?php
$pname = $_POST['pname'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$country = $_POST['country'];
$role = $_POST['role'];
$mob_no = $_POST['mob_no'];
$team_id = $_POST['team_id'];

/* INSERT PLAYER */
$query = "
INSERT INTO player (pname, dob, gender, country, role, mob_no)
VALUES ('$pname', '$dob', '$gender', '$country', '$role', '$mob_no')
RETURNING player_id
";

$result = pg_query($conn, $query);
$row = pg_fetch_assoc($result);
$player_id = $row['player_id'];

/* INSERT INTO player_team IF TEAM SELECTED */
if (!empty($team_id)) {
    pg_query($conn, "
        INSERT INTO player_team (player_id, team_id)
        VALUES ($player_id, $team_id)
    ");
}

header("Location: view.php");
?>