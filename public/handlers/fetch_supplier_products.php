
<?php 
    /* Author: John Roland Octavio
    * fetch_supplier_products.php
    * 
    * This handler retrieves all products supplied by a specific supplier.
    * Expects a GET parameter 'supplier_id'.
    * Returns JSON with 'success' and 'supplier_products' on success,
    * or 'status' and 'message' on error.
    * Requires an active session.
    */
    header("Content-Type: application/json");

    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $SupplierId = $_GET['supplier_id'];
        $Supplier = new Supplier($Conn);

        $SupplierData = $Supplier->getSupplierProducts($SupplierId);
        if (!$SupplierData['success']) {
            throw new Exception($SupplierData['message']);
        }
        
        $Response = [
            'success' => true,
            'supplier_products' => $SupplierData['product'],
        ];

        echo json_encode($Response);
    } catch (Exception $E) {
        error_log("Error: " . $E->getMessage());
        error_log("Trace: " . $E->getTraceAsString());
        echo json_encode([
            'status' => 'error',
            'message' => $E->getMessage()
        ]);
    }
?>