<link rel="stylesheet" href="../style.css">
<?php include '../navbar.php'; ?>

<div class="container">

<?php
include '../db.php';

$player_id = $_GET['player_id'] ?? 0;

if ($player_id == 0) {
    die("Invalid player ID");
}

/* FETCH PLAYER */
$player = pg_fetch_assoc(pg_query($conn,
    "SELECT * FROM player WHERE player_id=$player_id"
));

echo "<h2>Performance: {$player['pname']}</h2>";

/* ========================= */
/* MATCH-WISE PERFORMANCE */
/* ========================= */

$matches = pg_query($conn,
    "SELECT m.match_id, m.match_date, s.sname
     FROM player_stats ps
     JOIN match m ON ps.match_id = m.match_id
     JOIN sport s ON m.sport_id = s.sport_id
     WHERE ps.player_id = $player_id
     ORDER BY m.match_date DESC"
);

echo "<h3>Match-wise Performance</h3>";

while ($m = pg_fetch_assoc($matches)) {

    echo "<div style='
        background:rgba(0,0,0,0.6);
        padding:10px;
        margin-bottom:10px;
        border-radius:8px;
    '>";

    echo "<strong>Match ID: {$m['match_id']}</strong> | ";
    echo "{$m['sname']} | {$m['match_date']}<br>";

    /* FETCH STATS FOR THIS MATCH */
    $stats = pg_query($conn,
        "SELECT sd.stat_name, sd.stat_value
         FROM player_stats ps
         JOIN stat_detail sd ON ps.stat_id = sd.stat_id
         WHERE ps.player_id = $player_id
         AND ps.match_id = {$m['match_id']}"
    );

    while ($s = pg_fetch_assoc($stats)) {
        echo "<span style='margin-right:10px;'>
                {$s['stat_name']}: {$s['stat_value']}
              </span>";
    }

    echo "</div>";
}

/* ========================= */
/* OVERALL STATS */
/* ========================= */

echo "<h3>Overall Statistics</h3>";

$overall = pg_query($conn,
    "SELECT sd.stat_name, SUM(sd.stat_value) as total
     FROM player_stats ps
     JOIN stat_detail sd ON ps.stat_id = sd.stat_id
     WHERE ps.player_id = $player_id
     GROUP BY sd.stat_name"
);

echo "<div style='
    background:rgba(0,0,0,0.6);
    padding:15px;
    border-radius:8px;
'>";

while ($o = pg_fetch_assoc($overall)) {
    echo "<div style='margin-bottom:5px;'>
            {$o['stat_name']} : <strong>{$o['total']}</strong>
          </div>";
}

echo "</div>";
?>

</div>