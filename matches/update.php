<?php include '../db.php'; ?>

<?php
$id = intval($_POST['id']);
$t = $_POST['tournament_id'];
$s = intval($_POST['sport_id']);
$v = intval($_POST['venue_id']);
$date = $_POST['match_date'];
$status = $_POST['status'];

/* NULL HANDLING */
$tournament = ($t === "") ? null : intval($t);

$result = pg_query_params($conn, "
UPDATE match
SET tournament_id = $1,
    sport_id = $2,
    venue_id = $3,
    match_date = $4,
    status = $5
WHERE match_id = $6
", array($tournament, $s, $v, $date, $status, $id));

if (!$result) {
    die("Update failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>