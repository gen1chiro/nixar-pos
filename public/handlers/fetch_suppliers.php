<?php 
    /* Author: John Roland Octavio
    *  Fetches paginated supplier data and returns it as a JSON response.
    */
    header("Content-Type: application/json");

    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $Supplier = new Supplier($Conn);

        $Limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $Page = isset($_GET['page']) ? (int)$_GET['page']: 1;
        $Offset = ($Page - 1) * $Limit;

        $SupplierData = $Supplier->fetchPaginated($Limit, $Offset);
        if (!$SupplierData['success']) {
            throw new Exception($SupplierData['message']);
        }

        $TotalSuppliers = count($SupplierData);
        $TotalPages = $TotalSuppliers > 0 ? ceil($TotalSuppliers / $Limit) : 1;
        
        $Response = [
            'success' => true,
            'suppliers' => $SupplierData['data'],
            'totalPages' => $TotalPages,
            'currentPage' => $Page
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