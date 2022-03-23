<?php

namespace M133;

class Validate {
    public static function URL( $url ) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false)
            return false;
        return true;
    }

    public static function Email( $email ) {
        if (filter_var( $email, FILTER_VALIDATE_EMAIL) === false)
            return false;
        return true;
    }

    public static function Alphanumeric( $string ) {
        if (ctype_alnum( $string ) === false)
            return false;
        return true;
    }

    public static function String( $string ) {
        if ( !preg_match("/[A-Za-z\xC4\xD6\xDC\xDF\xE4\xF6\xFC\xE9\xE0\xE8\xEE\xC9\xC0\xC8\xCE]+/", $string) )
            return false;
        return true; 
    }
}