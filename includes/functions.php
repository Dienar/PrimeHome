<?php
require_once 'db.php';

// Получение всех квартир
function getAllProperties($filters = []) {
    global $db;
    
    $query = "SELECT * FROM properties WHERE 1=1";
    
    // Фильтр по району
    if (!empty($filters['district'])) {
        $district = $db->escape($filters['district']);
        $query .= " AND district = '$district'";
    }
    
    // Фильтр по количеству комнат
    if (!empty($filters['rooms'])) {
        $rooms = $db->escape($filters['rooms']);
        $query .= " AND rooms = $rooms";
    }
    
    // Фильтр по цене
    if (!empty($filters['max_price'])) {
        $max_price = $db->escape($filters['max_price']);
        $query .= " AND price <= $max_price";
    }
    
    $result = $db->query($query);
    $properties = [];
    
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
    
    return $properties;
}

// Получение одной квартиры
function getPropertyById($id) {
    global $db;
    $id = $db->escape($id);
    $query = "SELECT * FROM properties WHERE id = $id";
    $result = $db->query($query);
    
    return $result->fetch_assoc();
}

// Добавление в избранное
function addToFavorites($user_id, $property_id) {
    global $db;
    $user_id = $db->escape($user_id);
    $property_id = $db->escape($property_id);
    
    $query = "INSERT INTO favorites (user_id, property_id) VALUES ($user_id, $property_id)";
    return $db->query($query);
}

// Удаление из избранного
function removeFromFavorites($user_id, $property_id) {
    global $db;
    $user_id = $db->escape($user_id);
    $property_id = $db->escape($property_id);
    
    $query = "DELETE FROM favorites WHERE user_id = $user_id AND property_id = $property_id";
    return $db->query($query);
}

// Проверка, добавлено ли в избранное
function isFavorite($user_id, $property_id) {
    global $db;
    $user_id = $db->escape($user_id);
    $property_id = $db->escape($property_id);
    
    $query = "SELECT id FROM favorites WHERE user_id = $user_id AND property_id = $property_id";
    $result = $db->query($query);
    
    return $result->num_rows > 0;
}

// Получение избранных квартир пользователя
function getUserFavorites($user_id) {
    global $db;
    $user_id = $db->escape($user_id);
    
    $query = "SELECT p.* FROM properties p
              JOIN favorites f ON p.id = f.property_id
              WHERE f.user_id = $user_id";
    
    $result = $db->query($query);
    $favorites = [];
    
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
    
    return $favorites;
}
function getNoun($number, $one, $two, $five) {
    $number = abs($number) % 100;
    $n1 = $number % 10;
    if ($number > 10 && $number < 20) return $five;
    if ($n1 > 1 && $n1 < 5) return $two;
    if ($n1 == 1) return $one;
    return $five;
}
?>