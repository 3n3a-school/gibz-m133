<?php

namespace M133\Controllers;

class RankingsController extends \M133\Controller {
    public function getRanking( $event_id, $category_id ) {

        $rank_sql = "SELECT participant_name, position, time, LPAD(birthyear, 2, 0) AS birthyear, city, club, category.name as category_name FROM ranking
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
     * Returns all the rankings of a specific user
     */
    public function getUserRankings( $username ) {

        $rank_sql = "SELECT users.id AS user_id, users.username, event.id AS event_id, event.name AS event_name, category.id AS category_id, category.name AS category_name,
        ranking.position, ranking.time
        FROM user_ranking
        JOIN ranking ON user_ranking.ranking_id = ranking.id
        JOIN users ON user_ranking.user_id = users.id
        JOIN event ON ranking.event_id = event.id
        JOIN category ON ranking.category_id = category.id
        WHERE users.username = ?";

        $query_data = $this->db->queryData($rank_sql, [$username], "GetUserRankings");
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
        ranking.club = (SELECT name FROM club WHERE id = users.club_id)
        WHERE users.id NOT IN (
            SELECT user_id FROM user_ranking
        )
        ";

        $query_data = $this->db->createObject($user_ranks_sql, "Add Users Rankings");
    }

}