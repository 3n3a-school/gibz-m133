<?php

namespace M133;

abstract class Controller {

    public $db = NULL;

    function __construct($db) {
        $this->db = $db;
    }

    abstract public function handleGet();
}