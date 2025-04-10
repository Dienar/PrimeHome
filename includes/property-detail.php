<?php
require_once 'db.php';

class DetailsBuilding {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Получает данные объекта недвижимости по ID и типу
     */
    public function getPropertyDetails($id, $type) {
        $id = $this->db->escape($id);
        
        switch($type) {
            case 'new_buildings':
                $table = 'new_buildings';
                $query = "SELECT *, 'new' AS property_type FROM $table WHERE id = $id";
                break;
            case 'secondary_housing':
                $table = 'secondary_housing';
                $query = "SELECT *, 'secondary' AS property_type FROM $table WHERE id = $id";
                break;
            case 'premium_properties':
                $table = 'premium_properties';
                $query = "SELECT p.*, 
                         GROUP_CONCAT(f.name) AS feature_names,
                         GROUP_CONCAT(f.icon) AS feature_icons,
                         'premium' AS property_type
                         FROM $table p
                         LEFT JOIN property_feature_mapping pfm ON p.id = pfm.property_id
                         LEFT JOIN property_features f ON pfm.feature_id = f.id
                         WHERE p.id = $id
                         GROUP BY p.id";
                break;
            case 'studios':
                $table = 'studios';
                $query = "SELECT *, 'studio' AS property_type FROM $table WHERE id = $id";
                break;
            default:
                return null;
        }
        
        $result = $this->db->query($query);
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        $property = $result->fetch_assoc();
        
        // Для премиум объектов преобразуем особенности в массив
        if ($type === 'premium') {
            $property['features'] = [];
            if (!empty($property['feature_names'])) {
                $names = explode(',', $property['feature_names']);
                $icons = explode(',', $property['feature_icons']);
                
                for ($i = 0; $i < count($names); $i++) {
                    $property['features'][] = [
                        'name' => $names[$i],
                        'icon' => $icons[$i]
                    ];
                }
            }
            unset($property['feature_names']);
            unset($property['feature_icons']);
        }
        
        return $property;
    }
    
    /**
     * Получает похожие объекты
     */
    public function getSimilarProperties($currentId, $type, $district, $rooms = null, $limit = 3) {
        $currentId = $this->db->escape($currentId);
        $district = $this->db->escape($district);
        $rooms = $rooms ? $this->db->escape($rooms) : null;
        $limit = (int)$limit;
        
        switch($type) {
            case 'new':
                $table = 'new_buildings';
                $roomsCondition = $rooms ? " AND rooms = $rooms" : "";
                break;
            case 'secondary':
                $table = 'secondary_housing';
                $roomsCondition = $rooms ? " AND rooms = $rooms" : "";
                break;
            case 'premium':
                $table = 'premium_properties';
                $roomsCondition = $rooms ? " AND rooms = $rooms" : "";
                break;
            case 'studio':
                $table = 'studios';
                $roomsCondition = ""; // У студий всегда 0 комнат
                break;
            default:
                return [];
        }
        
        $query = "SELECT * FROM $table 
                 WHERE id != $currentId AND district = '$district' $roomsCondition
                 ORDER BY RAND() LIMIT $limit";
        
        $result = $this->db->query($query);
        $properties = [];
        
        while ($row = $result->fetch_assoc()) {
            $row['property_type'] = $type;
            $properties[] = $row;
        }
        
        return $properties;
    }
}
?>