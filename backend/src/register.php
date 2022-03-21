<?php

namespace M133;

include_once __DIR__ . '/config.php';

session_start();

class RegisterPage extends Page {
    private $is_authenticated = false;

    function __construct(
        private Template $template,
        private $config,
    ) {
        $this->checkSession();

        $this->checkFormSubmission();

        if ( ! $this->is_authenticated )
            $this->sendPage();
    }

    function checkSession() {
        if ( $this->isAuthenticated() ) {      

            header('Location: /');
            exit();

        }
    }

    function checkFormSubmission() {
        if ( 
            $this->arrayHasKeys($_POST, [
                "first_name", 
                "last_name",
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

            $registration = [];
                
            $registration['username'] = $_POST['username'];
            $registration['password'] = password_hash($_POST['password'], PASSWORD_ARGON2I);
            $registration['first_name'] = $_POST['first_name'];
            $registration['last_name'] = $_POST['last_name'];
            $registration['birthdate'] = strtotime($_POST['birthdate']);
            $registration['email'] = $_POST['email'];

            if ( $this->config->controllers['user']->usernameTaken(
                $registration['username']
            ) ) {
                echo "Username already taken";
                exit();
            }
            
            $this->config->controllers['user']->addUser( $registration );
    
            error_log("[REGISTER]: " . $registration['username']);
        
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

$page = new RegisterPage(
    $config->template,
    $config
);
