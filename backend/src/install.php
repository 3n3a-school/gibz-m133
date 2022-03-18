<?php

namespace M133;

include_once __DIR__ . '/config.php';

class TableCreator1000 {
    private $base_path = __DIR__ . '/sql/';
    private $setup_file = __DIR__ . '/.setupdone';

    function __construct(
        private Database $db
    ) {
        $this->checkInstalledAlready();
        $this->createTables();
        $this->prefillTables();
        $this->createInstalledFile();
    }

    function checkInstalledAlready() {
        if ( file_exists( $this->setup_file ) ) {
            // Setup already
            $this->redirectHome( "Already setup 😎");
        }
    }

    function createInstalledFile() {
        file_put_contents( $this->setup_file, "" );
    }

    function redirectHome( $message=null ) {
        header( 'Location: /' );
        echo $message;
        exit();
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
            $this->db->createObject($sql, $sql_file);
        }

        echo "Done creating Tables 😀";
    }

    function prefillTables() {
        $this->db->addData(
            "INSERT INTO role (name) VALUES (?)",
            [ "admin" ]
        );
    }
    
    function getSqlFile( $filename ) {
        return file_get_contents( $this->base_path . $filename);
    }
}

$table_creator = new TableCreator1000(
    $config->db
);