<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>О компании | <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/about.css">
    <?php require_once "includes/favicon.php" ?>
</head>
<body>
    <!-- Header -->
    <?php include_once "includes/header.php"; ?>

    <!-- Hero Section -->
    <section class="hero about-hero">
        <div class="container">
            <h1>О компании</h1>
            <p><?php echo SITE_NAME; ?> - надежный партнер в мире недвижимости с 2010 года</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2>Наша история</h2>
                    <p>Основанная в 2010 году, компания <?php echo SITE_NAME; ?> быстро завоевала доверие клиентов благодаря профессиональному подходу и индивидуальному вниманию к каждому клиенту.</p>
                    
                    <div class="about-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="feature-text">
                                <h3>10 000+</h3>
                                <p>Успешных сделок с недвижимостью</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-text">
                                <h3>50+</h3>
                                <p>Профессиональных агентов</p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="feature-text">
                                <h3>100%</h3>
                                <p>Покрытие Москвы и области</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="about-image">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Наш офис">
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="section values-section" style="background-color: #f0f4f8;">
        <div class="container">
            <div class="section-title">
                <h2>Наши ценности</h2>
                <p>Принципы, которые лежат в основе нашей работы</p>
            </div>
            
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Честность</h3>
                    <p>Мы говорим правду о состоянии объектов и условиях сделки, даже если это может уменьшить нашу прибыль.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Профессионализм</h3>
                    <p>Наши специалисты проходят регулярное обучение и знают все тонкости рынка недвижимости.</p>
                </div>
                
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Забота о клиенте</h3>
                    <p>Мы подбираем варианты, которые действительно подходят вам по всем параметрам.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="section team-section">
        <div class="container">
            <div class="section-title">
                <h2>Наша команда</h2>
                <p>Профессионалы с большим опытом работы</p>
            </div>
            
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/gendir.jpg" alt="Анна Смирнова">
                    </div>
                    <h3>Анна Смирнова</h3>
                    <p class="position">Генеральный директор</p>
                    <p class="experience">Опыт: 15 лет</p>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/sellmanager.jpg" alt="Дмитрий Иванов">
                    </div>
                    <h3>Дмитрий Иванов</h3>
                    <p class="position">Руководитель отдела продаж</p>
                    <p class="experience">Опыт: 12 лет</p>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/realtor.jpg" alt="Елена Петрова">
                    </div>
                    <h3>Елена Петрова</h3>
                    <p class="position">Старший риелтор</p>
                    <p class="experience">Опыт: 10 лет</p>
                </div>
                
                <div class="team-member">
                    <div class="member-photo">
                        <img src="img/ipoteka.jpg" alt="Алексей Волков">
                    </div>
                    <h3>Алексей Волков</h3>
                    <p class="position">Эксперт по ипотеке</p>
                    <p class="experience">Опыт: 8 лет</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacts Section -->
    <section class="section contacts-section" style="background-color: var(--primary); color: white;">
        <div class="container">
            <div class="contacts-grid">
                <div class="contacts-info">
                    <h2>Свяжитесь с нами</h2>
                    <p>Мы готовы ответить на все ваши вопросы и помочь с выбором недвижимости</p>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Москва, ул. Тверская, 15, офис 305</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+7 (495) 123-45-67</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@primehome.ru</span>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <span>Пн-Пт: 9:00 - 20:00, Сб-Вс: 10:00 - 18:00</span>
                    </div>
                </div>
                
                <div class="contacts-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <input type="text" placeholder="Ваше имя" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <input type="tel" placeholder="Телефон" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <textarea placeholder="Ваш вопрос" class="form-control" rows="4"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-white">Отправить</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once "includes/footer.php"; ?>

    <script src="assets/js/script.js"></script>
    <script src="assets/js/loginform.js"></script>
</body>
</html>