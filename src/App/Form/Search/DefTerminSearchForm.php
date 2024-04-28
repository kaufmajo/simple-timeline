<?php

declare(strict_types=1);

namespace App\Form\Search;

use Laminas\Form\Element;
use Laminas\Form\Form;

class DefTerminSearchForm extends Form
{
    public function init()
    {
        $this->setName('form-termin-def-search');

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
}
