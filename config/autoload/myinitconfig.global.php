<?php

declare(strict_types=1);

use App\Enum\TerminAnsichtEnum;
use App\Enum\TerminStatusEnum;

return [
    'my_init_config' => [
        'default_mng_search_status'      => [TerminStatusEnum::NORMAL->value, TerminStatusEnum::GESTRICHEN->value, TerminStatusEnum::MITTEILUNG->value, TerminStatusEnum::WARNUNG->value],
        'default_mng_search_ansicht'     => [TerminAnsichtEnum::TIMELINE->value, TerminAnsichtEnum::NONE->value],
        'default_def_tage'               => 90,
        'search_def_tage'                => 540,
        'default_mng_tage'               => 90,
        'cleanup_tage_termine'           => 370,
        'cleanup_tage_history'           => 30,
        'cleanup_tage_termin_history'    => 370,
        'considered_def_new'             => 3,
        'considered_mng_new'             => 3,
        'considered_def_updated'         => 3,
        'considered_mng_updated'         => 3,
        'cache'                          => [
            'debug'   => false,
            'browser' => [
                'enabled'        => true,
                'image_lifetime' => 60 * 60 * 24, // in seconds,
            ],
        ],
    ],
];
