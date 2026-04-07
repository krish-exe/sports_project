<?php include '../db.php'; ?>
<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<h2>Add Match</h2>

<form action="insert.php" method="POST">

Tournament:
<select name="tournament_id">
<option value="">No Tournament</option>
<?php
$t = pg_query($conn, "SELECT * FROM tournament");
while ($row = pg_fetch_assoc($t)) {
    echo "<option value='{$row['tournament_id']}'>{$row['tname']}</option>";
}
?>
</select><br><br>

Sport:
<select name="sport_id">
<?php
$s = pg_query($conn, "SELECT * FROM sport");
while ($row = pg_fetch_assoc($s)) {
    echo "<option value='{$row['sport_id']}'>{$row['sname']}</option>";
}
?>
</select><br><br>

Venue:
<input type="text" name="v_name" required><br><br>

Match Date: <input type="date" name="match_date" required><br><br>

Status:
<select name="status">
<option>Scheduled</option>
<option>Completed</option>
</select><br><br>

<button type="submit">Add Match</button>

</form>

<a href="view.php">Back</a>
</div>