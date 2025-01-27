<?php
require_once dirname(__FILE__) . '/private/conf.php';

# Require logged users
require dirname(__FILE__) . '/private/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'], $_POST['team'])) {
        $name = trim($_POST['name']);
        $team = trim($_POST['team']);

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $query = "INSERT OR REPLACE INTO players (playerid, name, team) VALUES (:id, :name, :team)";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        } else {
            $query = "INSERT INTO players (name, team) VALUES (:name, :team)";
            $stmt = $db->prepare($query);
        }

        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':team', $team, SQLITE3_TEXT);

        if (!$stmt->execute()) {
            die("Error executing query.");
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT name, team FROM players WHERE playerid = :id";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($result) {
        $row = $result->fetchArray(SQLITE3_ASSOC);
        if ($row) {
            $name = htmlspecialchars($row['name']);
            $team = htmlspecialchars($row['team']);
        } else {
            die("Player not found.");
        }
    } else {
        die("Error executing query.");
    }
}
?>
<!doctype html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/style.css">
        <title>Práctica RA3 - Players list</title>
    </head>
    <body>
        <header>
            <h1>Player</h1>
        </header>
        <main class="player">
            <form action="#" method="post">
                <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
                <h3>Player name</h3>
                <textarea name="name"><?= isset($name) ? $name : '' ?></textarea><br>
                <h3>Team name</h3>
                <textarea name="team"><?= isset($team) ? $team : '' ?></textarea><br>
                <input type="submit" value="Send">
            </form>
            <form action="#" method="post" class="menu-form">
                <a href="index.php">Back to home</a>
                <a href="list_players.php">Back to list</a>
                <input type="submit" name="Logout" value="Logout" class="logout">
            </form>
        </main>
        <footer class="listado">
            <img src="images/logo-iesra-cadiz-color-blanco.png">
            <h4>Puesta en producción segura</h4>
            < Please <a href="http://www.donate.co?amount=100&amp;destination=ACMEScouting/"> donate</a> >
        </footer>
    </body>
</html>
