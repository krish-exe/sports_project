<link rel="stylesheet" href="../style.css">

<?php
$conn = pg_connect("host=localhost dbname=sport_management_DB user=postgres password=asdfghjkl");

if ($conn) {
    
} else {
    echo "Connection failed!";
}
?>