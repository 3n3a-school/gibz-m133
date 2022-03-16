<?php

namespace M133\Controllers;

require_once __DIR__ . "/../lib/Controller.php";
use M133\Controller as Controller;

class IndexController extends Controller {
    public function handleGet() {
        return "Hello there";
    }
}