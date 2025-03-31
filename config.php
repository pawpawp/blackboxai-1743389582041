<?php
// SQLite Database Configuration
$db_file = 'file_tracking.db';

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create tables if they don't exist
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
} catch(PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
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