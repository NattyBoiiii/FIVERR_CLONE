<?php
require_once 'classloader.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);
    
    if ($category_id > 0) {
        $subcategories = $categoriesObj->getSubcategoriesByCategory($category_id);
        
        if ($subcategories) {
            echo json_encode([
                'success' => true,
                'subcategories' => $subcategories
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No subcategories found'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid category ID'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>
