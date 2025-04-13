<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Проверяем авторизацию пользователя
if (!isLoggedIn()) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

// Проверяем, что запрос отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['success' => false, 'message' => 'Недопустимый метод запроса']);
    exit;
}

// Получаем и проверяем данные
$property_id = isset($_POST['property_id']) ? (int)$_POST['property_id'] : null;
$property_type = isset($_POST['property_type']) ? trim($_POST['property_type']) : null;

if (!$property_id || !$property_type) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Неверные параметры запроса']);
    exit;
}

// Проверяем допустимые типы свойств
$allowed_types = ['new_buildings', 'secondary_housing', 'premium_properties', 'studios'];
if (!in_array($property_type, $allowed_types)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['success' => false, 'message' => 'Неверный тип свойства']);
    exit;
}

try {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    // Подготавливаем запрос на удаление
    $query = "DELETE FROM favorites WHERE user_id = ? AND property_id = ? AND property_type = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        throw new Exception("Ошибка подготовки запроса: " . $conn->error);
    }
    
    $user_id = $_SESSION['user_id'];
    $stmt->bind_param("iis", $user_id, $property_id, $property_type);
    
    if (!$stmt->execute()) {
        throw new Exception("Ошибка выполнения запроса: " . $stmt->error);
    }
    
    // Проверяем, была ли удалена хотя бы одна запись
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Удалено из избранного']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Объект не найден в избранном']);
    }
    
} catch (Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера: ' . $e->getMessage()]);
}