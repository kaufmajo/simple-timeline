<?php

declare(strict_types=1);

namespace App\Model\Media;

use interop\container\containerinterface;

class MediaInputFilterFactory
{
    /**
     * @return MediaInputFilter
     */
    public function __invoke(containerinterface $container)
    {
        $inputFilter = new MediaInputFilter();

        $inputFilter->init();

        return $inputFilter;
    }
}
