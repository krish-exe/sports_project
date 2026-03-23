<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Tournament Details</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container">

<?php
$tournament_id = $_GET['id'] ?? 0;

if ($tournament_id == 0) {
    die("Invalid Tournament ID");
}

/* TOURNAMENT INFO */
$tournament = pg_fetch_assoc(pg_query($conn, "
SELECT t.*, s.sname
FROM tournament t
JOIN sport s ON t.sport_id = s.sport_id
WHERE t.tournament_id = $tournament_id
"));

if (!$tournament) {
    echo "<h3>Tournament not found</h3>";
    exit;
}

echo "<h2>{$tournament['tname']}</h2>";
echo "<p>
<b>Sport:</b> {$tournament['sname']} |
<b>Dates:</b> {$tournament['start_date']} to {$tournament['end_date']} |
<b>Format:</b> {$tournament['format']}
</p>";
?>

<hr>

<h3>Matches in this Tournament</h3>

<table>
<tr>
<th>ID</th>
<th>Venue</th>
<th>Date</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php
$matches = pg_query($conn, "
SELECT m.*, v.v_name
FROM match m
LEFT JOIN venue v ON m.venue_id = v.venue_id
WHERE m.tournament_id = $tournament_id
ORDER BY m.match_date
");

if (!$matches) {
    die("Query failed: " . pg_last_error($conn));
}

if (pg_num_rows($matches) == 0) {
    echo "<tr><td colspan='5'>No matches in this tournament</td></tr>";
} else {
    while ($m = pg_fetch_assoc($matches)) {

        $venue = $m['v_name'] ? $m['v_name'] : "No Venue";

        echo "<tr>
        <td>{$m['match_id']}</td>
        <td>{$venue}</td>
        <td>{$m['match_date']}</td>
        <td>{$m['status']}</td>
        <td>
            <a class='btn' href='../matches/scorecard.php?match_id={$m['match_id']}'>Scorecard</a>
            <a class='btn' href='../matches/edit.php?id={$m['match_id']}'>Edit</a>
            <a class='btn' href='../matches/delete.php?id={$m['match_id']}'>Delete</a>
        </td>
        </tr>";
    }
}
?>

</table>

<br>
<a href="view.php"><button>Back</button></a>

</div>
</body>
</html>