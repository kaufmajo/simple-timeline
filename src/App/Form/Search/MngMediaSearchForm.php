<?php

declare(strict_types=1);

namespace App\Form\Search;

use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;

class MngMediaSearchForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this->setName('media_mng_search_form');

        $this->add(
            [
                'name'       => 'search_suchtext',
                'type'       => Element\Search::class,
                'options'    => [
                    'label'            => 'Suchbegriff',
                    'label_attributes' => [],
                ],
                'attributes' => [
                    'id' => 'input-search-suchtext',
                ],
            ]
        );

        $this->add(
            [
                'name'       => 'submit-button',
                'type'       => Element\Submit::class,
                'attributes' => [
                    'id'    => 'input-search-submit',
                    'value' => 'Suchen',
                ],
            ]
        );
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'search_suchtext' => [
                'required'   => true,
                'filters'    => [
                    ['name' => Filter\StripTags::class],
                ],
                'validators' => [],
            ],
        ];
    }
}
