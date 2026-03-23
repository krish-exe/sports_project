<?php
include '../db.php';

$id = $_GET['id'];

pg_query($conn, "DELETE FROM match WHERE match_id = $id");

header("Location: view.php");
?>