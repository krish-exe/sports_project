<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<?php
include '../db.php';

$id = $_GET['id'];

/* PLAYER DATA */
$result = pg_query($conn, "SELECT * FROM player WHERE player_id = $id");
$row = pg_fetch_assoc($result);

/* CURRENT TEAM */
$team_result = pg_query($conn, "
SELECT team_id FROM player_team WHERE player_id = $id
");

$current_team = null;
if (pg_num_rows($team_result) > 0) {
    $team_row = pg_fetch_assoc($team_result);
    $current_team = $team_row['team_id'];
}
?>

<h2>Edit Player</h2>

<form action="update.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['player_id']; ?>">

Name: <input type="text" name="pname" value="<?php echo $row['pname']; ?>"><br><br>

DOB: <input type="date" name="dob" value="<?php echo $row['dob']; ?>"><br><br>

Gender:
<select name="gender">
    <option value="Male" <?php if($row['gender']=='Male') echo 'selected'; ?>>Male</option>
    <option value="Female" <?php if($row['gender']=='Female') echo 'selected'; ?>>Female</option>
</select><br><br>

Country: <input type="text" name="country" value="<?php echo $row['country']; ?>"><br><br>

Role: <input type="text" name="role" value="<?php echo $row['role']; ?>"><br><br>

Mobile: <input type="text" name="mob_no" value="<?php echo $row['mob_no']; ?>"><br><br>

<!-- NEW: TEAM DROPDOWN -->
Team:
<select name="team_id">
    <option value="">No Team</option>

    <?php
    $teams = pg_query($conn, "SELECT * FROM team");

    while ($t = pg_fetch_assoc($teams)) {
        $selected = ($t['team_id'] == $current_team) ? "selected" : "";
        echo "<option value='{$t['team_id']}' $selected>{$t['tname']}</option>";
    }
    ?>
</select><br><br>

<button type="submit">Update</button>

</form>

<a href="view.php">Back</a>
</div>