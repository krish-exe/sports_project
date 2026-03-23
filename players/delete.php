
<?php
include '../db.php';

$id = $_GET['id'];

echo "<script>
if(confirm('Are you sure you want to delete?')) {
    window.location='delete.php?confirm=1&id=$id';
}
</script>";

if (isset($_GET['confirm'])) {
    pg_query($conn, "DELETE FROM player WHERE player_id = $id");
    echo "Deleted successfully<br>";
    echo "<a href='view.php'>Back</a>";
}
?>