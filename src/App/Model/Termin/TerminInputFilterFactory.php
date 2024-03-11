<?php

declare(strict_types=1);

namespace App\Model\Termin;

use interop\container\containerinterface;

class TerminInputFilterFactory
{
    public function __invoke(containerinterface $container): TerminInputFilter
    {
        $inputFilter = new TerminInputFilter();

        $inputFilter->init();

        return $inputFilter;
    }
}
