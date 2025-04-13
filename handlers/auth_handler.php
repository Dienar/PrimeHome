<?php
require_once '../includes/db.php';
session_start();

// Проверка метода запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Разрешены только POST-запросы');
}

// Подключение к БД
$db = new Database();
$conn = $db->getConnection();

// Определяем действие
$action = $_POST['action'] ?? '';
$redirect_url = '/auth.php'; // Страница с формой

try {
    switch ($action) {
        case 'login':
            try {
                // Обработка входа
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                if (empty($email)) throw new Exception('Укажите email');
                if (empty($password)) throw new Exception('Укажите пароль');

                // Сначала проверяем, является ли пользователь администратором
                $admin_stmt = $conn->prepare("SELECT id, admin_password FROM admin_form WHERE admin_email = ? AND role = 'admin'");
                $admin_stmt->bind_param("s", $email);
                $admin_stmt->execute();
                $admin_result = $admin_stmt->get_result();

                if ($admin_result->num_rows > 0) {
                    // Пользователь найден в таблице администраторов
                    $admin = $admin_result->fetch_assoc();
                    
                    if ($password != $admin['admin_password']) {
                        throw new Exception('Неверный пароль администратора');
                    }
                        // Успешная авторизация администратора
                        $_SESSION['admin'] = [
                            'id' => $admin['id'],
                            'role' => 'admin'
                        ];
                        $redirect_url = '../admin_panel/';
                        header("Location: $redirect_url");
                        exit;
                    
                }

                // Если не администратор, проверяем в таблице обычных пользователей
                $user_stmt = $conn->prepare("SELECT id, name, email, phone, password FROM users WHERE email = ? LIMIT 1");
                $user_stmt->bind_param("s", $email);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();

                if ($user_result->num_rows === 0) {
                    throw new Exception('Пользователь не найден');
                }

                $user = $user_result->fetch_assoc();

                // Проверка пароля
                if (!password_verify($password, $user['password'])) {
                    throw new Exception('Неверный пароль');
                }

                // Авторизация обычного пользователя
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone']
                ];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['auth_message'] = 'Вход выполнен успешно!';
                $redirect_url = '../profile.php?id='.$user['id'];

            } catch (Exception $e) {
                $_SESSION['auth_error'] = $e->getMessage();
                $redirect_url = '../'; // Возврат на страницу входа при ошибке
            }
            break;

        default:
            throw new Exception('Неизвестное действие');
    }
} catch (Exception $e) {
    $_SESSION['auth_error'] = $e->getMessage();
    $redirect_url = '../login.php';
}

// Перенаправление
header("Location: $redirect_url");
exit;
    