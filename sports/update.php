<?php
include '../db.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$name = $_POST['sname'];
$type = $_POST['is_team_sport'];

if ($id == 0 || empty($name)) {
    die("Invalid input");
}

/* Cast to proper boolean for PostgreSQL */
$is_team = ($type === '1') ? 'TRUE' : 'FALSE';

$result = pg_query($conn,
    "UPDATE sport SET sname = '$name', is_team_sport = $is_team WHERE sport_id = $id"
);

if (!$result) {
    die("Update failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>