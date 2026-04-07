<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Teams</title>
    <link rel="stylesheet" href="/sports_project/style.css">
</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>Teams</h2>

<a href="add.php"><button>Add Team</button></a><br><br>

<?php
$sport_id = $_GET['sport_id'] ?? '';

if ($sport_id) {
    $res = pg_query($conn, "SELECT sname FROM sport WHERE sport_id = $sport_id");
    $sport = pg_fetch_assoc($res);
    echo "<h3>Showing teams for: {$sport['sname']}</h3>";
}
?>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>City</th>
    <th>Country</th>
    <th>Sport</th>
    <th>Actions</th>
</tr>

<?php
if ($sport_id) {
    $query = "
    SELECT t.*, s.sname
    FROM team t
    JOIN sport s ON t.sport_id = s.sport_id
    WHERE t.sport_id = $sport_id
    ";
} else {
    $query = "
    SELECT t.*, s.sname
    FROM team t
    JOIN sport s ON t.sport_id = s.sport_id
    ";
}

$result = pg_query($conn, $query);

while ($row = pg_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['team_id']}</td>

        <!-- CLICKABLE TEAM -->
        <td>
            <a href='details.php?id={$row['team_id']}' style='color:#fff; text-decoration:underline;'>
                {$row['tname']}
            </a>
        </td>

        <td>{$row['city']}</td>
        <td>{$row['country']}</td>
        <td>{$row['sname']}</td>
        <td>
            <a class='btn' href='edit.php?id={$row['team_id']}'>Edit</a>
            <a class='btn' href='delete.php?id={$row['team_id']}'>Delete</a>
        </td>
    </tr>";
}
?>

</table>

<br>
<a href="../index.php"><button>Back to Home</button></a>

</div>
</body>
</html>