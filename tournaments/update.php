<?php include '../db.php'; ?>

<?php
$id = $_POST['id'];
$name = $_POST['name'];
$sport_id = $_POST['sport_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$format = $_POST['format'];

pg_query($conn, "
UPDATE tournament
SET name='$name',
    sport_id=$sport_id,
    start_date='$start_date',
    end_date='$end_date',
    format='$format'
WHERE tournament_id=$id
");

header("Location: view.php");
?>