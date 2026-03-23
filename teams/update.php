
<?php
include '../db.php';

$id = $_POST['id'];
$name = $_POST['tname'];
$city = $_POST['city'];
$country = $_POST['country'];
$sport_id = $_POST['sport_id'];

pg_query_params($conn,
    "UPDATE team
     SET tname=$1, city=$2, country=$3, sport_id=$4
     WHERE team_id=$5",
    array($name, $city, $country, $sport_id, $id)
);

echo "Updated successfully<br>";
echo "<a href='view.php'>Back</a>";
?>