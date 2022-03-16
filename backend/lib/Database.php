<?php

namespace M133;

class DatabaseConfig {

    public $servername;
    public $port;
    public $dbname;
    public $username;
    public $password;
    
    function __construct() {
        $this->servername = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'];
        $this->dbname = $_ENV['DB_DB'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }
}

class Database {

    public $conn = NULL;
    private $DB_CONFIG;

    function __construct() {
        $this->DB_CONFIG = new DatabaseConfig();
    }

    private function initConnection() {
        try{
            $servername = $this->DB_CONFIG->servername;
            $port = $this->DB_CONFIG->port;
            $dbname = $this->DB_CONFIG->dbname;
            $this->conn = new PDO(
                "mysql:host=$servername;port=$port;dbname=$dbname",
                $this->DB_CONFIG->username,
                $this->DB_CONFIG->password
            );
            $this->conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            echo "Connection failed: " . $e -> getMessage();
        }
    }

    function __destruct() {
        $this->conn = null;
    }
}