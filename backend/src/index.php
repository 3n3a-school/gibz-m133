<?php

// PHP - Router: https://github.com/bramus/router
require __DIR__ . '/lib/Router.php';
require __DIR__ . '/lib/Functions.php';

$router = new \Bramus\Router\Router();

header('X-Powered-By: eServer');

$router->get('/', function() {
    $title = "Willkommen im M133 - ENK";
    $date = date("D M. Y");
    $exercises = list_dirs(__DIR__);

    $exercises_html = create_html_list($exercises);

    echo <<<EOF
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="grid grid-rows-1 grid-cols-1 place-items-center h-full my-5">
    <div>
      <h1 class="text-3xl text-center font-bold underline">
        $title
      </h1>
      <p class="text-center">$date</p>

      <h2 class="text-2xl my-2">Aufgaben</h2>
      $exercises_html
    </div>
  </div>
</body>
</html>
EOF;

});

$router->run();