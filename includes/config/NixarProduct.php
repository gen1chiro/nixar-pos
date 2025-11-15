<?php 
    /** Author: John Roland Octavio
     * NixarProduct handles product CRUD, paginated listing, searching, pricing calculation, compatibility mappings, and related material/supplier lookups. 
    */
    class NixarProduct {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function fetchPaginated(?int $Limit = null, int $Offset = 0) {
            try {
                $Sql = "SELECT np.*, 
                               pm.*,
                               i.current_stock,
                               ROUND(ps.base_price + (ps.base_price * (np.mark_up / 100)), 2) AS final_price
                        FROM nixar_products np
                        JOIN product_materials pm
                            ON np.product_material_id = pm.product_material_id
                        JOIN product_suppliers ps
                            ON ps.nixar_product_sku = np.nixar_product_sku
                        JOIN inventory i
                            ON np.nixar_product_sku = i.product_sku
                        WHERE np.is_deleted = 0";
                if ($Limit !== null) {
                    $Sql .= " LIMIT ? OFFSET ?";
                }
                // Execute SQL Query
                $Stmt = $this->Conn->prepare($Sql);
                if(!$Stmt) {
                    throw new Exception("Failed to execute query: " . $this->Conn->error);
                }
                if ($Limit !== null) {
                    $Stmt->bind_param("ii", $Limit, $Offset);
                }
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

        public function update(array $UpdateData) {
            try {
                $Sql = "UPDATE nixar_products SET product_name = ?, 
                    product_material_id = ?, 
                    product_img_url = ?,
                    product_supplier_id = ?, 
                    mark_up = ? 
                WHERE nixar_product_sku = ?
                AND is_deleted = 0";
    
                $Stmt = $this->Conn->prepare($Sql);
                $Stmt->bind_param(
                    "sisiis", 
                    $UpdateData['product_name'], 
                    $UpdateData['product_material_id'],
                    $UpdateData['product_img_url'],
                    $UpdateData['product_supplier_id'],
                    $UpdateData['mark_up'],
                    $UpdateData['nixar_product_sku']
                );

                $Stmt->execute();
                if ($Stmt->errno) {
                    return [
                        'success' => false,
                        'message' => "Database error: {$Stmt->error}"
                    ];
                }
                
                $Stmt->close();
                return [
                    'success' => true,
                    'message' => "Product with SKU " . $UpdateData['nixar_product_sku'] . " successfully updated."
                ];
            } catch (Exception $E) {
                error_log("Error updating product: " . $E->getMessage());
                return [
                    'success' => false,
                    'message' => "Error: ". $E->getMessage()
                ];
            }
        }
        // remove
        public function remove(string $ProductSku) {
            try {
                $Sql = "UPDATE nixar_products 
                        SET is_deleted = 1 
                        WHERE nixar_product_sku = ?";
                $Stmt = $this->Conn->prepare($Sql);
                $Stmt->bind_param("s", $ProductSku);
    
                $Stmt->execute();
                if ($Stmt->affected_rows === 0) {
                    $Stmt->close();
                    return [
                        'success' => false,
                        'message' => "No product found with SKU { $ProductSku }."
                    ];
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
                $Sql = "INSERT INTO nixar_products(nixar_product_sku, product_material_id, product_supplier_id, product_name, product_img_url, mark_up) 
                        VALUES(?, ?, ?, ?, ?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                $Stmt->bind_param(
                    "siissd", 
                    $ProductData['product_sku'], 
                    $ProductData['material_id'], 
                    $ProductData['supplier_id'],
                    $ProductData['product_name'], 
                    $ProductData['image_url'],
                    $ProductData['mark_up']
                );
                $Stmt->execute();
                $Stmt->close();
    
                return [
                    'success' => true,
                    'message' => "Product with SKU {$ProductData['product_sku']} successfully created."
                ];
            } catch (Exception $E) {
                return [
                    "success" => false,
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
    
                return [
                    'success' => true,
                    'data' => $Rows
                ];
            } catch (Exception $E) {
                return [
                    "success" => false,
                    "message" => $E->getMessage()
                ];
            }
        }

        public function countPerCategory() {
            try {
                $Sql = "SELECT COUNT(np.nixar_product_sku) AS product_count,
                               pm.category
                        FROM nixar_products np
                        JOIN product_suppliers ps
                            ON np.product_supplier_id = ps.product_supplier_id
                        JOIN product_materials pm
                            ON np.product_material_id = pm.product_material_id
                        WHERE np.is_deleted = 0
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
                    "success" => false,
                    "message" => $E->getMessage()
                ];
            }
        }

        public function insertCompatible($ProductSku, $CarModelId, $ReturnId = false) {
            $Sql = "INSERT INTO product_compatibility(nixar_product_sku, car_model_id) VALUES(?, ?)";
            $Stmt = $this->Conn->prepare($Sql);
            if(!$Stmt) {
                throw new Exception('Failed to prepare INSERT query: ' . $this->Conn->error);
            }
            $Stmt->bind_param("si", $ProductSku, $CarModelId);
            $Stmt->execute();

            $Id = $Stmt->insert_id;
            $Stmt->close();

            return $ReturnId ? $Id : true;
        }

        public function fetchMaterials() {
            $Sql = "SELECT * FROM product_materials";
            $Result = $this->Conn->query($Sql);
            if (!$Result) {
                throw new Exception('Failed to execute query: ' . $this->Conn->error);
            }
            return $Result->fetch_all(MYSQLI_ASSOC);
        }

        public function fetchCompatible(string $ProductSku) {
            try {
                $Sql = "SELECT 
                    cm.make, 
                    cm.model, 
                    cm.year, 
                    cm.type, 
                    np.product_name, 
                    np.nixar_product_sku 
                FROM car_models cm 
                JOIN product_compatibility pc 
                    ON pc.car_model_id = cm.car_model_id 
                JOIN nixar_products np 
                    ON pc.nixar_product_sku = np.nixar_product_sku 
                WHERE np.nixar_product_sku = ?";
                
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare SELECT statement: ' . $this->Conn->error);
                }
                $Stmt->bind_param("s", $ProductSku);
                $Stmt->execute();

                $Result = $Stmt->get_result();
                $Rows = $Result->fetch_all(MYSQLI_ASSOC);
                $Stmt->close();

                return [
                    'success' => true,
                    'rows' => $Rows
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