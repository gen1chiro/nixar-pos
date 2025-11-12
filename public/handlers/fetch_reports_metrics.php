<?php
    header("Content-Type: application/json");

    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();

    try {
    $Inventory = new Inventory($Conn);
    $Metrics = $Inventory->salesReportMetrics();
    echo json_encode($Metrics);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
?>