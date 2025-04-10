<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Получаем вторичное жилье из базы данных
$query = "SELECT * FROM secondary_housing";
$result = $db->query($query);
$properties = [];

while ($row = $result->fetch_assoc()) {
    $properties[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вторичное жилье | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/secondary.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <!-- Header -->
    <?php include_once "includes/header.php"; ?>

    <!-- Hero Section -->
    <section class="hero secondary-hero">
        <div class="container">
            <h1>Вторичное жилье в Москве</h1>
            <p>Проверенные варианты квартир на вторичном рынке с полной юридической проверкой</p>
            
            <div class="search-box">
                <form method="GET" class="search-form">
                    <div class="form-group">
                        <label for="district">Район</label>
                        <select id="district" name="district" class="form-control">
                            <option value="">Любой район</option>
                            <option value="ЦАО">ЦАО</option>
                            <option value="САО">САО</option>
                            <option value="СВАО">СВАО</option>
                            <option value="ВАО">ВАО</option>
                            <option value="ЮВАО">ЮВАО</option>
                            <option value="ЮАО">ЮАО</option>
                            <option value="ЮЗАО">ЮЗАО</option>
                            <option value="ЗАО">ЗАО</option>
                            <option value="СЗАО">СЗАО</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rooms">Комнат</label>
                        <select id="rooms" name="rooms" class="form-control">
                            <option value="">Любое количество</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Цена до, ₽</label>
                        <input type="number" id="price" name="max_price" class="form-control" placeholder="10 000 000">
                    </div>
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn" style="width: 100%;">Найти</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Properties Section -->
    <section class="section" id="properties">
        <div class="container">
            <div class="section-title">
                <h2>Актуальные предложения</h2>
                <p>Проверенные варианты вторичного жилья</p>
            </div>
            
            <div class="properties">
                <?php foreach ($properties as $property): ?>
                <div class="property-card secondary-card">
                    <div class="property-badge">Вторичка</div>
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
                                <span><?php echo $property['rooms']; ?> <?php echo 'комнат(ы)'; ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-vector-square"></i>
                                <span><?php echo $property['area']; ?> м²</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-building"></i>
                                <span>Этаж: <?php echo $property['floor']; ?>/<?php echo $property['total_floors']; ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Год постройки: <?php echo $property['year_built']; ?></span>
                            </div>
                        </div>
                        
                        <div class="property-actions">
                            <a href="property-detail.php?type=secondary_housing&id=<?php echo $property['id']; ?>" class="btn btn-outline">Подробнее</a>
                            <?php if (isLoggedIn()): ?>
                                <button class="btn btn-favorite" data-property-id="<?php echo $property['id']; ?>" data-property-type="secondary">
                                    <i class="far fa-heart"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Advantages Section -->
    <section class="section advantages-section" style="background-color: #f0f4f8;">
        <div class="container">
            <div class="section-title">
                <h2>Почему стоит покупать вторичное жилье?</h2>
                <p>Преимущества вторичного рынка</p>
            </div>
            
            <div class="advantages-grid">
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                    <h3>Доступная цена</h3>
                    <p>Часто можно найти варианты дешевле, чем в новостройках</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Можно посмотреть</h3>
                    <p>Вы видите реальное состояние квартиры перед покупкой</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Удобные районы</h3>
                    <p>Больше вариантов в уже сложившихся районах с инфраструктурой</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Быстрый въезд</h3>
                    <p>Можно заселиться сразу после оформления сделки</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once "includes/footer.php"; ?>
   <script src="assets/js/loginform.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        
        localStorage.setItem('location',window.location);
    </script>

</body>
</html>