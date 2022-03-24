<?php

namespace M133;

class Importer {

    /**
     * Reads a CSV File into an Array
     */
    function readCsvFile( $filepath ) {
        return str_getcsv( $this->filehandler->read($filepath) );
    }
}