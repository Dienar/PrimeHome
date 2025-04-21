<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

// Получаем избранное для текущего пользователя
$favorites = getUserFavorites($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избранное - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
    <!-- ... остальная часть head ... -->
<body>
    <?php require_once 'includes/header.php'; ?>
    
    <section class="section">
        <div class="container">
            <div class="profile-container">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($_SESSION['name']) ?></h3>
                    <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    
                    <ul class="profile-menu">
                        <li><a href="profile.php?id=<?php echo $_SESSION['user_id']?>"><i class="fas fa-user"></i> Профиль</a></li>
                        <li class="active"><a href="favorites.php"><i class="fas fa-heart"></i> Избранное</a></li>
                        <li><a href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Выход</a></li>
                    </ul>
                </div>
                
                <div class="profile-content">
                    <h2>Избранные квартиры</h2>
                    
                    <?php if (empty($favorites)): ?>
                        <div class="empty-favorites">
                            <i class="far fa-heart"></i>
                            <p>У вас пока нет избранных квартир</p>
                            <a href="index.php" class="btn-profile">Найти квартиру</a>
                        </div>
                    <?php else: ?>
                        <div class="properties">
                            <?php foreach ($favorites as $property): ?>
                            <div class="property-card">
                                <div class="property-img">
                                    <img src="<?php echo htmlspecialchars($property['image_path'] ?: 'https://via.placeholder.com/600x400'); ?>" 
                                         alt="<?php echo htmlspecialchars($property['title']); ?>">
                                </div>
                                
                                <div class="property-info">
                                    <h4><?php echo htmlspecialchars($property['title']); ?></h4>
                                    <div class="property-price"><?php echo number_format($property['price'], 0, '', ' '); ?> ₽</div>
                                    <div class="property-address"><?php echo htmlspecialchars($property['address']); ?></div>
                                    <div class="property-features">
                                        <div class="feature">
                                            <i class="fas fa-vector-square"></i>
                                            <span><?php echo $property['area']; ?> м²</span>
                                        </div>
                                    </div>
                                    <div class="property-actions">
                                        <a href="property-detail.php?id=<?php echo $property['property_id']; ?>&type=<?php echo $property['property_type']; ?>" 
                                           class="btn btn-outline">
                                            Подробнее
                                        </a>
                                        <form method="post" action="handlers/remove_favorite.php" style="display: inline;">
                                            <input type="hidden" name="property_id" value="<?php echo $property['property_id']; ?>">
                                            <input type="hidden" name="property_type" value="<?php echo $property['property_type']; ?>">
                                            <button type="submit" class="btn btn-favorite active">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php require_once 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
    <script src="assets/js/whereuser.js"></script>
    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
    // Обработка всех форм удаления из избранного 
    localStorage.setItem('location',window.location);
    document.querySelectorAll('form[action*="remove_favorite.php"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Находим родительскую карточку и удаляем ее
                    const card = form.closest('.property-card');
                    if (card) {
                        card.style.opacity = '0';
                        setTimeout(() => card.remove(), 300);
                    }
                    
                    // Можно добавить уведомление
                    alert(data.message);
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при удалении');
            });
        });
    });
});
    </script>
</body>
</html>