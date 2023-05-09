<?php

require_once '../vendor/autoload.php';
require_once '../config/dotenv.php';

class Database {
    private $host;
    private $user;
    private $password;
    private $database;
    private $conn;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->user =  $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->database = $_ENV['DB_NAME'];
        
        $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function execute($query) {
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            die('Query failed: ' . mysqli_error($this->conn));
        }

        return $result;
    }

    public function escapeString($string) {
        return mysqli_real_escape_string($this->conn, $string);
    }

    public function __destruct() {
        mysqli_close($this->conn);
    }
}