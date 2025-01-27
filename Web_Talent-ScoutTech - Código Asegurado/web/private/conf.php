<?php

try {
    $db = new SQLite3(dirname(__FILE__) . "/database.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
} catch (Exception $e) {
    die("Unable to open database: " . $e->getMessage());
}

?>
