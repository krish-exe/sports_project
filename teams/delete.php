<?php
include '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid team ID");
}

/* 1. Remove this team from all matches */
pg_query($conn, "DELETE FROM match_team WHERE team_id = $id");

/* 2. Remove all players from this team (player_team junction) */
pg_query($conn, "DELETE FROM player_team WHERE team_id = $id");

/* 3. Remove from match_player records */
pg_query($conn, "DELETE FROM match_player WHERE team_id = $id");

/* 4. Delete the team */
$result = pg_query($conn, "DELETE FROM team WHERE team_id = $id");

if (!$result) {
    die("Delete failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>