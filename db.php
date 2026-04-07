<link rel="stylesheet" href="../style.css">

<?php
$conn = pg_connect("host=localhost dbname=sport_management_DB user=postgres password=kjsce");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if ($conn) {
    
} else {
    echo "Connection failed!";
}
?>