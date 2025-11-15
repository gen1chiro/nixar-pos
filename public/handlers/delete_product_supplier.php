<?php 
    /** Author: John Roland Octavio
     * update_supplier_info.php
     * 
     * Handles the deletion of a product-supplier association record.
     * Expects a GET parameter `product_supplier_id`.
     * Validates the input, removes the record from the `product_suppliers` table via the Supplier class,
     * and returns a JSON response indicating success or failure.
     */
    header('Content-Type: application/json');
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $ProductSupplierId = $_GET['product_supplier_id'];
        if (!$ProductSupplierId) {
            echo json_encode(['success' => false, 'message' => 'No product supplier id provided.']);
        }
        $Supplier = new Supplier($Conn);
        $Result = $Supplier->removeProduct($ProductSupplierId);

        echo json_encode($Result);
    } catch (Exception $E) {
        echo json_encode([
            'success' => false,
            'message' => $E->getMessage()
        ]);
    }
?>