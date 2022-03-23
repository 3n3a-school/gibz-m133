<?php

namespace M133\Controllers;

class UserController extends \M133\Controller {
    public function getUser( $username, $fields=["username", "first_name", "last_name"] ) {

        $user_fields = implode( ", ", $fields);
        $user_sql = "SELECT $user_fields FROM users WHERE username = ?";

        $query_data = $this->db->queryData($user_sql, [ $username ], "Username " . $username);
        $data = ! empty ($query_data) ? $query_data[0] : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }

    public function getUserWClub( $username, $fields=["username", "first_name", "last_name"] ) {

        $user_fields = implode( ", ", $fields);
        $user_fields .= ", club.name AS club_name";
        $user_sql = "SELECT $user_fields
        FROM users 
        LEFT JOIN club
        ON club_id = club.id
        WHERE username = ?";

        $query_data = $this->db->queryData($user_sql, [ $username ], "Username " . $username);
        $data = ! empty ($query_data) ? $query_data[0] : NULL;

        if ( ! empty($data) )
            return $data;
        return NULL;
    }

    public function addUser( $userinfo ) {
        $user_sql = "INSERT INTO users (first_name, last_name, birthdate, club_id, username, password, email, is_active, is_verified) VALUES (?, ?, FROM_UNIXTIME(?), ?, ?, ?, ?, ?, ?)";

        $this->db->changeData( $user_sql, [
            $userinfo['first_name'],
            $userinfo['last_name'],
            $userinfo['birthdate'],
            $userinfo['club_id'],
            $userinfo['username'],
            $userinfo['password'],
            $userinfo['email'],
            true,
            true
        ], "User " . $userinfo['username']);
    }

    public function usernameTaken( $username ) {

        $existance_sql = "SELECT username FROM users WHERE username = ?";

        $query_data = $this->db->queryData($existance_sql, [$username], "Username " . $username);
        $data = ! empty ($query_data) ? $query_data[0] : NULL;

        if ( ! empty($data) &&
            $data['username'] == $username)
            return true;
        return false;
    }

    public function validCreds( $username, $password ) {

        $user_sql = "SELECT username, password FROM users WHERE username = ?";
        $query_data = $this->db->queryData($user_sql, [$username], "Username " . $username);
        $data = ! empty ($query_data) ? $query_data[0] : NULL;

        if ( ! empty($data) &&
            $data['username'] == $username && 
            password_verify( $password, $data['password'])
        )
            return true;
        return false;
    }
}