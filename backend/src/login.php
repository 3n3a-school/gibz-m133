<?php

namespace M133;

session_start();

include_once __DIR__ . '/config.php';

class LoginPage extends Page {
    private $is_authenticated = false;

    function __construct(
        private Template $template,
        private $config
    ) {
        $this->checkSession();

        if ( ! $this->is_authenticated )
            $this->sendPage();
    }

    function formKeysExist() {
        return array_key_exists('username', $_POST) &&
        array_key_exists('password', $_POST) &&
        Validate::Alphanumeric($_POST['username']);
    }

    function checkSession() {
        if ( $this->isNotAuthenticated() &&
            $this->formKeysExist()
             ) {
        
            session_unset();
            session_destroy();
            session_start();
            
            $username = $_POST['username'];
            $password = $_POST['password'];

            $isUsernameTaken = $this->config->controllers['user']->usernameTaken( $_POST['username'] );
            
            if ( ! $isUsernameTaken ) {
                echo "User not registered.";
                error_log("[LOGIN-ATTEMPT]: $username - registered: " . ($isUsernameTaken ? "true" : "false"));
                exit( );
            }

            $areCredsValid = $this->config->controllers['user']->validCreds($username, $password);

            error_log("[LOGIN-ATTEMPT]: $username - registered: " . ($isUsernameTaken ? "true" : "false") . " - creds: " . ($areCredsValid ? "true" : "false"));

            if ( ! $areCredsValid &&
                $isUsernameTaken ) {
                echo "Wrong user, password combination.";
                exit( );
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

$page = new LoginPage(
    $config->template,
    $config
);
