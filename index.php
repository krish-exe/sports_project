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

    $is_team = ($s['is_team_sport'] === 't');

    /* Route to correct view */
    $link = $is_team
        ? "teams/view.php?sport_id={$s['sport_id']}"
        : "players/view.php?sport_id={$s['sport_id']}";

    $type_label = $is_team ? "Team Sport" : "Individual Sport";
    $icon = $is_team ? "👥" : "🏃";

    echo "
    <a href='$link' style='text-decoration:none; color:inherit;'>
        <div class='card'>
            <h3>{$icon} {$s['sname']}</h3>
            <p style='opacity:0.7; font-size:0.85em;'>$type_label</p>
        </div>
    </a>
    ";
}
?>

</div>

</div>

</body>
</html>