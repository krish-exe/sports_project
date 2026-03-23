<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Team Details</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container">

<?php
$team_id = $_GET['id'] ?? 0;

/* =========================
   ADD PLAYER TO TEAM
========================= */
if (isset($_POST['add_player'])) {
    $player_id = $_POST['player_id'];

    pg_query($conn, "
        INSERT INTO player_team (player_id, team_id)
        VALUES ($player_id, $team_id)
    ");
}

/* =========================
   REMOVE PLAYER FROM TEAM
========================= */
if (isset($_GET['remove_player'])) {
    $pid = $_GET['remove_player'];

    pg_query($conn, "
        DELETE FROM player_team
        WHERE player_id = $pid AND team_id = $team_id
    ");
}

/* =========================
   TEAM INFO
========================= */
$team_query = "
SELECT t.*, s.sname
FROM team t
JOIN sport s ON t.sport_id = s.sport_id
WHERE t.team_id = $team_id
";

$team_result = pg_query($conn, $team_query);
$team = pg_fetch_assoc($team_result);

if (!$team) {
    echo "<h3>Team not found</h3>";
    exit;
}

echo "<h2>{$team['tname']}</h2>";
echo "<p><b>City:</b> {$team['city']} | <b>Country:</b> {$team['country']} | <b>Sport:</b> {$team['sname']}</p>";
?>

<hr>

<h3>Players in this Team</h3>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Role</th>
    <th>Country</th>
    <th>Action</th>
</tr>

<?php
$players_query = "
SELECT p.*
FROM player p
JOIN player_team pt ON p.player_id = pt.player_id
WHERE pt.team_id = $team_id
";

$players = pg_query($conn, $players_query);

if (pg_num_rows($players) == 0) {
    echo "<tr><td colspan='5'>No players in this team</td></tr>";
} else {
    while ($p = pg_fetch_assoc($players)) {
        echo "<tr>
            <td>{$p['player_id']}</td>
            <td>{$p['pname']}</td>
            <td>{$p['role']}</td>
            <td>{$p['country']}</td>
            <td>
                <a class='btn' href='details.php?id=$team_id&remove_player={$p['player_id']}'>Remove</a>
            </td>
        </tr>";
    }
}
?>

</table>

<hr>

<h3>Add Player to Team</h3>

<form method="POST">

<select name="player_id" required>
    <option value="">Select Player</option>

    <?php
    /* PLAYERS WITH NO TEAM */
    $available_players = pg_query($conn, "
        SELECT p.*
        FROM player p
        LEFT JOIN player_team pt ON p.player_id = pt.player_id
        WHERE pt.player_id IS NULL
    ");

    while ($ap = pg_fetch_assoc($available_players)) {
        echo "<option value='{$ap['player_id']}'>{$ap['pname']}</option>";
    }
    ?>
</select>

<br><br>
<button type="submit" name="add_player">Add Player</button>

</form>

<br>
<a href="view.php"><button>Back to Teams</button></a>

</div>
</body>
</html>