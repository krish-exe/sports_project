<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/sports_project/style.css">
</head>
<body>

<?php include '../navbar.php'; ?>

<div class="container">

<h2>Add Sport</h2>

<form action="insert.php" method="POST">

Sport Name:
<input type="text" name="sname" required minlength="2"><br>

Type:
<select name="is_team_sport" required>
    <option value="">Select</option>
    <option value="1">Team Sport</option>
    <option value="0">Individual Sport</option>
</select><br>

<button type="submit">Add Sport</button>

</form>

</div>
</body>
</html>