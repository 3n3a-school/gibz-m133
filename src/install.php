<?php

namespace M133;

include_once __DIR__ . '/config.php';

class TableCreator1000 {
    private $base_path = __DIR__ . '/sql/';
    private $setup_file = __DIR__ . '/.setupdone';

    function __construct(
        private Database $db,
        private FileHandler $fh
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

        echo "Starting the creation of Tables ⌛...<br>";

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

        echo "Done creating Tables 😀<br>";
    }

    function prefillTables() {

        echo "Starting the filling in of some Tables 🥧...<br>";

        $prefill_sql = [
            "role" => [
                "sql" => "INSERT INTO role (name) VALUES (?)",
                "values" => [ ["admin"], ["user"], ["owner"] ]
            ],
            "user" => [
                "sql" => "INSERT INTO users (first_name, last_name, birthdate, club_id, username, password, email, is_active, is_verified)
                        VALUES (?, ?, FROM_UNIXTIME(?), ?, ?, ?, ?, ?, ?)",
                "values" => [ 
                    [ "Administrative", "User", strtotime("1. January 1999"), NULL, "admin", password_hash("admin", PASSWORD_ARGON2I), "admin@email.com", true, true ]
                    ]
            ],
            "user_role" => [
                "sql" => "INSERT INTO user_role (user_id, role_id) VALUES (?,?)",
                "values" => [ [1, 2], [1, 3], [1, 1] ]
            ],
            "category" => [
                "is-file" => true,
                "sql" => $this->fh->read(__DIR__ . '/sql/prefill/category.sql'),
            ],
            "club" => [
                "is-file" => true,
                "sql" => $this->fh->read(__DIR__ . '/sql/prefill/club.sql'),
            ],
            "event" => [
                "sql" => "INSERT INTO event (name, organizer_id, date, place) VALUES (?,(SELECT id FROM club WHERE name = ?),FROM_UNIXTIME(?),?)",
                "values" => [
                    [ "1. Nationaler OL (Langdistanz)", "OLG Cordoba", strtotime("-3 day"), "Geissberg"],
                ]
            ],
            "ranking" => [
                "is-file" => true,
                "sql" => $this->fh->read(__DIR__ . '/sql/prefill/ranking.sql'),
            ],
            "event_meta" => [
                "is-file" => true,
                "sql" => $this->fh->read(__DIR__ . '/sql/prefill/event_meta.sql'),
            ],
        ];

        foreach ($prefill_sql as $sql_file_name => $sql_file_value) {
            if (array_key_exists('is-file', $sql_file_value)) {
                $this->db->createObject($sql_file_value['sql'], $sql_file_name . "_prefill");
            } else {
                // add prefill all values
                foreach ($sql_file_value['values'] as $value) {
                    $this->db->changeData(
                        $sql_file_value['sql'],
                        $value,
                        $sql_file_name . "_prefill"
                    );
                }
            }
        }

        echo "Finished the filling up of tasty tables 🤑<br>";

    }
    


    function getSqlFile( $filename ) {
        return file_get_contents( $this->base_path . $filename);
    }
}

$table_creator = new TableCreator1000(
    $config->db,
    new FileHandler()
);