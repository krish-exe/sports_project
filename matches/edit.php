<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<?php
include '../db.php';

/* SAFE ID HANDLING */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    die("Invalid match ID");
}

/* FETCH MATCH WITH JOIN */
$query = "
SELECT m.*, v.v_name
FROM match m
LEFT JOIN venue v ON m.venue_id = v.venue_id
WHERE m.match_id = $id
";

$result = pg_query($conn, $query);

if (!$result) {
    die("Match query failed: " . pg_last_error($conn));
}

$m = pg_fetch_assoc($result);

if (!$m) {
    echo "<h3>Match not found</h3>";
    exit;
}
?>

<h2>Edit Match</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $m['match_id']; ?>">

<!-- TOURNAMENT -->
Tournament:
<select name="tournament_id">
<option value="">No Tournament</option>
<?php
$t = pg_query($conn, "SELECT * FROM tournament");

while ($row = pg_fetch_assoc($t)) {
    $selected = ($row['tournament_id'] == $m['tournament_id']) ? "selected" : "";
    echo "<option value='{$row['tournament_id']}' $selected>{$row['tname']}</option>";
}
?>
</select><br><br>

<!-- SPORT -->
Sport:
<select name="sport_id" required>
<?php
$s = pg_query($conn, "SELECT * FROM sport");

while ($row = pg_fetch_assoc($s)) {
    $selected = ($row['sport_id'] == $m['sport_id']) ? "selected" : "";
    echo "<option value='{$row['sport_id']}' $selected>{$row['sname']}</option>";
}
?>
</select><br><br>

<!-- ✅ VENUE FIXED -->
Venue:
<select name="venue_id" required>
<option value="">Select Venue</option>
<?php
$v = pg_query($conn, "SELECT * FROM venue");

while ($row = pg_fetch_assoc($v)) {
    $selected = ($row['venue_id'] == $m['venue_id']) ? "selected" : "";
    echo "<option value='{$row['venue_id']}' $selected>
            {$row['v_name']} ({$row['city']})
          </option>";
}
?>
</select><br><br>

<!-- DATE (FIXED FORMAT) -->
Match Date:
<input type="date" name="match_date"
value="<?php echo date('Y-m-d', strtotime($m['match_date'])); ?>"><br><br>

<!-- STATUS -->
Status:
<select name="status">
<option value="Scheduled" <?php if($m['status']=='Scheduled') echo 'selected'; ?>>Scheduled</option>
<option value="Completed" <?php if($m['status']=='Completed') echo 'selected'; ?>>Completed</option>
</select><br><br>

<button type="submit" name="update">Update</button>

</form>

<br>
<a href="view.php"><button>Back</button></a>

</div>