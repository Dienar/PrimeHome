<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Получаем студии из базы данных
$query = "SELECT * FROM studios ORDER BY price ASC";
$result = $db->query($query);
$studios = [];

while ($row = $result->fetch_assoc()) {
    $studios[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Студии | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/studios.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <!-- Header -->
    <?php include_once "includes/header.php"; ?>

    <!-- Hero Section -->
    <section class="hero studios-hero">
        <div class="container">
            <h1>Студии в Москве</h1>
            <p>Компактные и удобные решения для комфортной жизни</p>
            
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

    <!-- Studios Section -->
    <section class="section" id="studios">
        <div class="container">
            <div class="section-title">
                <h2>Актуальные предложения</h2>
                <p>Лучшие студии в Москве</p>
            </div>
            
            <div class="studios-grid">
                <?php foreach ($studios as $studio): ?>
                <div class="studio-card">
                    <div class="studio-badge">Студия</div>
                    <div class="studio-img">
                        <img src="<?php echo $studio['image_path'] ?: 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo htmlspecialchars($studio['title']); ?>">
                    </div>
                    <div class="studio-info">
                        <h3 class="studio-title"><?php echo htmlspecialchars($studio['title']); ?></h3>
                        <div class="studio-price"><?php echo number_format($studio['price'], 0, '', ' '); ?> ₽</div>
                        <div class="studio-address"><?php echo htmlspecialchars($studio['address']); ?>, <?php echo htmlspecialchars($studio['district']); ?></div>
                        
                        <div class="studio-features">
                            <div class="feature">
                                <i class="fas fa-vector-square"></i>
                                <span><?php echo $studio['area']; ?> м²</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-building"></i>
                                <span>Этаж: <?php echo $studio['floor']; ?>/<?php echo $studio['total_floors']; ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-couch"></i>
                                <span><?php echo $studio['furniture'] ? 'С мебелью' : 'Без мебели'; ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-utensils"></i>
                                <span><?php echo $studio['kitchenette'] ? 'Кухонный уголок' : 'Отдельная кухня'; ?></span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-bath"></i>
                                <span><?php echo $studio['bathroom_type'] === 'combined' ? 'Совмещенный санузел' : 'Раздельный санузел'; ?></span>
                            </div>
                            <?php if ($studio['year_built']): ?>
                            <div class="feature">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Год постройки: <?php echo $studio['year_built']; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="studio-actions">
                            <a href="property-detail.php?type=studios&id=<?php echo $studio['id']; ?>" class="btn btn-outline">Подробнее</a>
                            <?php if (isLoggedIn()): ?>
                                <form class="favorite-form" method="post" action="handlers/favorite_handler.php">
                                <button class="btn btn-favorite" data-property-id="<?php echo $studio['id']; ?>" data-property-type="studios">
                                    <i class="far fa-heart"></i>
                                </button>
                                </form>
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
                <h2>Почему стоит выбрать студию?</h2>
                <p>Преимущества компактного жилья</p>
            </div>
            
            <div class="advantages-grid">
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                    <h3>Доступная цена</h3>
                    <p>Стоимость студии значительно ниже, чем у многокомнатных квартир</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-broom"></i>
                    </div>
                    <h3>Легкость уборки</h3>
                    <p>Компактное пространство требует меньше времени на уборку</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Современные планировки</h3>
                    <p>Практичные решения для комфортного проживания</p>
                </div>
                
                <div class="advantage-card">
                    <div class="advantage-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3>Центральные районы</h3>
                    <p>Большинство студий расположены в центре города</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once "includes/footer.php"; ?>
<script src="assets/js/loginform.js"></script>
    <script src="assets/js/script.js"></script>
    <script src="assets/js/whereuser.js"></script>
    <script>
    
        localStorage.setItem('location',window.location);
    </script>

</body>
</html>