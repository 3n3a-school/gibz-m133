<?php

namespace M133;

class DatabaseConfig {
    function __construct(
        public $servername,
        public $port,
        public $dbname,
        public $username,
        public $password,
    ) {}
}

class Database {

    private $conn = NULL;

    function __construct(
        private DatabaseConfig $DB_CONFIG
    ) {}

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

    /**
     * Create a database object
     */
    private function createObject($sql) {
        $this->conn->exec($sql);
    }

    /**
     * Add a datarecord to a table
     */
    public function addData($sql, $values) {
        $conn->prepare($sql)->execute($values);
    }
    
    /**
     * Delete a datarecord from table
     */
    public function delData($sql, $values) {
        $conn->prepare($sql)->execute($values);
    }

    /**
     * Returns data record(s) from a query
     */
    public function getData($sql) {
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function __destruct() {
        $this->conn = null;
    }
}