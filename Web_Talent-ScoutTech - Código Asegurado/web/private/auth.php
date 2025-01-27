<?php
session_start(); // Solo una vez, en la primera línea sin salida previa
require_once dirname(__FILE__) . '/conf.php';

$userId = FALSE;

// Check whether a pair of user and password are valid; returns true if valid.
function areUserAndPasswordValid($user, $password) {
    global $db, $userId;

    // Use a prepared statement to avoid SQL Injection
    $stmt = $db->prepare('SELECT userId, password FROM users WHERE username = :username');
    $stmt->bindValue(':username', $user, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray();
    if ($row && password_verify($password, $row['password'])) {
        $userId = $row['userId'];
        $_SESSION['userId'] = $userId; // Use session instead of cookie for sensitive data
        return TRUE;
    } else {
        return FALSE;
    }
}

// On login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    if (areUserAndPasswordValid($username, $password)) {
        $_SESSION['username'] = $username;
    }
}

// On logout
if (isset($_POST['Logout'])) {
    // Destroy session
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}

// Check user and password
if (isset($_SESSION['username'])) {
    if (areUserAndPasswordValid($_SESSION['username'], $_SESSION['password'])) {
        $login_ok = TRUE;
        $error = "";
    } else {
        $login_ok = FALSE;
        $error = "Invalid user or password.<br>";
    }
} else {
    $login_ok = FALSE;
    $error = "This page requires you to be logged in.<br>";
}

if ($login_ok == FALSE) {
?>
    <!doctype html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="css/style.css">
        <title>Práctica RA3 - Authentication page</title>
    </head>
    <body>
    <header class="auth">
        <h1>Authentication page</h1>
    </header>
    <section class="auth">
        <div class="message">
            <?= $error ?>
        </div>
        <section>
            <div>
                <h2>Login</h2>
                <form action="#" method="post">
                    <label>User</label>
                    <input type="text" name="username" required><br>
                    <label>Password</label>
                    <input type="password" name="password" required><br>
                    <input type="submit" value="Login">
                </form>
            </div>

            <div>
                <h2>Logout</h2>
                <form action="#" method="post">
                    <input type="submit" name="Logout" value="Logout">
            </div>
        </section>
    </section>
    <footer>
        <h4>Puesta en producción segura</h4>
        < Please <a href="http://www.donate.co?amount=100&amp;destination=ACMEScouting/"> donate</a> >
    </footer>
    <?php
    exit (0);
}
?>
