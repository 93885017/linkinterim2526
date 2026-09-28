<?php
// generate connect mysql database class
class dbConnect {
    private $conn;

    function connect($autoRedirect = true) { 
        include_once 'config/config.php';
        try {
            $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
        } catch (Exception $e) {
            // echo "Failed to connect to MySQL: " . mysqli_connect_error();
            $this->conn = null;

            // error_log("Failed to connect to MySQL: " . mysqli_connect_error());

            if ($autoRedirect) {
                header("Location: contingency_page.php");
            }
        }
        // return database handler
        return $this->conn;
    }
}
