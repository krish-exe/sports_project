<?php
include '../db.php';

$match_id = isset($_POST['match_id']) ? intval($_POST['match_id']) : 0;
$player_id = isset($_POST['player_id']) ? intval($_POST['player_id']) : 0;

if ($match_id == 0 || $player_id == 0) {
    die("Invalid input");
}

/* Verify this match is actually an individual sport */
$sport_check = pg_fetch_assoc(pg_query($conn,
    "SELECT s.is_team_sport FROM match m
     JOIN sport s ON m.sport_id = s.sport_id
     WHERE m.match_id = $match_id"
));

if (!$sport_check || $sport_check['is_team_sport']) {
    die("This match is a team sport — add players via teams.");
}

/* Insert into match_player with no team */
pg_query_params($conn,
    "INSERT INTO match_player (match_id, player_id)
     VALUES ($1, $2)
     ON CONFLICT (match_id, player_id) DO NOTHING",
    array($match_id, $player_id)
);

header("Location: details.php?id=$match_id");
exit;
?>