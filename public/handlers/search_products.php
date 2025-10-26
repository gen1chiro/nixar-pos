<?php 
    // Let the browser know that we are sending JSON as response
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  

    $Query = isset($_GET['q']) ? InputValidator::sanitizeData($_GET['q']) : '';
    if($Query === '') {
        echo json_encode([]);
        exit;
    }

    error_log("Query: $Query");
    try {
        $Limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $Page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $Offset = ($Page - 1) * $Limit;
    
        $Inventory = new Inventory($Conn);
        $Rows = $Inventory->searchInventoryByKeyword($Query, $Limit, $Offset);
        $Count = $Inventory->countBySearchKeyword($Query);
        $TotalPages = ceil($Count / $Limit);
    
        // Append base path for js to display
        foreach($Rows as &$Product) {
            $Product['product_img_url'] = $BASE_IMAGE_URL . $Product['product_img_url'];
        }

        $Response = [
            'inventory' => $Rows,
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