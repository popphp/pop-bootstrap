<?php

return array_merge(
    include 'http/api.php',
    include 'http/web.php',
    [
    '*' => [
        'controller' => 'Pab\Controller\IndexController',
        'action'     => 'error'
    ]
]);