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
        $CarType = $Sanitized['car_type'];
        $StockCount = $Sanitized['stock_count'];
        $Markup = $Sanitized['mark_up'];
        $SupplierId = $Sanitized['product_supplier_id'];
        $BasePrice = $Sanitized['base_price'];
        $Threshold = $Sanitized['min_threshold'];
        // Car Compatibility
        $CarMake = $_POST['car_make'];
        $CarModel = $_POST['car_model'];
        $CarYear = $_POST['car_year'];
        // Transform Car Details into an array
        $CompatibleCars = [];
        for($I = 0; $I < count($CarMake); $I++) {
            $CompatibleCars[] = [
                'make' => $CarMake[$I],
                'model' => $CarModel[$I],
                'year' => $CarYear[$I],
                'type' => $CarType
            ];
        }
        // Save image to `../assets/img/uploads/` and store base image url
        $UploadFileName = null;
        if ($Image && $Image['error'] === 0) {
            $Dir = __DIR__ . '/../assets/img/uploads/';
            $ImgPath = basename($Image['name']);
            $FileName = time(). '_' . uniqid() . '_' . $ImgPath;
            $SavePath = "{$Dir}{$FileName}";
            // Save image to `public/img/uploads`
            if(move_uploaded_file($Image['tmp_name'], $SavePath)) {
                $UploadFileName = $FileName;
            }
        }

        // begin transaction
        $Conn->autocommit(false);
        try {
            $Product = new NixarProduct($Conn);
            $Supplier = new Supplier($Conn);
            $Model = new CarModel($Conn);
            $Inventory = new Inventory($Conn);

            $Conn->begin_transaction();
            // Insert supplier info
            $SupplierInfo = [
                'id' => $SupplierId,
                'sku' => $ProductSku,
                'base_price' => $BasePrice
            ];
            $SupplierInsertId = $Supplier->add($SupplierInfo, true);
            // Insert product information
            $ProductMeta = [
                'product_sku' => $ProductSku,
                'material_id' => $MaterialId,
                'supplier_id' => $SupplierInsertId,
                'product_name' => $ProductName,
                'image_url' => $UploadFileName,
                'mark_up' => $Markup
            ];
            $Status = $Product->create($ProductMeta);
            error_log("Product creation status: " . var_export($Status, true));
            if (!$Status) {
                throw new Exception('Failed to add product.');
            }
            // Insert inventory
            $InventoryMeta = [
                'product_sku' => $ProductSku,
                'current_stock' => $StockCount,
                'min_threshold' => $Threshold
            ];
            $InventoryStatus = $Inventory->create($InventoryMeta);
            error_log("Inventory creation status: " . var_export($InventoryStatus, true));
            if (!$Status) {
                throw new Exception('Failed to add inventory product.');
            }
            // Insert car models
            $ProductCompatibleId = [];
            for ($I = 0; $I < count($CompatibleCars); $I++) {
                $ProductCompatibleId[] = $Model->add($CompatibleCars[$I], true);
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
        } finally {
            $Conn->autocommit(true);
        }
    }
?>