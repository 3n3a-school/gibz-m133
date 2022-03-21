<?php
namespace M133;

abstract class Page {
    abstract public function sendPage(); 

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