<?php
require_once 'db.php';
require_once 'functions.php';

session_start();

// Регистрация пользователя
function registerUser($username, $email, $password, $full_name, $phone) {
    global $db;
    
    // Проверяем, существует ли пользователь
    $username = $db->escape($username);
    $email = $db->escape($email);
    
    $check_query = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
    $result = $db->query($check_query);
    
    if ($result->num_rows > 0) {
        return ['success' => false, 'message' => 'Пользователь с таким именем или email уже существует'];
    }
    
    // Хешируем пароль
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Добавляем пользователя
    $insert_query = "INSERT INTO users (username, email, password, full_name, phone) 
                     VALUES ('$username', '$email', '$hashed_password', '$full_name', '$phone')";
    
    if ($db->query($insert_query)) {
        return ['success' => true, 'message' => 'Регистрация прошла успешно'];
    } else {
        return ['success' => false, 'message' => 'Ошибка при регистрации'];
    }
}

// Авторизация пользователя
function loginUser($username, $password) {
    global $db;
    
    $username = $db->escape($username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = $db->query($query);
    
    if ($result->num_rows === 0) {
        return ['success' => false, 'message' => 'Пользователь не найден'];
    }
    
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        
        return ['success' => true, 'message' => 'Авторизация прошла успешно'];
    } else {
        return ['success' => false, 'message' => 'Неверный пароль'];
    }
}

// Выход пользователя
function logoutUser() {
    session_unset();
    session_destroy();
}

// Проверка авторизации
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Получение данных текущего пользователя
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    global $db;
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = $db->query($query);
    
    return $result->fetch_assoc();
}
?>