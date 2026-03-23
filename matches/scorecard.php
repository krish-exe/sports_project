<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>

<div class="container">

<?php
include '../db.php';

$match_id = $_GET['match_id'] ?? 0;

if ($match_id == 0) {
    die("Invalid match ID");
}

/* FETCH MATCH */
$match = pg_fetch_assoc(pg_query($conn,
    "SELECT * FROM match WHERE match_id=$match_id"
));

/* AUTO-FILL match_player */
pg_query($conn,
    "INSERT INTO match_player (match_id, player_id, team_id)
     SELECT mt.match_id, pt.player_id, mt.team_id
     FROM match_team mt
     JOIN player_team pt ON mt.team_id = pt.team_id
     WHERE mt.match_id = $match_id
     ON CONFLICT (match_id, player_id) DO NOTHING"
);

/* FETCH PLAYERS GROUPED BY TEAM */
$players = pg_query($conn,
    "SELECT t.team_id, t.tname, p.player_id, p.pname
     FROM match_team mt
     JOIN team t ON mt.team_id = t.team_id
     JOIN player_team pt ON t.team_id = pt.team_id
     JOIN player p ON pt.player_id = p.player_id
     WHERE mt.match_id = $match_id
     ORDER BY t.team_id, p.pname"
);

/* HANDLE SAVE */
if (isset($_POST['save'])) {

    $player_id = $_POST['player_id'];
    $stat_name = $_POST['stat_name'];
    $stat_value = $_POST['stat_value'];

    $check = pg_query($conn,
        "SELECT stat_id FROM player_stats 
         WHERE match_id=$match_id AND player_id=$player_id"
    );

    if (pg_num_rows($check) > 0) {
        $row = pg_fetch_assoc($check);
        $stat_id = $row['stat_id'];
    } else {
        $res = pg_query($conn,
            "INSERT INTO player_stats (match_id, player_id)
             VALUES ($match_id, $player_id)
             RETURNING stat_id"
        );
        $stat_id = pg_fetch_result($res, 0, 0);
    }

    $check2 = pg_query($conn,
        "SELECT * FROM stat_detail 
         WHERE stat_id=$stat_id AND stat_name='$stat_name'"
    );

    if (pg_num_rows($check2) > 0) {
        pg_query($conn,
            "UPDATE stat_detail 
             SET stat_value=$stat_value
             WHERE stat_id=$stat_id AND stat_name='$stat_name'"
        );
    } else {
        pg_query($conn,
            "INSERT INTO stat_detail (stat_id, stat_name, stat_value)
             VALUES ($stat_id, '$stat_name', $stat_value)"
        );
    }
}

/* FETCH STATS */
$all_stats = pg_query($conn,
    "SELECT ps.player_id, sd.stat_name, sd.stat_value
     FROM player_stats ps
     LEFT JOIN stat_detail sd ON ps.stat_id = sd.stat_id
     WHERE ps.match_id = $match_id"
);

$stats_arr = [];
while ($row = pg_fetch_assoc($all_stats)) {
    $stats_arr[$row['player_id']][] = $row;
}
?>

<h2>Scorecard - Match ID: <?=$match_id?></h2>

<?php
$current_team = null;

while ($p = pg_fetch_assoc($players)) {

    /* TEAM TITLE */
    if ($current_team != $p['team_id']) {
        $current_team = $p['team_id'];

        echo "<h3 style='border-bottom:2px solid #cc7722; padding-bottom:5px;'>
                Team: {$p['tname']}
              </h3>";
    }
?>

<!-- PLAYER ROW -->
<div style="
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:rgba(0,0,0,0.6);
    padding:10px;
    margin-bottom:8px;
    border-radius:8px;
">

    <!-- PLAYER NAME -->
    <div style="width:20%; font-weight:bold;">
        <?=$p['pname']?>
    </div>

    <!-- STATS -->
    <div style="width:40%;">
        <?php
        if (isset($stats_arr[$p['player_id']])) {
            foreach ($stats_arr[$p['player_id']] as $s) {
                if ($s['stat_name']) {
                    echo "<span style='margin-right:10px;'>
                            {$s['stat_name']}: {$s['stat_value']}
                          </span>";
                }
            }
        } else {
            echo "<span style='opacity:0.7;'>No stats</span>";
        }
        ?>
    </div>

    <!-- INPUT -->
    <div style="width:40%;">
        <form method="POST" style="display:flex; gap:6px;">
            <input type="hidden" name="player_id" value="<?=$p['player_id']?>">

            <input type="text" name="stat_name" placeholder="stat"
                   style="flex:1; padding:5px; font-size:12px;">

            <input type="number" name="stat_value" placeholder="value"
                   style="width:70px; padding:5px; font-size:12px;">

            <button type="submit" name="save"
                    style="padding:5px 10px;">Save</button>
        </form>
    </div>

</div>

<?php } ?>

</div>