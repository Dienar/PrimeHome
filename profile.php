<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$favorites = getUserFavorites($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Обновление профиля
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    
    global $db;
    $user_id = $_SESSION['user_id'];
    
    $update_query = "UPDATE users SET full_name = '$full_name', phone = '$phone'";
    
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $update_query .= ", password = '$hashed_password'";
    }
    
    $update_query .= " WHERE id = $user_id";
    
    $result = $db->query($update_query);
    
    if ($result) {
        $_SESSION['full_name'] = $full_name;
        $success_message = 'Профиль успешно обновлен';
    } else {
        $error_message = 'Ошибка при обновлении профиля';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <section class="section">
        <div class="container">
            <div class="profile-container">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></h3>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    
                    <ul class="profile-menu">
                        <li class="active"><a href="profile.php"><i class="fas fa-user"></i> Профиль</a></li>
                        <li><a href="favorites.php"><i class="fas fa-heart"></i> Избранное</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выход</a></li>
                    </ul>
                </div>
                
                <div class="profile-content">
                    <h2>Мой профиль</h2>
                    
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-error"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="profile-form">
                        <div class="form-group">
                            <label for="username">Имя пользователя</label>
                            <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                        </div>
                        
                        <div class="form-group">
                            <label for="full_name">ФИО</label>
                            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Новый пароль (оставьте пустым, если не хотите менять)</label>
                            <input type="password" id="password" name="password">
                        </div>
                        
                        <button type="submit" class="btn">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>