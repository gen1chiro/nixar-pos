<?php 
    // Import all config and utils
    require_once __DIR__ . '/DatabaseConnection.php';
    require_once __DIR__ . '/SessionManager.php';
    require_once __DIR__ . '/InputValidator.php';
    require_once __DIR__ . '/NixarProduct.php';
    require_once __DIR__ . '/Inventory.php';
    
    $BASE_IMAGE_URL = 'http://localhost/nixar-pos/public/assets/img/uploads/';
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
?>