<?php 
    header("Content-Type: application/json");
    include_once __DIR__ . '/../../includes/config/_init.php';  
    SessionManager::checkSession();

    $Conn = DatabaseConnection::getInstance()->getConnection();
    if($_SERVER["REQUEST_METHOD"] === "POST") {
        // Clean received data, extract cart products and re-sanitize
        $Sanitized = InputValidator::sanitizeArray($_POST);
        $Cart = json_decode($_POST['checkout_products'], true);
        $Sanitized['checkout_products'] = InputValidator::sanitizeArray($Cart);
        
        $UserId = SessionManager::get('user_id');
        // error_log("checkout products: ". json_encode($Sanitized['checkout_products']));
        $ReceiptData = [
            'total_amount' => $Sanitized['total_amount'],
            'discount' => $Sanitized['discount']
        ];
        $CustomerData = [
            'name' => $Sanitized['cust_name'],
            'email' => $Sanitized['cust_email'],
            'address' => $Sanitized['cust_address'],
            'phone_no' => $Sanitized['cust_phone_no']
        ];
        $CarModelData = [
            'make' => $Sanitized['make'],
            'model' => $Sanitized['model'],
            'year' => $Sanitized['year'],
            'type' => $Sanitized['car_type']
        ];
        // Insert data in the order of: receipt, receipt_details, customer, transaction, car_details
        $Conn->begin_transaction();
        try {
            $Transaction = new Transaction($Conn);
            $Customer = new Customer($Conn);
            $CarModel = new CarModel($Conn);
            // Perform INSERT on table `receipts`
            $ReceiptIdRef = $Transaction->addReceipt($ReceiptData);
            if (!$ReceiptIdRef['success']) {
                throw new Exception("INSERT operation in table `receipt` unsuccessful.");
            }
            // Perform INSERT on table `receipt_details `for each `checkout_products`
            foreach ($Sanitized['checkout_products'] as $ReceiptEntry) {
                $ReceiptProductDetail = [
                    'receipt_id' => $ReceiptIdRef['receipt_id'],
                    'nixar_product_sku' => $ReceiptEntry['sku'],
                    'quantity' => (int)$ReceiptEntry['quantity']
                ];
                $Transaction->addReceiptDetail($ReceiptProductDetail);
            }
            // Perform INSERT on table `customers`
            $CustIdRef = $Customer->add($CustomerData);
            $TransactionData = [
                'issuer_id' => $UserId,
                'receipt_id' => $ReceiptIdRef['receipt_id'],
                'customer_id' => $CustIdRef['customer_id'],
                'payment_method' => $Sanitized['payment_method']
            ];
            // Perform INSERT on table `transaction`
            $Transaction->createTransaction($TransactionData);
            // Perform INSERT on table `car_details`
            $CarDetailData = [
                'customer_id' => $CustIdRef['customer_id'],
                'plate_no' => $Sanitized['plate_no'],
                'car_model' => $CarModelData
            ];
            $CarModel->addCarDetails($CarDetailData);

            $Conn->commit();
            echo json_encode([
                'success' => true,
                'message' => "Transaction information successfully saved."
            ]);
        } catch (Exception $E) {
            error_log("Error on Inventory: ". $E->getMessage());
            $Conn->rollback();
            echo json_encode([
                'success' => false,
                'message' => $E->getMessage()
            ]);
        } finally {
            $Conn->autocommit(true);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request method.'
        ]);
    }
?>