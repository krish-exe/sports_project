<?php include '../db.php'; ?>
<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>
<div class="container">

<h2>Add Team</h2>

<form action="insert.php" method="POST">

    Team Name: <input type="text" name="tname" required><br><br>

    City: <input type="text" name="city"><br><br>

    Country: <input type="text" name="country"><br><br>

    Sport:
    <select name="sport_id">
        <?php
        /* Only show team sports — individual sports don't have teams */
        $sports = pg_query($conn, "SELECT * FROM sport WHERE is_team_sport = TRUE ORDER BY sname");

        while ($s = pg_fetch_assoc($sports)) {
            echo "<option value='{$s['sport_id']}'>{$s['sname']}</option>";
        }
        ?>
    </select><br><br>

    <button type="submit">Add Team</button>
</form>

<a href="view.php">Back</a>
</div>