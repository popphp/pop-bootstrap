<?php

return array_merge(
    include 'http/api.php',
    include 'http/web.php',
    [
    '*' => [
        'controller' => 'App\Controller\IndexController',
        'action'     => 'error'
    ]
]);