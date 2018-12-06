<?php

namespace Sibirix;

use Sibirix\TextContent\Controller;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'router' => [
        'routes' => require 'routes.config.php',
    ],

    'controllers' => [
        'factories' => [
            Controller\ActionsController::class => ReflectionBasedAbstractFactory::class,
        ]
    ],
];
