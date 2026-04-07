
<?php
include '../db.php';

$name = $_POST['tname'];
$city = $_POST['city'];
$country = $_POST['country'];
$sport_id = $_POST['sport_id'];
if (empty($name)) {
    die("Team name required");
}
pg_query_params($conn,
    "INSERT INTO team (tname, city, country, sport_id)
     VALUES ($1, $2, $3, $4)",
    array($name, $city, $country, $sport_id)
);

echo "Team added successfully<br>";
echo "<a href='view.php'>Back</a>";
?>