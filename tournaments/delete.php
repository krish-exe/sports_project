<?php
include '../db.php';

$id = $_GET['id'];

/* OPTIONAL: REMOVE TOURNAMENT FROM MATCHES FIRST */
pg_query($conn, "
UPDATE match
SET tournament_id = NULL
WHERE tournament_id = $id
");

/* DELETE TOURNAMENT */
pg_query($conn, "
DELETE FROM tournament
WHERE tournament_id = $id
");

header("Location: view.php");
?>