
<?php
include '../db.php';

$name = $_POST['tname'];
$sport_id = $_POST['sport_id'];
$start = $_POST['start_date'];
$end = $_POST['end_date'];
$format = $_POST['format'];

pg_query_params($conn,
    "INSERT INTO tournament (tname, sport_id, start_date, end_date, format)
     VALUES ($1, $2, $3, $4, $5)",
    array($name, $sport_id, $start, $end, $format)
);

echo "Tournament added<br>";
echo "<a href='view.php'>Back</a>";
?>