<?php 
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  

    try {
        $Sanitized = InputValidator::sanitizeArray($_GET);
        $Category = $Sanitized['category'];
        $Inventory = new Inventory($Conn);

        $Sql = "SELECT * FROM product_inventory_view
                WHERE category = ?";

        $Stmt = $Conn->prepare($Sql);
        if(!$Stmt) {
            throw new Exception("Failed to prepare query: " . $this->Conn->error);
        }
        $Stmt->bind_param("s", $Category);
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
        echo json_encode([
            "status" => "error",
            "message" => $E->getMessage()
        ]);
    }
?>