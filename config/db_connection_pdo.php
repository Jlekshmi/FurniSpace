<?php
// Define database connection constants
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_furnispace');
define('CHARSET', 'utf8mb4');

try {
    // Create the DSN (Data Source Name)
    $data_source_name = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . CHARSET;

    // Create a PDO instance
    $pdo = new PDO($data_source_name, DB_USER, DB_PASSWORD);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection Successful";
} catch (PDOException $e) {
    // Display connection error message
    echo "Connnection failed: " . $e->getMessage();
}
