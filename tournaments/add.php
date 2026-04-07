<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">
<?php include '../db.php'; ?>

<h2>Add Tournament</h2>

<form action="insert.php" method="POST">

Name: <input type="text" name="tname" required><br><br>

Sport:
<select name="sport_id">
<?php
$sports = pg_query($conn, "SELECT * FROM sport");
while ($s = pg_fetch_assoc($sports)) {
    echo "<option value='{$s['sport_id']}'>{$s['sname']}</option>";
}
?>
</select><br><br>

Start Date: <input type="date" name="start_date"><br><br>
End Date: <input type="date" name="end_date"><br><br>

Format: <input type="text" name="format"><br><br>

<button type="submit">Add Tournament</button>
</form>

<a href="view.php">Back</a>
</div>