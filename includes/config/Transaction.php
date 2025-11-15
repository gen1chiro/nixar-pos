<?php 
    /** Author: John Roland Octavio
     * Transaction handles creating receipts and transactions, adding receipt details, and retrieving transactions within a given date range. 
    */
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
                $Stmt->bind_param("iiis", $TransactionData['issuer_id'], $TransactionData['receipt_id'], $TransactionData['customer_id'], $TransactionData['payment_method']);
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

        public function fetchTransactionByRange($Start, $End) {
            try {
                $Sql = "SELECT 
                        t.transaction_id, 
                        t.customer_id, 
                        t.created_at AS transaction_created_at, 
                        t.payment_method, u.name AS issuer, 
                        r.receipt_id, r.created_at AS issued_at, 
                        r.total_amount, r.discount, 
                        SUM(rd.quantity) AS item_qty 
                    FROM transactions t 
                    JOIN users u 
                        ON t.issuer_id = u.user_id 
                    JOIN receipts r 
                        ON t.receipt_id = r.receipt_id 
                    JOIN receipt_details rd 
                        ON r.receipt_id = rd.receipt_id
                    WHERE t.created_at >= ? AND t.created_at <= ?
                    GROUP BY t.transaction_id, t.customer_id, t.created_at, t.payment_method, u.name, r.created_at, r.total_amount, r.discount 
                    ORDER BY t.created_at DESC";
            
                $Stmt = $this->Conn->prepare($Sql);
                if (!$Stmt) {
                    throw new Exception("Failed to prepare SELECT statement: {$this->Conn->error}");
                }
                $Stmt->bind_param("ss", $Start, $End);
                $Stmt->execute();

                $Result = $Stmt->get_result();
                $Rows = $Result->fetch_all(MYSQLI_ASSOC);

                $Result->free();
                $Stmt->close();
                
                return [
                    'success' => true,
                    'transactions' => $Rows
                ];
            } catch (Exception $E) {
                error_log("Transaction Error: {$E->getMessage()}");
                return [
                    'status' => false,
                    'message' => $E->getMessage()
                ];
            }
        }
    }
?>