<div class="navbar">

    <div class="logo">🏟 SportsDB</div>

    <div class="nav-links" id="navLinks">
        <a href="/sports_project/index.php">Home</a>
        <a href="/sports_project/sports/view.php">Sports</a>
        <a href="/sports_project/players/view.php">Players</a>
        <a href="/sports_project/teams/view.php">Teams</a>
        <a href="/sports_project/tournaments/view.php">Tournaments</a>
        <a href="/sports_project/matches/view.php">Matches</a>
    </div>

    <div class="menu-toggle" onclick="toggleMenu()">☰</div>

</div>

<script>
function toggleMenu() {
    document.getElementById("navLinks").classList.toggle("active");
}
</script>