<?php 
    /** Author: John Roland Octavio
     *  DatabaseConnection provides a singleton mysqli connection to the application's database.
     */
    class DatabaseConnection {
        private static ?DatabaseConnection $Instance = null;
        private mysqli $Connection;

        private string $HOST = 'localhost';
        private string $DB = 'nixar_autoglass_db';
        private string $USER = 'nixarposautoglass';
        private string $PASS = 'Nixarposdb123*';

        private function __construct() {
            $this->Connection = new mysqli(
                $this->HOST,
                $this->USER,
                $this->PASS,
                $this->DB
            );

            if ($this->Connection->connect_error) {
                throw new Exception("Failed to connect to the database: " . $this->Connection->connect_error);
            }
        }

        public static function getInstance(): DatabaseConnection {
            if(self::$Instance == null) {
                self::$Instance = new DatabaseConnection();
            }
            return self::$Instance;
        }

        public function getConnection() {
            return $this->Connection;
        }
    }

?>