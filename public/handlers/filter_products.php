<?php 
    /* Author: John Roland Octavio
    * Fetches inventory items with optional filters (material, model, type, stock, price range),
    * applies pagination, and returns results as JSON including image URLs.
    */
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    $Filters = InputValidator::sanitizeArray($_GET);

    $Limit = isset($Filters['limit']) ? (int)$Filters['limit'] : 10;
    $Page = isset($Filters['page']) ? (int)$Filters['page'] : 1;
    $Offset = ($Page - 1) * $Limit; 

    $WhereSql = "WHERE 1 = 1";
    $Params = [];
    $Types = '';

    if(!empty($Filters['material'])) {
        $WhereSql .= " AND material_name = ?";
        $Params[] = $Filters['material'];
        $Types .= 's';
    }

    if(!empty($Filters['model'])) {
        $WhereSql .= " AND compatible_cars LIKE ?";
        $Params[] = "%{$Filters['model']}%";
        $Types .= 's';
    }

    if(!empty($Filters['type'])) {
        $WhereSql .= " AND compatible_cars LIKE ?";
        $Params[] = "%{$Filters['type']}%";
        $Types .= 's';
    }

    if(!empty($Filters['stock'])) {
        $Stock = $Filters['stock'];
        if($Stock === 'inStock') {
            $WhereSql .= " AND current_stock > 0";
        } else if ($Stock === 'notInStock') {
            $WhereSql .= " AND current_stock = 0";
        }
    };

    if(isset($Filters['max_range']) && $Filters['max_range'] > 0) {
        $WhereSql .= " AND final_price <= ?";
        $Params[] = (float)$Filters['max_range'];
        $Types .= 'd';
    }

    $BaseSql = "SELECT * from product_inventory_view {$WhereSql} ORDER BY current_stock DESC LIMIT ? OFFSET ?";
    $Params[] = $Limit;
    $Params[] = $Offset;
    $Types .= 'ii';

    try {
        $Stmt = $Conn->prepare($BaseSql);
        if(!$Stmt) {
            throw new Exception("Failed to execute FETCH query" . $Conn->error);
        }
        $Stmt->bind_param($Types, ...$Params);
        $Stmt->execute();

        $Result = $Stmt->get_result();
        $Rows = $Result->fetch_all(MYSQLI_ASSOC);
        $Stmt->close();

        foreach($Rows as &$Row) {
            $Row['product_img_url'] = $BASE_IMAGE_URL . $Row['product_img_url'];
        }

        $CountSql = "SELECT COUNT(*) AS total FROM product_inventory_view {$WhereSql}";
        $CountStmt = $Conn->prepare($CountSql);
        if(!$CountStmt) {
            throw new Exception("Failed to execute COUNT query" . $Conn->error);
        }

        $TrimmedTypes = rtrim($Types, 'ii');
        $TrimmedParams = array_slice($Params, 0, -2);
        if (!empty($TrimmedTypes)) {
            $CountStmt->bind_param($TrimmedTypes, ...$TrimmedParams);
        }
        $CountStmt->execute();
        $TotalRows = $CountStmt->get_result()->fetch_assoc()['total'];
        $CountStmt->close();

        $TotalPages = max(1, ceil($TotalRows / $Limit));

        echo json_encode([
            'success' => true,
            'sql' => $BaseSql,
            'count_sql' => $CountSql,
            'totalRows'   => $TotalRows,
            'totalPages'  => $TotalPages,
            'currentPage' => $Page,
            'inventory' => $Rows
        ]);
    } catch (Exception $E) {
        error_log("Error: " . $E->getMessage());
        error_log("Trace: " . $E->getTraceAsString());
        echo json_encode([
            'success' => false,
            'message' => $E->getMessage()
        ]);
    }
?>