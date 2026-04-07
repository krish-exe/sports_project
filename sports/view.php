<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/sports_project/style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>Sports</h2>

<a href="add.php"><button>Add Sport</button></a><br><br>

<table>
<tr>
    <th>ID</th>
    <th>Sport Name</th>
    <th>Type</th>
    <th>Actions</th>
</tr>

<?php
$result = pg_query($conn, "SELECT * FROM sport");

while ($row = pg_fetch_assoc($result)) {
    $type = ($row['is_team_sport'] === 't') ? "Team" : "Individual";

    echo "<tr>
        <td>{$row['sport_id']}</td>
        <td>
            <a href='details.php?id={$row['sport_id']}' style='color:#fff; text-decoration:underline;'>
                {$row['sname']}
            </a>
        </td>
        <td>{$type}</td>
        <td>
            <a class='btn' href='edit.php?id={$row['sport_id']}'>Edit</a>
            <a class='btn' href='delete.php?id={$row['sport_id']}'>Delete</a>
        </td>
    </tr>";
}
?>

</table>

</div>
</body>
</html>