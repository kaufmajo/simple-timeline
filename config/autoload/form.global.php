<?php

use App\Form;

return [
    'form_elements' => [
        'factories' => [
            Form\MediaForm::class => Form\MediaFormFactory::class,
            Form\TerminForm::class => Form\TerminFormFactory::class,
            Form\Element\Select\TerminKategorieElementSelect::class => Form\Element\Select\TerminKategorieElementSelectFactory::class,
            Form\Element\Select\TerminAnsichtElementSelect::class => Form\Element\Select\TerminAnsichtElementSelectFactory::class,
            Form\Element\Select\TerminStatusElementSelect::class => Form\Element\Select\TerminStatusElementSelectFactory::class,
            Form\Search\DefTerminSearchForm::class => Form\Search\DefTerminSearchFormFactory::class,
        ],
    ],
];
