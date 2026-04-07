<?php include '../db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Sport Details</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

<?php
$sport_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($sport_id == 0) {
    die("Invalid Sport ID");
}

/* FETCH SPORT */
$sport = pg_fetch_assoc(pg_query($conn,
    "SELECT * FROM sport WHERE sport_id = $sport_id"
));

if (!$sport) {
    echo "<h3>Sport not found</h3>";
    exit;
}

$is_team_sport = ($sport['is_team_sport'] === 't');

echo "<h2>{$sport['sname']}</h2>";
echo "<p><b>Type:</b> " . ($is_team_sport ? "Team Sport" : "Individual Sport") . "</p>";
?>

<hr>

<?php if ($is_team_sport): ?>

    <!-- ========================= -->
    <!-- TEAM SPORT: SHOW TEAMS   -->
    <!-- ========================= -->

    <h3>Teams in this Sport</h3>

    <table>
    <tr>
        <th>ID</th>
        <th>Team Name</th>
        <th>City</th>
        <th>Country</th>
        <th>Actions</th>
    </tr>

    <?php
    $teams = pg_query($conn,
        "SELECT * FROM team WHERE sport_id = $sport_id ORDER BY tname"
    );

    if (pg_num_rows($teams) == 0) {
        echo "<tr><td colspan='5'>No teams found for this sport</td></tr>";
    } else {
        while ($t = pg_fetch_assoc($teams)) {
            echo "<tr>
                <td>{$t['team_id']}</td>
                <td>
                    <a href='../teams/details.php?id={$t['team_id']}' style='color:#fff; text-decoration:underline;'>
                        {$t['tname']}
                    </a>
                </td>
                <td>{$t['city']}</td>
                <td>{$t['country']}</td>
                <td>
                    <a class='btn' href='../teams/edit.php?id={$t['team_id']}'>Edit</a>
                    <a class='btn' href='../teams/delete.php?id={$t['team_id']}'>Delete</a>
                </td>
            </tr>";
        }
    }
    ?>
    </table>

    <br>
    <a href="../teams/add.php"><button>Add Team for this Sport</button></a>

<?php else: ?>

    <!-- =============================== -->
    <!-- INDIVIDUAL SPORT: SHOW PLAYERS  -->
    <!-- =============================== -->

    <h3>Players in this Sport</h3>

    <p style="color: #aaa; font-style: italic;">
        This is an individual sport. Players participate directly without teams.
    </p>

    <table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Country</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>

    <?php
    /* CREATE player_sport table if it doesn't exist yet */
    pg_query($conn, "
        CREATE TABLE IF NOT EXISTS player_sport (
            player_id INTEGER NOT NULL REFERENCES player(player_id) ON DELETE CASCADE,
            sport_id  INTEGER NOT NULL REFERENCES sport(sport_id)  ON DELETE CASCADE,
            PRIMARY KEY (player_id, sport_id)
        )
    ");

    /* HANDLE ADD PLAYER */
    if (isset($_POST['add_player'])) {
        $pid = intval($_POST['player_id']);
        pg_query($conn, "
            INSERT INTO player_sport (player_id, sport_id)
            VALUES ($pid, $sport_id)
            ON CONFLICT DO NOTHING
        ");
    }

    /* HANDLE REMOVE PLAYER */
    if (isset($_GET['remove_player'])) {
        $pid = intval($_GET['remove_player']);
        pg_query($conn, "
            DELETE FROM player_sport
            WHERE player_id = $pid AND sport_id = $sport_id
        ");
        echo "<script>location.href='details.php?id=$sport_id';</script>";
    }

    /* FETCH ALL PLAYERS FOR THIS SPORT (registered + from matches) */
    $players = pg_query($conn, "
        SELECT DISTINCT p.*
        FROM player p
        WHERE p.player_id IN (
            SELECT player_id FROM player_sport WHERE sport_id = $sport_id
        )
        OR p.player_id IN (
            SELECT mp.player_id
            FROM match_player mp
            JOIN match m ON mp.match_id = m.match_id
            WHERE m.sport_id = $sport_id
        )
        ORDER BY p.pname
    ");

    if (pg_num_rows($players) == 0) {
        echo "<tr><td colspan='5'>No players registered for this sport yet</td></tr>";
    } else {
        while ($p = pg_fetch_assoc($players)) {
            echo "<tr>
                <td>{$p['player_id']}</td>
                <td>{$p['pname']}</td>
                <td>{$p['country']}</td>
                <td>{$p['role']}</td>
                <td>
                    <a class='btn' href='../players/performance.php?player_id={$p['player_id']}'>Performance</a>
                    <a class='btn' href='../players/edit.php?id={$p['player_id']}'>Edit</a>
                    <a class='btn' href='details.php?id=$sport_id&remove_player={$p['player_id']}'>Remove</a>
                </td>
            </tr>";
        }
    }
    ?>
    </table>

    <hr>

    <h3>Add Existing Player to this Sport</h3>

    <form method="POST">
        <select name="player_id" required>
            <option value="">Select Player</option>
            <?php
            /* Players not already in this sport */
            $available = pg_query($conn, "
                SELECT * FROM player
                WHERE player_id NOT IN (
                    SELECT player_id FROM player_sport WHERE sport_id = $sport_id
                )
                AND player_id NOT IN (
                    SELECT mp.player_id
                    FROM match_player mp
                    JOIN match m ON mp.match_id = m.match_id
                    WHERE m.sport_id = $sport_id
                )
                ORDER BY pname
            ");

            while ($ap = pg_fetch_assoc($available)) {
                echo "<option value='{$ap['player_id']}'>{$ap['pname']} ({$ap['country']})</option>";
            }
            ?>
        </select>
        <br><br>
        <button type="submit" name="add_player">Add Player</button>
    </form>

    <br>
    <a href="../players/add.php"><button>+ Create New Player</button></a>

<?php endif; ?>

<br>
<a href="view.php"><button>Back to Sports</button></a>

</div>
</body>
</html>