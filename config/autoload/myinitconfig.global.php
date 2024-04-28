<?php

declare(strict_types=1);

use App\Enum\TerminAnsichtEnum;
use App\Enum\TerminStatusEnum;

return [
    'my_init_config' => [
        'default_mng_search_status'      => [TerminStatusEnum::NORMAL->value, TerminStatusEnum::GESTRICHEN->value, TerminStatusEnum::MITTEILUNG->value, TerminStatusEnum::WARNUNG->value],
        'default_mng_search_ansicht'     => [TerminAnsichtEnum::TIMELINE->value, TerminAnsichtEnum::NONE->value],
        'cleanup_tage_termine'           => 370, // in days
        'cleanup_tage_history'           => 30,  // in days
        'cleanup_tage_termin_history'    => 370, // in days
        'considered_as_new'             => 3,    // in days
        'considered_as_updated'         => 3,    // in days
        'cache'                          => [
            'debug'   => false,
            'browser' => [
                'enabled'        => true,
                'image_lifetime' => 60 * 60 * 24, // in seconds
            ],
        ],
    ],
];
