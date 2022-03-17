<?php

namespace M133;

include_once __DIR__ . '/lib/index.php';

use M133\Filehandler as Filehandler;
use M133\DatabaseConfig as DbConfig;
use M133\Database as Database;
use M133\Template as Template;

class Config {
   
    function __construct(
        public Template $template,
        public Database $db,
    ) {
        ini_set("log_errors", 1);
        ini_set("error_log", __DIR__ . "/log/errors.log");
    }
}

$db_config = new DbConfig(
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DB'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
);

$db = new Database($db_config);

$views_path = __DIR__ . '/frontend/views/';
$templ = new Template($views_path);

$config = new Config($templ, $db);