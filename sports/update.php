<?php
include '../db.php';

$id = $_POST['id'];
$name = $_POST['sname'];
$type = $_POST['is_team_sport'];

pg_query_params($conn,
    "UPDATE sport 
     SET sname=$1, is_team_sport=$2
     WHERE sport_id=$3",
    array($name, $type, $id)
);

echo "Updated successfully<br>";
echo "<a href='view.php'>Back</a>";
?>