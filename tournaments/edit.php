<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<?php
include '../db.php';

$id = $_GET['id'];

/* FETCH TOURNAMENT */
$result = pg_query($conn, "
SELECT * FROM tournament WHERE tournament_id = $id
");

$row = pg_fetch_assoc($result);
?>

<h2>Edit Tournament</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['tournament_id']; ?>">

Name:
<input type="text" name="tname" value="<?php echo $row['tname']; ?>" required><br><br>

Sport:
<select name="sport_id">
<?php
$sports = pg_query($conn, "SELECT * FROM sport");

while ($s = pg_fetch_assoc($sports)) {
    $selected = ($s['sport_id'] == $row['sport_id']) ? "selected" : "";
    echo "<option value='{$s['sport_id']}' $selected>{$s['sname']}</option>";
}
?>
</select><br><br>

Start Date:
<input type="date" name="start_date" value="<?php echo $row['start_date']; ?>"><br><br>

End Date:
<input type="date" name="end_date" value="<?php echo $row['end_date']; ?>"><br><br>

Format:
<input type="text" name="format" value="<?php echo $row['format']; ?>"><br><br>

<button type="submit">Update</button>

</form>

<a href="view.php">Back</a>

</div>