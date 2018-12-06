<?php

namespace Sibirix;

use Sibirix\TextContent\Controller;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'actions::actions' => [
        'type' => Literal::class,
        'options' => [
            'route' => '/actions/',
            'defaults' => [
                'controller' => Controller\ActionsController::class,
                'action'     => 'index'
            ]
        ],
        'may_terminate' => true,
        'child_routes'  => [
            'detail'    => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => ':code[/]',
                    'defaults' => [
                        'controller' => Controller\ActionsController::class,
                        'action'     => 'detail',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'detail-with-page'    => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '[page-:page/]',
                            'defaults' => [
                                'controller' => Controller\ActionsController::class,
                                'action'     => 'detail'
                            ],
                        ],
                    ],
                ],
            ],
            'index-with-page'    => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '[page-:page/]',
                    'defaults' => [
                        'controller' => Controller\ActionsController::class,
                        'action'     => 'index'
                    ],
                ],
            ],
        ],
    ],
];