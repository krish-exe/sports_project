<?php include '../db.php'; ?>
<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<h2>Tournaments</h2>

<a href="add.php"><button>Add Tournament</button></a><br><br>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Sport</th>
<th>Dates</th>
<th>Format</th>
<th>Actions</th>
</tr>

<?php
$query = "
SELECT t.*, s.sname
FROM tournament t
JOIN sport s ON t.sport_id = s.sport_id
";

$result = pg_query($conn, $query);

while ($row = pg_fetch_assoc($result)) {
    echo "<tr>
    <td>{$row['tournament_id']}</td>

    <!-- CLICKABLE -->
    <td>
        <a href='details.php?id={$row['tournament_id']}' style='color:#fff; text-decoration:underline;'>
            {$row['tname']}
        </a>
    </td>

    <td>{$row['sname']}</td>
    <td>{$row['start_date']} to {$row['end_date']}</td>
    <td>{$row['format']}</td>
    <td>
        <a class='btn' href='edit.php?id={$row['tournament_id']}'>Edit</a>
        <a class='btn' href='delete.php?id={$row['tournament_id']}'>Delete</a>
    </td>
    </tr>";
}
?>
</table>
</div>