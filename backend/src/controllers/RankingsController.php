<?php

namespace M133\Controllers;

class RankingsController extends \M133\Controller {
    public function getRanking( $event_id, $category_id ) {

        $rank_sql = "SELECT participant_name, position, time, birthyear, city, club, category.name as category_name FROM ranking
        JOIN category ON category_id = category.id
        WHERE category.id = ?
        AND event_id = ?
        -- make NULLS last in sorting
        ORDER BY -position DESC";

        $query_data = $this->db->queryData($rank_sql, [ $category_id, $event_id ], "RankingTable");
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }
}