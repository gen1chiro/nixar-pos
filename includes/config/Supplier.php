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

        public function add($SupplierInfo, $ReturnId = false) {
            $SupplierSql = "INSERT INTO product_suppliers(nixar_product_sku, supplier_id, base_price) VALUES(?, ?, ?)";
            $Stmt = $this->Conn->prepare($SupplierSql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
            }
            $Stmt->bind_param("sid", $SupplierInfo['sku'], $SupplierInfo['id'], $SupplierInfo['base_price']);
            $Stmt->execute();

            $Id = $Stmt->insert_id;
            $Stmt->close();

            return $ReturnId ? $Id : true;
        }
    }
?>