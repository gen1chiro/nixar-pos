<?php 
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    try {
        $Sanitized = InputValidator::sanitizeArray($_GET);
        $Category = $Sanitized['category'];
        $Inventory = new Inventory($Conn);

        $Sql = "SELECT np.*, 
                       ROUND(ps.base_price + (ps.base_price * (np.mark_up / 100)), 2) AS final_price,
                       pm.category
                FROM nixar_products np
                JOIN product_materials pm
                    ON np.product_material_id = pm.product_material_id
                JOIN inventory i 
                    ON np.nixar_product_sku = i.nixar_product_sku
                JOIN product_suppliers ps
                    ON np.product_supplier_id = ps.product_supplier_id
                WHERE np.is_deleted = 0";

        if ($Category !== 'all') {
            $Sql .= " AND pm.category = ?";
        }

        $Stmt = $Conn->prepare($Sql);
        if(!$Stmt) {
            throw new Exception("Failed to prepare query: " . $Conn->error);
        }

        if ($Category !== 'all') {
            $Stmt->bind_param("s", $Category);
        }
        $Stmt->execute();

        $Result = $Stmt->get_result();
        $Rows = $Result->fetch_all(MYSQLI_ASSOC);
        $Stmt->close();

        // Append base path for js to display
        foreach($Rows as &$Product) {
            $Product['product_img_url'] = $BASE_IMAGE_URL . $Product['product_img_url'];
        }
        
        echo json_encode([
            "status" => "success",
            "products" => $Rows
        ]);
    } catch (Exception $E) {
        error_log("Error: " . $E->getMessage());
        error_log("Trace: " . $E->getTraceAsString());
        echo json_encode([
            "status" => "error",
            "message" => $E->getMessage()
        ]);
    }
?>