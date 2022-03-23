<?php

namespace M133\Controllers;

class ClubsController extends \M133\Controller {
    public function getAllClubs( ) {

        $club_sql = "SELECT id, name FROM club";

        $query_data = $this->db->queryData($club_sql, [], "AllClubs");
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }
}