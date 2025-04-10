<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrimeHome - Квартиры в Москве с ипотекой</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/studios.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <!-- Header -->
    <?php
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/auth.php';


$tables = [
    'new_buildings' => 'Новостройки',
    'premium_properties' => 'Премиум',
    'secondary_housing' => 'Вторичка', 
    'studios' => 'Студия'
];

$all_properties = [];
$errors = [];

foreach ($tables as $table => $type) {
    try {
        $sql = "SELECT id, title, price, image_path, address, district FROM $table";
        $result = $db->query($sql);
        
        if ($result === false) {
            $errors[$table] = "Ошибка запроса: " . $db->getConnection()->error;
            continue;
        }
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['property_type'] = $type;
                $row['table_name'] = $table;
                $all_properties[] = $row;
            }
        } else {
            $errors[$table] = "Таблица пуста";
        }
    } catch (Exception $e) {
        $errors[$table] = "Ошибка: " . $e->getMessage();
    }
}

// Вывод ошибок для отладки (можно убрать после диагностики)
if (!empty($errors)) {
    echo "<div style='background:#fdd; padding:10px; margin:20px;'>";
    echo "<h3>Ошибки при загрузке данных:</h3>";
    foreach ($errors as $table => $error) {
        echo "<p><strong>$table:</strong> $error</p>";
    }
    echo "</div>";
}
?>


    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Найдите свою идеальную квартиру в Москве</h1>
            <p>Более 10 000 вариантов жилья от проверенных застройщиков с возможностью ипотечного кредитования</p>
            <?php
            ?>
            <div class="search-box">
                <form class="search-form">
                    <div class="form-group">
                        <label for="district">Район</label>
                        <select id="district" class="form-control">
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
                        <select id="rooms" class="form-control">
                            <option value="">Любое количество</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4+">4+</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Цена до, ₽</label>
                        <input type="text" id="price" class="form-control" placeholder="10 000 000">
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
                <p>Все квартиры в Москве</p>
            </div>
            
            <div class="studios-grid">
                <?php foreach ($all_properties as $property): ?>
                <div class="studio-card">
                    <div class="studio-badge"><?php echo htmlspecialchars($property['property_type']); ?></div>
                    <div class="studio-img">
                        <img src="<?php echo $property['image_path'] ?: 'https://via.placeholder.com/600x400'; ?>" alt="<?php echo htmlspecialchars($property['title']); ?>">
                    </div>
                    <div class="studio-info">
                        <h3 class="studio-title"><?php echo htmlspecialchars($property['title']); ?></h3>
                        <div class="studio-price"><?php echo number_format($property['price'], 0, '', ' '); ?> ₽</div>
                        <?php if (!empty($property['address'])): ?>
                        <div class="studio-address"><?php echo htmlspecialchars($property['address']); ?>, <?php echo htmlspecialchars($property['district']); ?></div>
                        <?php endif; ?>
                        
                        <div class="studio-features">
                            
                        </div>
                        
                        <div class="studio-actions">
                            <a href="property-detail.php?type=<?php echo $property['table_name']; ?>&id=<?php echo $property['id']; ?>" class="btn btn-outline">Подробнее</a>
                            <?php if (isLoggedIn()): ?>
                                <button class="btn btn-favorite" data-property-id="<?php echo $property['id']; ?>" data-property-type="<?php echo $property['table_name']; ?>">
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
                <!-- Property 2 -->
               

    <!-- Mortgage Calculator Section -->
    <section class="section" id="mortgage" style="background-color: #f0f4f8;">
        <div class="container">
            <div class="section-title">
                <h2>Ипотечный калькулятор</h2>
                <p>Рассчитайте ежемесячный платеж и условия кредитования для выбранной квартиры</p>
            </div>
            
            <div class="mortgage-calculator">
                <div class="calculator-grid">
                    <div class="calculator-form">
                        <div class="range-slider">
                            <label for="propertyPrice">Стоимость квартиры (₽)</label>
                            <input type="range" id="propertyPrice" min="1000000" max="50000000" step="100000" value="10000000">
                            <div class="range-values">
                                <span>1 000 000 ₽</span>
                                <span id="priceValue">10 000 000 ₽</span>
                                <span>50 000 000 ₽</span>
                            </div>
                        </div>
                        
                        <div class="range-slider">
                            <label for="initialPayment">Первоначальный взнос (₽)</label>
                            <input type="range" id="initialPayment" min="0" max="50000000" step="100000" value="2000000">
                            <div class="range-values">
                                <span>0 ₽</span>
                                <span id="initialPaymentValue">2 000 000 ₽</span>
                                <span>50 000 000 ₽</span>
                            </div>
                        </div>
                        
                        <div class="range-slider">
                            <label for="loanTerm">Срок кредита (лет)</label>
                            <input type="range" id="loanTerm" min="1" max="30" step="1" value="15">
                            <div class="range-values">
                                <span>1 год</span>
                                <span id="loanTermValue">15 лет</span>
                                <span>30 лет</span>
                            </div>
                        </div>
                        
                        <div class="range-slider">
                            <label for="interestRate">Процентная ставка (%)</label>
                            <input type="range" id="interestRate" min="1" max="20" step="0.1" value="7.5">
                            <div class="range-values">
                                <span>1%</span>
                                <span id="interestRateValue">7.5%</span>
                                <span>20%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="calculator-result">
                        <div class="result-item">
                            <div class="result-label">Сумма кредита</div>
                            <div class="result-value" id="loanAmount">8 000 000 ₽</div>
                        </div>
                        <div class="result-item">
                            <div class="result-label">Ежемесячный платеж</div>
                            <div class="result-value" id="monthlyPayment">73 962 ₽</div>
                        </div>
                        <div class="result-item">
                            <div class="result-label">Переплата по кредиту</div>
                            <div class="result-value" id="totalInterest">5 313 160 ₽</div>
                        </div>
                        <div class="result-item">
                            <div class="result-label">Общая сумма выплат</div>
                            <div class="result-value" id="totalPayment">13 313 160 ₽</div>
                        </div>
                        
                        <button class="btn" style="margin-top: 20px; width: 100%;" id="applyMortgageBtn">Оформить заявку на ипотеку</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
<?php include_once "includes/footer.php"; ?>

    <!-- Login Modal -->
    <div class="modal" id="loginModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Вход в личный кабинет</h2>
            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">Войти</button>
            </form>
            <p style="text-align: center; margin-top: 20px;">Еще нет аккаунта? <a href="#" style="color: var(--primary);">Зарегистрироваться</a></p>
        </div>
    </div>

    <!-- Mortgage Application Modal -->
    <div class="modal" id="mortgageModal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Заявка на ипотеку</h2>
            <form id="mortgageForm">
                <div class="form-group">
                    <label for="mortgageName">ФИО</label>
                    <input type="text" id="mortgageName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="mortgagePhone">Телефон</label>
                    <input type="tel" id="mortgagePhone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="mortgageEmail">Email</label>
                    <input type="email" id="mortgageEmail" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="mortgageAmount">Сумма кредита</label>
                    <input type="text" id="mortgageAmount" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label for="mortgageTerm">Срок кредита</label>
                    <input type="text" id="mortgageTerm" class="form-control" readonly>
                </div>
                <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">Отправить заявку</button>
            </form>
        </div>
    </div>

<script src="assets/js/script.js" ></script>
<script>
        localStorage.setItem('location',window.location);
    </script>
</body>
</html>