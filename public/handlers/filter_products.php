<?php 
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    $Filters = InputValidator::sanitizeArray($_GET);

    $Limit = isset($Filters['limit']) ? (int)$Filters['limit'] : 10;
    $Page = isset($Filters['page']) ? (int)$Filters['page'] : 1;
    $Offset = ($Page - 1) * $Limit; 

    $BaseSql = "SELECT * from product_inventory_view WHERE 1 = 1";
    $Params = [];
    $Types = '';

    if(!empty($Filters['material'])) {
        $BaseSql .= " AND material_name = ?";
        $Params[] = $Filters['material'];
        $Types .= 's';
    }

    if(!empty($Filters['model'])) {
        $BaseSql .= " AND car_make_model LIKE ?";
        $Params[] = "%{$Filters['model']}%";
        $Types .= 's';
    }

    if(!empty($Filters['type'])) {
        $BaseSql .= " AND type = ?";
        $Params[] = $Filters['type'];
        $Types .= 's';
    }

    if(!empty($Filters['stock'])) {
        if($Filters['stock'] === 1) {
            $BaseSql .= " AND current_stock > 0";
        } else if($Filters['stock'] === 0) {
            $BaseSql .= " AND current_stock = 0";
        }
    };

    if(!empty($Filters['max_range'])) {
        $BaseSql .= " AND final_price <= ?";
        $Params[] = (float)$Filters['max_range'];
        $Types .= 'd';
    }

    $BaseSql .= " ORDER BY current_stock DESC LIMIT ? OFFSET ?";
    $Params[] = $Limit;
    $Params[] = $Offset;
    $Types .= 'ii';
    try {
        $Stmt = $Conn->prepare($BaseSql);
        if(!$Stmt) {
            throw new Exception("Failed to execure query" . $this->Conn->error);
        }
        $Stmt->bind_param($Types, ...$Params);
        $Stmt->execute();

        $Result = $Stmt->get_result();
        $Rows = $Result->fetch_all(MYSQLI_ASSOC);
        $Stmt->close();


        echo json_encode([
            'sql' => $BaseSql,
            'params' => $Params,
            'types' => $Types,
            'inventory' => $Rows
        ]);
    } catch (Exception $E) {
        error_log("Error: " . $E->getMessage());
        error_log("Trace: " . $E->getTraceAsString());
        echo json_encode([
            'status' => 'error',
            'message' => $E->getMessage()
        ]);
    }
?>