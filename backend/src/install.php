<?php

namespace M133;

include_once __DIR__ . '/config.php';

class TableCreator1000 {
    function __construct(
        private Database $db
    ) {
        $this->createTables();
        $this->prefillTables();
    }

    function createTables() {
        $this->create...
    }

    function prefillTables() {}

    function createClub() {
        $sql = "
        CREATE TABLE IF NOT EXISTS
        ";

        $db->createObject($sql);
    }
}
