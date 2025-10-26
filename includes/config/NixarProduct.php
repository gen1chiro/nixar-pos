<?php 
    class NixarProduct {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function fetchPaginated(int $Limit = 10, int $Offset = 0) {
            try {
                $Sql = "SELECT np.*, 
                               pm.*,
                               ROUND(ps.base_price + (ps.base_price * (np.mark_up / 100)), 2) AS final_price
                        FROM nixar_products np
                        JOIN product_materials pm
                            ON np.product_material_id = pm.product_material_id
                        JOIN product_suppliers ps
                            ON ps.nixar_product_sku = np.nixar_product_sku
                        WHERE np.is_deleted = 0
                        LIMIT ? OFFSET ?";
                // Execute SQL Query
                $Stmt = $this->Conn->prepare($Sql);
                if(!$Stmt) {
                    throw new Exception("Failed to execute query: " . $this->Conn->error);
                }
                $Stmt->bind_param("ii", $Limit, $Offset);
                $Stmt->execute();

                $Result = $Stmt->get_result();
                $Rows = $Result->fetch_all(MYSQLI_ASSOC);
                $Stmt->close();

                return $Rows;
            } catch (Exception $E) {
                return [
                    "status" => "error",
                    "message" => $E->getMessage()
                ];
            }
        }
        // TODO: updateInfo
        // remove
        public function remove(string $ProductSku) {
            try {
                $Sql = "UPDATE nixar_products 
                        SET is_deleted = 1 
                        WHERE nixar_product_sku = ?";
                $Stmt = $this->Conn->prepare($Sql);
                $Stmt->bind_param("s", $ProductSku);
    
                $Status = $Stmt->execute();
                if(!$Status) {
                    throw new Exception("Failed to execute query: " . $this->Conn->error);
                }
    
                $Stmt->close();
    
                return [
                    'success' => true,
                    'message' => "Product with SKU { $ProductSku } successfully deleted."
                ];
            } catch (Exception $E) {
                return [
                    'success' => false,
                    'message' => "Error: ". $E->getMessage()
                ];
            }
        }
        // create
        public function create(array $ProductData) {
            try {
                $Sql = "INSERT INTO nixar_products(nixar_product_sku, product_material_id, product_name, product_image_url, mark_up) 
                        VALUES(?,?,?,?,?)";
                $Stmt = $this->Conn->prepare($Sql);
                $Stmt->bind_param(
                    "sissd", 
                    $ProductData['product_sku'], 
                    $ProductData['material_id'], 
                    $ProductData['product_name'], 
                    $ProductData['image_url'],
                    $ProductData['mark_up']
                );
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

        public function searchBySkuOrKeyword(string $Query, int $Limit, int $Offset) {
            try {
                $Like = "%{$Query}%";
                $Sql = "SELECT * FROM nixar_products 
                        WHERE nixar_product_sku LIKE ? 
                            OR product_name LIKE ?
                        LIMIT ? OFFSET ?";
                $Stmt = $this->Conn->prepare($Sql);
                $Stmt->bind_param("ssii", 
                    $Like, 
                    $Like,
                    $Limit,
                    $Offset
                );
                $Stmt->execute();
                $Result = $Stmt->get_result();
                $Rows = $Result->fetch_all(MYSQLI_ASSOC);
                $Stmt->close();
    
                return $Rows;
            } catch (Exception $E) {
                return [
                    "status" => "error",
                    "message" => $E->getMessage()
                ];
            }
        }

        public function countPerCategory() {
            try {
                $Sql = "SELECT COUNT(np.nixar_product_sku) AS product_count,
                               pm.category
                        FROM nixar_products np
                        LEFT JOIN product_materials pm
                            ON np.product_material_id = pm.product_material_id
                        WHERE pm.category IN ('Glass', 'Rubber', 'Tints')
                        GROUP BY pm.category";
    
                $Stmt = $this->Conn->query($Sql);
                $Result = [];
                
                while($Row = $Stmt->fetch_assoc()) {
                    $Result[strtolower($Row['category'])] = (int)$Row['product_count'];
                }
                
                $Result['all_products'] = array_sum($Result); 
                $Stmt->close();
    
                return $Result;
            } catch (Exception $E) {
                return [
                    "status" => "error",
                    "message" => $E->getMessage()
                ];
            }
        }
    }

?>