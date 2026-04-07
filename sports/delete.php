<?php
include '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid sport ID");
}

/* -----------------------------------------------
   Sport is referenced by: tournament, team, match
   We must clean up in reverse dependency order.
----------------------------------------------- */

/* 1. Get all matches for this sport */
$matches = pg_query($conn, "SELECT match_id FROM match WHERE sport_id = $id");
while ($m = pg_fetch_assoc($matches)) {
    $mid = $m['match_id'];

    /* Delete stat_detail -> player_stats -> match_player -> match_team for each match */
    pg_query($conn, "
        DELETE FROM stat_detail
        WHERE stat_id IN (
            SELECT stat_id FROM player_stats WHERE match_id = $mid
        )
    ");
    pg_query($conn, "DELETE FROM player_stats WHERE match_id = $mid");
    pg_query($conn, "DELETE FROM match_player WHERE match_id = $mid");
    pg_query($conn, "DELETE FROM match_team WHERE match_id = $mid");
}

/* 2. Delete all matches for this sport */
pg_query($conn, "DELETE FROM match WHERE sport_id = $id");

/* 3. Detach tournaments from this sport (or delete them) */
pg_query($conn, "DELETE FROM tournament WHERE sport_id = $id");

/* 4. Remove players from teams of this sport (via player_team) */
$teams = pg_query($conn, "SELECT team_id FROM team WHERE sport_id = $id");
while ($t = pg_fetch_assoc($teams)) {
    $tid = $t['team_id'];
    pg_query($conn, "DELETE FROM match_player WHERE team_id = $tid");
    pg_query($conn, "DELETE FROM player_team WHERE team_id = $tid");
}

/* 5. Delete teams for this sport */
pg_query($conn, "DELETE FROM team WHERE sport_id = $id");

/* 6. Delete the sport */
$result = pg_query($conn, "DELETE FROM sport WHERE sport_id = $id");

if (!$result) {
    die("Delete failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>