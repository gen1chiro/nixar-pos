<?php 
    header("Content-Type: application/json");

    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    // Default to (2000-01-01 00:00:00) as start and the current date as end if no input is provided
    $Start = !empty($_GET['start']) ? InputValidator::sanitizeData($_GET['start']) : '2000-01-01 00:00:00';
    $End   = !empty($_GET['end'])   ? InputValidator::sanitizeData($_GET['end']) : date("Y-m-d") . " 23:59:59";

    // YYYY-MM-DD
    if ($End && strlen($End) === 10) {
        $End .= ' 23:59:59';
    }

    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $Transaction = new Transaction($Conn);
        $Inventory = new Inventory($Conn);

        // Fetch transactions within date
        $AllTransactions = $Transaction->fetchTransactionByRange($Start, $End);
        if (!$AllTransactions['success']) {
            error_log("Transaction Failed to fetch data: {$AllTransactions['message']}");
            $AllTransactions = ['success' => false, 'transactions' => []]; 
        }
        // Fetch Inventory record
        $ProductInventory = $Inventory->fetchInventory();
        if (!$ProductInventory['success']) {
            $ProductInventory = ['success' => false, 'data' => []];
        }
        // Fetch aggregated metrics
        $AllSales = $Inventory->salesReportMetrics();
        $BestItemCategory = $Inventory->inventoryMetricsBestSellingItem();
        $LowStock = $Inventory->inventoryMetricsStock();
        $MostSold = $Inventory->inventoryMetricsSold();
        $SalesTime = $Inventory->salesListMetricsByTime();
        $SalesCategory = $Inventory->salesListMetricsByCategory();
        $ListMostSold = $Inventory->inventoryListMetricsMostSold();
        $BestSelling = $Inventory->inventoryListMetricsBestSelling();
        $ListLowStock = $Inventory->inventoryListMetricsStock();

        echo json_encode([
            'success' => true,
            'result' => [
                'transactions' => $AllTransactions['transactions'],
                'inventory' => $ProductInventory['data'],
                'sales_metrics' => [
                    'all_sales' => $AllSales, 
                    'best_item_category' => $BestItemCategory, 
                    'low_stock' => $LowStock, 
                    'most_sold' => $MostSold
                ],
                'list_metrics' => [
                    'sales_time_of_day' => $SalesTime, 
                    'sales_category' => $SalesCategory, 
                    'most_sold' => $ListMostSold, 
                    'best_selling' => $BestSelling, 
                    'low_stock_count' => $ListLowStock
                ],
            ]
        ]);
    } catch (Exception $E) {
        error_log("Handle generate report error: " . $E->getMessage());
        echo json_encode([
            'success' => false,
            'message' => $E->getMessage()
        ]);
    }
?>