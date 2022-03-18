<?php

namespace M133;

abstract class Controller {
    function __construct(
        public Database $db,
    ) {
    }
}