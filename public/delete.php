<?php
require_once __DIR__ . '/../src/bootstrap.php';
use CT275\Project\Product;
$product = new Product($PDO);
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['id'])
        && ($product->find($_POST['id'])) !== null
    ) 
        
            {
                $imagePath = $product->image;
                if ($product->delete()) {
                    if (!empty($imagePath) && file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
}
redirect('/Admin.php');