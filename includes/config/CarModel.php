<?php 
    /** Author: John Roland Octavio
     *  CarModel manages car model records and customer car details by detecting existing models, inserting unique models, and linking customer vehicles while handling errors and logging.
     */
    class CarModel {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        // Function that checks for any 1:1 duplicate in the car_models table and returns the id if an entry exists.
        public function findExisting($CarModelData) {
            // Check for duplicates
            $Check = "SELECT car_model_id FROM car_models WHERE make = ? AND model = ? AND year = ? AND type = ?";
            $CheckStmt = $this->Conn->prepare($Check);
            if (!$CheckStmt) {
                throw new Exception('Failed to prepare SELECT query: ' . $this->Conn->error);
            }

            $CheckStmt->bind_param("ssis", $CarModelData['make'], $CarModelData['model'], $CarModelData['year'], $CarModelData['type']);
            $CheckStmt->execute();

            $CarModelId = null;
            $CheckDuplicate = $CheckStmt->get_result();
            if ($Duplicate = $CheckDuplicate->fetch_assoc()) {
                $CarModelId = (int)$Duplicate['car_model_id'];
            }

            $CheckStmt->close();
            return $CarModelId;
        }

        // Function that adds car details in the car_model table when there are no duplicate entries.
        public function add($CarModelData) {
            try {
                $DuplicateId = $this->findExisting($CarModelData);
                if($DuplicateId) {
                    return [
                        'success' => true,
                        'car_model_id' => (int)$DuplicateId,
                        'message' => 'Existing car model found.'
                    ];
                }
                // Perform INSERT only if its a unique entry;
                $CarModelSql = "INSERT INTO car_models(make, model, year, type) VALUES(?, ?, ?, ?)";
                $Stmt = $this->Conn->prepare($CarModelSql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT query: ' . $this->Conn->error);
                }
                $Stmt->bind_param('ssis', $CarModelData['make'], $CarModelData['model'], $CarModelData['year'], $CarModelData['type']);
                if (!$Stmt->execute()) {
                    throw new Exception('Failed to execute INSERT: ' . $Stmt->error);
                }
                $Id = $Stmt->insert_id;
                $Stmt->close();

                return [
                    'success' => true,
                    'message' => "Car model successfully added.",
                    'car_model_id' => (int)$Id
                ];
            } catch (Exception $E) {
                error_log("Error on Car Model: ". $E->getMessage());
                return [
                    "success" => false,
                    "message" => $E->getMessage()
                ];
            }
        }

        public function addCarDetails($CarDetailData) {
            try {
                $ExistingCarModel = $this->findExisting($CarDetailData['car_model']);
                if(!$ExistingCarModel) {
                    $AddCarModel = $this->add($CarDetailData['car_model']);
                    if (!$AddCarModel['success']) {
                        throw new Exception("Failed to add new car model: " . $AddCarModel['message']);
                    }
                    $ExistingCarModel = $AddCarModel['car_model_id'];
                }
                // Perform INSERT only if its a unique entry;
                $Sql = "INSERT INTO car_details(customer_id, car_model_id, plate_no) VALUES(?, ?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT query: ' . $this->Conn->error);
                }

                $Stmt->bind_param('iis', $CarDetailData['customer_id'], $ExistingCarModel, $CarDetailData['plate_no']);
                if (!$Stmt->execute()) {
                    throw new Exception('Failed to execute INSERT: ' . $Stmt->error);
                }
                $Stmt->close();

                return [
                    'success' => true,
                    'message' => "Car model successfully added.",
                ];
            } catch (Exception $E) {
                error_log("Error on Car Model: ". $E->getMessage());
                return [
                    "success" => false,
                    "message" => $E->getMessage()
                ];
            }
        }
    }

?>