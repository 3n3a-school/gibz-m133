<?php

namespace M133;

session_start();

include_once __DIR__ . '/lib/index.php';
include_once __DIR__ . '/config.php';

use M133\Template as Template;

class Page {
    private $is_authenticated = false;

    function __construct(
        private Template $template,
    ) {
        $this->checkSession();

        $this->checkFormSubmission();

        if ( ! $this->is_authenticated )
            $this->sendPage();
    }

    function isAuthenticated() {
        return isset($_SESSION['is_authenticated']) &&
            $_SESSION['is_authenticated'] === true;
    }

    function checkSession() {
        if ( $this->isAuthenticated() ) {      

            header('Location: /');
            exit();

        }
    }

    function arrayHasKeys( $haystack, $needles ) {
        $contains = false;

        foreach ($needles as $n) {
            if (array_key_exists( $n, $haystack ))
                $contains = true;
        }

        return $contains;
    }

    function checkFormSubmission() {
        if ( 
            $this->arrayHasKeys($_POST, [
                "first_name", 
                "last_name"
                "email",
                "username", 
                "password",
                "birthdate",
            ]) &&
            Validate::Alphanumeric($_POST['username']) &&
            Validate::Alphanumeric($_POST['first_name']) &&
            Validate::Alphanumeric($_POST['last_name']) &&
            Validate::Email($_POST['email'])
        ) {
                
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_ARGON2I);
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $birthdate = strtotime($_POST['birthdate']);
            $email = $_POST['email'];

            // TODO: save to db
            // if success in saving 
            // do below

            // sql
            // "INSERT INTO users (first_name, last_name, birthdate, club_id, username, password, email, is_active, is_verified)
            // VALUES (?, ?, FROM_UNIXTIME(?), ?, ?, ?, ?, ?, ?)"
    
            error_log("[REGISTER]: $username");
        
            header('Location: /login.php');
            exit();
        }
    }

    function sendPage() {
        $this->template->render(
            'base.html', [
                'auth' => $_SESSION['is_authenticated'] ? 'true' : 'false',
                'title' => 'Register',
                'content' => 'register.html',
                'head_scripts' => 'head_scripts.html',
                'footer' => 'footer.html',
                'address' => ''
            ]
        );
    }
}

$page = new Page(
    $config->template
);
