<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">
<?php include '../db.php'; ?>

<h2>Matches</h2>

<a href="add.php"><button>Add Match</button></a><br><br>

<table>
<tr>
<th>ID</th>
<th>Tournament</th>
<th>Sport</th>
<th>Venue</th>
<th>Date</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php

/* CORRECT QUERY (NO $id, LEFT JOIN for optional tournament) */
$query = "
SELECT m.*, t.tname AS tournament, s.sname, v.v_name
FROM match m
LEFT JOIN tournament t ON m.tournament_id = t.tournament_id
JOIN sport s ON m.sport_id = s.sport_id
JOIN venue v ON m.venue_id = v.venue_id
";

$result = pg_query($conn, $query);

/* DEBUG SAFETY */
if (!$result) {
    die("Query failed: " . pg_last_error($conn));
}

while ($row = pg_fetch_assoc($result)) {

    $tournament = $row['tournament'] ? $row['tournament'] : "No Tournament";

    echo "<tr>
<td>{$row['match_id']}</td>
<td>$tournament</td>
<td>{$row['sname']}</td>
<td>{$row['v_name']}</td>
<td>{$row['match_date']}</td>
<td>{$row['status']}</td>
<td>
<a class='btn' href='scorecard.php?match_id={$row['match_id']}'>Scorecard</a>
<a class='btn' href='edit.php?id={$row['match_id']}'>Edit</a>
<a class='btn' href='delete.php?id={$row['match_id']}'>Delete</a>
<a class='btn' href='details.php?id={$row['match_id']}'>View Teams</a>
</td>
</tr>";
}
?>

</table>

</div>