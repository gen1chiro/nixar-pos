<?php
    /** Author: John Roland Octavio
     * update_supplier_info.php
     * 
     * Receives a JSON payload via POST containing supplier information (supplier_id, supplier_name, contact_no),
     * sanitizes the input, updates the supplier record in the database, and returns a JSON response indicating
     * success or failure.
     */
    header('Content-Type: application/json');
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $Raw = file_get_contents('php://input');
        // Convert JSON to Associative Array and sanitize
        $Data = InputValidator::sanitizeArray(json_decode($Raw, true));

        $Supplier = new Supplier($Conn);
        $SupplierUpdateResult = $Supplier->updateSupplierInfo(['supplier_id' => $Data['supplier_id'], 'supplier_name' => $Data['supplier_name'], 'contact_no' => $Data['contact_no']]);

        echo json_encode($SupplierUpdateResult);
    } catch (Exception $E) {
        echo json_encode([
            'success' => false,
            'message' => $E->getMessage()
        ]);
    }
?>