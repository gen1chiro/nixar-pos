<?php 
    /* Author: John Roland Octavio
    * Handles deletion of a product by SKU; validates input, calls product removal, 
    * and returns JSON success/error response.
    */
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();
    SessionManager::requireAdminAccess();
    
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        try {
            $Conn = DatabaseConnection::getInstance()->getConnection();
            $Product = new NixarProduct($Conn);
            $ProductSku = $_POST['product_sku'];
    
            if (!$ProductSku) {
                throw new Exception("Missing product SKU.");
            }
            
            $Result = $Product->remove($ProductSku);
            
            echo json_encode([
                'success' => (bool) $Result,
                'message' => $Result ? 'Product removed successfully.' : 'An error occured while removing the product.'
            ]);
        } catch (Exception $E) {
            echo json_encode([
                'success' => false,
                'message' => $E->getMessage()
            ]);
        }
    }
?>