<?php
session_start();
require_once "../includes/db.php";

// Проверка авторизации администратора
if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_login.php");
    exit;
}

$db = $db->getConnection();

// Обработка добавления квартиры
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_apartment'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $address = $_POST['address'];
    
    $stmt = $db->prepare("INSERT INTO apartments (title, description, price, address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $description, $price, $address);
    $stmt->execute();
    
    $_SESSION['message'] = "Квартира успешно добавлена!";
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Получение списка пользователей
$users = [];
$stmt = $db->prepare("SELECT id, name, email, phone, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            min-height: 100vh;
            background: #343a40;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.5);
        }
        .sidebar .nav-link:hover {
            color: rgba(255,255,255,.75);
        }
        .sidebar .nav-link.active {
            color: #fff;
        }
        .main-content {
            padding: 20px;
        }
        .table-container {
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .action-btns .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Сайдбар -->
        <?php require_once "includes/sidebar.php" ?>

        <!-- Основной контент -->
        <div class="main-content flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Управление пользователями</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApartmentModal">
                    <i class="fas fa-plus me-2"></i>Добавить квартиру
                </button>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="table-container">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Дата регистрации</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= htmlspecialchars($user['phone']) ?></td>
                            <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                            <td class="action-btns">
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Модальное окно добавления квартиры -->
    <div class="modal fade" id="addApartmentModal" tabindex="-1" aria-labelledby="addApartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addApartmentModalLabel">Добавить квартиру</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Название</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Ссылка на картинку</label>
                            <input type="text" class="form-control" id="img" name="img" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Цена</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Адрес</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" name="add_apartment" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="admin.js"> </script>
</body>
</html>