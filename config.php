<?php
// MySQL Database Configuration
$db_host = 'localhost';
$db_name = 'file_tracking';
$db_user = 'root';
$db_pass = '';

// Verify MySQL server is running
try {
    $conn = new PDO("mysql:host=$db_host", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
    $conn->exec("USE `$db_name`");
    
    // Check if tables exist, if not create them
    $tables = $conn->query("SHOW TABLES LIKE 'incoming'")->rowCount();
    if ($tables == 0) {
        $conn->exec(file_get_contents('database.sql'));
    }
} catch(PDOException $e) {
    die("<h2>Database Connection Error</h2>
        <p><b>Error:</b> " . $e->getMessage() . "</p>
        <p>Please verify:</p>
        <ol>
            <li>XAMPP MySQL service is running</li>
            <li>MySQL credentials in config.php are correct</li>
            <li>MySQL user has proper permissions</li>
        </ol>");
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