<?php
// Database Configuration
$db_file = 'file_tracking.db';
$use_mysql = false;

// First try MySQL connection
try {
    $db_host = 'localhost';
    $db_name = 'file_tracking'; 
    $db_user = 'root';
    $db_pass = '';
    
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $use_mysql = true;
    
    // Check if tables exist
    $tables = $conn->query("SHOW TABLES LIKE 'incoming'")->rowCount();
    if ($tables == 0) {
        $conn->exec(file_get_contents('database.sql'));
    }
} catch(PDOException $mysql_e) {
    // MySQL failed, try SQLite fallback
    try {
        $conn = new PDO("sqlite:$db_file");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create SQLite tables if they don't exist
        $conn->exec("CREATE TABLE IF NOT EXISTS incoming (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            status TEXT NOT NULL,
            control_no TEXT UNIQUE NOT NULL,
            date_received TEXT NOT NULL,
            office_requestor TEXT NOT NULL,
            transaction_type TEXT NOT NULL,
            action_taken TEXT NOT NULL,
            date_forwarded TEXT,
            received_by TEXT NOT NULL,
            remarks TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
        
        $conn->exec("CREATE TABLE IF NOT EXISTS outgoing (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            control_no TEXT UNIQUE NOT NULL,
            date TEXT NOT NULL,
            time TEXT NOT NULL,
            document TEXT NOT NULL,
            client_name TEXT NOT NULL,
            agency_office TEXT NOT NULL,
            contact_no TEXT,
            action_taken TEXT NOT NULL,
            acted_by TEXT NOT NULL,
            date_acted TEXT NOT NULL,
            remarks TEXT,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } catch(PDOException $sqlite_e) {
        die("<h2>Database Connection Error</h2>
            <p>Failed to connect to both MySQL and SQLite databases.</p>
            <p><b>MySQL Error:</b> {$mysql_e->getMessage()}</p>
            <p><b>SQLite Error:</b> {$sqlite_e->getMessage()}</p>");
    }
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session start
session_start();

// Flash message function
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : '';
            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}
?>