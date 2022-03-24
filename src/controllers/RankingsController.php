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
    
    /**
     * Searches for users name in rankings
     * if name, birthyear and club match -> added to user_ranking
     */
    public function addUserRankings() {

        $user_ranks_sql = "INSERT INTO user_ranking (user_id, ranking_id)
        SELECT users.id, ranking.id FROM users
            INNER JOIN ranking 
            ON ranking.participant_name = (
                SELECT CONCAT(users.first_name, ' ', users.last_name) AS full_name
            ) AND
            ranking.birthyear = DATE_FORMAT(users.birthdate, '%y') AND
            ranking.club = (SELECT name FROM club WHERE id = users.club_id)";

        $query_data = $this->db->createObject($user_ranks_sql, "Add Users Rankings");
    }

}