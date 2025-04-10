<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'includes/property-detail.php';

// Проверяем параметры
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';

if (!$id || !in_array($type, ['new_buildings', 'secondary_housing', 'premium_properties', 'studios'])) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

$details = new DetailsBuilding($db);
$property = $details->getPropertyDetails($id, $type);

if (!$property) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

// Получаем похожие объекты
$similarProperties = $details->getSimilarProperties(
    $id, 
    $type, 
    $property['district'],
    isset($property['rooms']) ? $property['rooms'] : null
);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['title']); ?> | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/property-detail.css">
</head>
<body>
    <!-- Header -->
    <?php include_once "includes/header.php"; ?>

    <!-- Property Detail Section -->
    <section class="section property-detail-section">
        <div class="container">
            <div class="property-detail-header">
                <button class="back-button" id="btnback">< назад</button>
                <h1><?php echo htmlspecialchars($property['title']); ?></h1>
                <div class="property-meta">
                    <span class="property-address">
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo htmlspecialchars($property['address']); ?>, <?php echo htmlspecialchars($property['district']); ?>
                    </span>
                    <span class="property-price"><?php echo number_format($property['price'], 0, '', ' '); ?> ₽</span>
                </div>
            </div>
            
            <div class="property-detail-grid">
                <div class="property-gallery">
                    <div class="main-image">
                        <img src="<?php echo $property['image_path'] ?: 'https://via.placeholder.com/800x600'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                    </div>
                    <!-- Здесь можно добавить миниатюры для галереи -->
                </div>
                
                <div class="property-info">
                    <div class="property-features">
                        <h3>Характеристики</h3>
                        <ul>
                            <?php if (isset($property['rooms'])): ?>
                            <li>
                                <span class="feature-label">Количество комнат:</span>
                                <span class="feature-value"><?php echo $property['rooms']; ?></span>
                            </li>
                            <?php endif; ?>
                            
                            <li>
                                <span class="feature-label">Площадь:</span>
                                <span class="feature-value"><?php echo $property['area']; ?> м²</span>
                            </li>
                            
                            <?php if (isset($property['floor']) && isset($property['total_floors'])): ?>
                            <li>
                                <span class="feature-label">Этаж:</span>
                                <span class="feature-value"><?php echo $property['floor']; ?> из <?php echo $property['total_floors']; ?></span>
                            </li>
                            <?php endif; ?>
                            
                            <?php if (isset($property['year_built'])): ?>
                            <li>
                                <span class="feature-label">Год постройки:</span>
                                <span class="feature-value"><?php echo $property['year_built']; ?></span>
                            </li>
                            <?php endif; ?>
                            
                            <?php if ($type === 'new' && isset($property['completion_date'])): ?>
                            <li>
                                <span class="feature-label">Срок сдачи:</span>
                                <span class="feature-value"><?php echo date('m.Y', strtotime($property['completion_date'])); ?></span>
                            </li>
                            <?php endif; ?>
                            
                            <?php if ($type === 'premium' && isset($property['property_type'])): ?>
                            <li>
                                <span class="feature-label">Тип объекта:</span>
                                <span class="feature-value">
                                    <?php 
                                    $types = [
                                        'apartment' => 'Апартаменты',
                                        'penthouse' => 'Пентхаус',
                                        'townhouse' => 'Таунхаус',
                                        'villa' => 'Особняк'
                                    ];
                                    echo $types[$property['property_type']] ?? $property['property_type'];
                                    ?>
                                </span>
                            </li>
                            <?php endif; ?>
                            
                            <?php if ($type === 'studio'): ?>
                            <li>
                                <span class="feature-label">Мебель:</span>
                                <span class="feature-value"><?php echo $property['furniture'] ? 'Да' : 'Нет'; ?></span>
                            </li>
                            <li>
                                <span class="feature-label">Кухня:</span>
                                <span class="feature-value"><?php echo $property['kitchenette'] ? 'Кухонный уголок' : 'Отдельная кухня'; ?></span>
                            </li>
                            <li>
                                <span class="feature-label">Санузел:</span>
                                <span class="feature-value"><?php echo $property['bathroom_type'] === 'combined' ? 'Совмещенный' : 'Раздельный'; ?></span>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <?php if ($type === 'premium' && !empty($property['features'])): ?>
                    <div class="property-special-features">
                        <h3>Особенности</h3>
                        <div class="features-grid">
                            <?php foreach ($property['features'] as $feature): ?>
                            <div class="feature-item">
                                <i class="fas <?php echo htmlspecialchars($feature['icon']); ?>"></i>
                                <span><?php echo htmlspecialchars($feature['name']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="property-description">
                        <h3>Описание</h3>
                        <p><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
                    </div>
                    
                    <div class="property-actions">
                        <?php if (isLoggedIn()): ?>
                            <button class="btn btn-favorite" data-property-id="<?php echo $property['id']; ?>" data-property-type="<?php echo $type; ?>">
                                <i class="far fa-heart"></i> В избранное
                            </button>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-outline">
                                <i class="fas fa-heart"></i> Войдите, чтобы добавить в избранное
                            </a>
                        <?php endif; ?>
                        
                        <button class="btn" id="contactOwnerBtn">
                            <i class="fas fa-phone"></i> Связаться с <?php echo $type === 'new' ? 'застройщиком' : 'владельцем'; ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($similarProperties)): ?>
            <div class="similar-properties">
                <h2>Похожие предложения</h2>
                <div class="properties-grid">
                    <?php foreach ($similarProperties as $similar): ?>
                    <div class="property-card">
                        <div class="property-img">
                            <img src="<?php echo $similar['image_path'] ?: 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo htmlspecialchars($similar['title']); ?>">
                        </div>
                        <div class="property-info">
                            <h3><?php echo htmlspecialchars($similar['title']); ?></h3>
                            <div class="property-price"><?php echo number_format($similar['price'], 0, '', ' '); ?> ₽</div>
                            <div class="property-address"><?php echo htmlspecialchars($similar['address']); ?></div>
                            <a href="property-detail.php?type=<?php echo $similar['property_type']; ?>&id=<?php echo $similar['id']; ?>" class="btn btn-outline">Подробнее</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Modal -->
    <div class="modal" id="contactModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Связаться с <?php echo $type === 'new' ? 'застройщиком' : 'владельцем'; ?></h2>
            <form id="contactForm">
                <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                <input type="hidden" name="property_type" value="<?php echo $type; ?>">
                
                <div class="form-group">
                    <label for="contactName">Ваше имя</label>
                    <input type="text" id="contactName" name="name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="contactPhone">Телефон</label>
                    <input type="tel" id="contactPhone" name="phone" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="contactMessage">Сообщение</label>
                    <textarea id="contactMessage" name="message" class="form-control" rows="4">Меня интересует <?php echo htmlspecialchars($property['title']); ?></textarea>
                </div>
                
                <button type="submit" class="btn">Отправить запрос</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once "includes/footer.php"; ?>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/property-detail.js"></script>
</body>
</html>