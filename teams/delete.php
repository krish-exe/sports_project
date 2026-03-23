
<?php
include '../db.php';

$id = $_GET['id'];

pg_query($conn, "DELETE FROM team WHERE team_id = $id");

echo "Deleted successfully<br>";
echo "<a href='view.php'>Back</a>";
?>