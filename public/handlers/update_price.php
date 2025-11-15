<?php 
    /* Author: John Roland Octavio
    * Updates the base price of a product for a given supplier.
    * Expects POST parameters: `base_price` and `product_supplier_id`.
    */

    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    $Conn = DatabaseConnection::getInstance()->getConnection();
    $BasePrice = isset($_POST['base_price']) ? floatval($_POST['base_price']) : null;
    $ProductSupplierId = isset($_POST['product_supplier_id']) ? intval($_POST['product_supplier_id']) : null;
    
    if ($BasePrice === null || $ProductSupplierId === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing or invalid parameters.'
        ]);
        exit;
    }
    
    try {
        $Supplier = new Supplier($Conn);
        
        $Result = $Supplier->updateBasePrice([
            'base_price' => $BasePrice,
            'product_supplier_id' => $ProductSupplierId
        ]);

        echo json_encode($Result);
    } catch (Exception $E) {
        echo json_encode([
            'success' => false,
            'message' => $E->getMessage()
        ]);
    }
?>