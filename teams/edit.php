<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">
<?php
include '../db.php';

$id = $_GET['id'];
$result = pg_query($conn, "SELECT * FROM team WHERE team_id = $id");
$row = pg_fetch_assoc($result);
?>

<h2>Edit Team</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['team_id']; ?>">

Name: <input type="text" name="tname" value="<?php echo $row['tname']; ?>"><br><br>

City: <input type="text" name="city" value="<?php echo $row['city']; ?>"><br><br>

Country: <input type="text" name="country" value="<?php echo $row['country']; ?>"><br><br>

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

<button type="submit">Update</button>
</form>

<a href="view.php">Back</a>
</div>