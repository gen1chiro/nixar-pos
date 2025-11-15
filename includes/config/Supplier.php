<?php 
/** Author: John Roland Octavio
 * Supplier manages supplier records and supplier–product relationships, offering paginated listing, CRUD operations, base price updates, and retrieval of products supplied by a given supplier. 
*/
class Supplier {
    private mysqli $Conn;

    public function __construct(mysqli $Conn) {
        $this->Conn = $Conn;
    }

    public function fetchPaginated(?int $Limit = null, int $Offset = 0) {
        $Sql = "SELECT 
                s.supplier_id,
                s.supplier_name, 
                s.contact_no, 
                COUNT(ps.nixar_product_sku) AS product_supply_count 
            FROM suppliers s
            JOIN product_suppliers ps 
                ON s.supplier_id = ps.supplier_id 
            JOIN nixar_products np 
                ON np.nixar_product_sku = ps.nixar_product_sku 
            GROUP BY s.supplier_id"; 
        try {
            if ($Limit !== null) {
                $Sql .= " LIMIT ? OFFSET ?";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new exception("Failed to prepare SELECT query: {$this->Conn->error}");
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
        
            $Rows = $Result->fetch_all(MYSQLI_ASSOC);

            $Result->free();
            $Stmt->close();

            return [
                'success' => true,
                'data' => $Rows
            ];
        } catch (Exception $E) {
            return [
                'success' => false,
                'message' => $E->getMessage()
            ];
        }
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

    public function updateSupplierInfo($SupplierInfo) {
        try {
            $Sql = "UPDATE suppliers SET supplier_name = ?, contact_no = ? WHERE supplier_id = ?";
            $Stmt = $this->Conn->prepare($Sql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare UPDATE statement: ' . $this->Conn->error);
            }
            $Stmt->bind_param(
                "sii",
                $SupplierInfo['supplier_name'],
                $SupplierInfo['contact_no'],
                $SupplierInfo['supplier_id']
            );
            $Stmt->execute();
            $Stmt->close();

            return [
                'success' => true,
                'message' => "Successfully updated supplier information."
            ];
        } catch (Exception $E) {
            error_log("Error: " . $E->getMessage());
            error_log("Trace: " . $E->getTraceAsString());
            return [
                'success' => false,
                'message' => $E->getMessage()
            ];
        }
    }
    public function updateProductSupplier($SupplierInfo) {
        try {
            $Sql = "UPDATE product_suppliers SET supplier_id = ?, base_price = ? WHERE product_supplier_id = ?";
            $Stmt = $this->Conn->prepare($Sql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare UPDATE statement: ' . $this->Conn->error);
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

    public function updateBasePrice(array $UpdateData) {
      try {
          $Sql = "UPDATE product_suppliers SET base_price = ? WHERE product_supplier_id = ?";
          $Stmt = $this->Conn->prepare($Sql);
          if (!$Stmt) {
              throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
          }
          $Stmt->bind_param("di",$UpdateData['base_price'], $UpdateData['product_supplier_id']);
          $Stmt->execute();
          $Stmt->close();

          return [
            'success' => true,
            'message' => 'Produce base price updated successfully.'
          ];
      } catch (Exception $E) {
          error_log("Error: " . $E->getMessage());
          error_log("Trace: " . $E->getTraceAsString());
          return [
              'success' => false,
              'message' => $E->getMessage()
          ];
      }
    }

    public function removeProduct($ProductSupplierId) {
        try {
            $Sql = "DELETE FROM product_suppliers WHERE product_supplier_id = ?";
            $Stmt = $this->Conn->prepare($Sql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare DELETE statement: ' . $this->Conn->error);
            }
            $Stmt->bind_param("i", $ProductSupplierId);

            if (!$Stmt->execute()) {
                throw new Exception('Failed to execute DELETE statement: ' . $Stmt->error);
            }
            // Check if a row was actually deleted
            $IsDeleted = $Stmt->affected_rows > 0;
            return [
                'success' => $IsDeleted,
                'message' => $IsDeleted ? 'Product successfully removed from supplier.' : 'No Product has been deleted.'
            ];
        } catch (Exception $E) {
            return [
                'success' => false,
                'message' => $E->getMessage()
            ];
        }
    }
    public function getSupplierProducts(int $SupplierId) {
        try {
            $Sql = "SELECT 
                np.product_name, 
                np.nixar_product_sku,
                ps.product_supplier_id, 
                ps.base_price
            FROM nixar_products np 
            JOIN product_suppliers ps 
                ON np.nixar_product_sku = ps.nixar_product_sku 
            JOIN suppliers s 
                ON s.supplier_id = ps.supplier_id 
            WHERE s.supplier_id = ?";

            $Stmt = $this->Conn->prepare($Sql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare SELECT statement: ' . $this->Conn->error);
            }
            $Stmt->bind_param("i", $SupplierId);
            $Stmt->execute();
            $Result = $Stmt->get_result();
        
            $Rows = $Result->fetch_all(MYSQLI_ASSOC);
            $Stmt->close();

            return [
              'success' => true,
              'product' => $Rows
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