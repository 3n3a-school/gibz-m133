<?php

namespace M133;

include_once __DIR__ . '/config.php';

use M133\Page as Page;
use M133\Validate as Validate;

class IndexPage extends Page {

    public function sendPage() {
        $this->sendPageWrapper([
            'title' => 'Home',
            'app_content' => 'index.html',
            'ranking_table' => 'components/ranking_table.html'
        ]);
    }
}

// Instantiate new App with Router...
$index = new IndexPage(
    $config
);