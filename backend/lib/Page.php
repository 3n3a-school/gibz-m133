<?php
namespace M133;

abstract class Page {

    public Template $template;
    public Database $database;
    
    function __construct(
        public $config
    ) {
        $this->template = $this->config->template;
        $this->database = $this->config->db;

        $this->checkInstalled($this->config->setupDonePath);
        $this->initRoutes();
        $this->sendPage();
    }

    function sendPageWrapper( $tags ) {
        if ($this->isAuthenticated()) {
            $username = $this->getSessionValueIfExists('username');
            $user = $this->config->controllers['user']->getUser( $username, ["first_name", "last_name", "email", "username"] );
            $this->template->renderIntoBase(
                $tags,
                $this->config->menus,
                $user
            );

        } else {
            
            header('Location: /login.php');

        }
    }

    abstract public function sendPage(); 

    function checkInstalled($path) {
        if ( ! file_exists( $path ) ) {
            // Setup not done
            // Start installation
            header( 'Location: /install.php' );
            error_log("[INSTALLATION] Starting Installation 🤩");
            exit();
        }
    }

    public function initRoutes() {
        // Authentication
        session_start();
        
        header('X-Powered-By: eServer');
    }

    function arrayHasKeys( $haystack, $needles ) {
        $contains = false;

        foreach ($needles as $n) {
            if (array_key_exists( $n, $haystack ))
                $contains = true;
        }

        return $contains;
    }

    /**
     * Gets a Session Value if in existance
     * otherwise returns false
     */
    function getSessionValueIfExists( $key ) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return false;
    }

    /**
     * Checks if Session was previously authenticated
     */
    function isAuthenticated() {
        return isset($_SESSION['is_authenticated']) &&
            $_SESSION['is_authenticated'] === true;
    }

    /**
     * Checks if a Session was not authenticated
     */
    function isNotAuthenticated() {
        return ( 
            ! isset($_SESSION['is_authenticated']) || 
            $_SESSION['is_authenticated'] === false
        );
    }
}