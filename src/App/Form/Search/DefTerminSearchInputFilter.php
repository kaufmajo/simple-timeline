<?php

declare(strict_types=1);

namespace App\Form\Search;

use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

class DefTerminSearchInputFilter extends InputFilter
{
    public function init()
    {
        $this->add(
            [
                'name'       => 'search_suchtext',
                'required'   => false,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ]
        );
    }
}
