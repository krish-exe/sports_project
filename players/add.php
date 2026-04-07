<?php include '../db.php'; ?>
<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>

<div class="container">
<h2>Add Player</h2>

<form action="insert.php" method="POST">

Name: 
<input type="text" name="pname" required minlength="2"><br><br>

DOB:
<input type="date" name="dob" required><br><br>

Gender:
<select name="gender" required>
    <option value="">Select</option>
    <option>Male</option>
    <option>Female</option>
</select><br><br>

Country:
<input type="text" name="country" required><br><br>

Role:
<input type="text" name="role" required><br><br>

Mobile:
<input type="text" name="mob_no" pattern="[0-9]{10}" required><br><br>

Team:
<select name="team_id">
    <option value="">No Team</option>
    <?php
    $teams = pg_query($conn, "SELECT * FROM team");
    while ($t = pg_fetch_assoc($teams)) {
        echo "<option value='{$t['team_id']}'>{$t['tname']}</option>";
    }
    ?>
</select><br><br>

<button type="submit">Add Player</button>

</form>

<a href="view.php">Back</a>
</div>