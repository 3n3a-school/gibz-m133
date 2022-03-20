<?php

namespace M133;

session_start();

include_once __DIR__ . '/config.php';

use M133\Template as Template;

class Page {
    private $is_authenticated = false;

    function __construct(
        private Template $template,
        private $config
    ) {
        $this->checkSession();

        if ( ! $this->is_authenticated )
            $this->sendPage();
    }

    function isNotAuthenticated() {
        return ( 
            ! isset($_SESSION['is_authenticated']) || 
            $_SESSION['is_authenticated'] === false
        );
    }

    function formKeysExist() {
        return array_key_exists('username', $_POST) &&
        array_key_exists('password', $_POST) &&
        Validate::Alphanumeric($_POST['username']);
    }

    function checkSession() {
        if ( $this->isNotAuthenticated() &&
            $this->formKeysExist() &&
            $this->config->controllers['user']->usernameTaken( $_POST['username'] )
             ) {
        
            session_unset();
            session_destroy();
            session_start();
            
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ( ! $this->config->controllers['user']->validCreds($username, $password) ) {
                echo "Wrong user, password combination.";
                exit();
            }
            
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['is_authenticated'] = true;
            $_SESSION['session_timeout'] = strtotime("+1 day");

            error_log("[LOGIN]: $username");
        
            header('Location: /');
            exit();
        
        } else {
            $_SESSION['username'] = NULL;
            $_SESSION['is_authenticated'] = false;
            $_SESSION['session_timeout'] = NULL;

            $this->is_authenticated = false;
        }
    }

    function sendPage() {
        $this->template->render(
            'base.html', [
                'auth' => $_SESSION['is_authenticated'] ? 'true' : 'false',
                'title' => 'Login',
                'content' => 'login.html',
                'head_scripts' => 'head_scripts.html',
                'footer' => 'footer.html',
            ]
        );
    }
}

$page = new Page(
    $config->template,
    $config
);
