    <?php 
        include_once __DIR__ . '/../../includes/config/_init.php';

        $Conn = DatabaseConnection::getInstance()->getConnection();
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            try {
                $Sanitized = InputValidator::sanitizeArray($_POST);
                $Username = $Sanitized['username'];
                $Password = $Sanitized['password'];
        
                $Sql = "SELECT * FROM users WHERE name = ?";
                $Stmt = $Conn->prepare($Sql); 
                $Stmt->bind_param("s", $Username);
                $Stmt->execute();
                
                $Result = $Stmt->get_result();
                if($Result->num_rows === 0) {
                    throw new Exception("Username is not found.");
                }
        
                $User = $Result->fetch_assoc();
                $ValidPassword = password_verify($Password, $User['password_hashed']);
                if($ValidPassword) {
                    $UserInfo = [
                        "user_id" => $User['user_id'],
                        "user_name" => $Username,
                        "role" => $User['role']
                    ];
                    SessionManager::createUser($UserInfo);
                    
                    switch($User['role']) {
                        case 'admin': 
                            header('Location: ../inventory.php');
                            break;
                        case 'cashier':
                            header('Location: ../transaction.php');
                            break;
                        default:
                            header('Location: ../index.php');
                            break;
                    }
                    exit;  
                }
                echo "Invalid Login!";
                exit;
            } catch (Exception $E) {
                error_log("Error: " . $E->getMessage());
                error_log("Trace: " . $E->getTraceAsString());
                echo "<p>" . htmlspecialchars($E->getMessage()) . "</p>";
            }
        }
    ?>