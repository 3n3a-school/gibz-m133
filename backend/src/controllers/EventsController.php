<?php

namespace M133\Controllers;

class EventsController extends \M133\Controller {
    public function getAllEvents( ) {

        $event_sql = "SELECT event.id, event.name, event.date, event.place, club.name AS club_name
        FROM event
        LEFT JOIN club
        ON event.organizer_id = club.id;";

        $query_data = $this->db->queryData($event_sql, [], "AllEvents");
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }
}