

<header>
        <div class="container header-container">
            <div class="logo">
                <i class="fas fa-home"></i>
                <span>PrimeHome</span>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Квартиры</a></li>
                    <li><a href="#mortgage">Ипотека</a></li>
                    <li><a href="about.php">О компании</a></li>
                    <li><a href="#contact">Контакты</a></li>
                </ul>
            </nav>
            <?php 
            require_once 'init.php';
            if(empty($_SESSION['user_id'])){
                echo "<a href='#' class='btn' id='loginBtn'>Войти</a>";
            }else{
                $href = 'profile.php?id='.$_SESSION['user_id'];
                echo "<a href=$href class='btn' >Профиль</a>";
            }
           ?>
        </div>
    </header>