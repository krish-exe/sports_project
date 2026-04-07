<?php
include '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid player ID");
}

/* 1. Delete stat_detail rows linked to this player's stats */
pg_query($conn, "
    DELETE FROM stat_detail
    WHERE stat_id IN (
        SELECT stat_id FROM player_stats WHERE player_id = $id
    )
");

/* 2. Delete player_stats */
pg_query($conn, "DELETE FROM player_stats WHERE player_id = $id");

/* 3. Delete from match_player */
pg_query($conn, "DELETE FROM match_player WHERE player_id = $id");

/* 4. Delete from player_team */
pg_query($conn, "DELETE FROM player_team WHERE player_id = $id");

/* 5. Delete the player */
$result = pg_query($conn, "DELETE FROM player WHERE player_id = $id");

if (!$result) {
    die("Delete failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>