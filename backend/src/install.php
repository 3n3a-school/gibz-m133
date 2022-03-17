<?php

namespace M133;

include_once __DIR__ . '/config.php';

class TableCreator1000 {
    private $base_path = __DIR__ . '/sql/';

    function __construct(
        private Database $db
    ) {
        $this->createTables();
        $this->prefillTables();
    }

    function createTables() {

        // order of array defines creation order
        $table_sql = [
            "event_meta",
            "event",
            "category",
            "event_category_meta",
            "ranking",
            "user",
            "user_ranking",
            "user_role",
            "club",
            "role"
        ];

        foreach ($table_sql as $sql_file) {
            $sql = getSqlFile( "$sql_file.sql" );
            $db->createObject($sql);
        }
    }

    function prefillTables() {}
    
    function getSqlFile( $filename ) {
        return file_get_contents( $this->base_path . $filename);
    }
