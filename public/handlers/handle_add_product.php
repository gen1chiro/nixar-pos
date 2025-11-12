<?php 
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $Sanitized = InputValidator::sanitizeArray($_POST);
        // Product Information
        $Image = $_FILES['product_image'] ?? null;
        $ProductName = $Sanitized['product_name'];
        $ProductSku = $Sanitized['product_sku'];
        $MaterialId = $Sanitized['product_material_id'];
        $StockCount = $Sanitized['stock_count'];
        $Markup = $Sanitized['mark_up'];
        $SupplierId = $Sanitized['product_supplier_id'];
        $BasePrice = $Sanitized['base_price'];
        $Threshold = $Sanitized['min_threshold'];
        // Car Compatibility
        $CarMake = $_POST['car_make'];
        $CarModel = $_POST['car_model'];
        $CarYear = $_POST['car_year'];
        $CarType = $_POST['car_type'];
        // Transform Car Details into an array
        $CompatibleCars = [];
        for($I = 0; $I < count($CarMake); $I++) {
            $CompatibleCars[] = [
                'make' => $CarMake[$I],
                'model' => $CarModel[$I],
                'year' => $CarYear[$I],
                'type' => $CarType[$I]
            ];
        }
        // Save image to `../assets/img/uploads/` and store base image url
        $UploadFileName = Image::uploadToDirectory($Image);

        $Conn->begin_transaction();
        try {
            $Product = new NixarProduct($Conn);
            $Supplier = new Supplier($Conn);
            $Model = new CarModel($Conn);
            $Inventory = new Inventory($Conn);

            // Insert supplier info
            $SupplierInfo = [
                'id' => $SupplierId,
                'sku' => $ProductSku,
                'base_price' => $BasePrice
            ];
            $SupplierInsertId = $Supplier->add($SupplierInfo);
            // Insert product information
            $ProductMeta = [
                'product_sku' => $ProductSku,
                'material_id' => $MaterialId,
                'supplier_id' => $SupplierInsertId,
                'product_name' => $ProductName,
                'image_url' => $UploadFileName,
                'mark_up' => $Markup
            ];
            $Result = $Product->create($ProductMeta);
            error_log("Product creation status: " . var_export($Result, true));
            if (!$Result['success']) {
                throw new Exception('Failed to add product.');
            }
            // Insert inventory
            $InventoryMeta = [
                'product_sku' => $ProductSku,
                'current_stock' => $StockCount,
                'min_threshold' => $Threshold
            ];
            $InventoryResult = $Inventory->create($InventoryMeta);
            error_log("Inventory creation status: " . var_export($InventoryStatus, true));
            if (!$InventoryResult['success']) {
                throw new Exception('Failed to add inventory product.');
            }
            // Insert car models
            $ProductCompatibleId = [];
            for ($I = 0; $I < count($CompatibleCars); $I++) {
                $CarModelEntry = $Model->add($CompatibleCars[$I]);
                $ProductCompatibleId[] = $CarModelEntry['car_model_id'];
            }
            // Insert Product Compatibility
            foreach ($ProductCompatibleId as $CompatibleId) {
                $Result = $Product->insertCompatible($ProductSku, $CompatibleId);
            }
            // commit if success, else rollback
            $Conn->commit();
            echo json_encode(['success' => true, 'message' => 'Product successfully added.']);
        } catch (Exception $E) {
            $Conn->rollback();
            error_log("Error: " . $E->getMessage());
            error_log("Trace: " . $E->getTraceAsString());
            echo json_encode(['success' => false, 'message' => $E->getMessage()]);
        }
    }
?>