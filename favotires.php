<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$favorites = getUserFavorites($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избранное - <?php echo SITE_NAME; ?></title>
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
                    <h3><?php echo htmlspecialchars($_SESSION['full_name'] ?: $_SESSION['username']); ?></h3>
                    <p><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                    
                    <ul class="profile-menu">
                        <li><a href="profile.php"><i class="fas fa-user"></i> Профиль</a></li>
                        <li class="active"><a href="favorites.php"><i class="fas fa-heart"></i> Избранное</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выход</a></li>
                    </ul>
                </div>
                
                <div class="profile-content">
                    <h2>Избранные квартиры</h2>
                    
                    <?php if (empty($favorites)): ?>
                        <div class="empty-favorites">
                            <i class="far fa-heart"></i>
                            <p>У вас пока нет избранных квартир</p>
                            <a href="index.php" class="btn">Найти квартиру</a>
                        </div>
                    <?php else: ?>
                        <div class="properties">
                            <?php foreach ($favorites as $property): ?>
                            <div class="property-card">
                                <div class="property-img">
                                    <img src="<?php echo $property['image_path'] ?: 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                                </div>
                                <div class="property-info">
                                    <div class="property-price"><?php echo number_format($property['price'], 0, '', ' '); ?> ₽</div>
                                    <div class="property-address"><?php echo htmlspecialchars($property['address']); ?></div>
                                    <div class="property-features">
                                        <div class="feature">
                                            <i class="fas fa-bed"></i>
                                            <span><?php echo $property['rooms']; ?> <?php echo getNoun($property['rooms'], 'комната', 'комнаты', 'комнат'); ?></span>
                                        </div>
                                        <div class="feature">
                                            <i class="fas fa-vector-square"></i>
                                            <span><?php echo $property['area']; ?> м²</span>
                                        </div>
                                    </div>
                                    <div class="property-actions">
                                        <a href="property-detail.php?id=<?php echo $property['id']; ?>" class="btn btn-outline">Подробнее</a>
                                        <button class="btn btn-favorite active" data-property-id="<?php echo $property['id']; ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
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
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>