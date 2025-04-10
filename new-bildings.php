<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';

// Получаем новостройки из базы данных
$query = "SELECT * FROM new_buildings ORDER BY completion_date ASC";
$result = $db->query($query);
$new_buildings = [];

while ($row = $result->fetch_assoc()) {
    $new_buildings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новостройки Москвы | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/stylenb.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <!-- Header -->
    <?php include_once "includes/header.php"; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Новостройки Москвы</h1>
            <p>Современные жилые комплексы от ведущих застройщиков с гарантией качества</p>
            
            <div class="search-box">
                <form class="search-form" method="GET" action="new-buildings.php">
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

    <!-- New Buildings Section -->
    <section class="section" id="new-buildings">
        <div class="container">
            <div class="section-title">
                <h2>Современные новостройки</h2>
                <p>Лучшие предложения от проверенных застройщиков</p>
            </div>
            
            <div class="properties">
                <?php foreach ($new_buildings as $building): ?>
                <div class="property-card new-building-card">
                    <div class="property-badge">Новостройка</div>
                    <div class="property-img">
                        <img src="<?php echo $building['image_path'] ?: 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo htmlspecialchars($building['title']); ?>">
                    </div>
                    <div class="property-info">
                        <div class="property-developer">Застройщик: <?php echo htmlspecialchars($building['developer']); ?></div>
                        <h3 class="property-title"><?php echo htmlspecialchars($building['title']); ?></h3>
                        <div class="property-price">от <?php echo number_format($building['price'], 0, '', ' '); ?> ₽</div>
                        <div class="property-address"><?php echo htmlspecialchars($building['address']); ?>, <?php echo htmlspecialchars($building['district']); ?></div>
                        
                        <div class="property-features">
                            <div class="feature">
                                <i class="fas fa-home"></i>
                                <span>Сдача: <?php echo date('m.Y', strtotime($building['completion_date'])); ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-door-open"></i>
                                <span>Квартиры: <?php echo htmlspecialchars($building['rooms_available']); ?></span>
                            </div>
                        </div>
                        
                        <div class="property-description">
                            <?php echo htmlspecialchars(mb_substr($building['description'], 0, 100) . '...'); ?>
                        </div>
                        
                        <div class="property-actions">
                            <a href="property-detail.php?type=new_buildings&id=<?php echo $building['id']; ?>" class="btn btn-outline">Подробнее</a>
                            <?php if (isLoggedIn()): ?>
                                <button class="btn btn-favorite" data-property-id="<?php echo $building['id']; ?>" data-property-type="new">
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
                <h2>Почему стоит покупать в новостройке?</h2>
                <p>Преимущества нового жилья</p>
            </div>
            
            <div class="advantages-grid">
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Современные технологии</h3>
                    <p>Новые строительные материалы и технологии, соответствующие современным стандартам</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3>Рассрочка от застройщика</h3>
                    <p>Выгодные условия покупки с минимальным первоначальным взносом</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Гарантия качества</h3>
                    <p>Официальная гарантия от застройщика на все конструкции и инженерные системы</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-paint-roller"></i>
                    </div>
                    <h3>Чистовая отделка</h3>
                    <p>Возможность выбрать отделку "под ключ" или сделать ремонт по своему вкусу</p>
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