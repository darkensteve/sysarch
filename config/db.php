<?php
// Database configuration
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "sitin_monitoring";

// Set this to true to enable database connection, false to use only static data
$use_database = false; // Keep this false until the database is created

// Connection variable to be used throughout the application
$conn = null;

// Only attempt database connection if enabled
if ($use_database) {
    try {
        // Only try to connect if use_database is true
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        
        // Check connection
        if (!$conn) {
            // Log the error but don't stop execution
            error_log("Database connection failed: " . mysqli_connect_error());
        }
    } catch (Exception $e) {
        // Log the error but don't stop execution
        error_log("Database exception: " . $e->getMessage());
    }
}

// Function to check if database is connected
function is_db_connected() {
    global $conn, $use_database;
    return ($use_database && $conn !== null);
}

// Function to simulate database operations
function simulateDbQuery($query) {
    // This function can be expanded later to simulate different queries
    return true;
}
?>
