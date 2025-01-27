<?php
require_once dirname(__FILE__) . '/private/conf.php';

# Require logged users
require_once dirname(__FILE__) . '/private/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['body']) && !empty($_GET['id'])) {
    # Validate and sanitize input
    $playerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $userId = filter_input(INPUT_COOKIE, 'userId', FILTER_VALIDATE_INT);
    $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);

    if ($playerId && $userId && $body) {
        # Use prepared statement to prevent SQL injection
        $stmt = $db->prepare("INSERT INTO comments (playerId, userId, body) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $playerId, SQLITE3_INTEGER);
        $stmt->bindParam(2, $userId, SQLITE3_INTEGER);
        $stmt->bindParam(3, $body, SQLITE3_TEXT);

        if ($stmt->execute()) {
            header("Location: list_players.php");
            exit();
        } else {
            die("Error executing query");
        }
    } else {
        die("Invalid input data");
    }
}

# Show form
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Práctica RA3 - Comments creator</title>
</head>
<body>
<header>
    <h1>Comments creator</h1>
</header>
<main class="player">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . (isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '')); ?>" method="post">
        <h3>Write your comment</h3>
        <textarea name="body" required></textarea><br>
        <input type="submit" value="Send"><br>
    </form><br>
    <form action="logout.php" method="post" class="menu-form">
        <a href="list_players.php">Back to list</a>
        <input type="submit" name="Logout" value="Logout" class="logout">
    </form>
</main>
<footer class="listado">
    <img src="images/logo-iesra-cadiz-color-blanco.png" alt="Logo IES RA Cádiz">
    <h4>Puesta en producción segura</h4>
    Considere <a href="http://www.donate.co?amount=100&amp;destination=ACMEScouting/">donar</a>
</footer>
</body>
</html>
