<?php include '../db.php'; ?>
<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid sport ID");
}

$result = pg_query($conn, "SELECT * FROM sport WHERE sport_id = $id");
$row = pg_fetch_assoc($result);

if (!$row) {
    die("Sport not found");
}
?>

<h2>Edit Sport</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['sport_id']; ?>">

Sport Name:
<input type="text" name="sname" value="<?php echo htmlspecialchars($row['sname']); ?>" required minlength="2"><br><br>

Type:
<select name="is_team_sport" required>
    <option value="1" <?php if ($row['is_team_sport']) echo 'selected'; ?>>Team Sport</option>
    <option value="0" <?php if (!$row['is_team_sport']) echo 'selected'; ?>>Individual Sport</option>
</select><br><br>

<button type="submit">Update</button>

</form>

<a href="view.php">Back</a>
</div>