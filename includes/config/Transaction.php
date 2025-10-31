<?php 
    class Transaction {
        private mysqli $Conn;

        public function __construct(mysqli $Conn) {
            $this->Conn = $Conn;
        }

        public function createTransaction($TransactionData) {
            try {
                $Sql = "INSERT INTO transactions(issuer_id, receipt_id, customer_id, payment_method) VALUES(?, ?, ?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
                }
                $Stmt->bind_param("iiis", $TransactionData['issuer_id'], $TransactionData['customer_id'], $TransactionData['customer_id'], $TransactionData['payment_method']);
                $Stmt->execute();

                $Stmt->close();
                return [
                    'success' => true, 
                    'message' => "Transaction data successfully added.",
                ];
            } catch(Exception $E) {
                error_log("Error on Transaction: ". $E->getMessage());
                return [
                    'success' => false,
                    'message' => $E->getMessage()
                ];
            }
        }
        // Function to insert a receipt entry in the database and return the created id of the receipt entry.
        public function addReceipt($ReceiptData) {
            try {
                $Sql = "INSERT INTO receipts(total_amount, discount) VALUES(?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
                }
                $Stmt->bind_param("dd", $ReceiptData['total_amount'], $ReceiptData['discount']);
                $Stmt->execute();

                $Id = $Stmt->insert_id;
                $Stmt->close();
                return [
                    'success' => true,
                    'message' => 'Receipt successfully added.',
                    'receipt_id' => $Id
                ];
            } catch(Exception $E) {
                error_log("Error on Transaction: ". $E->getMessage());
                return [
                    'success' => false,
                    'message' => $E->getMessage()
                ];
            }
        }

        public function addReceiptDetail($ReceiptData) {
            try {
                $Sql = "INSERT INTO receipt_details(receipt_id, nixar_product_sku, quantity) VALUES (?, ?, ?)";
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception('Failed to prepare INSERT statement: ' . $this->Conn->error);
                }
                $Stmt->bind_param("isi", $ReceiptData['receipt_id'], $ReceiptData['nixar_product_sku'], $ReceiptData['quantity']);
                $Stmt->execute();

                $Stmt->close();
                return [
                    'success' => true,
                    'message' => 'Receipt detail successfully added.'
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