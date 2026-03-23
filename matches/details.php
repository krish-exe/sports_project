<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Match Details</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container">

<?php
$match_id = $_GET['id'] ?? 0;

if ($match_id == 0) {
    die("Invalid Match ID");
}

/* =========================
   ADD TEAM
========================= */
if (isset($_POST['add_team'])) {
    $team_id = $_POST['team_id'];

    pg_query($conn, "
        INSERT INTO match_team (match_id, team_id)
        VALUES ($match_id, $team_id)
        ON CONFLICT DO NOTHING
    ");
}

/* =========================
   REMOVE TEAM
========================= */
if (isset($_GET['remove_team'])) {
    $tid = $_GET['remove_team'];

    pg_query($conn, "
        DELETE FROM match_team
        WHERE match_id = $match_id AND team_id = $tid
    ");
}

/* =========================
   MATCH INFO
========================= */
$match = pg_fetch_assoc(pg_query($conn, "
SELECT m.*, s.sname, v.v_name
FROM match m
JOIN sport s ON m.sport_id = s.sport_id
LEFT JOIN venue v ON m.venue_id = v.venue_id
WHERE m.match_id = $match_id
"));

if (!$match) {
    echo "<h3>Match not found</h3>";
    exit;
}

echo "<h2>Match #{$match['match_id']}</h2>";
echo "<p><b>Sport:</b> {$match['sname']} |
<b>Venue:</b> {$match['v_name']} |
<b>Date:</b> {$match['match_date']} |
<b>Status:</b> {$match['status']}</p>";
?>

<hr>

<h3>Teams in this Match</h3>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Action</th>
</tr>

<?php
$teams = pg_query($conn, "
SELECT t.*
FROM team t
JOIN match_team mt ON t.team_id = mt.team_id
WHERE mt.match_id = $match_id
");

if (pg_num_rows($teams) == 0) {
    echo "<tr><td colspan='3'>No teams added</td></tr>";
} else {
    while ($t = pg_fetch_assoc($teams)) {
        echo "<tr>
        <td>{$t['team_id']}</td>
        <td>{$t['tname']}</td>
        <td>
            <a class='btn' href='details.php?id=$match_id&remove_team={$t['team_id']}'>Remove</a>
        </td>
        </tr>";
    }
}
?>
</table>

<hr>

<h3>Add Team to Match</h3>

<form method="POST">

<select name="team_id" required>
<option value="">Select Team</option>

<?php
/* TEAMS NOT ALREADY IN MATCH */
$available = pg_query($conn, "
SELECT *
FROM team
WHERE team_id NOT IN (
    SELECT team_id FROM match_team WHERE match_id = $match_id
)
");

while ($a = pg_fetch_assoc($available)) {
    echo "<option value='{$a['team_id']}'>{$a['tname']}</option>";
}
?>
</select>

<br><br>
<button name="add_team">Add Team</button>

</form>

<br>
<a href="view.php"><button>Back</button></a>

</div>
</body>
</html>