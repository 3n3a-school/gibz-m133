<?php

namespace M133;

use \PDO;

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
    ) {
        $this->initConnection();
    }

    public function initConnection() {
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
    public function createObject($sql, $name=NULL) {
        try {
            $this->conn->exec($sql);
            error_log( ($name ? $name : "DbObject") . " created successfully");
        } catch(PDOException $e) {
            error_log( "PDOException: " . $sql . " - " . $e->getMessage());
        } catch (Exception $e) {
            error_log( "General Exception: " . $sql . " - " . $e->getMessage());
        }
    }

    /**
     * Add a datarecord to a table
     */
    public function changeData($sql, $values, $name = null) {
        try {
            $this->conn->prepare($sql)->execute($values);
            error_log( ($name ? $name : "DbObject") . " changed successfully");
        } catch(PDOException $e) {
            error_log( "PDOException: " . $sql . " - " . $e->getMessage());
        } catch (Exception $e) {
            error_log( "General Exception: " . $sql . " - " . $e->getMessage());
        }
    }

    public function queryData($sql, $values, $name = null) {
        try {
            error_log( ($name ? $name : "DbObject") . " queried successfully");
            return $this->conn->prepare($sql)->execute($values)->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log( "PDOException: " . $sql . " - " . $e->getMessage());
        } catch (Exception $e) {
            error_log( "General Exception: " . $sql . " - " . $e->getMessage());
        }
    }

    function __destruct() {
        $this->conn = null;
    }
}