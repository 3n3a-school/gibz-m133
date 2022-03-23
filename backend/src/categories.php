<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;

class CategoryPage extends Page {

    private $current_event_id;


    // overwrites parent _Page_'s constructor
    function __construct(
        public Template $template,
        public Database $database,
        public $config
    ) {
        $this->checkInstalled(__DIR__ . '/.setupdone');
        $this->initRoutes();
        $this->getKeys();
        $this->sendPage();
    }

    function getKeys() {
        if (array_key_exists('event_id', $_GET) &&
        $_GET['event_id'] != null) {
            $this->current_event_id = $_GET['event_id'];
        } else {
            header('Location: /events.php');
            exit();
        }
    }

    public function sendPage() {
        $categories = $this->config->controllers['category']->getAllCategories();
        $categories_html = "";

        foreach ($categories as $cat) {
            $name = $cat["name"];
            $id = $cat['id'];
            $categories_html .= $this->template->render('components/event_item.html', [
                "id"=>$id,
                "name"=>$name, 
                "url_prefix"=>"rankings.php?event_id=" . $this->current_event_id . "&category_id=",
            ], true);
        }
        
        $event_name = $this->config->controllers['event']->getEventName($this->current_event_id) ?? "No Event Name found";
        $this->sendPageWrapper(
            [
                'title' => 'Categories: ' . $event_name,
                'app_content' => 'categories.html',
                'categories' => $categories_html
            ]
        );
    }
}

// Instantiate new App with Router...
$index = new CategoryPage(
    $config->template,
    $config->db,
    $config
);