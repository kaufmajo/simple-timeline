<?php

declare(strict_types=1);

namespace App\Form\Search;

use interop\container\containerinterface;

class DefTerminSearchInputFilterFactory
{
    public function __invoke(containerinterface $container): DefTerminSearchInputFilter
    {
        $inputFilter = new DefTerminSearchInputFilter();

        $inputFilter->init();

        return $inputFilter;
    }
}
