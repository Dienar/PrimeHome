<?php
// Файл: mortgage_handler.php
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json');

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Метод не разрешен']));
}

$db = new Database();
$conn = $db->getConnection();

try {
    $name = $db->escape($_POST['name'] ?? '');
    $phone = $db->escape($_POST['phone'] ?? '');
    $email = $db->escape($_POST['email'] ?? '');
    $amount = $db->escape($_POST['amount'] ?? '');
    $term = $db->escape($_POST['term'] ?? '');
    
    // Валидация
    if (empty($name) || empty($phone) || empty($email) || empty($amount) || empty($term)) {
        throw new Exception('Все поля обязательны для заполнения');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Некорректный email');
    }
    
    // Сохраняем заявку
    $sql = "INSERT INTO mortgage_applications 
            (name, phone, email, amount, term, created_at, status) 
            VALUES ('$name', '$phone', '$email', '$amount', '$term', NOW(), 'new')";
    
    if ($db->query($sql)) {
        echo json_encode(['success' => true, 'message' => 'Заявка успешно отправлена']);
    } else {
        throw new Exception('Ошибка при отправке заявки: ' . $conn->error);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}