<?php 
    class Supplier {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function fetchAll() {
            $Sql = "SELECT * FROM suppliers";
            $Result = $this->Conn->query($Sql);
            if (!$Result) {
                throw new Exception('Failed to execute query: ' . $this->Conn->error);
            }
            return $Result->fetch_all(MYSQLI_ASSOC);
        }

        public function add($SupplierInfo) {
            $SupplierSql = "INSERT INTO product_suppliers(nixar_product_sku, supplier_id, base_price) VALUES(?, ?, ?)";
            $Stmt = $this->Conn->prepare($SupplierSql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
            }
            $Stmt->bind_param("sid", $SupplierInfo['sku'], $SupplierInfo['id'], $SupplierInfo['base_price']);
            $Stmt->execute();

            $Id = $Stmt->insert_id;
            $Stmt->close();

            return $Id;
        }

        public function updateProductSupplier($SupplierInfo) {
            try {
                $Sql = "UPDATE product_suppliers SET supplier_id = ?, base_price = ? WHERE product_supplier_id = ?";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
                }

                $Stmt->bind_param(
                    "idi",
                    $SupplierInfo['supplier_id'],
                    $SupplierInfo['base_price'],
                    $SupplierInfo['product_supplier_id']
                );
                $Stmt->execute();
                $Stmt->close();

                return [
                    'success' => true,
                    'message' => "Successfully updated product supplier of: {$SupplierInfo['nixar_product_sku']}"
                ];
            } catch (Exception $E) {
                return [
                    'success' => false,
                    'message' => $E->getMessage()
                ];
            }
        }
    }
?>