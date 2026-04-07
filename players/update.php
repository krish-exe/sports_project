<?php include '../db.php'; ?>

<?php
$id = $_POST['id'];
$pname = $_POST['pname'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$country = $_POST['country'];
$role = $_POST['role'];
$mob_no = $_POST['mob_no'];
$team_id = $_POST['team_id'];

/* UPDATE PLAYER */
pg_query($conn, "
UPDATE player
SET pname='$pname',
    dob='$dob',
    gender='$gender',
    country='$country',
    role='$role',
    mob_no='$mob_no'
WHERE player_id=$id
");

/* RESET TEAM (important for 1-team rule) */
pg_query($conn, "DELETE FROM player_team WHERE player_id = $id");

/* ADD NEW TEAM IF SELECTED */
if (!empty($team_id)) {
    pg_query($conn, "
        INSERT INTO player_team (player_id, team_id)
        VALUES ($id, $team_id)
    ");
}

header("Location: view.php");
?>