<?php

namespace M133\Controllers;

class EventsController extends \M133\Controller {
    public function getAllEvents( $only_future=false ) {

        $future_date = $only_future ? "" : "WHERE DATE(event.date) < CURDATE()";
        $event_sql = "SELECT event.id, event.name, event.date, event.place, club.name AS club_name
        FROM event
        LEFT JOIN club
        ON event.organizer_id = club.id
        $future_date;";

        $query_data = $this->db->queryData($event_sql, [], "AllEvents");
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }

    public function getEventList() {

        $event_sql = "SELECT id, name FROM event";
        $query_data = $this->db->queryData($event_sql, [], "AllEvents List");
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }

    public function getEventName( $id ) {
        $get_event_sql = "SELECT name FROM event WHERE id = ?";

        $query_data = $this->db->queryData($get_event_sql, [$id], "GetEventName for " . $id);
        $data = ! empty ($query_data) ? $query_data[0] : NULL;

        if ( ! empty($data) )
            return $data['name'];
        return false;
    }

    public function getEventMeta( $id ) {
        $get_event_meta_sql = "SELECT name, description
        FROM event_meta
        WHERE event_id = ?";

        $query_data = $this->db->queryData($get_event_meta_sql, [$id], "GetEventMeta for " . $id);
        $data = ! empty ($query_data) ? $query_data : NULL;

        if ( ! empty($data) )
            return $data;
        return false;
    }

    public function addEvent( $event ) {
        $event_sql = "INSERT INTO event (
            name, place, date, organizer_id
        ) VALUES (?, ?, FROM_UNIXTIME(?), ?)";

        $this->db->changeData( $event_sql, [
            $event['name'],
            $event['place'],
            strtotime($event['date']),
            $event['organizer_id'],
        ], "Event " . $event['name']);
    }
   
}