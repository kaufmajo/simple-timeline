<?php

declare(strict_types=1);

return [
    // ...
    'mezzio-authorization-rbac' => [
        'roles'       => [
            'admin'  => [],
            'termin' => ['admin'],
            'media'  => ['termin'],
        ],
        'permissions' => [
            'media'  => [
                'manage.home.read',
                'manage.media.read',
                'manage.media.write',
            ],
            'termin' => [
                'manage.termin.read',
                'manage.termin.write',
            ],
            'admin'  => [
                'default.app.cleanup',
            ],
        ],
    ],
];
