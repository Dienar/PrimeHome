<?php
// Инициализация сессии (если еще не запущена)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Подключение базы данных
require_once 'config.php';
require_once 'db.php';
$db = new Database();

?>