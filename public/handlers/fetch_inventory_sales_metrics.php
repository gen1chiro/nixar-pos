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
        $all_sales_metric = $Inventory->salesReportMetrics();
        $inv_metric_best_item_category = $Inventory->inventoryMetricsBestSellingItem();
        $inv_metric_low_stock = $Inventory->inventoryMetricsStock();
        $inv_metric_most_sold = $Inventory->inventoryMetricsSold();
        $metrics = [
            "all_sales_metric" =>  $all_sales_metric,
            "inv_metric_best_item_category" => $inv_metric_best_item_category,
            "inv_metric_low_stock" => $inv_metric_low_stock,
            "inv_metric_most_sold" => $inv_metric_most_sold
        ];

        echo json_encode($metrics);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>