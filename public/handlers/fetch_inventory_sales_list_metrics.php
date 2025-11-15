<?php
    /* 
        Author: Ignatius Warren Benjamin D. Javelona
    */
    header("Content-Type: application/json");

    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $Inventory = new Inventory($Conn);
        $sales_limetric_time = $Inventory->salesListMetricsByTime();
        $sales_limetric_category = $Inventory->salesListMetricsByCategory();
        $inv_limetric_most_sold = $Inventory->inventoryListMetricsMostSold();
        $inv_limetric_best_selling = $Inventory->inventoryListMetricsBestSelling();
        $inv_limetric_low_stock = $Inventory->inventoryListMetricsStock();
        $metrics = [
            "sales_limetric_time" =>  $sales_limetric_time,
            "sales_limetric_category" => $sales_limetric_category,
            "inv_limetric_most_sold" => $inv_limetric_most_sold,
            "inv_limetric_best_selling" => $inv_limetric_best_selling,
            "inv_limetric_low_stock" => $inv_limetric_low_stock
        ];

        echo json_encode($metrics);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>