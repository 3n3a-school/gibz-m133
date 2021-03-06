<?php

namespace M133;

include_once __DIR__ . '/shared/index.php';
include_once __DIR__ . '/controllers/index.php';

use M133\Filehandler as Filehandler;
use M133\DatabaseConfig as DbConfig;
use M133\Database as Database;
use M133\Template as Template;
use M133\Page as Page;
use M133\Controllers\UserController as UserController;
use M133\Controllers\EventsController as EventsController;
use M133\Controllers\CategoriesController as CategoriesController;
use M133\Controllers\RankingsController as RankingsController;
use M133\Controllers\ClubsController as ClubsController;

class Config {

    public $controllers = [];
    public $setupDonePath = __DIR__ . '/.setupdone';
    private $logfilePath  =__DIR__ . "/log/errors.log";

    /**
     * The menus displayed on all pages
     */
    public $menus = [
        'main' => [
            "Home" => "/index.php",
            "Events" => "/events.php",
            "Calendar" => "/calendar.php",
            "Your Rankings" => "/user_rankings.php"
        ],
        'person' => [
            "Profile" => "#show-user-modal",
            "Settings" => "",
            "Logout" => "/logout.php"
        ]
    ];
   
    function __construct(
        public Template $template,
        public Database $db,
    ) {
        if (array_key_exists('DEBUG', $_ENV) && $_ENV['DEBUG'] == 1) {
            if (!file_exists($this->logfilePath)) {
                // create file
                file_put_contents( $this->logfilePath, "--- ERRORS LOG ---\n");
            }
            ini_set("log_errors", 1);
            ini_set("error_log", $this->logfilePath);
        }

        $this->controllers['user'] = new UserController($this->db);
        $this->controllers['event'] = new EventsController($this->db);
        $this->controllers['category'] = new CategoriesController($this->db);
        $this->controllers['rank'] = new RankingsController($this->db);
        $this->controllers['club'] = new ClubsController($this->db);
    }
}

// For Docker :)
$db_config = new DbConfig(
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_DB'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
);

// For XAMPP :(
// $db_config = new DbConfig(
//     'localhost',
//     '3306',
//     'm133', // db name
//     'm133', //username
//     '1234', // pw
// );

$db = new Database($db_config);

$views_path = __DIR__ . '/views/';
$templ = new Template($views_path);

$config = new Config($templ, $db);