<?php 
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $Sanitized = InputValidator::sanitizeArray($_POST);
        
        $Image = $_FILES['product_image'] ?? null;


        // Update data from tables nixar_products, inventory, and product_suppliers
        $Conn->begin_transaction();
        try {
            $Product = new NixarProduct($Conn);
            $Inventory = new Inventory($Conn);
            $Supplier = new Supplier($Conn);
            
            $UpdatedImage = Image::uploadToDirectory($Image);
            // Perform UPDATE operation on Product Data
            $ProductData = [
                'nixar_product_sku' => $Sanitized['product_sku'],
                'product_img_url' => $UpdatedImage,
                'product_name' => $Sanitized['product_name'],
                'product_material_id' => $Sanitized['product_material_id'],
                'product_supplier_id' => $Sanitized['product_supplier_id'],
                'mark_up' => $Sanitized['mark_up']
            ];

            $Result = $Product->update($ProductData);
            if(!$Result['success']) {
                throw new Exception("Failed to update product: {$Sanitized['product_sku']}. Message: {$Result['message']}");
            }
            // Perform UPDATE operation on Inventory Data
            $InventoryData = [
                'inventory_id' => $Sanitized['inventory_id'],
                'stock_count' => $Sanitized['stock_count'],
                'min_threshold' => $Sanitized['min_threshold']
            ];

            $InventoryResult = $Inventory->update($InventoryData);
            if(!$InventoryResult['success']) {
                throw new Exception("Failed to update inventory: {$Sanitized['product_sku']}.");
            }
            // Perform UPDATE operation on Supplier Data
            $ProductSupplierData = [
                'product_supplier_id' => $Sanitized['product_supplier_id'],
                'nixar_product_sku' => $Sanitized['product_sku'],
                'supplier_id' => $Sanitized['supplier_id'],
                'base_price' => (float)$Sanitized['base_price']
            ];
            
            $ProductSupplierResult = $Supplier->updateProductSupplier($ProductSupplierData);
            if(!$ProductSupplierResult['success']) {
                throw new Exception("Failed to update product supplier of: {$Sanitized['product_sku']}");
            }
            
            $Conn->commit();
            echo json_encode([
                'success' => true,
                'message' => "Product {$Sanitized['product_sku']} updated successfully."
            ]);
        } catch (Exception $E) {
            error_log("Error editing product: " . $E->getMessage());
            $Conn->rollback();
            echo json_encode([
                'success' => false,
                'message' => $E->getMessage() 
            ]);
        } finally {
            $Conn->autocommit(true);
        }
    }

?>