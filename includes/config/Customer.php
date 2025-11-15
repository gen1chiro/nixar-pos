<?php 
    /** Author: John Roland Octavio
     *  Customer manages customer lookup and insertion: it detects existing customers and inserts new ones while returning consistent success/error responses.
     */
    class Customer {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function findExisting($CustomerData) {
            $Check = "SELECT customer_id FROM customers WHERE name = ? AND email = ? AND phone_no = ?";
            $CheckStmt = $this->Conn->prepare($Check);
            if (!$CheckStmt) {
                throw new Exception('Failed to prepare SELECT query: ' . $this->Conn->error);
            }

            $CheckStmt->bind_param("ssi", $CustomerData['name'], $CustomerData['email'], $CustomerData['phone_no']);
            $CheckStmt->execute();

            $CustId = null;
            $CheckDuplicate = $CheckStmt->get_result();
            if ($Duplicate = $CheckDuplicate->fetch_assoc()) {
                $CustId = (int)$Duplicate['car_model_id'];
            }

            $CheckStmt->close();
            return $CustId;
        }

        public function add($CustomerData) {
            try {
                $DuplicateId = $this->findExisting($CustomerData);
                if($DuplicateId) {
                    return $DuplicateId;
                }

                $Sql = "INSERT INTO customers(name, email, address, phone_no) VALUES(?, ?, ?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT query: ' . $this->Conn->error);
                }
                $Stmt->bind_param('sssi', $CustomerData['name'], $CustomerData['email'], $CustomerData['address'], $CustomerData['phone_no']);
                $Stmt->execute();
                if (!$Stmt->execute()) {
                    throw new Exception("Failed to execute INSERT: {$Stmt->error}");
                }
                $Id = $Stmt->insert_id;
                $Stmt->close();

                return [
                    'success' => true,
                    'customer_id' => $Id
                ];
            } catch (Exception $E) {
                error_log("Error on Customer: ". $E->getMessage());
                return [
                    'success' => true,
                    'message' => $E->getMessage()
                ];
            }
        }
    }

?>