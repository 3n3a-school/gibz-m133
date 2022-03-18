<?php

namespace M133\Controllers;

class UserController extends \M133\Controller {
    public function getUser( $user_id ) {
        return 0;
    }

    public function addUser( $userinfo ) {
        $user_sql = "INSERT INTO users (first_name, last_name, birthdate, club_id, username, password, email, is_active, is_verified) VALUES (?, ?, FROM_UNIXTIME(?), ?, ?, ?, ?, ?, ?)";

        $this->db->addData( $user_sql, [
            $userinfo['first_name'],
            $userinfo['last_name'],
            $userinfo['birthdate'],
            NULL,
            $userinfo['username'],
            $userinfo['password'],
            $userinfo['email'],
            true,
            true
        ], "User " . $userinfo['username']);
    }
}