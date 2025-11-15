<?php 
    /**
     * Author: John Roland L. Octavio
     * The SessionManager class is a centralized utility that handles all user session operations for the NIXAR POS web application.
     * It ensures controlled access, user authentication, and session-based redirection between the login page and protected pages.
     */
    class SessionManager {
        private static ?SessionManager $Instance = null;

        private function __construct() {
            if(session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        }

        public static function createUser(array $User) {
            self::getInstance();

            foreach($User as $Key => $Value) {
                $_SESSION[$Key] = $Value;
            }
            $_SESSION['logged_in'] = true;
        }

        public static function destroy() {
            self::getInstance();

            session_unset();
            session_destroy();
        }

        public static function getInstance(): SessionManager {
            if(self::$Instance === null) {
                self::$Instance = new SessionManager();
            }

            return self::$Instance;
        }

        public static function set($Key, $Value) {
            self::getInstance();
            $_SESSION[$Key] = $Value;
        }

        public static function get($Key) {
            self::getInstance();
            return $_SESSION[$Key] ?? null;
        }

        public static function has($Key) {
            self::getInstance();
            return isset($_SESSION[$Key]);
        }

        public static function checkSession() {
            self::getInstance();

            $CurrentPage = basename($_SERVER['PHP_SELF']);
            $LoginPage = 'index.php';
            $IsLoggedIn = self::isLoggedIn();
            $Role = self::get('role') ?? null;
            $AdminPages = ['reports.php', 'supplier.php', 'report-preview.php'];
            // Redirect to login page since there are no active users or sessions yet
            if (!$IsLoggedIn || !$Role) {
                if ($CurrentPage !== $LoginPage) {
                    self::redirect('index.php');
                }
            }

            // Redirect to default pages according to role if there is a session or an active user
            $DefaultPages = [
                'admin' => 'reports.php',
                'cashier' => 'transaction.php',
            ];

            $PageToDirect = $DefaultPages[$Role] ?? 'index.php';

            if ($IsLoggedIn && $CurrentPage === $LoginPage) {
                self::redirect($PageToDirect);
            }

            // Cashier is redirected to its main page when it attemps to access admin pages
            if ($Role === 'cashier' && in_array($CurrentPage, $AdminPages)) {
                self::redirect($PageToDirect);
            }
        }

        public static function redirect($Target) {
            header("Location: /nixar-pos/public/{$Target}");
            exit;
        }

        public static function isLoggedIn() {
            return self::has('logged_in') && self::get('logged_in') === true;
        }

        public static function requireAdminAccess() {
            self::getInstance();

            $IsLoggedIn = self::isLoggedIn();
            $Role = self::get('role');

            if (!$IsLoggedIn) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'unauthenticated']);
                exit;
            }

            if ($Role !== 'admin') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden access.'
                ]);
                exit;
            }
        }
    }
?>