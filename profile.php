<?php
require_once 'includes/auth.php';


// Проверка авторизации с перенаправлением
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Получаем ID пользователя из GET-параметра
$user_id = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

// Проверяем, что ID пользователя получен
if (!$user_id) {
    die('Ошибка: не удалось определить ID пользователя');
}

// Получаем данные пользователя из БД
$db=$db->getConnection();
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Проверяем, что пользователь найден
if (!$user) {
    die('Ошибка: пользователь не найден');
}



// Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Очистка и валидация данных
    $full_name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    try {
        // Базовое обновление
        $params = [htmlspecialchars($full_name), htmlspecialchars($phone)];
        $types = "ss";
        $query = "UPDATE users SET name = ?, phone = ?";
        
        // Добавляем пароль, если он указан
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $params[] = $hashed_password;
            $types .= "s";
            $query .= ", password = ?";
        }
        
        $query .= " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";
        
        $stmt = $db->prepare($query);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            $_SESSION['full_name'] = $full_name;
            $_SESSION['success_message'] = 'Профиль успешно обновлен';
            header("Location: profile.php?id=$user_id");
            exit;
        } else {
            throw new Exception('Ошибка при обновлении профиля: ' . $db->error);
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: profile.php?id=$user_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - <?php echo defined('SITE_NAME') ? SITE_NAME : 'PrimeHome'; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="section">
        <div class="container-profile">
            <div class="profile-container">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($user['name'] ?? 'Не указано'); 
                                $_SESSION['name'] = htmlspecialchars($user['name']);
                    ?></h3>
                    <p><?php echo htmlspecialchars($user['email'] ?? 'Не указан'); 
                                $_SESSION['email'] = htmlspecialchars($user['email']);
                    ?></p>
                    
                    <ul class="profile-menu">
                        <li class="active"><a href="profile.php?id=<?= $user_id ?>"><i class="fas fa-user"></i> Профиль</a></li>
                        <li><a href="favotires.php"><i class="fas fa-heart"></i> Избранное</a></li>
                        <li><a href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Выход</a></li>
                    </ul>
                </div>
                
                <div class="profile-content">
                    <h2>Мой профиль</h2>
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success"><?php 
                            echo htmlspecialchars($_SESSION['success_message']); 
                            unset($_SESSION['success_message']);
                        ?></div>
                    <?php elseif (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-error"><?php 
                            echo htmlspecialchars($_SESSION['error_message']); 
                            unset($_SESSION['error_message']);
                        ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="profile-form">
                        <div class="form-group">
                            <label for="name">ФИО</label>
                            <input type="text" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" 
                                   value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="tel" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Новый пароль (оставьте пустым, если не хотите менять)</label>
                            <input type="password" id="password" name="password">
                        </div>
                        
                        <button type="submit" class="btn-profile">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
    <script src="assets/js/whereuser.js"></script>
    <script>
        localStorage.setItem('location',window.location);
    </script>
</body>
</html>