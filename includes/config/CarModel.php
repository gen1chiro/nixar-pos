<?php 
    class CarModel {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function add($CarModelData, $ReturnId = false) {
            // Check for duplicates
            $Check = "SELECT car_model_id FROM car_models WHERE make = ? AND model = ? AND year = ? AND type = ?";
            $CheckStmt = $this->Conn->prepare($Check);
            if (!$CheckStmt) {
                throw new Exception('Failed to prepare SELECT query: ' . $this->Conn->error);
            }

            $CheckStmt->bind_param("ssis", $CarModelData['make'], $CarModelData['model'], $CarModelData['year'], $CarModelData['type']);
            $CheckStmt->execute();

            $CheckDuplicate = $CheckStmt->get_result();
            if ($Duplicate = $CheckDuplicate->fetch_assoc()) {
                $CheckStmt->close();
                return $ReturnId ? (int)$Duplicate['car_model_id'] : true;
            }
            // Perform INSERT only if its a unique entry;
            $CarModelSql = "INSERT INTO car_models(make, model, year, type) VALUES(?, ?, ?, ?)";
            $Stmt = $this->Conn->prepare($CarModelSql);
            if (!$Stmt) {
                throw new Exception('Failed to prepare INSERT query: ' . $this->Conn->error);
            }
            $Stmt->bind_param('ssis', $CarModelData['make'], $CarModelData['model'], $CarModelData['year'], $CarModelData['type']);
            $Stmt->execute();
            if (!$Stmt->execute()) {
                throw new Exception('Failed to execute INSERT: ' . $Stmt->error);
            }
            $Id = $Stmt->insert_id;
            $Stmt->close();

            return $ReturnId ? $Id : true;
        }
    }

?>