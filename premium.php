<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Получаем элитную недвижимость с особенностями из базы данных
$query = "SELECT p.*, 
          GROUP_CONCAT(f.id) AS feature_ids,
          GROUP_CONCAT(f.name) AS feature_names,
          GROUP_CONCAT(f.icon) AS feature_icons
          FROM premium_properties p
          LEFT JOIN property_feature_mapping pfm ON p.id = pfm.property_id
          LEFT JOIN property_features f ON pfm.feature_id = f.id
          GROUP BY p.id
          ORDER BY p.price DESC";

$result = $db->query($query);
$properties = [];

while ($row = $result->fetch_assoc()) {
    // Формируем массив особенностей
    $features = [];
    if (!empty($row['feature_ids'])) {
        $ids = explode(',', $row['feature_ids']);
        $names = explode(',', $row['feature_names']);
        $icons = explode(',', $row['feature_icons']);
        
        for ($i = 0; $i < count($ids); $i++) {
            $features[] = [
                'id' => $ids[$i],
                'name' => $names[$i],
                'icon' => $icons[$i]
            ];
        }
    }
    
    $row['features'] = $features;
    $properties[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Элитная недвижимость | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/premium.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <!-- Header -->
    <?php include_once "includes/header.php"; ?>

    <!-- Hero Section -->
    <section class="section" id="premium-properties">
        <div class="container">
            <div class="section-title">
                <h2>Эксклюзивные объекты</h2>
                <p>Подборка самых престижных объектов недвижимости</p>
            </div>
            
            <div class="properties">
                <?php foreach ($properties as $property): ?>
                <div class="property-card premium-card">
                    <div class="property-badge">
                        <?php 
                        $types = [
                            'apartment' => 'Апартаменты',
                            'penthouse' => 'Пентхаус',
                            'townhouse' => 'Таунхаус',
                            'villa' => 'Особняк'
                        ];
                        echo $types[$property['property_type']];
                        ?>
                    </div>
                    <div class="property-img">
                        <img src="<?php echo $property['image_path'] ?: 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                    </div>
                    <div class="property-info">
                        <h3 class="property-title"><?php echo htmlspecialchars($property['title']); ?></h3>
                        <div class="property-price"><?php echo number_format($property['price'], 0, '', ' '); ?> ₽</div>
                        <div class="property-address"><?php echo htmlspecialchars($property['address']); ?>, <?php echo htmlspecialchars($property['district']); ?></div>
                        
                        <div class="property-features">
                            <div class="feature">
                                <i class="fas fa-bed"></i>
                                <span><?php echo $property['rooms']; ?> <?php echo 'комната(ы)'; ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-vector-square"></i>
                                <span><?php echo $property['area']; ?> м²</span>
                            </div>
                        </div>
                        
                        <div class="premium-features">
                        <h4>Особенности:</h4>
                        <div class="features-grid">
                            <?php foreach ($property['features'] as $feature): ?>
                            <div class="feature-item">
                                <i class="fas <?php echo htmlspecialchars($feature['icon']); ?>"></i>
                                <span><?php echo htmlspecialchars($feature['name']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                        
                        <div class="property-actions">
                            <a href="property-detail.php?type=premium_properties&id=<?php echo $property['id']; ?>" class="btn btn-outline">Подробнее</a>
                            <button class="btn btn-request" data-property-id="<?php echo $property['id']; ?>">Запрос</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Consultation Section -->
    <section class="section consultation-section" style="background-color: #f0f4f8;">
        <div class="container">
            <div class="consultation-grid">
                <div class="consultation-content">
                    <h2>Персональный подбор элитной недвижимости</h2>
                    <p>Наши эксперты подберут для вас объект, полностью соответствующий вашим требованиям и предпочтениям.</p>
                    <ul class="benefits-list">
                        <li><i class="fas fa-check"></i> Полная конфиденциальность</li>
                        <li><i class="fas fa-check"></i> Индивидуальный подход</li>
                        <li><i class="fas fa-check"></i> Юридическая проверка</li>
                        <li><i class="fas fa-check"></i> Переговоры о цене</li>
                    </ul>
                </div>
                
                <div class="consultation-form">
                    <form id="premiumConsultationForm">
                        <h3>Запрос на консультацию</h3>
                        <div class="form-group">
                            <input type="text" placeholder="Ваше имя" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" placeholder="Телефон" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Ваши пожелания" class="form-control" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn">Отправить запрос</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once "includes/footer.php"; ?>

    <!-- Request Modal -->
    <div class="modal" id="requestModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Запрос на объект</h2>
            <form id="propertyRequestForm">
                <input type="hidden" id="requestPropertyId">
                <div class="form-group">
                    <label for="requestName">Ваше имя</label>
                    <input type="text" id="requestName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="requestPhone">Телефон</label>
                    <input type="tel" id="requestPhone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="requestEmail">Email</label>
                    <input type="email" id="requestEmail" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="requestMessage">Ваш запрос</label>
                    <textarea id="requestMessage" class="form-control" rows="4"></textarea>
                </div>
                <button type="submit" class="btn">Отправить запрос</button>
            </form>
        </div>
    </div>
    <script src="assets/js/loginform.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/premium.js"></script>
    <script>
        localStorage.setItem('location',window.location);
    </script>

</body>
</html>