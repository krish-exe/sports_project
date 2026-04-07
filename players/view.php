<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Players</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>Players</h2>

<a href="add.php"><button>Add Player</button></a><br><br>

<form method="GET">
    Search Name: <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    <?php if (!empty($_GET['sport_id'])): ?>
        <input type="hidden" name="sport_id" value="<?php echo intval($_GET['sport_id']); ?>">
    <?php endif; ?>
    <button type="submit">Search</button>
</form>

<br>

<?php
$search   = $_GET['search'] ?? '';
$sport_id = isset($_GET['sport_id']) ? intval($_GET['sport_id']) : 0;

/* Show sport name as context if filtering */
if ($sport_id > 0) {
    $sport_res = pg_query($conn, "SELECT sname FROM sport WHERE sport_id = $sport_id");
    $sport_row = pg_fetch_assoc($sport_res);
    if ($sport_row) {
        echo "<h3>Showing players for: {$sport_row['sname']}</h3>";
    }
}
?>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>DOB</th>
    <th>Gender</th>
    <th>Country</th>
    <th>Role</th>
    <th>Team</th>
    <th>Mobile</th>
    <th>Actions</th>
</tr>

<?php
if ($sport_id > 0) {
    /* Filter players who have participated in matches of this sport */
    $query = "
        SELECT DISTINCT p.*, t.tname
        FROM player p
        LEFT JOIN player_team pt ON p.player_id = pt.player_id
        LEFT JOIN team t ON pt.team_id = t.team_id
        JOIN match_player mp ON p.player_id = mp.player_id
        JOIN match m ON mp.match_id = m.match_id
        WHERE m.sport_id = $sport_id
        AND p.pname ILIKE '%$search%'
        ORDER BY p.pname
    ";
} else {
    $query = "
        SELECT p.*, t.tname
        FROM player p
        LEFT JOIN player_team pt ON p.player_id = pt.player_id
        LEFT JOIN team t ON pt.team_id = t.team_id
        WHERE p.pname ILIKE '%$search%'
        ORDER BY p.pname
    ";
}

$result = pg_query($conn, $query);

if (!$result) {
    die("Query failed: " . pg_last_error($conn));
}

while ($row = pg_fetch_assoc($result)) {
    $team = $row['tname'] ? $row['tname'] : "No Team";

    echo "<tr>
        <td>{$row['player_id']}</td>
        <td>{$row['pname']}</td>
        <td>{$row['dob']}</td>
        <td>{$row['gender']}</td>
        <td>{$row['country']}</td>
        <td>{$row['role']}</td>
        <td>$team</td>
        <td>{$row['mob_no']}</td>
        <td>
            <a class='btn' href='performance.php?player_id={$row['player_id']}'>Performance</a>
            <a class='btn' href='edit.php?id={$row['player_id']}'>Edit</a>
            <a class='btn' href='delete.php?id={$row['player_id']}'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

</div>

</body>
</html>