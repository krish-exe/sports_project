<?php
include '../db.php';

$name = $_POST['sname'];
$type = $_POST['is_team_sport'];

/* VALIDATION */
if (empty($name)) {
    die("Sport name required");
}

pg_query_params($conn,
    "INSERT INTO sport (sname, is_team_sport)
     VALUES ($1, $2)",
    array($name, $type)
);

echo "Sport added successfully<br>";
echo "<a href='view.php'>Back</a>";
?>