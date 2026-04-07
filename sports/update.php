<?php
include '../db.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$name = $_POST['sname'];
$type = $_POST['is_team_sport'];

if ($id == 0 || empty($name)) {
    die("Invalid input");
}

$result = pg_query_params($conn,
    "UPDATE sport SET sname = $1, is_team_sport = $2 WHERE sport_id = $3",
    array($name, $type, $id)
);

if (!$result) {
    die("Update failed: " . pg_last_error($conn));
}

header("Location: view.php");
exit;
?>