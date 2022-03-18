<?php

namespace M133;

class Validate {
    public static URL( $url ) {
        if (filter_var($url, FILTER_VALIDATE_URL) === false)
            return false;
        return true;
    }

    public static Email( $email ) {
        if (filter_var( $email, FILTER_VALIDATE_EMAIL) === false)
            return false;
        return true;
    }

    public static Alphanumeric( $string ) {
        if (ctype_alnum( $string ) === false)
            return false;
        return true;
    }
}