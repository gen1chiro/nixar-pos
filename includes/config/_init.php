<?php 
    // Import all config and utils
    require_once __DIR__ . '/DatabaseConnection.php';
    require_once __DIR__ . '/SessionManager.php';
    require_once __DIR__ . '/InputValidator.php';
    require_once __DIR__ . '/NixarProduct.php';
    require_once __DIR__ . '/Inventory.php';
    require_once __DIR__ . '/Supplier.php';
    require_once __DIR__ . '/CarModel.php';
    require_once __DIR__ . '/Transaction.php';
    require_once __DIR__ . '/Customer.php';

    $BASE_IMAGE_URL = 'http://localhost/nixar-pos/public/assets/img/uploads/';

    $CarTypes = [
      'Sedan', 'Hatchback', 'SUV', 'Pickup', 'Coupe',
      'Convertible', 'Van', 'Minivan', 'Wagon', 'Jeep', 
      'Truck', 'Electric Vehicle'
    ];
  
    $ProductCategories = [
        'Glass', 'Accessories', 'Tints', 'Mirrors'
    ];
?>