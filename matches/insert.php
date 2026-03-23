<?php
include '../db.php';

$t = $_POST['tournament_id'];
$s = $_POST['sport_id'];
$v_name = $_POST['v_name'];
$date = $_POST['match_date'];
$status = $_POST['status'];

/* HANDLE NULL TOURNAMENT */
$tournament = !empty($t) ? $t : NULL;

/* =========================
   HANDLE VENUE
========================= */

/* CHECK IF EXISTS */
$check = pg_query_params($conn,
    "SELECT venue_id FROM venue WHERE v_name = $1",
    array($v_name)
);

if (pg_num_rows($check) > 0) {
    $row = pg_fetch_assoc($check);
    $venue_id = $row['venue_id'];
} else {
    /* INSERT NEW VENUE */
    $insert = pg_query_params($conn,
        "INSERT INTO venue (v_name) VALUES ($1) RETURNING venue_id",
        array($v_name)
    );

    $row = pg_fetch_assoc($insert);
    $venue_id = $row['venue_id'];
}

/* =========================
   INSERT MATCH
========================= */

pg_query_params($conn,
    "INSERT INTO match (tournament_id, sport_id, venue_id, match_date, status)
     VALUES ($1, $2, $3, $4, $5)",
    array($tournament, $s, $venue_id, $date, $status)
);

header("Location: view.php");
?>