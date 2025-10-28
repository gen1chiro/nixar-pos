<?php 
    class Inventory {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function create(array $InventoryMeta) {
            try {
                $Sql = "INSERT INTO inventory(nixar_product_sku, current_stock, min_threshold) VALUES (?, ?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
                }

                $Stmt->bind_param("sii", $InventoryMeta['product_sku'], $InventoryMeta['current_stock'], $InventoryMeta['min_threshold']);
                $Status = $Stmt->execute();
                $Stmt->close();

                return $Status;
            } catch (Exception $E) {
                return [
                    "status" => "error",
                    "message" => $E->getMessage()
                ];
            }
        }
        // TODO: fetchInventory
        public function fetchInventory(?int $Limit = null, int $Offset = 0): array {
            $Sql = "SELECT * FROM product_inventory_view ORDER BY current_stock DESC, product_name ASC";
            if ($Limit !== null) {
                $Sql .= " LIMIT ? OFFSET ?";
                $Stmt = $this->Conn->prepare($Sql);
                if(!$Stmt) {
                    throw new Exception("Failed to execute query: ". $this->Conn->error);
                }
                $Stmt->bind_param("ii", $Limit, $Offset);
            } else {
                $Stmt = $this->Conn->prepare($Sql);
                if(!$Stmt) {
                    throw new Exception("Failed to execute query: ". $this->Conn->error);
                }
            }

            $Stmt->execute();
            $Result = $Stmt->get_result();
            // Fetch all inventory data as an associative array
            $Rows = $Result->fetch_all(MYSQLI_ASSOC);
            // Free memory resources and return rows
            $Result->free();
            $Stmt->close();

            return $Rows;
        }

        public function searchInventoryByKeyword(string $Query, int $Limit, int $Offset) {
            $Like = "%{$Query}%";
            $MatchSql = "SELECT * FROM product_inventory_view 
                WHERE car_make_model LIKE ? 
                  OR type LIKE ? 
                  OR product_name LIKE ?
                ORDER BY current_stock DESC
                LIMIT ? OFFSET ?";
            $Stmt = $this->Conn->prepare($MatchSql);
            if(!$Stmt) {
                throw new Exception("Failed to execute query: ". $this->Conn->error);
            }
            $Stmt->bind_param("sssii", $Like, $Like, $Like, $Limit, $Offset);
            $Stmt->execute();
            
            $Result = $Stmt->get_result();
            $Rows = $Result->fetch_all(MYSQLI_ASSOC);
            $Stmt->close();
            return $Rows;
        }

        public function getInventoryCount() {
            $Sql = "SELECT COUNT(*) AS total FROM product_inventory_view";
            $Result = $this->Conn->query($Sql);
            $Row = $Result->fetch_assoc();
            return (int)$Row['total'];
        }

        public function countBySearchKeyword($Keyword) {
            $Like = "%{$Keyword}%";
            $Sql = "SELECT COUNT(*) AS total
                        FROM product_inventory_view
                        WHERE car_make_model LIKE ?
                        OR type LIKE ?
                        OR product_name LIKE ?";
            $Stmt = $this->Conn->prepare($Sql);
            if(!$Stmt) {
                throw new Exception("Failed to execute query: ". $this->Conn->error);
            }
            $Stmt->bind_param("sss", $Like, $Like, $Like);
            $Stmt->execute();
            
            $Result = $Stmt->get_result();
            $Row = $Result->fetch_assoc();
            $Stmt->close();
            return (int)$Row['total'];
        }
    }

?>