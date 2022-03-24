<?php

namespace M133;

class Importer {

    private $csv_array = array();
    private $event_id;
    private $import_keys = [
        "participant_name" => "Name",
        "category_id" => "Kategorie",
        "position" => "Rang",
        "time" => "Zeit",
        "birthyear" => "Jahrgang",
        "city" => "Ort",
        "club" => "Club",
    ];
    private $import_delim = ";";

    function __construct(
        private $config,
    ) {}

    /**
     * Reads a CSV File into an Array
     */
    public function readCsvFile( $filepath, $event_id ) {
        $this->event_id = $event_id;

        $assoc_array = [];
        if (($handle = fopen($filepath, "r")) !== false) {                 // open for reading
            if (($data = fgetcsv($handle, 0, $this->import_delim)) !== false) {         // extract header data
                $keys = $data;                                             // save as keys
            }
            while (($data = fgetcsv($handle, 0, $this->import_delim)) !== false) {      // loop remaining rows of data
                $assoc_array[] = array_combine($keys, $data);              // push associative subarrays
            }
            fclose($handle);                                               // close when done
        }

        $this->csv_array = $assoc_array;
    }

    /**
     * Saves Ranking List into DB
     */
    public function saveRankingList() {
        foreach ($this->csv_array as $item) {

            $position = $item[
                $this->import_keys["position"]
            ];
            $position = empty($position) ? NULL : (int)$position;

            $this->config->controllers['rank']->addRanking(
                [
                    $item[
                        $this->import_keys["participant_name"]
                    ],
                    (int)$this->event_id,
                    $item[
                        $this->import_keys["category_id"]
                    ],
                    $position,
                    $item[
                        $this->import_keys["time"]
                    ],
                    $item[
                        $this->import_keys["birthyear"]
                    ],
                    $item[
                        $this->import_keys["city"]
                    ],
                    $item[
                        $this->import_keys["club"]
                    ]
                ]
            );

        }
        
        echo "Successfully imported ranking list";
        echo "<a href=\"/\">Return to home</a>";
    }
}