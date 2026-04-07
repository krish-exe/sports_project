<?php
include '../db.php';

$name = trim($_POST['sname']);
$type = $_POST['is_team_sport']; // "1" or "0"

if (empty($name)) {
    die("Sport name required");
}

/* Cast to proper boolean for PostgreSQL */
$is_team = ($type === '1') ? 'TRUE' : 'FALSE';

$result = pg_query($conn,
    "INSERT INTO sport (sname, is_team_sport) VALUES ('$name', $is_team)"
);

if (!$result) {
    die("Insert failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>