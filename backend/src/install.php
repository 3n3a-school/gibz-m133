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

        echo "Starting the creation of Tables ⌛...";

        // order of array defines creation order
        $table_sql = [
            "role",
            "club",
            "event",
            "event_meta",
            "category",
            "event_category_meta",
            "ranking",
            "user",
            "user_ranking",
            "user_role",
        ];

        foreach ($table_sql as $sql_file) {
            $sql = $this->getSqlFile( "$sql_file.sql" );
            $this->db->createObject($sql);
        }

        echo "Done creating Tables 😀";
    }

    function prefillTables() {}
    
    function getSqlFile( $filename ) {
        return file_get_contents( $this->base_path . $filename);
    }
}

$table_creator = new TableCreator1000(
    $config->db
);