<?php
// Iniciar sesión de forma segura
session_start();

// Incluir el archivo de configuración
require_once dirname(__FILE__) . '/private/conf.php';

// Requerir autenticación de usuarios
require_once dirname(__FILE__) . '/private/auth.php';

// Función para escapar salida HTML
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener y validar el ID del jugador
$playerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($playerId === false || $playerId === null) {
    die("ID de jugador inválido");
}

// Preparar la consulta usando declaraciones preparadas
$query = "SELECT C.commentId, U.username, C.body 
          FROM comments C 
          JOIN users U ON U.userId = C.userId 
          WHERE C.playerId = ? 
          ORDER BY C.commentId DESC";

$stmt = $db->prepare($query);
$stmt->bind_param("i", $playerId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Error en la consulta: " . $db->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Práctica RA3 - Comments editor</title>
</head>
<body>
    <header>
        <h1>Comments editor</h1>
    </header>
    <main class="player">
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<div>
                    <h4>" . h($row['username']) . "</h4> 
                    <p>commented: " . h($row['body']) . "</p>
                  </div>";
        }
        ?>

        <div>
            <a href="list_players.php">Back to list</a>
            <a class="black" href="add_comment.php?id=<?php echo h($playerId); ?>">Add comment</a>
        </div>
    </main>
    <footer class="listado">
        <img src="images/logo-iesra-cadiz-color-blanco.png" alt="Logo IES RA Cádiz">
        <h4>Puesta en producción segura</h4>
        &lt; Please <a href="https://www.example.com/donate">donate</a> &gt;
    </footer>
</body>
</html>
