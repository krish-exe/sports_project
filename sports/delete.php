<?php
include '../db.php';

$id = $_GET['id'];

pg_query($conn, "DELETE FROM sport WHERE sport_id = $id");

echo "Deleted successfully<br>";
echo "<a href='view.php'>Back</a>";
?>