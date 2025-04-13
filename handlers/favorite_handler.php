<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Требуется авторизация']));
}

$property_id = (int)$_POST['property_id'];
$property_type = $_POST['property_type'];

// Валидация
if ($property_id <= 0 || !in_array($property_type, ['new_buildings', 'secondary_housing','premium_properties', 'studios'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => $property_type]));
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Преобразование типа
    $type_map = [
        'new_buildings' => 'new_buildings',
        'secondary_housing' => 'secondary_housing',
        'premium_properties' => 'premium_properties',
        'studios' => 'studios'
    ];
    $property_type_short = $type_map[$property_type] ?? null;
    
    // Проверка существования объекта
    $stmt = $conn->prepare("SELECT id FROM {$property_type} WHERE id = ?");
    $stmt->bind_param("i", $property_id);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        throw new Exception('Объект не найден');
    }
    
    // Добавление в избранное
    $stmt = $conn->prepare("INSERT INTO favorites (user_id, property_id, property_type, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $_SESSION['user_id'], $property_id, $property_type_short);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Добавлено']);
    } else {
        throw new Exception($conn->error);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Уже добавлено']);
}