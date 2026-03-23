<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Sports Dashboard</title>
    <link rel="stylesheet" href="/sports_project/style.css">
</head>

<body>

<?php include 'navbar.php'; ?>

<div class="container">

<h2>Sports Dashboard</h2>

<div class="grid">

<?php
$sports = pg_query($conn, "SELECT * FROM sport");

while ($s = pg_fetch_assoc($sports)) {

    // Icon based on type
   

    // Clickable card
    echo "
    <a href='teams/view.php?sport_id={$s['sport_id']}' style='text-decoration:none; color:inherit;'>
        <div class='card'>
            <h3>{$s['sname']}</h3>
           
        </div>
    </a>
    ";
}
?>

</div>

</div>

</body>
</html>