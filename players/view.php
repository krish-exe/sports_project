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
    Search Name: <input type="text" name="search">
    <button type="submit">Search</button>
</form>

<br>

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
$search = $_GET['search'] ?? '';

$query = "
SELECT p.*, t.tname
FROM player p
LEFT JOIN player_team pt ON p.player_id = pt.player_id
LEFT JOIN team t ON pt.team_id = t.team_id
WHERE p.pname ILIKE '%$search%'
";

$result = pg_query($conn, $query);

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