<?php 
    header("Content-Type: application/json");

    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $Inventory = new Inventory($Conn);

        $Limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $Page = isset($_GET['page']) ? (int)$_GET['page']: 1;
        $Offset = ($Page - 1) * $Limit;

        $InventoryData = $Inventory->fetchInventory($Limit, $Offset);
        $TotalProducts = $Inventory->getInventoryCount();

        $TotalPages = ceil($TotalProducts / $Limit);
        
        // Append base path for js to display
        foreach($InventoryData as &$Product) {
            $Product['product_img_url'] = $BASE_IMAGE_URL . $Product['product_img_url'];
        }

        $Response = [
            'inventory' => $InventoryData,
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