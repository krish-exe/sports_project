<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">
<?php
include '../db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) die("Invalid team ID");

$result = pg_query($conn, "SELECT * FROM team WHERE team_id = $id");
$row = pg_fetch_assoc($result);
if (!$row) die("Team not found");
?>

<h2>Edit Team</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['team_id']; ?>">

Name: <input type="text" name="tname" value="<?php echo htmlspecialchars($row['tname']); ?>"><br><br>

City: <input type="text" name="city" value="<?php echo htmlspecialchars($row['city']); ?>"><br><br>

Country: <input type="text" name="country" value="<?php echo htmlspecialchars($row['country']); ?>"><br><br>

Sport:
<select name="sport_id">
<?php
/* Only show team sports */
$sports = pg_query($conn, "SELECT * FROM sport WHERE is_team_sport = TRUE ORDER BY sname");

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