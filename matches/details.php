<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Match Details</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<?php include '../navbar.php'; ?>

<div class="container">

<?php
$match_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($match_id == 0) {
    die("Invalid Match ID");
}

/* =========================
   ADD TEAM (team sports only)
========================= */
if (isset($_POST['add_team'])) {
    $team_id = intval($_POST['team_id']);

    /* Verify the sport is a team sport before inserting */
    $sport_check = pg_fetch_assoc(pg_query($conn,
        "SELECT s.is_team_sport FROM match m
         JOIN sport s ON m.sport_id = s.sport_id
         WHERE m.match_id = $match_id"
    ));

    if ($sport_check && $sport_check['is_team_sport']) {
        pg_query($conn, "
            INSERT INTO match_team (match_id, team_id)
            VALUES ($match_id, $team_id)
            ON CONFLICT DO NOTHING
        ");
    }
}

/* =========================
   REMOVE TEAM
========================= */
if (isset($_GET['remove_team'])) {
    $tid = intval($_GET['remove_team']);

    pg_query($conn, "
        DELETE FROM match_team
        WHERE match_id = $match_id AND team_id = $tid
    ");
}

/* =========================
   MATCH INFO
========================= */
$match = pg_fetch_assoc(pg_query($conn, "
    SELECT m.*, s.sname, s.is_team_sport, v.v_name
    FROM match m
    JOIN sport s ON m.sport_id = s.sport_id
    LEFT JOIN venue v ON m.venue_id = v.venue_id
    WHERE m.match_id = $match_id
"));

if (!$match) {
    echo "<h3>Match not found</h3>";
    exit;
}

$is_team_sport = ($match['is_team_sport'] === 't');

echo "<h2>Match #{$match['match_id']}</h2>";
echo "<p>
    <b>Sport:</b> {$match['sname']} (" . ($is_team_sport ? "Team" : "Individual") . ") |
    <b>Venue:</b> {$match['v_name']} |
    <b>Date:</b> {$match['match_date']} |
    <b>Status:</b> {$match['status']}
</p>";
?>

<hr>

<?php if ($is_team_sport): ?>

    <!-- ========================= -->
    <!-- TEAM SPORT: SHOW TEAMS   -->
    <!-- ========================= -->

    <h3>Teams in this Match</h3>

    <table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Action</th>
    </tr>

    <?php
    $teams = pg_query($conn, "
        SELECT t.*
        FROM team t
        JOIN match_team mt ON t.team_id = mt.team_id
        WHERE mt.match_id = $match_id
    ");

    if (pg_num_rows($teams) == 0) {
        echo "<tr><td colspan='3'>No teams added</td></tr>";
    } else {
        while ($t = pg_fetch_assoc($teams)) {
            echo "<tr>
                <td>{$t['team_id']}</td>
                <td>{$t['tname']}</td>
                <td>
                    <a class='btn' href='details.php?id=$match_id&remove_team={$t['team_id']}'>Remove</a>
                </td>
            </tr>";
        }
    }
    ?>
    </table>

    <hr>

    <h3>Add Team to Match</h3>

    <form method="POST">
    <select name="team_id" required>
        <option value="">Select Team</option>
        <?php
        $available = pg_query($conn, "
            SELECT * FROM team
            WHERE sport_id = {$match['sport_id']}
            AND team_id NOT IN (
                SELECT team_id FROM match_team WHERE match_id = $match_id
            )
        ");

        while ($a = pg_fetch_assoc($available)) {
            echo "<option value='{$a['team_id']}'>{$a['tname']}</option>";
        }
        ?>
    </select>
    <br><br>
    <button name="add_team">Add Team</button>
    </form>

<?php else: ?>

    <!-- ================================ -->
    <!-- INDIVIDUAL SPORT: SHOW PLAYERS  -->
    <!-- ================================ -->

    <h3>Players in this Match</h3>

    <p style="color: #aaa; font-style: italic;">
        This is an individual sport — players participate directly, not as teams.
    </p>

    <table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Country</th>
        <th>Role</th>
        <th>Action</th>
    </tr>

    <?php
    $players = pg_query($conn, "
        SELECT p.*
        FROM player p
        JOIN match_player mp ON p.player_id = mp.player_id
        WHERE mp.match_id = $match_id
        ORDER BY p.pname
    ");

    if (pg_num_rows($players) == 0) {
        echo "<tr><td colspan='5'>No players added to this match yet</td></tr>";
    } else {
        while ($p = pg_fetch_assoc($players)) {
            echo "<tr>
                <td>{$p['player_id']}</td>
                <td>{$p['pname']}</td>
                <td>{$p['country']}</td>
                <td>{$p['role']}</td>
                <td>
                    <a class='btn' href='details.php?id=$match_id&remove_player={$p['player_id']}'>Remove</a>
                </td>
            </tr>";
        }
    }
    ?>
    </table>

    <?php
    /* REMOVE PLAYER from individual match */
    if (isset($_GET['remove_player'])) {
        $pid = intval($_GET['remove_player']);
        pg_query($conn, "DELETE FROM match_player WHERE match_id = $match_id AND player_id = $pid");
        echo "<script>location.href='details.php?id=$match_id';</script>";
    }
    ?>

    <hr>

    <h3>Add Player to Match</h3>

    <form method="POST" action="add_player.php">
        <input type="hidden" name="match_id" value="<?= $match_id ?>">
        <select name="player_id" required>
            <option value="">Select Player</option>
            <?php
            $available_players = pg_query($conn, "
                SELECT p.*
                FROM player p
                WHERE p.player_id NOT IN (
                    SELECT player_id FROM match_player WHERE match_id = $match_id
                )
                ORDER BY p.pname
            ");

            while ($ap = pg_fetch_assoc($available_players)) {
                echo "<option value='{$ap['player_id']}'>{$ap['pname']} ({$ap['country']})</option>";
            }
            ?>
        </select>
        <br><br>
        <button type="submit">Add Player</button>
    </form>

<?php endif; ?>

<br>
<a href="view.php"><button>Back</button></a>

</div>
</body>
</html>