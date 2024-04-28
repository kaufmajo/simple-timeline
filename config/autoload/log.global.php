<?php

use Laminas\Log\Formatter\Simple;
use Laminas\Log\Logger;
use Laminas\Log\Processor\RequestId;

return [
    'log' => [
        'AppLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => 1,
                    'options' => [
                        'stream' => __DIR__ . '/../../data/log/app.txt',
                        'formatter' => [
                            'name' => Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => [
                                'name' => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => Logger::DEBUG,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => RequestId::class,
                ],
            ],
        ],
        'DbLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => 1,
                    'options' => [
                        'stream' => __DIR__ . '/../../data/log/db.txt',
                        'formatter' => [
                            'name' => Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => [
                                'name' => 'priority',
                                'options' => [
                                    'operator' => '<=',
                                    'priority' => Logger::DEBUG,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => RequestId::class,
                ],
            ],
        ],
    ]
];