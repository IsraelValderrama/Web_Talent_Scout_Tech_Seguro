<?php
require_once dirname(__FILE__) . '/private/conf.php';

// Requiere autenticación de usuarios
require_once dirname(__FILE__) . '/private/auth.php'; // Asegúrate de que auth.php redirija si no hay sesión válida

// Verificar la conexión a la base de datos
if (!isset($db) || !$db) {
    die("Error de conexión a la base de datos");
}

// Consulta para obtener los jugadores
$query = "SELECT playerid, name, team FROM players ORDER BY playerId DESC";

// Preparar y ejecutar la consulta
$stmt = $db->prepare($query);
if (!$stmt) {
    die("Error al preparar la consulta");
}

$result = $stmt->execute();

if (!$result) {
    die("Error al ejecutar la consulta");
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
    <header class="listado">
        <h1>Players list</h1>
    </header>
    <main class="listado">
        <section>
            <ul>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "
                        <li>
                            <div>
                                <span>Name: " . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "</span>
                                <span>Team: " . htmlspecialchars($row['team'], ENT_QUOTES, 'UTF-8') . "</span>
                            </div>
                            <div>
                                <a href=\"show_comments.php?id=" . htmlspecialchars($row['playerid'], ENT_QUOTES, 'UTF-8') . "\">(show/add comments)</a>
                                <a href=\"insert_player.php?id=" . htmlspecialchars($row['playerid'], ENT_QUOTES, 'UTF-8') . "\">(edit player)</a>
                            </div>
                        </li>\n";
                    }
                ?>
            </ul>
            <form action="#" method="post" class="menu-form">
                <a href="index.php">Back to home</a>
                <input type="submit" name="Logout" value="Logout" class="logout">
            </form>
        </section>
    </main>
    <footer class="listado">
        <img src="images/logo-iesra-cadiz-color-blanco.png">
        <h4>Puesta en producción segura</h4>
        <p>Please <a href="http://www.donate.co?amount=100&amp;destination=ACMEScouting/">donate</a></p>
    </footer>
</body>

</html>