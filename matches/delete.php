<?php
include '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid match ID");
}

/* 
   match_team, match_player, and player_stats all have 
   ON DELETE CASCADE on match_id in the schema.
   But we manually clean stat_detail first since it 
   cascades from player_stats (stat_id), not match directly.
*/

/* 1. Delete stat_detail for all stats in this match */
pg_query($conn, "
    DELETE FROM stat_detail
    WHERE stat_id IN (
        SELECT stat_id FROM player_stats WHERE match_id = $id
    )
");

/* 2. Delete player_stats (cascades would handle match_player & match_team, but explicit is safer) */
pg_query($conn, "DELETE FROM player_stats WHERE match_id = $id");
pg_query($conn, "DELETE FROM match_player WHERE match_id = $id");
pg_query($conn, "DELETE FROM match_team WHERE match_id = $id");

/* 3. Delete the match */
$result = pg_query($conn, "DELETE FROM match WHERE match_id = $id");

if (!$result) {
    die("Delete failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>