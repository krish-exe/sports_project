<?php
include '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid tournament ID");
}

/* 1. Detach matches from this tournament (set to NULL) */
pg_query($conn, "
    UPDATE match SET tournament_id = NULL WHERE tournament_id = $id
");

/* 2. Delete the tournament */
$result = pg_query($conn, "DELETE FROM tournament WHERE tournament_id = $id");

if (!$result) {
    die("Delete failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>