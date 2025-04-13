<?php
require_once 'db.php';

// Получение всех квартир
function getAllProperties($filters = []) {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $query = "SELECT * FROM properties WHERE 1=1";
    
    // Фильтр по району
    if (!empty($filters['district'])) {
        $district = $conn->real_escape_string($filters['district']);
        $query .= " AND district = '$district'";
    }
    
    // Фильтр по количеству комнат
    if (!empty($filters['rooms'])) {
        $rooms = $conn->real_escape_string($filters['rooms']);
        $query .= " AND rooms = $rooms";
    }
    
    // Фильтр по цене
    if (!empty($filters['max_price'])) {
        $max_price = $conn->real_escape_string($filters['max_price']);
        $query .= " AND price <= $max_price";
    }
    
    $result = $conn->query($query);
    $properties = [];
    
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
    
    return $properties;
}

// Получение одной квартиры
function getPropertyById($id) {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $id = $conn->real_escape_string($id);
    $query = "SELECT * FROM properties WHERE id = $id";
    $result = $conn->query($query);
    
    return $result->fetch_assoc();
}

// Добавление в избранное
function getUserFavorites($userId) {
    $db = Database::getInstance();
    $conn = $db->getConnection();
    
    $query = "SELECT 
                f.id as favorite_id, 
                f.property_id, 
                f.property_type,
                CASE 
                    WHEN f.property_type = 'new_buildings' THEN n.title
                    WHEN f.property_type = 'secondary_housing' THEN s.title
                    WHEN f.property_type = 'premium_properties' THEN p.title
                    WHEN f.property_type = 'studios' THEN st.title
                END as title,
                CASE 
                    WHEN f.property_type = 'new_buildings' THEN n.price
                    WHEN f.property_type = 'secondary_housing' THEN s.price
                    WHEN f.property_type = 'premium_properties' THEN p.price
                    WHEN f.property_type = 'studios' THEN st.price
                END as price,
                CASE 
                    WHEN f.property_type = 'new_buildings' THEN n.image_path
                    WHEN f.property_type = 'secondary_housing' THEN s.image_path
                    WHEN f.property_type = 'premium_properties' THEN p.image_path
                    WHEN f.property_type = 'studios' THEN st.image_path
                END as image_path,
                CASE 
                    WHEN f.property_type = 'new_buildings' THEN n.address
                    WHEN f.property_type = 'secondary_housing' THEN s.address
                    WHEN f.property_type = 'premium_properties' THEN p.address
                    WHEN f.property_type = 'studios' THEN st.address
                END as address,
                CASE 
                    WHEN f.property_type = 'new_buildings' THEN n.rooms_available
                    WHEN f.property_type = 'secondary_housing' THEN s.rooms
                    WHEN f.property_type = 'premium_properties' THEN p.rooms
                    WHEN f.property_type = 'studios' THEN NULL
                END as rooms,
                CASE 
                    WHEN f.property_type = 'new_buildings' THEN n.area
                    WHEN f.property_type = 'secondary_housing' THEN s.area
                    WHEN f.property_type = 'premium_properties' THEN p.area
                    WHEN f.property_type = 'studios' THEN st.area
                END as area
              FROM favorites f
              LEFT JOIN new_buildings n ON f.property_id = n.id AND f.property_type = 'new_buildings'
              LEFT JOIN secondary_housing s ON f.property_id = s.id AND f.property_type = 'secondary_housing'
              LEFT JOIN premium_properties p ON f.property_id = p.id AND f.property_type = 'premium_properties'
              LEFT JOIN studios st ON f.property_id = st.id AND f.property_type = 'studios'
              WHERE f.user_id = ?";
    
    $stmt = $conn->prepare($query);
    
    if ($stmt === false) {
        error_log("Failed to prepare query: " . $conn->error);
        return [];
    }
    
    if (!$stmt->bind_param("i", $userId)) {
        error_log("Failed to bind parameters: " . $stmt->error);
        return [];
    }
    
    if (!$stmt->execute()) {
        error_log("Failed to execute query: " . $stmt->error);
        return [];
    }
    
    $result = $stmt->get_result();
    $favorites = [];
    
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
    
    return $favorites;
}
// Удаление из избранного
// (You should add this function if needed)

function getNoun($number, $one, $two, $five) {
    $number = abs($number) % 100;
    $n1 = $number % 10;
    if ($number > 10 && $number < 20) return $five;
    if ($n1 > 1 && $n1 < 5) return $two;
    if ($n1 == 1) return $one;
    return $five;
}