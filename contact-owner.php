<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Неподдерживаемый метод']);
    exit;
}

$propertyId = $_POST['property_id'] ?? 0;
$propertyType = $_POST['property_type'] ?? '';
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$message = $_POST['message'] ?? '';

// Валидация данных
if (empty($propertyId) || empty($propertyType) || empty($name) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Заполните обязательные поля']);
    exit;
}

try {
    // Здесь должна быть логика сохранения запроса в базу данных
    // и/или отправки уведомления владельцу/агенту
    
    // Пример сохранения в базу:
    $query = "INSERT INTO contact_requests (property_id, property_type, name, phone, message) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param('issss', $propertyId, $propertyType, $name, $phone, $message);
    $result = $stmt->execute();
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Запрос успешно отправлен']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении запроса']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка сервера: ' . $e->getMessage()]);
}